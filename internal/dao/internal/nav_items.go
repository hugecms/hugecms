// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// NavItemsDao is the data access object for the table nav_items.
type NavItemsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  NavItemsColumns    // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// NavItemsColumns defines and stores column names for the table nav_items.
type NavItemsColumns struct {
	Id        string //
	MenuId    string // 所属菜单集
	ParentId  string // 父级ID（0代表顶级）
	Title     string // 菜单显示标题
	LinkType  string // 链接类型：custom自定义/content内容/term分类
	LinkValue string // 链接目标值（自定义URL 或 content_id/term_id）
	OpenType  string // 打开方式：0本窗口，1新窗口
	Icon      string // 小图标CSS类
	IsActive  string // 是否启用：1是，0否
	Sort      string // 排序权重
	CreatedAt string //
	UpdatedAt string //
}

// navItemsColumns holds the columns for the table nav_items.
var navItemsColumns = NavItemsColumns{
	Id:        "id",
	MenuId:    "menu_id",
	ParentId:  "parent_id",
	Title:     "title",
	LinkType:  "link_type",
	LinkValue: "link_value",
	OpenType:  "open_type",
	Icon:      "icon",
	IsActive:  "is_active",
	Sort:      "sort",
	CreatedAt: "created_at",
	UpdatedAt: "updated_at",
}

// NewNavItemsDao creates and returns a new DAO object for table data access.
func NewNavItemsDao(handlers ...gdb.ModelHandler) *NavItemsDao {
	return &NavItemsDao{
		group:    "default",
		table:    "nav_items",
		columns:  navItemsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *NavItemsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *NavItemsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *NavItemsDao) Columns() NavItemsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *NavItemsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *NavItemsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *NavItemsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
