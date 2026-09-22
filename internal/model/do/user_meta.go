// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// UserMeta is the golang structure of table user_meta for DAO operations like Where/Data.
type UserMeta struct {
	g.Meta    `orm:"table:user_meta, do:true"`
	Id        any         //
	UserId    any         // 关联用户ID
	MetaKey   any         // 元数据键名
	MetaValue any         // 元数据值（JSON或序列化数据）
	CreatedAt *gtime.Time //
	UpdatedAt *gtime.Time //
}
