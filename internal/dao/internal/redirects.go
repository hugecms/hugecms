// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// RedirectsDao is the data access object for the table redirects.
type RedirectsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  RedirectsColumns   // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// RedirectsColumns defines and stores column names for the table redirects.
type RedirectsColumns struct {
	Id         string //
	SourcePath string // 来源路径（站内相对路径，以 / 开头）
	TargetPath string // 目标路径（相对路径或完整URL）
	StatusCode string // HTTP状态码：301永久重定向，302临时重定向
	Hits       string // 命中次数（冗余计数，事务内维护）
	LastHitAt  string // 最后命中时间
	Remark     string // 备注（如：slug 改版、栏目迁移）
	Status     string // 状态：0停用，1启用
	CreatedAt  string //
	UpdatedAt  string //
}

// redirectsColumns holds the columns for the table redirects.
var redirectsColumns = RedirectsColumns{
	Id:         "id",
	SourcePath: "source_path",
	TargetPath: "target_path",
	StatusCode: "status_code",
	Hits:       "hits",
	LastHitAt:  "last_hit_at",
	Remark:     "remark",
	Status:     "status",
	CreatedAt:  "created_at",
	UpdatedAt:  "updated_at",
}

// NewRedirectsDao creates and returns a new DAO object for table data access.
func NewRedirectsDao(handlers ...gdb.ModelHandler) *RedirectsDao {
	return &RedirectsDao{
		group:    "default",
		table:    "redirects",
		columns:  redirectsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *RedirectsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *RedirectsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *RedirectsDao) Columns() RedirectsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *RedirectsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *RedirectsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *RedirectsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
