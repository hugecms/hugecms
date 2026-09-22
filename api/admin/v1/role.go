package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

// RoleSearchReq 角色列表查询请求
type RoleSearchReq struct {
	g.Meta   `path:"/roles" method:"get" tags:"角色管理" summary:"分页查询角色列表"`
	Page     int    `json:"page" in:"query" d:"1" dc:"页码"`
	PageSize int    `json:"page_size" in:"query" d:"10" dc:"每页条数"`
	Keyword  string `json:"keyword" in:"query" dc:"搜索关键词（名称/标识）"`
}

// RoleSearchRes 角色列表响应
type RoleSearchRes struct {
	*model.RoleSearchOutput
}

// RoleGetReq 获取角色详情请求
type RoleGetReq struct {
	g.Meta `path:"/roles/{id}" method:"get" tags:"角色管理" summary:"获取角色详情与已分配权限"`
	Id     int64 `json:"id" in:"path" v:"required#角色ID不能为空" dc:"角色ID"`
}

// RoleGetRes 角色详情响应
type RoleGetRes struct {
	*model.RoleDetailOutput
}

// RoleCreateReq 创建角色请求
type RoleCreateReq struct {
	g.Meta        `path:"/roles" method:"post" tags:"角色管理" summary:"创建新角色"`
	Name          string  `json:"name" v:"required|length:2,50#请输入角色名称|角色名称长度为2-50位" dc:"角色名称"`
	Alias         string  `json:"alias" v:"required|regex:^[a-z0-9_]+$#请输入角色标识|标识仅支持小写字母数字下划线" dc:"角色唯一标识"`
	Description   string  `json:"description" dc:"角色说明"`
	PermissionIds []int64 `json:"permission_ids" dc:"授予的权限ID列表"`
}

// RoleCreateRes 创建角色响应
type RoleCreateRes struct {
	Id int64 `json:"id" dc:"新角色ID"`
}

// RoleUpdateReq 更新角色请求
type RoleUpdateReq struct {
	g.Meta        `path:"/roles/{id}" method:"put" tags:"角色管理" summary:"更新角色信息与权限"`
	Id            int64   `json:"id" in:"path" v:"required#角色ID不能为空" dc:"角色ID"`
	Name          string  `json:"name" v:"required|length:2,50#请输入角色名称|角色名称长度为2-50位" dc:"角色名称"`
	Alias         string  `json:"alias" v:"required|regex:^[a-z0-9_]+$#请输入角色标识|标识仅支持小写字母数字下划线" dc:"角色唯一标识"`
	Description   string  `json:"description" dc:"角色说明"`
	PermissionIds []int64 `json:"permission_ids" dc:"授予的权限ID列表"`
}

// RoleUpdateRes 更新角色响应
type RoleUpdateRes struct{}

// RoleDeleteReq 删除角色请求
type RoleDeleteReq struct {
	g.Meta `path:"/roles/{id}" method:"delete" tags:"角色管理" summary:"删除角色"`
	Id     int64 `json:"id" in:"path" v:"required#角色ID不能为空" dc:"角色ID"`
}

// RoleDeleteRes 删除角色响应
type RoleDeleteRes struct{}
