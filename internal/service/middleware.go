// ================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// You can delete these comments if you wish manually maintain this interface file.
// ================================================================================

package service

import (
	"github.com/gogf/gf/v2/net/ghttp"
)

type (
	IMiddleware interface {
		// CORS 跨域中间件
		CORS(r *ghttp.Request)
		// HandlerResponse 统一返回包装与异常拦截中间件
		HandlerResponse(r *ghttp.Request)
		// Auth 鉴权中间件：从 Header/Cookie/Query 读取 JWT，载入 ContextUser
		Auth(r *ghttp.Request)
		// RequirePermission 细粒度权限拦截器
		RequirePermission(permissionCode string) ghttp.HandlerFunc
	}
)

var (
	localMiddleware IMiddleware
)

func Middleware() IMiddleware {
	if localMiddleware == nil {
		panic("implement not found for interface IMiddleware, forgot register?")
	}
	return localMiddleware
}

func RegisterMiddleware(i IMiddleware) {
	localMiddleware = i
}
