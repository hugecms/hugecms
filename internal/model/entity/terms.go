// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Terms is the golang structure for table terms.
type Terms struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	TaxonomyId   uint64      `json:"taxonomyId"   orm:"taxonomy_id"   ` // 所属分类法ID
	Name         string      `json:"name"         orm:"name"          ` // 分类项名称（如：科技、体育）
	Slug         string      `json:"slug"         orm:"slug"          ` // 分类项别名（URL友好）
	ParentId     uint64      `json:"parentId"     orm:"parent_id"     ` // 父级ID（0代表顶级）
	Description  string      `json:"description"  orm:"description"   ` // 分类项描述
	Sort         int         `json:"sort"         orm:"sort"          ` // 排序权重
	ContentCount uint        `json:"contentCount" orm:"content_count" ` // 该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` //
	UpdatedAt    *gtime.Time `json:"updatedAt"    orm:"updated_at"    ` //
}
