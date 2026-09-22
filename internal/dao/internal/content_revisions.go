// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// ContentRevisionsDao is the data access object for the table content_revisions.
type ContentRevisionsDao struct {
	table    string                  // table is the underlying table name of the DAO.
	group    string                  // group is the database configuration group name of the current DAO.
	columns  ContentRevisionsColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler      // handlers for customized model modification.
}

// ContentRevisionsColumns defines and stores column names for the table content_revisions.
type ContentRevisionsColumns struct {
	Id           string //
	ContentId    string // 内容主表ID
	AuthorId     string // 修改人
	RevisionData string // 修改时的全量数据快照（JSON）
	Remark       string // 修改备注
	CreatedAt    string // 修订时间
}

// contentRevisionsColumns holds the columns for the table content_revisions.
var contentRevisionsColumns = ContentRevisionsColumns{
	Id:           "id",
	ContentId:    "content_id",
	AuthorId:     "author_id",
	RevisionData: "revision_data",
	Remark:       "remark",
	CreatedAt:    "created_at",
}

// NewContentRevisionsDao creates and returns a new DAO object for table data access.
func NewContentRevisionsDao(handlers ...gdb.ModelHandler) *ContentRevisionsDao {
	return &ContentRevisionsDao{
		group:    "default",
		table:    "content_revisions",
		columns:  contentRevisionsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *ContentRevisionsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *ContentRevisionsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *ContentRevisionsDao) Columns() ContentRevisionsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *ContentRevisionsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *ContentRevisionsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *ContentRevisionsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
