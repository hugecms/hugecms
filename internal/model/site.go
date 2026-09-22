package model

import "github.com/gogf/gf/v2/os/gtime"

// SiteItem 站点信息条目
type SiteItem struct {
	Id         int64       `json:"id"`
	SiteName   string      `json:"site_name"`
	SiteCode   string      `json:"site_code"`
	Domain     string      `json:"domain"`
	Domains    string      `json:"domains"`
	SiteLogo   string      `json:"site_logo"`
	Favicon    string      `json:"favicon"`
	Timezone   string      `json:"timezone"`
	Language   string      `json:"language"`
	TemplateId int64       `json:"template_id"`
	Config     string      `json:"config"`
	Status     int         `json:"status"`
	CreatedAt  *gtime.Time `json:"created_at"`
	UpdatedAt  *gtime.Time `json:"updated_at"`
}

// SiteSearchInput 站点查询入参
type SiteSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"page_size"`
	Keyword  string `json:"keyword"`
	SiteCode string `json:"site_code"`
	Domain   string `json:"domain"`
	Status   *int   `json:"status"`
}

// SiteSearchOutput 站点查询出参
type SiteSearchOutput struct {
	List  []SiteItem `json:"list"`
	Total int        `json:"total"`
	Page  int        `json:"page"`
	Size  int        `json:"size"`
}

// SiteCreateInput 创建站点入参
type SiteCreateInput struct {
	SiteName   string `json:"site_name" v:"required|length:2,100#请输入站点名称|站点名称长度为2-100位"`
	SiteCode   string `json:"site_code" v:"required|regex:^[a-zA-Z0-9_-]+$#请输入站点代码|站点代码仅支持英文字母数字下划线减号"`
	Domain     string `json:"domain" v:"required#主域名不能为空"`
	Domains    string `json:"domains"`
	SiteLogo   string `json:"site_logo"`
	Favicon    string `json:"favicon"`
	Timezone   string `json:"timezone" d:"Asia/Shanghai"`
	Language   string `json:"language" d:"zh-CN"`
	TemplateId int64  `json:"template_id"`
	Config     string `json:"config"`
	Status     int    `json:"status" d:"1"`
}

// SiteUpdateInput 更新站点入参
type SiteUpdateInput struct {
	Id         int64  `json:"id" v:"required#站点ID不能为空"`
	SiteName   string `json:"site_name" v:"required|length:2,100#请输入站点名称|站点名称长度为2-100位"`
	SiteCode   string `json:"site_code" v:"required|regex:^[a-zA-Z0-9_-]+$#请输入站点代码|站点代码仅支持英文字母数字下划线减号"`
	Domain     string `json:"domain" v:"required#主域名不能为空"`
	Domains    string `json:"domains"`
	SiteLogo   string `json:"site_logo"`
	Favicon    string `json:"favicon"`
	Timezone   string `json:"timezone"`
	Language   string `json:"language"`
	TemplateId int64  `json:"template_id"`
	Config     string `json:"config"`
	Status     int    `json:"status"`
}
