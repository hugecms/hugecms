// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// UserRolesDao is the data access object for the table user_roles.
type UserRolesDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  UserRolesColumns   // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// UserRolesColumns defines and stores column names for the table user_roles.
type UserRolesColumns struct {
	Id        string //
	UserId    string // 用户ID
	RoleId    string // 角色ID
	DataScope string // 数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）
	CreatedAt string //
}

// userRolesColumns holds the columns for the table user_roles.
var userRolesColumns = UserRolesColumns{
	Id:        "id",
	UserId:    "user_id",
	RoleId:    "role_id",
	DataScope: "data_scope",
	CreatedAt: "created_at",
}

// NewUserRolesDao creates and returns a new DAO object for table data access.
func NewUserRolesDao(handlers ...gdb.ModelHandler) *UserRolesDao {
	return &UserRolesDao{
		group:    "default",
		table:    "user_roles",
		columns:  userRolesColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *UserRolesDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *UserRolesDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *UserRolesDao) Columns() UserRolesColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *UserRolesDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *UserRolesDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *UserRolesDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
