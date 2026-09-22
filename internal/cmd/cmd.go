package cmd

import (
	"context"

	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/net/ghttp"
	"github.com/gogf/gf/v2/os/gcmd"

	"hugecms/internal/controller/admin"
	"hugecms/internal/controller/common"
	"hugecms/internal/controller/portal"
	"hugecms/internal/service"
)

var (
	Main = gcmd.Command{
		Name:  "main",
		Usage: "main",
		Brief: "start http server",
		Func: func(ctx context.Context, parser *gcmd.Parser) (err error) {
			s := g.Server()

			// 启动后台常驻定时调度任务
			service.Cron().Start(ctx)

			// 静态资源目录映射
			s.AddStaticPath("/upload", "resource/public/upload")
			s.AddStaticPath("/static", "resource/public")

			// 管理后台 Admin API
			s.Group("/api/admin", func(group *ghttp.RouterGroup) {
				group.Middleware(
					service.Middleware().CORS,
					service.Middleware().HandlerResponse,
				)

				// 公开免鉴权路由 (登录)
				group.Bind(
					admin.Auth.Login,
				)

				// 需认证鉴权路由组
				group.Group("/", func(authGroup *ghttp.RouterGroup) {
					authGroup.Middleware(service.Middleware().Auth)
					authGroup.Bind(
						admin.Auth.Logout,
						admin.Auth.Info,
						admin.User,
						admin.Role,
						admin.Permission,
						admin.Option,
						admin.Site,
						admin.Appearance,
						admin.AuditLog,
						admin.ContentModel,
						admin.ModelField,
						admin.Content,
						admin.Taxonomy,
						admin.Comment,
						admin.RecycleBin,
						admin.Form,
						admin.Marketing,
					)
				})
			})

			// 通用公共 API (附件上传等)
			s.Group("/api/common", func(group *ghttp.RouterGroup) {
				group.Middleware(
					service.Middleware().CORS,
					service.Middleware().HandlerResponse,
					service.Middleware().Auth,
				)
				group.Bind(
					common.Attachment,
				)
			})

			// 前台公共 API (表单提交等)
			s.Group("/api/portal", func(group *ghttp.RouterGroup) {
				group.Middleware(
					service.Middleware().CORS,
					service.Middleware().HandlerResponse,
				)
				group.Bind(
					portal.Form,
				)
			})

			// 短链重定向路由 (/s/:code)
			s.Group("/", func(group *ghttp.RouterGroup) {
				group.Bind(
					portal.ShortLink,
				)
			})

			s.Run()
			return nil
		},
	}
)
