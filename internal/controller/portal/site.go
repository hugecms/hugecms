package portal

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/net/ghttp"

	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Site = cSite{}

type cSite struct{}

// Index 前台首页
func (c *cSite) Index(r *ghttp.Request) {
	ctx := r.Context()
	page := r.Get("page", 1).Int()
	size := r.Get("size", 10).Int()

	data, err := service.Portal().GetHomeData(ctx, page, size)
	if err != nil {
		r.Response.WriteStatusExit(500, err.Error())
		return
	}

	service.Portal().RenderPortal(ctx, r, "index.html", g.Map{
		"data": data,
	})
}

// Category 分类列表页
func (c *cSite) Category(r *ghttp.Request) {
	ctx := r.Context()
	slug := r.Get("alias").String()
	if slug == "" {
		slug = r.Get("slug").String()
	}
	page := r.Get("page", 1).Int()
	size := r.Get("size", 10).Int()

	data, err := service.Portal().GetCategoryData(ctx, slug, page, size)
	if err != nil {
		r.Response.WriteStatusExit(404, "分类不存在或暂无内容")
		return
	}

	service.Portal().RenderPortal(ctx, r, "category.html", g.Map{
		"data": data,
	})
}

// Detail 内容详情页
func (c *cSite) Detail(r *ghttp.Request) {
	ctx := r.Context()
	slug := r.Get("slug").String()
	clientIp := r.GetClientIp()

	data, err := service.Portal().GetDetailData(ctx, slug, clientIp)
	if err != nil {
		r.Response.WriteStatusExit(404, "内容不存在或未发布")
		return
	}

	service.Portal().RenderPortal(ctx, r, "detail.html", g.Map{
		"data": data,
	})
}

// Comment 提交评论
func (c *cSite) Comment(r *ghttp.Request) {
	ctx := r.Context()
	contentId := r.Get("content_id").Int64()
	parentId := r.Get("parent_id", 0).Int64()
	authorName := r.Get("author_name").String()
	authorEmail := r.Get("author_email").String()
	content := r.Get("content").String()

	err := service.Portal().PostComment(ctx, model.PortalCommentPostInput{
		ContentId:   contentId,
		ParentId:    parentId,
		AuthorName:  authorName,
		AuthorEmail: authorEmail,
		Content:     content,
		Ip:          r.GetClientIp(),
		UserAgent:   r.UserAgent(),
	})

	if r.IsAjaxRequest() {
		if err != nil {
			r.Response.WriteJson(g.Map{"code": 1, "message": err.Error()})
		} else {
			r.Response.WriteJson(g.Map{"code": 0, "message": "评论发表成功，请等待审核！"})
		}
		return
	}

	// 常规表单提交，跳回详情页锚点
	referer := r.Referer()
	if referer != "" {
		r.Response.RedirectTo(referer + "#comments")
	} else {
		r.Response.RedirectTo("/")
	}
}
