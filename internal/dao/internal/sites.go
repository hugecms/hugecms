// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// SitesDao is the data access object for the table sites.
type SitesDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  SitesColumns       // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// SitesColumns defines and stores column names for the table sites.
type SitesColumns struct {
	Id         string //
	SiteName   string // 站点名称
	SiteCode   string // 站点代码（子域名或标识）
	Domain     string // 主域名（如：www.example.com）
	Domains    string // 附加域名列表（JSON数组）
	SiteLogo   string // 站点Logo
	Favicon    string // 站点图标
	Timezone   string // 时区
	Language   string // 默认语言
	TemplateId string // 当前使用的模板ID（关联 page_templates，逻辑关联）
	Config     string // 站点配置（SEO默认值、社交分享等）
	Status     string // 状态：0停用，1启用
	CreatedAt  string //
	UpdatedAt  string //
}

// sitesColumns holds the columns for the table sites.
var sitesColumns = SitesColumns{
	Id:         "id",
	SiteName:   "site_name",
	SiteCode:   "site_code",
	Domain:     "domain",
	Domains:    "domains",
	SiteLogo:   "site_logo",
	Favicon:    "favicon",
	Timezone:   "timezone",
	Language:   "language",
	TemplateId: "template_id",
	Config:     "config",
	Status:     "status",
	CreatedAt:  "created_at",
	UpdatedAt:  "updated_at",
}

// NewSitesDao creates and returns a new DAO object for table data access.
func NewSitesDao(handlers ...gdb.ModelHandler) *SitesDao {
	return &SitesDao{
		group:    "default",
		table:    "sites",
		columns:  sitesColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *SitesDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *SitesDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *SitesDao) Columns() SitesColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *SitesDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *SitesDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *SitesDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
