// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Roles is the golang structure of table roles for DAO operations like Where/Data.
type Roles struct {
	g.Meta      `orm:"table:roles, do:true"`
	Id          any         //
	Name        any         // 角色名称（如：主编、运营）
	Alias       any         // 角色标识（如：chief_editor）
	IsSystem    any         // 是否系统内置（不可删除）：1是，0否
	Description any         // 角色描述
	CreatedAt   *gtime.Time //
	UpdatedAt   *gtime.Time //
}
