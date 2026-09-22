// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// StatisticsDailyDao is the data access object for the table statistics_daily.
type StatisticsDailyDao struct {
	table    string                 // table is the underlying table name of the DAO.
	group    string                 // group is the database configuration group name of the current DAO.
	columns  StatisticsDailyColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler     // handlers for customized model modification.
}

// StatisticsDailyColumns defines and stores column names for the table statistics_daily.
type StatisticsDailyColumns struct {
	Id                string //
	StatDate          string // 统计日期
	NewContents       string // 新增内容数
	PublishedContents string // 发布内容数
	TotalContents     string // 累计内容总数
	TotalViews        string // 全站浏览量
	NewComments       string // 新增评论数
	NewUsers          string // 新增注册用户数
	ActiveUsers       string // 活跃用户数（登录/操作）
	TotalUsers        string // 累计注册用户数
	CreatedAt         string //
	UpdatedAt         string //
}

// statisticsDailyColumns holds the columns for the table statistics_daily.
var statisticsDailyColumns = StatisticsDailyColumns{
	Id:                "id",
	StatDate:          "stat_date",
	NewContents:       "new_contents",
	PublishedContents: "published_contents",
	TotalContents:     "total_contents",
	TotalViews:        "total_views",
	NewComments:       "new_comments",
	NewUsers:          "new_users",
	ActiveUsers:       "active_users",
	TotalUsers:        "total_users",
	CreatedAt:         "created_at",
	UpdatedAt:         "updated_at",
}

// NewStatisticsDailyDao creates and returns a new DAO object for table data access.
func NewStatisticsDailyDao(handlers ...gdb.ModelHandler) *StatisticsDailyDao {
	return &StatisticsDailyDao{
		group:    "default",
		table:    "statistics_daily",
		columns:  statisticsDailyColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *StatisticsDailyDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *StatisticsDailyDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *StatisticsDailyDao) Columns() StatisticsDailyColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *StatisticsDailyDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *StatisticsDailyDao) Ctx(ctx context.Context) *gdb.Model {
	model := dao.DB().Model(dao.table)
	for _, handler := range dao.handlers {
		model = handler(model)
	}
	return model.Safe().Ctx(ctx)
}

// Transaction wraps the transaction logic using function f.
// It rolls back the transaction and returns the error if function f returns a non-nil error.
// It commits the transaction and returns nil if function f returns nil.
//
// Note: Do not commit or roll back the transaction in function f,
// as it is automatically handled by this function.
func (dao *StatisticsDailyDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
