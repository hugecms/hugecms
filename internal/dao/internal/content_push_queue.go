// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// ContentPushQueueDao is the data access object for the table content_push_queue.
type ContentPushQueueDao struct {
	table    string                  // table is the underlying table name of the DAO.
	group    string                  // group is the database configuration group name of the current DAO.
	columns  ContentPushQueueColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler      // handlers for customized model modification.
}

// ContentPushQueueColumns defines and stores column names for the table content_push_queue.
type ContentPushQueueColumns struct {
	Id           string //
	ContentId    string // 被推送的内容ID
	PushType     string // 推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss
	PushData     string // 推送数据的最终形态（预处理后JSON）
	Status       string // 状态：pending/processing/success/failed（执行与重试走 Laravel 队列）
	RetryCount   string // 已重试次数
	MaxRetries   string // 最大重试次数
	ErrorMessage string // 失败时的错误信息
	FinishedAt   string // 完成时间
	CreatedAt    string //
	UpdatedAt    string //
}

// contentPushQueueColumns holds the columns for the table content_push_queue.
var contentPushQueueColumns = ContentPushQueueColumns{
	Id:           "id",
	ContentId:    "content_id",
	PushType:     "push_type",
	PushData:     "push_data",
	Status:       "status",
	RetryCount:   "retry_count",
	MaxRetries:   "max_retries",
	ErrorMessage: "error_message",
	FinishedAt:   "finished_at",
	CreatedAt:    "created_at",
	UpdatedAt:    "updated_at",
}

// NewContentPushQueueDao creates and returns a new DAO object for table data access.
func NewContentPushQueueDao(handlers ...gdb.ModelHandler) *ContentPushQueueDao {
	return &ContentPushQueueDao{
		group:    "default",
		table:    "content_push_queue",
		columns:  contentPushQueueColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *ContentPushQueueDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *ContentPushQueueDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *ContentPushQueueDao) Columns() ContentPushQueueColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *ContentPushQueueDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *ContentPushQueueDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *ContentPushQueueDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
