package cron

import (
	"context"

	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gcron"

	"hugecms/internal/model"
	"hugecms/internal/service"
)

type sCron struct{}

func init() {
	service.RegisterCron(New())
}

func New() service.ICron {
	return &sCron{}
}

// Start 注册并启动系统常驻定时任务
func (s *sCron) Start(ctx context.Context) {
	// 1. 每日 03:30 聚合前一天统计数据
	_, _ = gcron.AddSingleton(ctx, "0 30 3 * * *", func(ctx context.Context) {
		g.Log().Info(ctx, "[Cron] 开始执行每日统计数据聚合...")
		if out, err := s.RunStatisticsAggregate(ctx, ""); err != nil {
			g.Log().Errorf(ctx, "[Cron] 统计数据聚合失败: %v", err)
		} else {
			g.Log().Infof(ctx, "[Cron] 统计数据聚合完成, 日期: %s", out.StatDate)
		}
	}, "statistics_daily_aggregate")

	// 2. 每日 03:10 清理超过保留期的回收站数据
	_, _ = gcron.AddSingleton(ctx, "0 10 3 * * *", func(ctx context.Context) {
		g.Log().Info(ctx, "[Cron] 开始执行过期回收站物理清理...")
		if count, err := s.RunPurgeExpiredRecycleBin(ctx); err != nil {
			g.Log().Errorf(ctx, "[Cron] 回收站清理失败: %v", err)
		} else {
			g.Log().Infof(ctx, "[Cron] 回收站清理完成, 彻底清除 %d 条记录", count)
		}
	}, "recycle_bin_purge_expired")

	// 3. 每分钟检查定时发布的内容
	_, _ = gcron.AddSingleton(ctx, "0 * * * * *", func(ctx context.Context) {
		if count, err := s.RunPublishScheduled(ctx); err != nil {
			g.Log().Errorf(ctx, "[Cron] 自动发布定时内容失败: %v", err)
		} else if count > 0 {
			g.Log().Infof(ctx, "[Cron] 自动发布定时内容完成, 共发布 %d 篇", count)
		}
	}, "content_publish_scheduled")

	g.Log().Info(ctx, "[Cron] 定时调度服务已成功启动")
}

// Stop 停止所有定时任务
func (s *sCron) Stop(ctx context.Context) {
	gcron.Stop("statistics_daily_aggregate")
	gcron.Stop("recycle_bin_purge_expired")
	gcron.Stop("content_publish_scheduled")
	g.Log().Info(ctx, "[Cron] 定时调度服务已停止")
}

func (s *sCron) RunStatisticsAggregate(ctx context.Context, date string) (*model.StatisticsDailyAggregateOutput, error) {
	return service.Statistics().Aggregate(ctx, model.StatisticsDailyAggregateInput{Date: date})
}

func (s *sCron) RunPurgeExpiredRecycleBin(ctx context.Context) (int, error) {
	return service.RecycleBin().PurgeExpired(ctx)
}

func (s *sCron) RunPublishScheduled(ctx context.Context) (int, error) {
	return service.Content().PublishScheduled(ctx)
}
