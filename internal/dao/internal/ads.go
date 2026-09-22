// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// AdsDao is the data access object for the table ads.
type AdsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  AdsColumns         // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// AdsColumns defines and stores column names for the table ads.
type AdsColumns struct {
	Id           string //
	PositionId   string // 所属广告位ID
	Title        string // 广告标题
	AdType       string // 广告类型：image/text/video/html
	CoverImage   string // 广告图片/视频封面URL
	Content      string // 广告内容（纯文本或HTML代码）
	LinkUrl      string // 广告跳转链接
	LinkTarget   string // 打开方式：0本窗口，1新窗口
	Sort         string // 展示排序（数值越小越靠前）
	StartTime    string // 投放开始时间（NULL表示立即开始）
	EndTime      string // 投放结束时间（NULL表示永久，过期由时间判断）
	DisplayLimit string // 展示次数上限（0不限）
	ClickLimit   string // 点击次数上限（0不限）
	DisplayCount string // 实际展示次数
	ClickCount   string // 实际点击次数
	Status       string // 状态：0停用，1启用
	CreatedAt    string //
	UpdatedAt    string //
}

// adsColumns holds the columns for the table ads.
var adsColumns = AdsColumns{
	Id:           "id",
	PositionId:   "position_id",
	Title:        "title",
	AdType:       "ad_type",
	CoverImage:   "cover_image",
	Content:      "content",
	LinkUrl:      "link_url",
	LinkTarget:   "link_target",
	Sort:         "sort",
	StartTime:    "start_time",
	EndTime:      "end_time",
	DisplayLimit: "display_limit",
	ClickLimit:   "click_limit",
	DisplayCount: "display_count",
	ClickCount:   "click_count",
	Status:       "status",
	CreatedAt:    "created_at",
	UpdatedAt:    "updated_at",
}

// NewAdsDao creates and returns a new DAO object for table data access.
func NewAdsDao(handlers ...gdb.ModelHandler) *AdsDao {
	return &AdsDao{
		group:    "default",
		table:    "ads",
		columns:  adsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *AdsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *AdsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *AdsDao) Columns() AdsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *AdsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *AdsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *AdsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
