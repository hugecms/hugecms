<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AttachmentRelation;
use App\Models\Comment;
use App\Models\Content;
use App\Models\ContentModel;
use App\Models\RecycleBin;
use App\Models\SeoMeta;
use App\Models\TermRelationship;
use App\Repositories\RecycleBinRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;
use RuntimeException;

class RecycleBinService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly RecycleBinRepository $repository,
    ) {}

    public function getRepository(): RecycleBinRepository
    {
        return $this->repository;
    }

    /**
     * 从回收站恢复（流程见 docs/development-conventions.md 第一节）：
     * 软删除场景内容行仍在（status=trash），仅翻回快照记录的原状态；
     * 行已被物理清除则按快照重建主行 + 模型数据行 + 全部关联（slug 冲突追加后缀）。
     *
     * @return int 恢复的内容ID
     */
    public function restore(int $recycleId): int
    {
        return (int) DB::transaction(function () use ($recycleId): int {
            $record = RecycleBin::query()->lockForUpdate()->findOrFail($recycleId);

            if ($record->target_type !== 'content') {
                throw new RuntimeException('暂仅支持 content 类型恢复，' . $record->target_type . ' 待扩展');
            }

            $snapshot = $this->decodeSnapshot($record->original_data);
            $row = $snapshot['content'] ?? [];
            if (empty($row['id'])) {
                throw new RuntimeException('快照数据不完整（缺少 content.id），无法恢复');
            }

            $contentId = (int) $row['id'];
            $content = Content::query()->find($contentId);

            if ($content) {
                // 软删除：仅翻回原状态（关联数据未受级联影响）
                $content->status = $row['status'] ?? 'draft';
                $content->save();
            } else {
                // 物理清除：按快照重建主行与关联
                if (!empty($row['slug']) && Content::query()->where('slug', $row['slug'])->exists()) {
                    $row['slug'] .= '-r' . $contentId;
                }
                $row = $this->normalizeRowDates($row);
                $row['created_at'] ??= now();
                $row['updated_at'] ??= now();
                Content::query()->insert($row);

                // 模型数据行（data_{alias}，表名来自 content_models.table_name）
                $dataRow = $snapshot['data'] ?? [];
                if (!empty($dataRow)) {
                    $table = ContentModel::query()->where('id', $row['model_id'] ?? 0)->value('table_name');
                    if (!empty($table) && DB::getSchemaBuilder()->hasTable($table)) {
                        $dataRow = $this->normalizeRowDates($dataRow);
                        $dataRow['created_at'] ??= now();
                        $dataRow['updated_at'] ??= now();
                        DB::table($table)->insert($dataRow);
                    }
                }

                // 幂等防御：多态表（seo_meta）无外键不随级联清除，可能残留同目标旧记录，先清后插
                SeoMeta::query()->where('target_type', 'content')->where('target_id', $contentId)->delete();

                foreach (($snapshot['relations']['comments'] ?? []) as $comment) {
                    Comment::query()->insertOrIgnore($this->normalizeRowDates($comment));
                }
                foreach (($snapshot['relations']['term_relationships'] ?? []) as $rel) {
                    TermRelationship::query()->insertOrIgnore($this->normalizeRowDates($rel));
                }
                foreach (($snapshot['relations']['attachment_relations'] ?? []) as $rel) {
                    AttachmentRelation::query()->insertOrIgnore($this->normalizeRowDates($rel));
                }
                if (!empty($snapshot['relations']['seo_meta'])) {
                    SeoMeta::query()->insert($this->normalizeRowDates($snapshot['relations']['seo_meta']));
                }
            }

            $record->delete();

            return $contentId;
        });
    }

    /**
     * 彻底清除（约定第一节第3条）：物理删除目标及其级联关联（评论/分类/附件关联/SEO/数据行随外键级联），
     * 并移除回收站记录。区别于脚手架 destroy（仅删记录、目标残留 trash 状态）。
     */
    public function purge(int $recycleId): void
    {
        DB::transaction(function () use ($recycleId): void {
            $record = RecycleBin::query()->lockForUpdate()->findOrFail($recycleId);

            if ($record->target_type === 'content') {
                $snapshot = $this->decodeSnapshot($record->original_data);
                $contentId = (int) ($snapshot['content']['id'] ?? 0);
                if ($contentId) {
                    // 外键级联清除 data_{alias}/评论/分类/附件关联；
                    // 多态表 seo_meta 无外键，需显式清除，否则残留孤儿记录并阻碍后续恢复
                    Content::query()->where('id', $contentId)->delete();
                    SeoMeta::query()->where('target_type', 'content')->where('target_id', $contentId)->delete();
                }
            }

            $record->delete();
        });
    }

    /**
     * 删除内容入回收站（约定第一节第1条）：写完整快照（主行 + 数据行 + 级联关联）+ status=trash。
     * 重复删除仅刷新快照。彻底清除走 purge（物理删除 + 级联）。
     *
     * @return int 内容ID
     */
    public function snapshotAndTrash(int $contentId): int
    {
        return (int) DB::transaction(function () use ($contentId): int {
            $content = Content::query()->lockForUpdate()->findOrFail($contentId);

            // 模型数据行（data_{alias}）
            $data = [];
            $table = ContentModel::query()->where('id', $content->model_id)->value('table_name');
            if (!empty($table) && Schema::hasTable($table)) {
                $row = DB::table($table)->where('content_id', $contentId)->first();
                if ($row) {
                    $data = (array) $row;
                }
            }

            // 级联关联快照（彻底删除时会被 CASCADE 清除，恢复时按此重建）
            $relations = [
                'comments' => Comment::query()->where('content_id', $contentId)->get()->toArray(),
                'term_relationships' => TermRelationship::query()->where('content_id', $contentId)->get()->toArray(),
                'attachment_relations' => AttachmentRelation::query()->where('content_id', $contentId)->get()->toArray(),
            ];
            $seoMeta = SeoMeta::query()->where('target_type', 'content')->where('target_id', $contentId)->first();
            if ($seoMeta) {
                $relations['seo_meta'] = $seoMeta->toArray();
            }

            $original = \json_encode([
                'content' => $content->toArray(),
                'data' => $data,
                'relations' => $relations,
            ], JSON_UNESCAPED_UNICODE);

            // 回收站记录（表无 updated_at 列，统一走查询构造器避免 Eloquent 时间戳写入）
            $now = now();
            $exists = DB::table('recycle_bin')
                ->where('target_type', 'content')
                ->where('target_id', (string) $contentId)
                ->first();
            if ($exists) {
                DB::table('recycle_bin')->where('id', $exists->id)->update([
                    'deleted_by' => auth()->id() ?? 0,
                    'original_data' => $original,
                    'created_at' => $now,
                ]);
            } else {
                DB::table('recycle_bin')->insert([
                    'deleted_by' => auth()->id() ?? 0,
                    'target_type' => 'content',
                    'target_id' => (string) $contentId,
                    'original_data' => $original,
                    'restore_data' => null,
                    'retention_days' => 30,
                    'created_at' => $now,
                ]);
            }

            // 软删除：行与关联保留，仅置 trash（前台可见性由状态机过滤）
            $content->status = 'trash';
            $content->save();

            return $contentId;
        });
    }

    /**
     * 快照行日期规范化：Eloquent toArray() 会把 datetime 列序列化为 ISO8601
     * （如 2026-09-10T07:09:20.000000Z），MySQL datetime 列无法直接接收，统一转回 Y-m-d H:i:s。
     *
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function normalizeRowDates(array $row): array
    {
        foreach ($row as $key => $value) {
            if (\is_string($value) && \preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $value)) {
                try {
                    $row[$key] = Carbon::parse($value)->format('Y-m-d H:i:s');
                } catch (Throwable) {
                    // 保持原值，交由数据库报错定位
                }
            }
        }

        return $row;
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeSnapshot(mixed $original): array
    {
        if (\is_array($original)) {
            return $original;
        }

        $decoded = \json_decode((string) $original, true);

        return \is_array($decoded) ? $decoded : [];
    }

    // please fill in your code here

}
