package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

// PermissionTreeReq 获取权限树请求
type PermissionTreeReq struct {
	g.Meta  `path:"/permissions/tree" method:"get" tags:"权限管理" summary:"获取权限树状结构"`
	Module  string `json:"module" in:"query" dc:"按模块筛选"`
	Keyword string `json:"keyword" in:"query" dc:"搜索关键词"`
}

// PermissionTreeRes 获取权限树响应
type PermissionTreeRes struct {
	*model.PermissionTreeOutput
}

// PermissionGetReq 获取指定权限详情请求
type PermissionGetReq struct {
	g.Meta `path:"/permissions/{id}" method:"get" tags:"权限管理" summary:"获取权限详情"`
	Id     int64 `json:"id" in:"path" v:"required#权限ID不能为空" dc:"权限ID"`
}

// PermissionGetRes 权限详情响应
type PermissionGetRes struct {
	*model.PermissionDetailOutput
}

// PermissionCreateReq 创建权限节点请求
type PermissionCreateReq struct {
	g.Meta      `path:"/permissions" method:"post" tags:"权限管理" summary:"创建权限节点"`
	ParentId    int64  `json:"parent_id" d:"0" dc:"父级ID（0为顶级）"`
	Name        string `json:"name" v:"required|length:2,50#请输入权限名称|名称长度为2-50位" dc:"权限名称"`
	Code        string `json:"code" v:"required|regex:^[a-zA-Z0-9_:]+$#请输入权限代码|代码仅支持英文数字下划线冒号" dc:"权限代码标识"`
	Module      string `json:"module" v:"required#所属模块不能为空" dc:"所属模块（如 system, content 等）"`
	Description string `json:"description" dc:"权限说明"`
	Sort        int    `json:"sort" d:"0" dc:"排序数值（从小到大）"`
}

// PermissionCreateRes 创建权限节点响应
type PermissionCreateRes struct {
	Id int64 `json:"id" dc:"新权限节点ID"`
}

// PermissionUpdateReq 更新权限节点请求
type PermissionUpdateReq struct {
	g.Meta      `path:"/permissions/{id}" method:"put" tags:"权限管理" summary:"更新权限节点"`
	Id          int64  `json:"id" in:"path" v:"required#权限ID不能为空" dc:"权限ID"`
	ParentId    int64  `json:"parent_id" dc:"父级ID"`
	Name        string `json:"name" v:"required|length:2,50#请输入权限名称|名称长度为2-50位" dc:"权限名称"`
	Code        string `json:"code" v:"required|regex:^[a-zA-Z0-9_:]+$#请输入权限代码|代码仅支持英文数字下划线冒号" dc:"权限代码标识"`
	Module      string `json:"module" v:"required#所属模块不能为空" dc:"所属模块"`
	Description string `json:"description" dc:"权限说明"`
	Sort        int    `json:"sort" dc:"排序数值"`
}

// PermissionUpdateRes 更新权限节点响应
type PermissionUpdateRes struct{}

// PermissionDeleteReq 删除权限节点请求
type PermissionDeleteReq struct {
	g.Meta `path:"/permissions/{id}" method:"delete" tags:"权限管理" summary:"删除权限节点"`
	Id     int64 `json:"id" in:"path" v:"required#权限ID不能为空" dc:"权限ID"`
}

// PermissionDeleteRes 删除权限节点响应
type PermissionDeleteRes struct{}
