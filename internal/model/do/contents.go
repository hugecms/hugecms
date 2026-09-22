// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Contents is the golang structure of table contents for DAO operations like Where/Data.
type Contents struct {
	g.Meta       `orm:"table:contents, do:true"`
	Id           any         //
	ModelId      any         // 所属模型ID
	Title        any         // 内容标题
	Slug         any         // URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）
	AuthorId     any         // 发布者用户ID
	Status       any         // 状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站
	Visibility   any         // 可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）
	Password     any         // 密码保护口令（visibility=password 时使用，哈希存储）
	Views        any         // 浏览量计数
	CommentCount any         // 评论数（审核通过的冗余计数，避免列表页逐条COUNT）
	Sort         any         // 手动排序权重（数值越大越靠前）
	IsTop        any         // 是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）
	PublishedAt  *gtime.Time // 计划/实际发布时间
	AuditStatus  any         // 审核状态：pending待审核/approved通过/rejected驳回
	AuditRemark  any         // 审核备注（驳回原因）
	AuditorId    any         // 审核人ID
	AuditedAt    *gtime.Time // 审核时间
	CreatedAt    *gtime.Time //
	UpdatedAt    *gtime.Time //
}
