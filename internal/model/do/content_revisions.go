// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// ContentRevisions is the golang structure of table content_revisions for DAO operations like Where/Data.
type ContentRevisions struct {
	g.Meta       `orm:"table:content_revisions, do:true"`
	Id           any         //
	ContentId    any         // 内容主表ID
	AuthorId     any         // 修改人
	RevisionData any         // 修改时的全量数据快照（JSON）
	Remark       any         // 修改备注
	CreatedAt    *gtime.Time // 修订时间
}
