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
	IStatistics interface {
		Aggregate(ctx context.Context, in model.StatisticsDailyAggregateInput) (*model.StatisticsDailyAggregateOutput, error)
		Search(ctx context.Context, in model.StatisticsDailySearchInput) (*model.StatisticsDailySearchOutput, error)
	}
)

var (
	localStatistics IStatistics
)

func Statistics() IStatistics {
	if localStatistics == nil {
		panic("implement not found for interface IStatistics, forgot register?")
	}
	return localStatistics
}

func RegisterStatistics(i IStatistics) {
	localStatistics = i
}
