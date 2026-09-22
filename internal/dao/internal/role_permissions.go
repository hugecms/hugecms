// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// RolePermissionsDao is the data access object for the table role_permissions.
type RolePermissionsDao struct {
	table    string                 // table is the underlying table name of the DAO.
	group    string                 // group is the database configuration group name of the current DAO.
	columns  RolePermissionsColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler     // handlers for customized model modification.
}

// RolePermissionsColumns defines and stores column names for the table role_permissions.
type RolePermissionsColumns struct {
	Id           string //
	RoleId       string // 角色ID
	PermissionId string // 权限ID
	IsDenied     string // 0=允许，1=拒绝（拒绝优先，覆盖性授权）
	CreatedAt    string //
}

// rolePermissionsColumns holds the columns for the table role_permissions.
var rolePermissionsColumns = RolePermissionsColumns{
	Id:           "id",
	RoleId:       "role_id",
	PermissionId: "permission_id",
	IsDenied:     "is_denied",
	CreatedAt:    "created_at",
}

// NewRolePermissionsDao creates and returns a new DAO object for table data access.
func NewRolePermissionsDao(handlers ...gdb.ModelHandler) *RolePermissionsDao {
	return &RolePermissionsDao{
		group:    "default",
		table:    "role_permissions",
		columns:  rolePermissionsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *RolePermissionsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *RolePermissionsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *RolePermissionsDao) Columns() RolePermissionsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *RolePermissionsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *RolePermissionsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *RolePermissionsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
