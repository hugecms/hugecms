// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// RolePermissions is the golang structure for table role_permissions.
type RolePermissions struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	RoleId       uint64      `json:"roleId"       orm:"role_id"       ` // 角色ID
	PermissionId uint64      `json:"permissionId" orm:"permission_id" ` // 权限ID
	IsDenied     uint        `json:"isDenied"     orm:"is_denied"     ` // 0=允许，1=拒绝（拒绝优先，覆盖性授权）
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` //
}
