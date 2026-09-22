// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// BlocksDao is the data access object for the table blocks.
type BlocksDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  BlocksColumns      // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// BlocksColumns defines and stores column names for the table blocks.
type BlocksColumns struct {
	Id        string //
	BlockName string // 区块名称
	BlockType string // 区块类型：header/footer/banner/content/sidebar/custom
	Content   string // 区块内容（HTML/JSON）
	Css       string // 自定义CSS样式
	Js        string // 自定义JS脚本
	IsGlobal  string // 是否全局区块（全站复用）：1是，0否
	Status    string // 状态：0停用，1启用
	CreatedAt string //
	UpdatedAt string //
}

// blocksColumns holds the columns for the table blocks.
var blocksColumns = BlocksColumns{
	Id:        "id",
	BlockName: "block_name",
	BlockType: "block_type",
	Content:   "content",
	Css:       "css",
	Js:        "js",
	IsGlobal:  "is_global",
	Status:    "status",
	CreatedAt: "created_at",
	UpdatedAt: "updated_at",
}

// NewBlocksDao creates and returns a new DAO object for table data access.
func NewBlocksDao(handlers ...gdb.ModelHandler) *BlocksDao {
	return &BlocksDao{
		group:    "default",
		table:    "blocks",
		columns:  blocksColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *BlocksDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *BlocksDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *BlocksDao) Columns() BlocksColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *BlocksDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *BlocksDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *BlocksDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
