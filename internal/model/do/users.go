// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Users is the golang structure of table users for DAO operations like Where/Data.
type Users struct {
	g.Meta          `orm:"table:users, do:true"`
	Id              any         //
	Name            any         // 显示昵称
	Email           any         //
	EmailVerifiedAt *gtime.Time //
	Password        any         //
	Avatar          any         // 头像URL
	Status          any         // 状态：0禁用，1启用
	LastLoginIp     any         // 最后登录IP（支持IPv6）
	LastLoginTime   *gtime.Time // 最后登录时间
	RememberToken   any         //
	ResetToken      any         //
	CreatedAt       *gtime.Time //
	UpdatedAt       *gtime.Time //
}
