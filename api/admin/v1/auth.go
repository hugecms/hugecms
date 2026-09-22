package v1

import (
	"time"

	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// AuthLoginReq 登录请求
type AuthLoginReq struct {
	g.Meta   `path:"/auth/login" method:"post" tags:"认证鉴权" summary:"管理员登录"`
	Email    string `json:"email" v:"required|email#请输入邮箱|邮箱格式不正确" dc:"电子邮箱"`
	Password string `json:"password" v:"required|length:6,32#请输入密码|密码长度需在6-32位" dc:"登录密码"`
}

// AuthLoginRes 登录响应
type AuthLoginRes struct {
	Token    string    `json:"token" dc:"JWT 鉴权凭证"`
	ExpireAt time.Time `json:"expire_at" dc:"过期时间"`
	User     UserInfo  `json:"user" dc:"用户信息"`
}

// AuthLogoutReq 登出请求
type AuthLogoutReq struct {
	g.Meta `path:"/auth/logout" method:"post" tags:"认证鉴权" summary:"退出登录"`
}

// AuthLogoutRes 登出响应
type AuthLogoutRes struct{}

// AuthInfoReq 当前登录用户信息请求
type AuthInfoReq struct {
	g.Meta `path:"/auth/info" method:"get" tags:"认证鉴权" summary:"获取当前登录用户信息与权限"`
}

// AuthInfoRes 当前登录用户信息响应
type AuthInfoRes struct {
	Id          int64       `json:"id" dc:"用户ID"`
	Name        string      `json:"name" dc:"昵称"`
	Email       string      `json:"email" dc:"邮箱"`
	Avatar      string      `json:"avatar" dc:"头像"`
	Status      int         `json:"status" dc:"状态"`
	Roles       []string    `json:"roles" dc:"所属角色别名"`
	Permissions []string    `json:"permissions" dc:"拥有权限代码"`
	CreatedAt   *gtime.Time `json:"created_at" dc:"注册时间"`
}

type UserInfo struct {
	Id      int64    `json:"id" dc:"用户ID"`
	Name    string   `json:"name" dc:"昵称"`
	Email   string   `json:"email" dc:"邮箱"`
	IsSuper bool     `json:"is_super" dc:"是否超级管理员"`
	Roles   []string `json:"roles" dc:"角色列表"`
}
