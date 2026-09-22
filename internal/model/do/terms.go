// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Terms is the golang structure of table terms for DAO operations like Where/Data.
type Terms struct {
	g.Meta       `orm:"table:terms, do:true"`
	Id           any         //
	TaxonomyId   any         // 所属分类法ID
	Name         any         // 分类项名称（如：科技、体育）
	Slug         any         // 分类项别名（URL友好）
	ParentId     any         // 父级ID（0代表顶级）
	Description  any         // 分类项描述
	Sort         any         // 排序权重
	ContentCount any         // 该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）
	CreatedAt    *gtime.Time //
	UpdatedAt    *gtime.Time //
}
