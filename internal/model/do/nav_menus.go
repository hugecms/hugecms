// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// NavMenus is the golang structure of table nav_menus for DAO operations like Where/Data.
type NavMenus struct {
	g.Meta      `orm:"table:nav_menus, do:true"`
	Id          any         //
	Name        any         // 菜单名称（如：主导航）
	Alias       any         // 菜单标识（如：main_nav）
	Description any         // 描述
	CreatedAt   *gtime.Time //
	UpdatedAt   *gtime.Time //
}
