<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\RecycleBin;
use App\Services\RecycleBinService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

#[Signature('recycle:purge-expired')]
#[Description('物理清除回收站中超过保留期的记录（快照目标及级联关联一并删除，见开发约定第一节）')]
class PurgeExpiredRecycleBin extends Command
{
    public function __construct(
        private readonly RecycleBinService $recycleBinService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expiredIds = RecycleBin::query()
            ->where('expire_at', '<', now())
            ->pluck('id');

        $count = 0;
        foreach ($expiredIds as $id) {
            try {
                $this->recycleBinService->purge((int) $id);
                $count++;
            } catch (Throwable $e) {
                $this->error("[#{$id}] 清除失败：{$e->getMessage()}");
            }
        }

        $this->info("已清除 {$count} 条过期回收站记录。");

        return self::SUCCESS;
    }
}
