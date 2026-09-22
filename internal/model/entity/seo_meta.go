// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// SeoMeta is the golang structure for table seo_meta.
type SeoMeta struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	TargetType   string      `json:"targetType"   orm:"target_type"   ` // 目标类型：content/term/custom_page
	TargetId     uint64      `json:"targetId"     orm:"target_id"     ` // 对应的目标实体ID
	Title        string      `json:"title"        orm:"title"         ` // SEO标题（浏览器Tab显示）
	Keywords     string      `json:"keywords"     orm:"keywords"      ` // SEO关键词（逗号分隔）
	Description  string      `json:"description"  orm:"description"   ` // SEO描述（搜索结果展示）
	CanonicalUrl string      `json:"canonicalUrl" orm:"canonical_url" ` // 权威链接（防止重复页）
	Robots       string      `json:"robots"       orm:"robots"        ` // 机器人抓取策略
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` //
	UpdatedAt    *gtime.Time `json:"updatedAt"    orm:"updated_at"    ` //
}
