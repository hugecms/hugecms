// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// UserMetaDao is the data access object for the table user_meta.
type UserMetaDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  UserMetaColumns    // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// UserMetaColumns defines and stores column names for the table user_meta.
type UserMetaColumns struct {
	Id        string //
	UserId    string // 关联用户ID
	MetaKey   string // 元数据键名
	MetaValue string // 元数据值（JSON或序列化数据）
	CreatedAt string //
	UpdatedAt string //
}

// userMetaColumns holds the columns for the table user_meta.
var userMetaColumns = UserMetaColumns{
	Id:        "id",
	UserId:    "user_id",
	MetaKey:   "meta_key",
	MetaValue: "meta_value",
	CreatedAt: "created_at",
	UpdatedAt: "updated_at",
}

// NewUserMetaDao creates and returns a new DAO object for table data access.
func NewUserMetaDao(handlers ...gdb.ModelHandler) *UserMetaDao {
	return &UserMetaDao{
		group:    "default",
		table:    "user_meta",
		columns:  userMetaColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *UserMetaDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *UserMetaDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *UserMetaDao) Columns() UserMetaColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *UserMetaDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *UserMetaDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *UserMetaDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
