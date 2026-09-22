// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Permissions is the golang structure of table permissions for DAO operations like Where/Data.
type Permissions struct {
	g.Meta      `orm:"table:permissions, do:true"`
	Id          any         //
	ParentId    any         // 父级权限ID（0表示顶级）
	Name        any         // 权限名称（如：文章编辑）
	Code        any         // 权限代码（如：content:article:edit）
	Module      any         // 所属模块（分组展示用）
	Description any         // 权限描述
	Sort        any         // 排序
	CreatedAt   *gtime.Time //
	UpdatedAt   *gtime.Time //
}
