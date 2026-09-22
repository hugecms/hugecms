// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// TermRelationships is the golang structure for table term_relationships.
type TermRelationships struct {
	Id        uint64      `json:"id"        orm:"id"         ` //
	ContentId uint64      `json:"contentId" orm:"content_id" ` // 内容主表ID
	TermId    uint64      `json:"termId"    orm:"term_id"    ` // 分类项ID
	Sort      int         `json:"sort"      orm:"sort"       ` // 该内容在此分类下的自定义排序
	CreatedAt *gtime.Time `json:"createdAt" orm:"created_at" ` //
}
