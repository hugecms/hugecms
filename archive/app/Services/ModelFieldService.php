<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ContentModel;
use App\Repositories\ModelFieldRepository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;
use RuntimeException;

class ModelFieldService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ModelFieldRepository $repository,
    ) {}

    public function getRepository(): ModelFieldRepository
    {
        return $this->repository;
    }

    /**
     * 新增字段后，向模型数据表（data_{alias}）同步物理列。
     *
     * column_type 形如 varchar(500)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json，
     * 解析失败时回退为 varchar(255)。幂等：表或列不存在时跳过。
     */
    public function addColumnToModelTable(int $modelId, string $columnName, string $columnType, string $comment = ''): void
    {
        if (!preg_match('/^[a-z][a-z0-9_]{0,58}$/', $columnName)) {
            throw new RuntimeException("非法物理列名：{$columnName}");
        }

        $model = ContentModel::query()->find($modelId);
        $tableName = $model?->table_name;
        if (empty($tableName)) {
            throw new RuntimeException("模型 {$modelId} 不存在或未绑定数据表");
        }

        if (!Schema::hasTable($tableName) || Schema::hasColumn($tableName, $columnName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($columnName, $columnType, $comment) {
            if (preg_match('/^varchar\((\d+)\)$/i', $columnType, $m)) {
                $table->string($columnName, (int) $m[1])->default('')->comment($comment);
            } elseif (preg_match('/^decimal\((\d+),(\d+)\)$/i', $columnType, $m)) {
                $table->decimal($columnName, (int) $m[1], (int) $m[2])->nullable()->comment($comment);
            } elseif (preg_match('/^int(eger)?$/i', $columnType)) {
                $table->integer($columnName)->default(0)->comment($comment);
            } elseif (strcasecmp($columnType, 'tinyint(1)') === 0) {
                $table->unsignedTinyInteger($columnName)->default(0)->comment($comment);
            } elseif (strcasecmp($columnType, 'longtext') === 0) {
                $table->longText($columnName)->nullable()->comment($comment);
            } elseif (strcasecmp($columnType, 'text') === 0) {
                $table->text($columnName)->nullable()->comment($comment);
            } elseif (strcasecmp($columnType, 'datetime') === 0) {
                $table->dateTime($columnName)->nullable()->comment($comment);
            } elseif (strcasecmp($columnType, 'json') === 0) {
                $table->json($columnName)->nullable()->comment($comment);
            } else {
                $table->string($columnName)->default('')->comment($comment);
            }
        });
    }

    // please fill in your code here

}
