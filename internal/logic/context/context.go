package context

import (
	"context"

	"github.com/gogf/gf/v2/net/ghttp"

	"hugecms/internal/model"
	"hugecms/internal/service"
)

type sContext struct{}

func init() {
	service.RegisterContext(New())
}

func New() service.IContext {
	return &sContext{}
}

// Init 初始化上下文对象并注入到请求中
func (s *sContext) Init(r *ghttp.Request, customCtx *model.Context) {
	r.SetCtxVar(model.ContextKey, customCtx)
}

// Get 从上下文获取自定义 Context 对象
func (s *sContext) Get(ctx context.Context) *model.Context {
	value := ctx.Value(model.ContextKey)
	if value == nil {
		return nil
	}
	if localCtx, ok := value.(*model.Context); ok {
		return localCtx
	}
	return nil
}

// SetUser 设置当前登录用户
func (s *sContext) SetUser(ctx context.Context, user *model.ContextUser) {
	c := s.Get(ctx)
	if c != nil {
		c.User = user
	}
}
