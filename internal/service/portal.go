// ================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// You can delete these comments if you wish manually maintain this interface file.
// ================================================================================

package service

import (
	"context"
	"hugecms/internal/model"

	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/net/ghttp"
)

type (
	IPortal interface {
		// GetHomeData 首页数据装配
		GetHomeData(ctx context.Context, page int, size int) (*model.PortalHomeOutput, error)
		// GetCategoryData 分类列表数据装配
		GetCategoryData(ctx context.Context, slug string, page int, size int) (*model.PortalCategoryOutput, error)
		// GetDetailData 内容详情数据装配
		GetDetailData(ctx context.Context, slug string, clientIp string) (*model.PortalDetailOutput, error)
		// PostComment 前台提交评论
		PostComment(ctx context.Context, in model.PortalCommentPostInput) error
		// RenderPortal 统一多主题渲染入口，具备降级回退机制
		RenderPortal(ctx context.Context, r *ghttp.Request, tplName string, data g.Map)
		// GetSitemapXml 动态生成 sitemap.xml
		GetSitemapXml(ctx context.Context, baseUrl string) (string, error)
		// GetRobotsTxt 动态生成 robots.txt
		GetRobotsTxt(ctx context.Context, baseUrl string) (string, error)
	}
)

var (
	localPortal IPortal
)

func Portal() IPortal {
	if localPortal == nil {
		panic("implement not found for interface IPortal, forgot register?")
	}
	return localPortal
}

func RegisterPortal(i IPortal) {
	localPortal = i
}
