// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// AttachmentRelations is the golang structure for table attachment_relations.
type AttachmentRelations struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	AttachmentId uint64      `json:"attachmentId" orm:"attachment_id" ` // 附件ID
	ContentId    uint64      `json:"contentId"    orm:"content_id"    ` // 关联的内容ID
	FieldKey     string      `json:"fieldKey"     orm:"field_key"     ` // 关联到内容的哪个字段（如：封面图、详情图集）
	Sort         int         `json:"sort"         orm:"sort"          ` // 在该内容下的排序
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` //
}
