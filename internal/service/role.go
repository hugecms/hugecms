// ================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// You can delete these comments if you wish manually maintain this interface file.
// ================================================================================

package service

import (
	"context"
	"hugecms/internal/model"
)

type (
	IRole interface {
		// Search 分页查询角色列表
		Search(ctx context.Context, in model.RoleSearchInput) (*model.RoleSearchOutput, error)
		// Create 新增角色并绑定权限列表
		Create(ctx context.Context, in model.RoleCreateInput) (int64, error)
		// Update 更新角色信息与权限节点
		Update(ctx context.Context, in model.RoleUpdateInput) error
		// Get 获取角色详情与关联权限 ID
		Get(ctx context.Context, id int64) (*model.RoleDetailOutput, error)
		// Delete 删除角色（系统内置角色不可删除）
		Delete(ctx context.Context, id int64) error
	}
)

var (
	localRole IRole
)

func Role() IRole {
	if localRole == nil {
		panic("implement not found for interface IRole, forgot register?")
	}
	return localRole
}

func RegisterRole(i IRole) {
	localRole = i
}
