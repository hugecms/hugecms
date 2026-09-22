// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// ContentModelsDao is the data access object for the table content_models.
type ContentModelsDao struct {
	table    string               // table is the underlying table name of the DAO.
	group    string               // group is the database configuration group name of the current DAO.
	columns  ContentModelsColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler   // handlers for customized model modification.
}

// ContentModelsColumns defines and stores column names for the table content_models.
type ContentModelsColumns struct {
	Id            string //
	Name          string // 模型名称（显示用，如：招聘信息）
	Alias         string // 模型别名（代码/URL用，如：recruitment）
	TableName     string // 对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）
	Description   string // 模型描述
	IsSystem      string // 是否系统内置：1是（不可删除），0否
	IsCommentable string // 是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）
	Status        string // 状态：0停用，1启用
	Sort          string // 排序权重
	CreatedAt     string //
	UpdatedAt     string //
}

// contentModelsColumns holds the columns for the table content_models.
var contentModelsColumns = ContentModelsColumns{
	Id:            "id",
	Name:          "name",
	Alias:         "alias",
	TableName:     "table_name",
	Description:   "description",
	IsSystem:      "is_system",
	IsCommentable: "is_commentable",
	Status:        "status",
	Sort:          "sort",
	CreatedAt:     "created_at",
	UpdatedAt:     "updated_at",
}

// NewContentModelsDao creates and returns a new DAO object for table data access.
func NewContentModelsDao(handlers ...gdb.ModelHandler) *ContentModelsDao {
	return &ContentModelsDao{
		group:    "default",
		table:    "content_models",
		columns:  contentModelsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *ContentModelsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *ContentModelsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *ContentModelsDao) Columns() ContentModelsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *ContentModelsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *ContentModelsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *ContentModelsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
