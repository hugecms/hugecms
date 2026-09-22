// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// UserRoles is the golang structure of table user_roles for DAO operations like Where/Data.
type UserRoles struct {
	g.Meta    `orm:"table:user_roles, do:true"`
	Id        any         //
	UserId    any         // 用户ID
	RoleId    any         // 角色ID
	DataScope any         // 数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）
	CreatedAt *gtime.Time //
}
