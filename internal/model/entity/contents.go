// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Contents is the golang structure for table contents.
type Contents struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	ModelId      uint64      `json:"modelId"      orm:"model_id"      ` // 所属模型ID
	Title        string      `json:"title"        orm:"title"         ` // 内容标题
	Slug         string      `json:"slug"         orm:"slug"          ` // URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）
	AuthorId     uint64      `json:"authorId"     orm:"author_id"     ` // 发布者用户ID
	Status       string      `json:"status"       orm:"status"        ` // 状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站
	Visibility   string      `json:"visibility"   orm:"visibility"    ` // 可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）
	Password     string      `json:"password"     orm:"password"      ` // 密码保护口令（visibility=password 时使用，哈希存储）
	Views        uint64      `json:"views"        orm:"views"         ` // 浏览量计数
	CommentCount uint        `json:"commentCount" orm:"comment_count" ` // 评论数（审核通过的冗余计数，避免列表页逐条COUNT）
	Sort         int         `json:"sort"         orm:"sort"          ` // 手动排序权重（数值越大越靠前）
	IsTop        uint        `json:"isTop"        orm:"is_top"        ` // 是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）
	PublishedAt  *gtime.Time `json:"publishedAt"  orm:"published_at"  ` // 计划/实际发布时间
	AuditStatus  string      `json:"auditStatus"  orm:"audit_status"  ` // 审核状态：pending待审核/approved通过/rejected驳回
	AuditRemark  string      `json:"auditRemark"  orm:"audit_remark"  ` // 审核备注（驳回原因）
	AuditorId    uint64      `json:"auditorId"    orm:"auditor_id"    ` // 审核人ID
	AuditedAt    *gtime.Time `json:"auditedAt"    orm:"audited_at"    ` // 审核时间
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` //
	UpdatedAt    *gtime.Time `json:"updatedAt"    orm:"updated_at"    ` //
}
