// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// FriendLinksDao is the data access object for the table friend_links.
type FriendLinksDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  FriendLinksColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// FriendLinksColumns defines and stores column names for the table friend_links.
type FriendLinksColumns struct {
	Id           string //
	Category     string // 链接分类（如：合作伙伴、友情链接）
	SiteName     string // 网站名称
	SiteUrl      string // 网站URL
	LogoUrl      string // 网站Logo URL
	Description  string // 网站描述
	ContactEmail string // 联系人邮箱
	Sort         string // 排序权重
	Status       string // 状态：0待审核，1已审核，2已拒绝
	CreatedAt    string //
	UpdatedAt    string //
}

// friendLinksColumns holds the columns for the table friend_links.
var friendLinksColumns = FriendLinksColumns{
	Id:           "id",
	Category:     "category",
	SiteName:     "site_name",
	SiteUrl:      "site_url",
	LogoUrl:      "logo_url",
	Description:  "description",
	ContactEmail: "contact_email",
	Sort:         "sort",
	Status:       "status",
	CreatedAt:    "created_at",
	UpdatedAt:    "updated_at",
}

// NewFriendLinksDao creates and returns a new DAO object for table data access.
func NewFriendLinksDao(handlers ...gdb.ModelHandler) *FriendLinksDao {
	return &FriendLinksDao{
		group:    "default",
		table:    "friend_links",
		columns:  friendLinksColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *FriendLinksDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *FriendLinksDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *FriendLinksDao) Columns() FriendLinksColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *FriendLinksDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *FriendLinksDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *FriendLinksDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
