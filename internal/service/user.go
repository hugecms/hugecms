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
	IUser interface {
		// Search 分页查询用户列表
		Search(ctx context.Context, in model.UserSearchInput) (*model.UserSearchOutput, error)
		// Create 新增用户并分配角色
		Create(ctx context.Context, in model.UserCreateInput) (int64, error)
		// Update 更新用户资料、密码与角色
		Update(ctx context.Context, in model.UserUpdateInput) error
		// Get 获取用户详细信息
		Get(ctx context.Context, id int64) (*model.UserDetailOutput, error)
		// Delete 删除用户（禁止删除超级管理员 ID:1）
		Delete(ctx context.Context, id int64) error
	}
)

var (
	localUser IUser
)

func User() IUser {
	if localUser == nil {
		panic("implement not found for interface IUser, forgot register?")
	}
	return localUser
}

func RegisterUser(i IUser) {
	localUser = i
}
