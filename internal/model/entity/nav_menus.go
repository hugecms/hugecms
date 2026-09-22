// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// NavMenus is the golang structure for table nav_menus.
type NavMenus struct {
	Id          uint64      `json:"id"          orm:"id"          ` //
	Name        string      `json:"name"        orm:"name"        ` // 菜单名称（如：主导航）
	Alias       string      `json:"alias"       orm:"alias"       ` // 菜单标识（如：main_nav）
	Description string      `json:"description" orm:"description" ` // 描述
	CreatedAt   *gtime.Time `json:"createdAt"   orm:"created_at"  ` //
	UpdatedAt   *gtime.Time `json:"updatedAt"   orm:"updated_at"  ` //
}
