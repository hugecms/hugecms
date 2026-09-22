// ================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// You can delete these comments if you wish manually maintain this interface file.
// ================================================================================

package service

import (
	"context"
	"hugecms/internal/model"
)

type (
	ICron interface {
		// Start 注册并启动系统常驻定时任务
		Start(ctx context.Context)
		// Stop 停止所有定时任务
		Stop(ctx context.Context)
		RunStatisticsAggregate(ctx context.Context, date string) (*model.StatisticsDailyAggregateOutput, error)
		RunPurgeExpiredRecycleBin(ctx context.Context) (int, error)
		RunPublishScheduled(ctx context.Context) (int, error)
	}
)

var (
	localCron ICron
)

func Cron() ICron {
	if localCron == nil {
		panic("implement not found for interface ICron, forgot register?")
	}
	return localCron
}

func RegisterCron(i ICron) {
	localCron = i
}
