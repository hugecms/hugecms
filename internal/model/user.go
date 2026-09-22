package model

import "github.com/gogf/gf/v2/os/gtime"

// UserSearchInput 用户分页检索入参
type UserSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"pageSize"`
	Keyword  string `json:"keyword"`
	Status   *int   `json:"status"`
	RoleId   int64  `json:"role_id"`
}

// UserSearchOutput 用户分页检索出参
type UserSearchOutput struct {
	List  []UserListItem `json:"list"`
	Total int            `json:"total"`
	Page  int            `json:"page"`
	Size  int            `json:"size"`
}

type UserListItem struct {
	Id            int64       `json:"id"`
	Name          string      `json:"name"`
	Email         string      `json:"email"`
	Avatar        string      `json:"avatar"`
	Status        int         `json:"status"`
	Roles         []string    `json:"roles"`
	LastLoginIp   string      `json:"last_login_ip"`
	LastLoginTime *gtime.Time `json:"last_login_time"`
	CreatedAt     *gtime.Time `json:"created_at"`
}

// UserCreateInput 创建用户入参
type UserCreateInput struct {
	Name      string  `json:"name"`
	Email     string  `json:"email"`
	Password  string  `json:"password"`
	Avatar    string  `json:"avatar"`
	Status    int     `json:"status"`
	RoleIds   []int64 `json:"role_ids"`
	DataScope string  `json:"data_scope"`
}

// UserUpdateInput 更新用户入参
type UserUpdateInput struct {
	Id        int64   `json:"id"`
	Name      string  `json:"name"`
	Email     string  `json:"email"`
	Password  string  `json:"password"` // 留空则不修改密码
	Avatar    string  `json:"avatar"`
	Status    int     `json:"status"`
	RoleIds   []int64 `json:"role_ids"`
	DataScope string  `json:"data_scope"`
}

// UserDetailOutput 用户详情出参
type UserDetailOutput struct {
	Id            int64       `json:"id"`
	Name          string      `json:"name"`
	Email         string      `json:"email"`
	Avatar        string      `json:"avatar"`
	Status        int         `json:"status"`
	RoleIds       []int64     `json:"role_ids"`
	Roles         []string    `json:"roles"`
	DataScope     string      `json:"data_scope"`
	LastLoginIp   string      `json:"last_login_ip"`
	LastLoginTime *gtime.Time `json:"last_login_time"`
	CreatedAt     *gtime.Time `json:"created_at"`
}
