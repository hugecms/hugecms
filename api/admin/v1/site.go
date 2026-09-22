package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

// SiteSearchReq 站点列表查询请求
type SiteSearchReq struct {
	g.Meta   `path:"/sites" method:"get" tags:"站点管理" summary:"分页查询站点列表"`
	Page     int    `json:"page" in:"query" d:"1" dc:"页码"`
	PageSize int    `json:"page_size" in:"query" d:"10" dc:"每页条数"`
	Keyword  string `json:"keyword" in:"query" dc:"搜索关键词（名称/代码）"`
	SiteCode string `json:"site_code" in:"query" dc:"站点代码"`
	Domain   string `json:"domain" in:"query" dc:"主域名"`
	Status   *int   `json:"status" in:"query" dc:"状态筛选：1启用 0停用"`
}

// SiteSearchRes 站点列表响应
type SiteSearchRes struct {
	*model.SiteSearchOutput
}

// SiteGetReq 获取站点详情请求
type SiteGetReq struct {
	g.Meta `path:"/sites/{id}" method:"get" tags:"站点管理" summary:"获取站点详情"`
	Id     int64 `json:"id" in:"path" v:"required#站点ID不能为空" dc:"站点ID"`
}

// SiteGetRes 站点详情响应
type SiteGetRes struct {
	*model.SiteItem
}

// SiteCreateReq 创建站点请求
type SiteCreateReq struct {
	g.Meta     `path:"/sites" method:"post" tags:"站点管理" summary:"创建站点"`
	SiteName   string `json:"site_name" v:"required|length:2,100#请输入站点名称|站点名称长度为2-100位" dc:"站点名称"`
	SiteCode   string `json:"site_code" v:"required|regex:^[a-zA-Z0-9_-]+$#请输入站点代码|站点代码仅支持英文字母数字下划线减号" dc:"站点标识代码"`
	Domain     string `json:"domain" v:"required#主域名不能为空" dc:"主域名"`
	Domains    string `json:"domains" dc:"附加域名列表（JSON数组）"`
	SiteLogo   string `json:"site_logo" dc:"站点Logo URL"`
	Favicon    string `json:"favicon" dc:"站点图标 URL"`
	Timezone   string `json:"timezone" d:"Asia/Shanghai" dc:"时区"`
	Language   string `json:"language" d:"zh-CN" dc:"默认语言"`
	TemplateId int64  `json:"template_id" dc:"页面模板ID"`
	Config     string `json:"config" dc:"扩展配置JSON"`
	Status     int    `json:"status" d:"1" dc:"状态：1启用 0停用"`
}

// SiteCreateRes 创建站点响应
type SiteCreateRes struct {
	Id int64 `json:"id" dc:"新站点ID"`
}

// SiteUpdateReq 更新站点请求
type SiteUpdateReq struct {
	g.Meta     `path:"/sites/{id}" method:"put" tags:"站点管理" summary:"更新站点"`
	Id         int64  `json:"id" in:"path" v:"required#站点ID不能为空" dc:"站点ID"`
	SiteName   string `json:"site_name" v:"required|length:2,100#请输入站点名称|站点名称长度为2-100位" dc:"站点名称"`
	SiteCode   string `json:"site_code" v:"required|regex:^[a-zA-Z0-9_-]+$#请输入站点代码|站点代码仅支持英文字母数字下划线减号" dc:"站点标识代码"`
	Domain     string `json:"domain" v:"required#主域名不能为空" dc:"主域名"`
	Domains    string `json:"domains" dc:"附加域名列表（JSON数组）"`
	SiteLogo   string `json:"site_logo" dc:"站点Logo URL"`
	Favicon    string `json:"favicon" dc:"站点图标 URL"`
	Timezone   string `json:"timezone" dc:"时区"`
	Language   string `json:"language" dc:"默认语言"`
	TemplateId int64  `json:"template_id" dc:"页面模板ID"`
	Config     string `json:"config" dc:"扩展配置JSON"`
	Status     int    `json:"status" dc:"状态：1启用 0停用"`
}

// SiteUpdateRes 更新站点响应
type SiteUpdateRes struct{}

// SiteDeleteReq 删除站点请求
type SiteDeleteReq struct {
	g.Meta `path:"/sites/{id}" method:"delete" tags:"站点管理" summary:"删除站点"`
	Id     int64 `json:"id" in:"path" v:"required#站点ID不能为空" dc:"站点ID"`
}

// SiteDeleteRes 删除站点响应
type SiteDeleteRes struct{}
