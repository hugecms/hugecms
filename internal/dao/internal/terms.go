// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// TermsDao is the data access object for the table terms.
type TermsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  TermsColumns       // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// TermsColumns defines and stores column names for the table terms.
type TermsColumns struct {
	Id           string //
	TaxonomyId   string // 所属分类法ID
	Name         string // 分类项名称（如：科技、体育）
	Slug         string // 分类项别名（URL友好）
	ParentId     string // 父级ID（0代表顶级）
	Description  string // 分类项描述
	Sort         string // 排序权重
	ContentCount string // 该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）
	CreatedAt    string //
	UpdatedAt    string //
}

// termsColumns holds the columns for the table terms.
var termsColumns = TermsColumns{
	Id:           "id",
	TaxonomyId:   "taxonomy_id",
	Name:         "name",
	Slug:         "slug",
	ParentId:     "parent_id",
	Description:  "description",
	Sort:         "sort",
	ContentCount: "content_count",
	CreatedAt:    "created_at",
	UpdatedAt:    "updated_at",
}

// NewTermsDao creates and returns a new DAO object for table data access.
func NewTermsDao(handlers ...gdb.ModelHandler) *TermsDao {
	return &TermsDao{
		group:    "default",
		table:    "terms",
		columns:  termsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *TermsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *TermsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *TermsDao) Columns() TermsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *TermsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *TermsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *TermsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
