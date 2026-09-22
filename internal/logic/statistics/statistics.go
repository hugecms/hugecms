package statistics

import (
	"context"
	"time"

	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/do"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sStatistics struct{}

func init() {
	service.RegisterStatistics(New())
}

func New() service.IStatistics {
	return &sStatistics{}
}

func (s *sStatistics) Aggregate(ctx context.Context, in model.StatisticsDailyAggregateInput) (*model.StatisticsDailyAggregateOutput, error) {
	day := in.Date
	if day == "" {
		// 默认统计昨天
		yesterday := time.Now().AddDate(0, 0, -1)
		day = yesterday.Format("2006-01-02")
	}

	start := day + " 00:00:00"
	end := day + " 23:59:59"

	// 1. 新增内容数
	newContents, err := dao.Contents.Ctx(ctx).WhereBetween(dao.Contents.Columns().CreatedAt, start, end).Count()
	if err != nil {
		return nil, err
	}

	// 2. 已发布内容数
	publishedContents, err := dao.Contents.Ctx(ctx).
		Where(dao.Contents.Columns().Status, "published").
		WhereBetween(dao.Contents.Columns().PublishedAt, start, end).
		Count()
	if err != nil {
		return nil, err
	}

	// 3. 累计内容总数（非回收站）
	totalContents, err := dao.Contents.Ctx(ctx).
		WhereNot(dao.Contents.Columns().Status, "trash").
		Count()
	if err != nil {
		return nil, err
	}

	// 4. 全站总浏览量
	totalViewsVal, err := dao.Contents.Ctx(ctx).Sum(dao.Contents.Columns().Views)
	if err != nil {
		return nil, err
	}
	totalViews := uint64(totalViewsVal)

	// 5. 新增评论数
	newComments, err := dao.Comments.Ctx(ctx).WhereBetween(dao.Comments.Columns().CreatedAt, start, end).Count()
	if err != nil {
		return nil, err
	}

	// 6. 新增注册用户数
	newUsers, err := dao.Users.Ctx(ctx).WhereBetween(dao.Users.Columns().CreatedAt, start, end).Count()
	if err != nil {
		return nil, err
	}

	// 7. 活跃用户数（最后登录时间在统计日内）
	activeUsers, err := dao.Users.Ctx(ctx).WhereBetween(dao.Users.Columns().LastLoginTime, start, end).Count()
	if err != nil {
		return nil, err
	}

	// 8. 累计注册用户数
	totalUsers, err := dao.Users.Ctx(ctx).Count()
	if err != nil {
		return nil, err
	}

	// 幂等：按日覆盖更新
	exists, err := dao.StatisticsDaily.Ctx(ctx).Where(dao.StatisticsDaily.Columns().StatDate, day).Count()
	if err != nil {
		return nil, err
	}

	statDateTime := gtime.NewFromStr(day)
	if exists > 0 {
		_, err = dao.StatisticsDaily.Ctx(ctx).Where(dao.StatisticsDaily.Columns().StatDate, day).Update(do.StatisticsDaily{
			NewContents:       uint(newContents),
			PublishedContents: uint(publishedContents),
			TotalContents:     uint(totalContents),
			TotalViews:        totalViews,
			NewComments:       uint(newComments),
			NewUsers:          uint(newUsers),
			ActiveUsers:       uint(activeUsers),
			TotalUsers:        uint(totalUsers),
			UpdatedAt:         gtime.Now(),
		})
	} else {
		_, err = dao.StatisticsDaily.Ctx(ctx).Insert(do.StatisticsDaily{
			StatDate:          statDateTime,
			NewContents:       uint(newContents),
			PublishedContents: uint(publishedContents),
			TotalContents:     uint(totalContents),
			TotalViews:        totalViews,
			NewComments:       uint(newComments),
			NewUsers:          uint(newUsers),
			ActiveUsers:       uint(activeUsers),
			TotalUsers:        uint(totalUsers),
			CreatedAt:         gtime.Now(),
			UpdatedAt:         gtime.Now(),
		})
	}

	if err != nil {
		return nil, err
	}

	return &model.StatisticsDailyAggregateOutput{
		StatDate:          day,
		NewContents:       uint(newContents),
		PublishedContents: uint(publishedContents),
		TotalContents:     uint(totalContents),
		TotalViews:        totalViews,
		NewComments:       uint(newComments),
		NewUsers:          uint(newUsers),
		ActiveUsers:       uint(activeUsers),
		TotalUsers:        uint(totalUsers),
	}, nil
}

func (s *sStatistics) Search(ctx context.Context, in model.StatisticsDailySearchInput) (*model.StatisticsDailySearchOutput, error) {
	m := dao.StatisticsDaily.Ctx(ctx)
	if in.StartDate != "" {
		m = m.WhereGTE(dao.StatisticsDaily.Columns().StatDate, in.StartDate)
	}
	if in.EndDate != "" {
		m = m.WhereLTE(dao.StatisticsDaily.Columns().StatDate, in.EndDate)
	}

	limit := in.Limit
	if limit <= 0 {
		limit = 30
	}

	var items []entity.StatisticsDaily
	if err := m.OrderDesc(dao.StatisticsDaily.Columns().StatDate).Limit(limit).Scan(&items); err != nil {
		return nil, err
	}

	list := make([]model.StatisticsDailyItem, len(items))
	for i, item := range items {
		list[i] = model.StatisticsDailyItem{
			Id:                item.Id,
			StatDate:          item.StatDate,
			NewContents:       item.NewContents,
			PublishedContents: item.PublishedContents,
			TotalContents:     item.TotalContents,
			TotalViews:        item.TotalViews,
			NewComments:       item.NewComments,
			NewUsers:          item.NewUsers,
			ActiveUsers:       item.ActiveUsers,
			TotalUsers:        item.TotalUsers,
			CreatedAt:         item.CreatedAt,
			UpdatedAt:         item.UpdatedAt,
		}
	}

	return &model.StatisticsDailySearchOutput{
		List: list,
	}, nil
}
