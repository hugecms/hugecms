// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// DataArticle is the golang structure for table data_article.
type DataArticle struct {
	Id        uint64      `json:"id"        orm:"id"         ` //
	ContentId uint64      `json:"contentId" orm:"content_id" ` // 关联内容主表ID（一对一）
	Field1    string      `json:"field1"    orm:"field_1"    ` // 文章摘要（text）
	Field2    string      `json:"field2"    orm:"field_2"    ` // 正文内容（rich_text）
	Field3    string      `json:"field3"    orm:"field_3"    ` // 封面图，存附件ID或URL（image）
	Extra     string      `json:"Extra"     orm:"_extra"     ` // 预留JSON扩展字段（未建模数据兜底）
	CreatedAt *gtime.Time `json:"createdAt" orm:"created_at" ` //
	UpdatedAt *gtime.Time `json:"updatedAt" orm:"updated_at" ` //
}
