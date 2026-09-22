// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Sites is the golang structure for table sites.
type Sites struct {
	Id         uint64      `json:"id"         orm:"id"          ` //
	SiteName   string      `json:"siteName"   orm:"site_name"   ` // 站点名称
	SiteCode   string      `json:"siteCode"   orm:"site_code"   ` // 站点代码（子域名或标识）
	Domain     string      `json:"domain"     orm:"domain"      ` // 主域名（如：www.example.com）
	Domains    string      `json:"domains"    orm:"domains"     ` // 附加域名列表（JSON数组）
	SiteLogo   string      `json:"siteLogo"   orm:"site_logo"   ` // 站点Logo
	Favicon    string      `json:"favicon"    orm:"favicon"     ` // 站点图标
	Timezone   string      `json:"timezone"   orm:"timezone"    ` // 时区
	Language   string      `json:"language"   orm:"language"    ` // 默认语言
	TemplateId uint64      `json:"templateId" orm:"template_id" ` // 当前使用的模板ID（关联 page_templates，逻辑关联）
	Config     string      `json:"config"     orm:"config"      ` // 站点配置（SEO默认值、社交分享等）
	Status     uint        `json:"status"     orm:"status"      ` // 状态：0停用，1启用
	CreatedAt  *gtime.Time `json:"createdAt"  orm:"created_at"  ` //
	UpdatedAt  *gtime.Time `json:"updatedAt"  orm:"updated_at"  ` //
}
