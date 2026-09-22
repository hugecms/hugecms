// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// TermRelationships is the golang structure of table term_relationships for DAO operations like Where/Data.
type TermRelationships struct {
	g.Meta    `orm:"table:term_relationships, do:true"`
	Id        any         //
	ContentId any         // 内容主表ID
	TermId    any         // 分类项ID
	Sort      any         // 该内容在此分类下的自定义排序
	CreatedAt *gtime.Time //
}
