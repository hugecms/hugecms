// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Users is the golang structure for table users.
type Users struct {
	Id              uint64      `json:"id"              orm:"id"                ` //
	Name            string      `json:"name"            orm:"name"              ` // 显示昵称
	Email           string      `json:"email"           orm:"email"             ` //
	EmailVerifiedAt *gtime.Time `json:"emailVerifiedAt" orm:"email_verified_at" ` //
	Password        string      `json:"password"        orm:"password"          ` //
	Avatar          string      `json:"avatar"          orm:"avatar"            ` // 头像URL
	Status          uint        `json:"status"          orm:"status"            ` // 状态：0禁用，1启用
	LastLoginIp     string      `json:"lastLoginIp"     orm:"last_login_ip"     ` // 最后登录IP（支持IPv6）
	LastLoginTime   *gtime.Time `json:"lastLoginTime"   orm:"last_login_time"   ` // 最后登录时间
	RememberToken   string      `json:"rememberToken"   orm:"remember_token"    ` //
	ResetToken      string      `json:"resetToken"      orm:"reset_token"       ` //
	CreatedAt       *gtime.Time `json:"createdAt"       orm:"created_at"        ` //
	UpdatedAt       *gtime.Time `json:"updatedAt"       orm:"updated_at"        ` //
}
