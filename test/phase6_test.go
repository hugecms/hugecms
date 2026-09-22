package test

import (
	"context"
	"testing"

	_ "hugecms/internal/logic"

	_ "github.com/gogf/gf/contrib/drivers/mysql/v2"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gctx"
	"github.com/gogf/gf/v2/os/gfile"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

func TestPhase6PortalAndResources(t *testing.T) {
	ctx := gctx.New()

	// 模拟超级管理员上下文
	localCtx := &model.Context{
		User: &model.ContextUser{
			Id:      1,
			Email:   "admin@example.com",
			Name:    "管理员",
			IsSuper: true,
		},
	}
	ctx = context.WithValue(ctx, model.ContextKey, localCtx)

	findPath := func(relPath string) string {
		if gfile.Exists(relPath) {
			return relPath
		}
		if gfile.Exists("../" + relPath) {
			return "../" + relPath
		}
		return relPath
	}

	// ==================== 1. 前台模板文件完整性与语法校验 ====================
	t.Run("TemplateIntegrity", func(t *testing.T) {
		tplFiles := []string{
			"resource/template/default/layouts/header.html",
			"resource/template/default/layouts/footer.html",
			"resource/template/default/index.html",
			"resource/template/default/category.html",
			"resource/template/default/detail.html",
		}
		for _, file := range tplFiles {
			p := findPath(file)
			if !gfile.Exists(p) {
				t.Fatalf("Template file missing: %s", file)
			}
		}
		t.Log("All 5 default theme templates exist.")

		// 验证 GoFrame View 模板语法解析
		view := g.View()
		_ = view.SetPath(findPath("resource/template"))
		view.BindFunc("noescape", func(s interface{}) string {
			return g.NewVar(s).String()
		})
		view.BindFunc("sub", func(a, b int) int {
			return a - b
		})
		view.BindFunc("add", func(a, b int) int {
			return a + b
		})
		view.BindFunc("now", func() *gtime.Time {
			return gtime.Now()
		})
		view.BindFunc("date", func(format string, t interface{}) string {
			return gtime.New(t).Format(format)
		})
		_, err := view.Parse(ctx, "default/index.html", g.Map{
			"data": &model.PortalHomeOutput{
				SiteName: "HugeCMS Test",
				Contents: []model.PortalContentItem{
					{Title: "测试文章", Slug: "test-slug"},
				},
			},
		})
		if err != nil {
			t.Fatalf("Parse index.html failed: %v", err)
		}
		t.Log("Template syntax parsing verified successfully.")
	})

	// ==================== 2. 前台首页数据装配测试 ====================
	t.Run("PortalHomeData", func(t *testing.T) {
		homeData, err := service.Portal().GetHomeData(ctx, 1, 10)
		if err != nil {
			t.Fatalf("Portal.GetHomeData failed: %v", err)
		}
		if homeData.SiteName == "" {
			t.Fatalf("Expected SiteName, got empty")
		}
		t.Logf("PortalHomeData loaded. SiteName: %s, NavItems count: %d, Contents count: %d",
			homeData.SiteName, len(homeData.NavItems), len(homeData.Contents))
	})

	// ==================== 3. 前台分类数据装配测试 ====================
	t.Run("PortalCategoryData", func(t *testing.T) {
		var firstTerm entity.Terms
		err := dao.Terms.Ctx(ctx).Limit(1).Scan(&firstTerm)
		if err != nil || firstTerm.Id == 0 {
			t.Skip("No terms in database, skipping category test.")
		}

		catData, err := service.Portal().GetCategoryData(ctx, firstTerm.Slug, 1, 10)
		if err != nil {
			t.Fatalf("Portal.GetCategoryData failed: %v", err)
		}
		if catData.Term == nil || catData.Term.Name != firstTerm.Name {
			t.Fatalf("Expected Term name %s, got %v", firstTerm.Name, catData.Term)
		}
		t.Logf("PortalCategoryData loaded for term: %s, Total: %d", catData.Term.Name, catData.Total)
	})

	// ==================== 4. 前台文章详情与浏览量防刷联动测试 ====================
	t.Run("PortalDetailData", func(t *testing.T) {
		var firstContent entity.Contents
		err := dao.Contents.Ctx(ctx).
			Where("status", "published").
			Where("audit_status", "approved").
			Where("visibility", "public").
			Limit(1).
			Scan(&firstContent)
		if err != nil || firstContent.Id == 0 {
			t.Skip("No published contents found, skipping detail test.")
		}

		oldViews := firstContent.Views
		detailData, err := service.Portal().GetDetailData(ctx, firstContent.Slug, "198.51.100.88")
		if err != nil {
			t.Fatalf("Portal.GetDetailData failed: %v", err)
		}
		if detailData.Content.Title != firstContent.Title {
			t.Fatalf("Expected Title %s, got %s", firstContent.Title, detailData.Content.Title)
		}

		// 验证浏览量是否触发递增
		var updatedContent entity.Contents
		_ = dao.Contents.Ctx(ctx).WherePri(firstContent.Id).Scan(&updatedContent)
		if updatedContent.Views <= oldViews {
			t.Fatalf("Expected views to increment from %d, got %d", oldViews, updatedContent.Views)
		}
		t.Logf("PortalDetailData loaded for %s, views incremented to %d", detailData.Content.Title, updatedContent.Views)
	})

	// ==================== 5. 前台访客发表评论测试 ====================
	t.Run("PortalPostComment", func(t *testing.T) {
		var firstContent entity.Contents
		_ = dao.Contents.Ctx(ctx).
			Where("status", "published").
			Limit(1).
			Scan(&firstContent)
		if firstContent.Id == 0 {
			t.Skip("No contents, skipping comment test.")
		}

		err := service.Portal().PostComment(ctx, model.PortalCommentPostInput{
			ContentId:   int64(firstContent.Id),
			ParentId:    0,
			AuthorName:  "前台测试访客",
			AuthorEmail: "visitor@example.com",
			Content:     "这是通过前台 SSR 接口提交的一条测试评论！",
			Ip:          "127.0.0.1",
			UserAgent:   "Go-Test",
		})
		if err != nil {
			t.Fatalf("Portal.PostComment failed: %v", err)
		}

		cnt, err := dao.Comments.Ctx(ctx).
			Where("content_id", firstContent.Id).
			Where("author_name", "前台测试访客").
			Count()
		if err != nil || cnt == 0 {
			t.Fatalf("Expected comment to be inserted into database, count: %d", cnt)
		}
		t.Log("PortalPostComment test passed.")
	})

	// ==================== 6. 后台前端独立资源与托管验证 ====================
	t.Run("AdminStaticHosting", func(t *testing.T) {
		entryFile := findPath("resource/admin/dist/client/index.html")
		if !gfile.Exists(entryFile) {
			t.Fatalf("Admin SPA entry index.html not found at %s", entryFile)
		}
		t.Log("Admin SPA build and static hosting validated successfully.")
	})
}
