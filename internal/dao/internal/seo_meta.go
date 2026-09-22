// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// SeoMetaDao is the data access object for the table seo_meta.
type SeoMetaDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  SeoMetaColumns     // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// SeoMetaColumns defines and stores column names for the table seo_meta.
type SeoMetaColumns struct {
	Id           string //
	TargetType   string // 目标类型：content/term/custom_page
	TargetId     string // 对应的目标实体ID
	Title        string // SEO标题（浏览器Tab显示）
	Keywords     string // SEO关键词（逗号分隔）
	Description  string // SEO描述（搜索结果展示）
	CanonicalUrl string // 权威链接（防止重复页）
	Robots       string // 机器人抓取策略
	CreatedAt    string //
	UpdatedAt    string //
}

// seoMetaColumns holds the columns for the table seo_meta.
var seoMetaColumns = SeoMetaColumns{
	Id:           "id",
	TargetType:   "target_type",
	TargetId:     "target_id",
	Title:        "title",
	Keywords:     "keywords",
	Description:  "description",
	CanonicalUrl: "canonical_url",
	Robots:       "robots",
	CreatedAt:    "created_at",
	UpdatedAt:    "updated_at",
}

// NewSeoMetaDao creates and returns a new DAO object for table data access.
func NewSeoMetaDao(handlers ...gdb.ModelHandler) *SeoMetaDao {
	return &SeoMetaDao{
		group:    "default",
		table:    "seo_meta",
		columns:  seoMetaColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *SeoMetaDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *SeoMetaDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *SeoMetaDao) Columns() SeoMetaColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *SeoMetaDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *SeoMetaDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *SeoMetaDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
