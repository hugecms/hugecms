// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Roles is the golang structure for table roles.
type Roles struct {
	Id          uint64      `json:"id"          orm:"id"          ` //
	Name        string      `json:"name"        orm:"name"        ` // 角色名称（如：主编、运营）
	Alias       string      `json:"alias"       orm:"alias"       ` // 角色标识（如：chief_editor）
	IsSystem    uint        `json:"isSystem"    orm:"is_system"   ` // 是否系统内置（不可删除）：1是，0否
	Description string      `json:"description" orm:"description" ` // 角色描述
	CreatedAt   *gtime.Time `json:"createdAt"   orm:"created_at"  ` //
	UpdatedAt   *gtime.Time `json:"updatedAt"   orm:"updated_at"  ` //
}
