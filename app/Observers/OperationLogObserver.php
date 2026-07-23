<?php

namespace App\Observers;

use App\Enums\ContentStatus;
use App\Enums\OperationLogType;
use App\Models\OperationLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class OperationLogObserver
{
    /**
     * 写入一条操作日志。
     *
     * @param  Model|class-string|string  $object  被操作对象或对象类型标识(如 'system')
     */
    public static function record(Model|string $object, OperationLogType $type, ?string $summary = null): void
    {
        $objectType = null;
        $objectId = null;

        if ($object instanceof Model) {
            $objectType = self::normalizeObjectType($object);
            $objectId = $object->getKey();
        } elseif (is_string($object)) {
            $objectType = $object === 'system' ? 'system' : $object;
        }

        OperationLog::create([
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'type' => $type,
            'object_type' => $objectType,
            'object_id' => $objectId,
            'summary' => $summary,
        ]);
    }

    public function created(Model $model): void
    {
        self::record($model, OperationLogType::Create, $this->buildSummary($model, 'created'));
    }

    public function updated(Model $model): void
    {
        $type = $this->isPublishEvent($model)
            ? OperationLogType::Publish
            : OperationLogType::Update;

        self::record($model, $type, $this->buildSummary($model, 'updated'));
    }

    public function deleted(Model $model): void
    {
        $type = method_exists($model, 'isForceDeleting') && $model->isForceDeleting()
            ? OperationLogType::ForceDelete
            : OperationLogType::Delete;

        self::record($model, $type, $this->buildSummary($model, 'deleted'));
    }

    public function restored(Model $model): void
    {
        self::record($model, OperationLogType::Restore, $this->buildSummary($model, 'restored'));
    }

    public function forceDeleted(Model $model): void
    {
        self::record($model, OperationLogType::ForceDelete, $this->buildSummary($model, 'force_deleted'));
    }

    /**
     * 判断是否从未发布状态进入已发布状态(发布事件)。
     */
    protected function isPublishEvent(Model $model): bool
    {
        if (! array_key_exists('status', $model->getCasts())) {
            return false;
        }

        $casts = $model->getCasts();
        if ($casts['status'] !== ContentStatus::class) {
            return false;
        }

        $original = $model->getOriginal('status');
        $current = $model->getAttribute('status');

        return $current instanceof ContentStatus
            && $current === ContentStatus::Published
            && (! $original instanceof ContentStatus || $original !== ContentStatus::Published);
    }

    /**
     * 生成变更摘要。created/deleted/restored/force_deleted 仅记录对象类型与标题/ID。
     */
    protected function buildSummary(Model $model, string $event): ?string
    {
        $title = $model->getAttribute('title')
            ?? $model->getAttribute('name')
            ?? ('#'.$model->getKey());

        if (in_array($event, ['created', 'deleted', 'restored', 'force_deleted'], true)) {
            return sprintf('%s %s', self::normalizeObjectType($model), $title);
        }

        $changes = $model->getChanges();
        unset($changes['updated_at']);

        if (empty($changes)) {
            return sprintf('%s %s(无字段变更)', self::normalizeObjectType($model), $title);
        }

        $keys = array_keys($changes);
        $limit = 6;
        $displayKeys = array_slice($keys, 0, $limit);
        $extra = count($keys) > $limit ? sprintf(', 等 %d 项', count($keys)) : '';

        return sprintf('%s %s 变更字段: %s%s', self::normalizeObjectType($model), $title, implode(', ', $displayKeys), $extra);
    }

    /**
     * 把模型类名转换为小写短标识(如 Article -> article)。
     */
    protected static function normalizeObjectType(Model $model): string
    {
        return strtolower(class_basename($model));
    }
}
