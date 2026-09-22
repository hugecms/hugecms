package model

import (
	"github.com/gogf/gf/v2/net/ghttp"
)

const (
	ContextKey = "ContextKey"
)

// Context 请求上下文对象
type Context struct {
	Session *ghttp.Session // 会话管理对象
	User    *ContextUser   // 当前请求的登录用户
	Data    map[string]any // 自定义存储变量
}

// ContextUser 当前登录用户信息与权限载荷
type ContextUser struct {
	Id          int64    `json:"id"`
	Email       string   `json:"email"`
	Name        string   `json:"name"`
	IsSuper     bool     `json:"is_super"`
	RoleIds     []int64  `json:"role_ids"`
	Roles       []string `json:"roles"`       // 角色别名列表，如 ["super_admin"]
	Permissions []string `json:"permissions"` // 权限代码列表，如 ["content:view", "content:create"]
	DataScope   string   `json:"data_scope"`  // 数据权限范围：all全部 / self本人
}

// HasRole 检查当前用户是否具备指定角色标识
func (u *ContextUser) HasRole(roleAlias string) bool {
	if u == nil {
		return false
	}
	if u.IsSuper {
		return true
	}
	for _, r := range u.Roles {
		if r == roleAlias {
			return true
		}
	}
	return false
}

// HasPermission 检查当前用户是否具备指定权限代码（超管直接通过）
func (u *ContextUser) HasPermission(permissionCode string) bool {
	if u == nil {
		return false
	}
	// 超管 Bypass 规则
	if u.IsSuper || u.HasRole("super_admin") {
		return true
	}
	for _, p := range u.Permissions {
		if p == permissionCode {
			return true
		}
	}
	return false
}

// IsAuthor 判断是否仅具备作者本人数据范围
func (u *ContextUser) IsAuthor() bool {
	if u == nil {
		return false
	}
	if u.IsSuper || u.HasRole("super_admin") {
		return false
	}
	return u.DataScope == "self"
}
