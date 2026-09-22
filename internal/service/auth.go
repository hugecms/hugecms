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
	IAuth interface {
		// Login 用户登录验证并签发 JWT
		Login(ctx context.Context, in model.AuthLoginInput) (*model.AuthLoginOutput, error)
		// GetContextUser 获取用户完整的上下文信息（角色、权限代码、数据范围）
		GetContextUser(ctx context.Context, userId int64) (*model.ContextUser, error)
		// GetUserInfo 获取用户个人信息详情
		GetUserInfo(ctx context.Context, userId int64) (*model.AuthUserInfoOutput, error)
	}
)

var (
	localAuth IAuth
)

func Auth() IAuth {
	if localAuth == nil {
		panic("implement not found for interface IAuth, forgot register?")
	}
	return localAuth
}

func RegisterAuth(i IAuth) {
	localAuth = i
}
