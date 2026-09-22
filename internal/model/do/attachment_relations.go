// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// AttachmentRelations is the golang structure of table attachment_relations for DAO operations like Where/Data.
type AttachmentRelations struct {
	g.Meta       `orm:"table:attachment_relations, do:true"`
	Id           any         //
	AttachmentId any         // 附件ID
	ContentId    any         // 关联的内容ID
	FieldKey     any         // 关联到内容的哪个字段（如：封面图、详情图集）
	Sort         any         // 在该内容下的排序
	CreatedAt    *gtime.Time //
}
