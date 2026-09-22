// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// RolePermissions is the golang structure of table role_permissions for DAO operations like Where/Data.
type RolePermissions struct {
	g.Meta       `orm:"table:role_permissions, do:true"`
	Id           any         //
	RoleId       any         // 角色ID
	PermissionId any         // 权限ID
	IsDenied     any         // 0=允许，1=拒绝（拒绝优先，覆盖性授权）
	CreatedAt    *gtime.Time //
}
