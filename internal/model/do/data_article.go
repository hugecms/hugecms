// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// DataArticle is the golang structure of table data_article for DAO operations like Where/Data.
type DataArticle struct {
	g.Meta    `orm:"table:data_article, do:true"`
	Id        any         //
	ContentId any         // 关联内容主表ID（一对一）
	Field1    any         // 文章摘要（text）
	Field2    any         // 正文内容（rich_text）
	Field3    any         // 封面图，存附件ID或URL（image）
	Extra     any         // 预留JSON扩展字段（未建模数据兜底）
	CreatedAt *gtime.Time //
	UpdatedAt *gtime.Time //
}
