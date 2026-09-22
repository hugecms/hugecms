package middleware

import (
	"fmt"
	"strings"

	"github.com/gogf/gf/v2/errors/gcode"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/net/ghttp"

	"hugecms/internal/model"
	"hugecms/internal/service"
	"hugecms/utility/jwt"
	"hugecms/utility/response"
)

type sMiddleware struct{}

func init() {
	service.RegisterMiddleware(New())
}

func New() service.IMiddleware {
	return &sMiddleware{}
}

// CORS 跨域中间件
func (s *sMiddleware) CORS(r *ghttp.Request) {
	r.Response.CORSDefault()
	r.Middleware.Next()
}

// HandlerResponse 统一返回包装与异常拦截中间件
func (s *sMiddleware) HandlerResponse(r *ghttp.Request) {
	// 初始化请求上下文
	service.Context().Init(r, &model.Context{
		Session: r.Session,
		Data:    make(map[string]any),
	})

	r.Middleware.Next()

	// 若已有自定义输出或重定向，则不进行统一封装
	if r.Response.BufferLength() > 0 {
		return
	}

	var (
		err  = r.GetError()
		res  = r.GetHandlerResponse()
		code = gerror.Code(err)
	)

	if err != nil {
		// 业务或系统异常处理
		errCode := code.Code()
		if errCode == gcode.CodeNil.Code() || errCode == -1 {
			errCode = 400
		}
		g.Log().Warningf(r.Context(), "API Error [%d]: %+v", errCode, err)
		response.Json(r, errCode, err.Error())
		return
	}

	// 正常返回封装
	response.Json(r, 0, "ok", res)
}

// Auth 鉴权中间件：从 Header/Cookie/Query 读取 JWT，载入 ContextUser
func (s *sMiddleware) Auth(r *ghttp.Request) {
	token := s.extractToken(r)
	if token == "" {
		response.Unauthorized(r, "未提供授权凭据")
		return
	}

	claims, err := jwt.ParseToken(r.Context(), token)
	if err != nil {
		response.Unauthorized(r, "登录凭据无效或已过期")
		return
	}

	// 加载用户及其角色、权限
	ctxUser, err := service.Auth().GetContextUser(r.Context(), claims.UserId)
	if err != nil {
		response.Unauthorized(r, "获取登录用户失败: "+err.Error())
		return
	}

	// 载入全局 Context
	service.Context().SetUser(r.Context(), ctxUser)
	r.Middleware.Next()
}

// RequirePermission 细粒度权限拦截器
func (s *sMiddleware) RequirePermission(permissionCode string) ghttp.HandlerFunc {
	return func(r *ghttp.Request) {
		ctx := r.Context()
		localCtx := service.Context().Get(ctx)
		if localCtx == nil || localCtx.User == nil {
			response.Unauthorized(r, "请先登录")
			return
		}

		user := localCtx.User
		// 超管 Bypass 规则
		if user.IsSuper || user.HasRole("super_admin") {
			r.Middleware.Next()
			return
		}

		// 校验当前用户是否拥有此权限
		if !user.HasPermission(permissionCode) {
			response.Forbidden(r, fmt.Sprintf("权限不足：缺少 %s 权限", permissionCode))
			return
		}

		r.Middleware.Next()
	}
}

// extractToken 优先从 Authorization 头读取，其次 Cookie / Query
func (s *sMiddleware) extractToken(r *ghttp.Request) string {
	authHeader := r.Header.Get("Authorization")
	if authHeader != "" {
		parts := strings.SplitN(authHeader, " ", 2)
		if len(parts) == 2 && strings.EqualFold(parts[0], "Bearer") {
			return strings.TrimSpace(parts[1])
		}
		return strings.TrimSpace(authHeader)
	}

	cookieToken := r.Cookie.Get("token").String()
	if cookieToken != "" {
		return cookieToken
	}

	return r.Get("token").String()
}
