// ================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// You can delete these comments if you wish manually maintain this interface file.
// ================================================================================

package service

import (
	"context"
	"hugecms/internal/model"

	"github.com/gogf/gf/v2/net/ghttp"
)

type (
	IContext interface {
		// Init 初始化上下文对象并注入到请求中
		Init(r *ghttp.Request, customCtx *model.Context)
		// Get 从上下文获取自定义 Context 对象
		Get(ctx context.Context) *model.Context
		// SetUser 设置当前登录用户
		SetUser(ctx context.Context, user *model.ContextUser)
	}
)

var (
	localContext IContext
)

func Context() IContext {
	if localContext == nil {
		panic("implement not found for interface IContext, forgot register?")
	}
	return localContext
}

func RegisterContext(i IContext) {
	localContext = i
}
