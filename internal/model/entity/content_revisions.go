// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// ContentRevisions is the golang structure for table content_revisions.
type ContentRevisions struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	ContentId    uint64      `json:"contentId"    orm:"content_id"    ` // 内容主表ID
	AuthorId     uint64      `json:"authorId"     orm:"author_id"     ` // 修改人
	RevisionData string      `json:"revisionData" orm:"revision_data" ` // 修改时的全量数据快照（JSON）
	Remark       string      `json:"remark"       orm:"remark"        ` // 修改备注
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` // 修订时间
}
