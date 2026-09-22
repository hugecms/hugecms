// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// AdPositionsDao is the data access object for the table ad_positions.
type AdPositionsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  AdPositionsColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// AdPositionsColumns defines and stores column names for the table ad_positions.
type AdPositionsColumns struct {
	Id          string //
	Name        string // 广告位名称（如：首页Banner）
	Code        string // 广告位代码（如：home_banner，模板调用用）
	Width       string // 建议宽度（像素）
	Height      string // 建议高度（像素）
	AdType      string // 支持的广告类型：image/text/video/html
	MaxCount    string // 该广告位最多展示广告数量
	Description string // 广告位描述
	Status      string // 状态：0停用，1启用
	CreatedAt   string //
	UpdatedAt   string //
}

// adPositionsColumns holds the columns for the table ad_positions.
var adPositionsColumns = AdPositionsColumns{
	Id:          "id",
	Name:        "name",
	Code:        "code",
	Width:       "width",
	Height:      "height",
	AdType:      "ad_type",
	MaxCount:    "max_count",
	Description: "description",
	Status:      "status",
	CreatedAt:   "created_at",
	UpdatedAt:   "updated_at",
}

// NewAdPositionsDao creates and returns a new DAO object for table data access.
func NewAdPositionsDao(handlers ...gdb.ModelHandler) *AdPositionsDao {
	return &AdPositionsDao{
		group:    "default",
		table:    "ad_positions",
		columns:  adPositionsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *AdPositionsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *AdPositionsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *AdPositionsDao) Columns() AdPositionsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *AdPositionsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *AdPositionsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *AdPositionsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
