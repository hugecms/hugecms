// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// UserRoles is the golang structure for table user_roles.
type UserRoles struct {
	Id        uint64      `json:"id"        orm:"id"         ` //
	UserId    uint64      `json:"userId"    orm:"user_id"    ` // 用户ID
	RoleId    uint64      `json:"roleId"    orm:"role_id"    ` // 角色ID
	DataScope string      `json:"dataScope" orm:"data_scope" ` // 数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）
	CreatedAt *gtime.Time `json:"createdAt" orm:"created_at" ` //
}
