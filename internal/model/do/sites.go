// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Sites is the golang structure of table sites for DAO operations like Where/Data.
type Sites struct {
	g.Meta     `orm:"table:sites, do:true"`
	Id         any         //
	SiteName   any         // 站点名称
	SiteCode   any         // 站点代码（子域名或标识）
	Domain     any         // 主域名（如：www.example.com）
	Domains    any         // 附加域名列表（JSON数组）
	SiteLogo   any         // 站点Logo
	Favicon    any         // 站点图标
	Timezone   any         // 时区
	Language   any         // 默认语言
	TemplateId any         // 当前使用的模板ID（关联 page_templates，逻辑关联）
	Config     any         // 站点配置（SEO默认值、社交分享等）
	Status     any         // 状态：0停用，1启用
	CreatedAt  *gtime.Time //
	UpdatedAt  *gtime.Time //
}
