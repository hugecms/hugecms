package model

import "github.com/gogf/gf/v2/os/gtime"

// PermissionTreeNode 树状权限节点
type PermissionTreeNode struct {
	Id          int64                `json:"id"`
	ParentId    int64                `json:"parent_id"`
	Name        string               `json:"name"`
	Code        string               `json:"code"`
	Module      string               `json:"module"`
	Description string               `json:"description"`
	Sort        int                  `json:"sort"`
	Children    []PermissionTreeNode `json:"children,omitempty"`
	CreatedAt   *gtime.Time          `json:"created_at"`
}

// PermissionSearchInput 权限检索入参
type PermissionSearchInput struct {
	Module  string `json:"module"`
	Keyword string `json:"keyword"`
}

// PermissionTreeOutput 树状权限输出
type PermissionTreeOutput struct {
	Tree []PermissionTreeNode `json:"tree"`
}

// PermissionCreateInput 创建权限入参
type PermissionCreateInput struct {
	ParentId    int64  `json:"parent_id"`
	Name        string `json:"name" v:"required#权限名称不能为空"`
	Code        string `json:"code" v:"required#权限标识不能为空"`
	Module      string `json:"module" v:"required#所属模块不能为空"`
	Description string `json:"description"`
	Sort        int    `json:"sort"`
}

// PermissionUpdateInput 更新权限入参
type PermissionUpdateInput struct {
	Id          int64  `json:"id" v:"required#权限ID不能为空"`
	ParentId    int64  `json:"parent_id"`
	Name        string `json:"name" v:"required#权限名称不能为空"`
	Code        string `json:"code" v:"required#权限标识不能为空"`
	Module      string `json:"module" v:"required#所属模块不能为空"`
	Description string `json:"description"`
	Sort        int    `json:"sort"`
}

// PermissionDetailOutput 权限详情输出
type PermissionDetailOutput struct {
	Id          int64       `json:"id"`
	ParentId    int64       `json:"parent_id"`
	Name        string      `json:"name"`
	Code        string      `json:"code"`
	Module      string      `json:"module"`
	Description string      `json:"description"`
	Sort        int         `json:"sort"`
	CreatedAt   *gtime.Time `json:"created_at"`
	UpdatedAt   *gtime.Time `json:"updated_at"`
}
