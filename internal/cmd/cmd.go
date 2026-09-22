package cmd

import (
	"context"
	"html/template"

	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/net/ghttp"
	"github.com/gogf/gf/v2/os/gcmd"
	"github.com/gogf/gf/v2/os/gfile"
	"github.com/gogf/gf/v2/os/gtime"
	"github.com/gogf/gf/v2/util/gconv"

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

			// 注册模板自定义函数
			g.View().BindFunc("noescape", func(s interface{}) template.HTML {
				return template.HTML(gconv.String(s))
			})
			g.View().BindFunc("sub", func(a, b int) int {
				return a - b
			})
			g.View().BindFunc("add", func(a, b int) int {
				return a + b
			})
			g.View().BindFunc("now", func() *gtime.Time {
				return gtime.Now()
			})
			g.View().BindFunc("date", func(format string, t interface{}) string {
				return gtime.New(t).Format(format)
			})

			// 启动后台常驻定时调度任务
			service.Cron().Start(ctx)

			// 静态资源目录映射
			s.AddStaticPath("/upload", "resource/public/upload")
			s.AddStaticPath("/static", "resource/public")
			if gfile.Exists("resource/admin/dist/client") {
				s.AddStaticPath("/admin", "resource/admin/dist/client")
			}

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

			// 前台门户 SSR 页面路由
			s.Group("/", func(group *ghttp.RouterGroup) {
				group.GET("/", portal.Site.Index)
				group.GET("/category/:alias", portal.Site.Category)
				group.GET("/c/:alias", portal.Site.Category)
				group.GET("/detail/:slug", portal.Site.Detail)
				group.GET("/a/:slug", portal.Site.Detail)
				group.POST("/comment", portal.Site.Comment)
				group.GET("/sitemap.xml", portal.Site.Sitemap)
				group.GET("/robots.txt", portal.Site.Robots)
			})

			s.Run()
			return nil
		},
	}
)
