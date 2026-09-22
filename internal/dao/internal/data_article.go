// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// DataArticleDao is the data access object for the table data_article.
type DataArticleDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  DataArticleColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// DataArticleColumns defines and stores column names for the table data_article.
type DataArticleColumns struct {
	Id        string //
	ContentId string // 关联内容主表ID（一对一）
	Field1    string // 文章摘要（text）
	Field2    string // 正文内容（rich_text）
	Field3    string // 封面图，存附件ID或URL（image）
	Extra     string // 预留JSON扩展字段（未建模数据兜底）
	CreatedAt string //
	UpdatedAt string //
}

// dataArticleColumns holds the columns for the table data_article.
var dataArticleColumns = DataArticleColumns{
	Id:        "id",
	ContentId: "content_id",
	Field1:    "field_1",
	Field2:    "field_2",
	Field3:    "field_3",
	Extra:     "_extra",
	CreatedAt: "created_at",
	UpdatedAt: "updated_at",
}

// NewDataArticleDao creates and returns a new DAO object for table data access.
func NewDataArticleDao(handlers ...gdb.ModelHandler) *DataArticleDao {
	return &DataArticleDao{
		group:    "default",
		table:    "data_article",
		columns:  dataArticleColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *DataArticleDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *DataArticleDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *DataArticleDao) Columns() DataArticleColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *DataArticleDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *DataArticleDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *DataArticleDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
