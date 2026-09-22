// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// ShortLinkClicksDao is the data access object for the table short_link_clicks.
type ShortLinkClicksDao struct {
	table    string                 // table is the underlying table name of the DAO.
	group    string                 // group is the database configuration group name of the current DAO.
	columns  ShortLinkClicksColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler     // handlers for customized model modification.
}

// ShortLinkClicksColumns defines and stores column names for the table short_link_clicks.
type ShortLinkClicksColumns struct {
	Id          string //
	ShortLinkId string // 短链接ID
	ClickIp     string // 点击者IP
	UserAgent   string // 浏览器UA
	Referer     string // 来源页
	CreatedAt   string //
}

// shortLinkClicksColumns holds the columns for the table short_link_clicks.
var shortLinkClicksColumns = ShortLinkClicksColumns{
	Id:          "id",
	ShortLinkId: "short_link_id",
	ClickIp:     "click_ip",
	UserAgent:   "user_agent",
	Referer:     "referer",
	CreatedAt:   "created_at",
}

// NewShortLinkClicksDao creates and returns a new DAO object for table data access.
func NewShortLinkClicksDao(handlers ...gdb.ModelHandler) *ShortLinkClicksDao {
	return &ShortLinkClicksDao{
		group:    "default",
		table:    "short_link_clicks",
		columns:  shortLinkClicksColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *ShortLinkClicksDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *ShortLinkClicksDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *ShortLinkClicksDao) Columns() ShortLinkClicksColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *ShortLinkClicksDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *ShortLinkClicksDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *ShortLinkClicksDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
