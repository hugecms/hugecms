// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// OptionsDao is the data access object for the table options.
type OptionsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  OptionsColumns     // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// OptionsColumns defines and stores column names for the table options.
type OptionsColumns struct {
	Id          string //
	OptionKey   string // 配置键名（storage_config/smtp_config/comment_config等）
	OptionValue string // 配置值（支持JSON复杂结构）
	Autoload    string // 启动时自动加载：0否，1是（配合 Laravel Cache 预热）
	CreatedAt   string //
	UpdatedAt   string //
}

// optionsColumns holds the columns for the table options.
var optionsColumns = OptionsColumns{
	Id:          "id",
	OptionKey:   "option_key",
	OptionValue: "option_value",
	Autoload:    "autoload",
	CreatedAt:   "created_at",
	UpdatedAt:   "updated_at",
}

// NewOptionsDao creates and returns a new DAO object for table data access.
func NewOptionsDao(handlers ...gdb.ModelHandler) *OptionsDao {
	return &OptionsDao{
		group:    "default",
		table:    "options",
		columns:  optionsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *OptionsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *OptionsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *OptionsDao) Columns() OptionsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *OptionsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *OptionsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *OptionsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
