// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Permissions is the golang structure for table permissions.
type Permissions struct {
	Id          uint64      `json:"id"          orm:"id"          ` //
	ParentId    uint64      `json:"parentId"    orm:"parent_id"   ` // 父级权限ID（0表示顶级）
	Name        string      `json:"name"        orm:"name"        ` // 权限名称（如：文章编辑）
	Code        string      `json:"code"        orm:"code"        ` // 权限代码（如：content:article:edit）
	Module      string      `json:"module"      orm:"module"      ` // 所属模块（分组展示用）
	Description string      `json:"description" orm:"description" ` // 权限描述
	Sort        int         `json:"sort"        orm:"sort"        ` // 排序
	CreatedAt   *gtime.Time `json:"createdAt"   orm:"created_at"  ` //
	UpdatedAt   *gtime.Time `json:"updatedAt"   orm:"updated_at"  ` //
}
