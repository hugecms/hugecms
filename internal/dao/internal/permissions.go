// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// PermissionsDao is the data access object for the table permissions.
type PermissionsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  PermissionsColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// PermissionsColumns defines and stores column names for the table permissions.
type PermissionsColumns struct {
	Id          string //
	ParentId    string // 父级权限ID（0表示顶级）
	Name        string // 权限名称（如：文章编辑）
	Code        string // 权限代码（如：content:article:edit）
	Module      string // 所属模块（分组展示用）
	Description string // 权限描述
	Sort        string // 排序
	CreatedAt   string //
	UpdatedAt   string //
}

// permissionsColumns holds the columns for the table permissions.
var permissionsColumns = PermissionsColumns{
	Id:          "id",
	ParentId:    "parent_id",
	Name:        "name",
	Code:        "code",
	Module:      "module",
	Description: "description",
	Sort:        "sort",
	CreatedAt:   "created_at",
	UpdatedAt:   "updated_at",
}

// NewPermissionsDao creates and returns a new DAO object for table data access.
func NewPermissionsDao(handlers ...gdb.ModelHandler) *PermissionsDao {
	return &PermissionsDao{
		group:    "default",
		table:    "permissions",
		columns:  permissionsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *PermissionsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *PermissionsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *PermissionsDao) Columns() PermissionsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *PermissionsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *PermissionsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *PermissionsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
