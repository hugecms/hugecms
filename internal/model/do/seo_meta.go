// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// SeoMeta is the golang structure of table seo_meta for DAO operations like Where/Data.
type SeoMeta struct {
	g.Meta       `orm:"table:seo_meta, do:true"`
	Id           any         //
	TargetType   any         // 目标类型：content/term/custom_page
	TargetId     any         // 对应的目标实体ID
	Title        any         // SEO标题（浏览器Tab显示）
	Keywords     any         // SEO关键词（逗号分隔）
	Description  any         // SEO描述（搜索结果展示）
	CanonicalUrl any         // 权威链接（防止重复页）
	Robots       any         // 机器人抓取策略
	CreatedAt    *gtime.Time //
	UpdatedAt    *gtime.Time //
}
