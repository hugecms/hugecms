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
	IPermission interface {
		// Tree 获取权限树状结构
		Tree(ctx context.Context, in model.PermissionSearchInput) (*model.PermissionTreeOutput, error)
		// Get 获取权限详情
		Get(ctx context.Context, id int64) (*model.PermissionDetailOutput, error)
		// Create 创建权限节点
		Create(ctx context.Context, in model.PermissionCreateInput) (int64, error)
		// Update 更新权限节点
		Update(ctx context.Context, in model.PermissionUpdateInput) error
		// Delete 删除权限节点
		Delete(ctx context.Context, id int64) error
	}
)

var (
	localPermission IPermission
)

func Permission() IPermission {
	if localPermission == nil {
		panic("implement not found for interface IPermission, forgot register?")
	}
	return localPermission
}

func RegisterPermission(i IPermission) {
	localPermission = i
}
