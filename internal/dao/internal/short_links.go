// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// ShortLinksDao is the data access object for the table short_links.
type ShortLinksDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  ShortLinksColumns  // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// ShortLinksColumns defines and stores column names for the table short_links.
type ShortLinksColumns struct {
	Id         string //
	ShortCode  string // 短链代码（如：abc123）
	TargetUrl  string // 原始目标URL
	Title      string // 链接标题/备注
	ClickCount string // 点击次数
	QrCodePath string // 二维码图片存储路径
	ExpireAt   string // 过期时间（NULL永不过期）
	Status     string // 状态：0停用，1启用
	CreatedAt  string //
	UpdatedAt  string //
}

// shortLinksColumns holds the columns for the table short_links.
var shortLinksColumns = ShortLinksColumns{
	Id:         "id",
	ShortCode:  "short_code",
	TargetUrl:  "target_url",
	Title:      "title",
	ClickCount: "click_count",
	QrCodePath: "qr_code_path",
	ExpireAt:   "expire_at",
	Status:     "status",
	CreatedAt:  "created_at",
	UpdatedAt:  "updated_at",
}

// NewShortLinksDao creates and returns a new DAO object for table data access.
func NewShortLinksDao(handlers ...gdb.ModelHandler) *ShortLinksDao {
	return &ShortLinksDao{
		group:    "default",
		table:    "short_links",
		columns:  shortLinksColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *ShortLinksDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *ShortLinksDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *ShortLinksDao) Columns() ShortLinksColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *ShortLinksDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *ShortLinksDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *ShortLinksDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
