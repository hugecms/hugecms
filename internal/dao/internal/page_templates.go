// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// PageTemplatesDao is the data access object for the table page_templates.
type PageTemplatesDao struct {
	table    string               // table is the underlying table name of the DAO.
	group    string               // group is the database configuration group name of the current DAO.
	columns  PageTemplatesColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler   // handlers for customized model modification.
}

// PageTemplatesColumns defines and stores column names for the table page_templates.
type PageTemplatesColumns struct {
	Id           string //
	TemplateName string // 模板名称
	TemplateCode string // 模板代码（唯一标识）
	Category     string // 类别：page页面/post文章/term分类模板
	PreviewImage string // 预览图URL
	Content      string // 模板内容（HTML/JSON结构）
	IsDefault    string // 是否默认模板
	IsSystem     string // 是否系统内置
	Status       string // 状态：0停用，1启用
	CreatedAt    string //
	UpdatedAt    string //
}

// pageTemplatesColumns holds the columns for the table page_templates.
var pageTemplatesColumns = PageTemplatesColumns{
	Id:           "id",
	TemplateName: "template_name",
	TemplateCode: "template_code",
	Category:     "category",
	PreviewImage: "preview_image",
	Content:      "content",
	IsDefault:    "is_default",
	IsSystem:     "is_system",
	Status:       "status",
	CreatedAt:    "created_at",
	UpdatedAt:    "updated_at",
}

// NewPageTemplatesDao creates and returns a new DAO object for table data access.
func NewPageTemplatesDao(handlers ...gdb.ModelHandler) *PageTemplatesDao {
	return &PageTemplatesDao{
		group:    "default",
		table:    "page_templates",
		columns:  pageTemplatesColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *PageTemplatesDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *PageTemplatesDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *PageTemplatesDao) Columns() PageTemplatesColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *PageTemplatesDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *PageTemplatesDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *PageTemplatesDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
