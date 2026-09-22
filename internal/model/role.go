package model

import "github.com/gogf/gf/v2/os/gtime"

// RoleSearchInput 角色检索入参
type RoleSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"pageSize"`
	Keyword  string `json:"keyword"`
}

// RoleSearchOutput 角色检索出参
type RoleSearchOutput struct {
	List  []RoleListItem `json:"list"`
	Total int            `json:"total"`
	Page  int            `json:"page"`
	Size  int            `json:"size"`
}

type RoleListItem struct {
	Id          int64       `json:"id"`
	Name        string      `json:"name"`
	Alias       string      `json:"alias"`
	IsSystem    int         `json:"is_system"`
	Description string      `json:"description"`
	CreatedAt   *gtime.Time `json:"created_at"`
	UpdatedAt   *gtime.Time `json:"updated_at"`
}

// RoleCreateInput 创建角色入参
type RoleCreateInput struct {
	Name          string  `json:"name"`
	Alias         string  `json:"alias"`
	Description   string  `json:"description"`
	PermissionIds []int64 `json:"permission_ids"`
}

// RoleUpdateInput 更新角色入参
type RoleUpdateInput struct {
	Id            int64   `json:"id"`
	Name          string  `json:"name"`
	Alias         string  `json:"alias"`
	Description   string  `json:"description"`
	PermissionIds []int64 `json:"permission_ids"`
}

// RoleDetailOutput 角色详情出参
type RoleDetailOutput struct {
	Id            int64       `json:"id"`
	Name          string      `json:"name"`
	Alias         string      `json:"alias"`
	IsSystem      int         `json:"is_system"`
	Description   string      `json:"description"`
	PermissionIds []int64     `json:"permission_ids"`
	CreatedAt     *gtime.Time `json:"created_at"`
}
