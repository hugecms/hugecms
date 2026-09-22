package test

import (
	"context"
	"strings"
	"testing"

	_ "hugecms/internal/logic"

	_ "github.com/gogf/gf/contrib/drivers/mysql/v2"
	"hugecms/internal/service"
)

func TestSeoSitemapAndRobots(t *testing.T) {
	ctx := context.Background()
	baseUrl := "http://localhost:8000"

	// 1. 测试 Sitemap 生成
	t.Run("GenerateSitemapXml", func(t *testing.T) {
		xmlStr, err := service.Portal().GetSitemapXml(ctx, baseUrl)
		if err != nil {
			t.Fatalf("GetSitemapXml returned error: %v", err)
		}
		if !strings.HasPrefix(xmlStr, "<?xml") {
			t.Fatalf("Expected sitemap to start with <?xml, got %s", xmlStr[:20])
		}
		if !strings.Contains(xmlStr, "<urlset") || !strings.Contains(xmlStr, "</urlset>") {
			t.Fatalf("Expected valid urlset XML structure")
		}
		if !strings.Contains(xmlStr, "<loc>http://localhost:8000/</loc>") {
			t.Fatalf("Expected homepage in sitemap")
		}
		if !strings.Contains(xmlStr, "/category/") {
			t.Logf("Notice: No categories found or sitemap contains no categories")
		}
		t.Logf("Sitemap XML generation verified. Total length: %d bytes", len(xmlStr))
	})

	// 2. 测试 Robots.txt 生成
	t.Run("GenerateRobotsTxt", func(t *testing.T) {
		robotsStr, err := service.Portal().GetRobotsTxt(ctx, baseUrl)
		if err != nil {
			t.Fatalf("GetRobotsTxt returned error: %v", err)
		}
		if !strings.Contains(robotsStr, "User-agent: *") {
			t.Fatalf("Expected User-agent in robots.txt")
		}
		if !strings.Contains(robotsStr, "Disallow: /admin/") {
			t.Fatalf("Expected Disallow: /admin/ in robots.txt")
		}
		if !strings.Contains(robotsStr, "Sitemap: http://localhost:8000/sitemap.xml") {
			t.Fatalf("Expected Sitemap URL in robots.txt")
		}
		t.Logf("Robots.txt generation verified successfully:\n%s", robotsStr)
	})

	// 3. 测试文章详情页面的 Open Graph 及 Breadcrumbs
	t.Run("DetailOpenGraphAndBreadcrumbs", func(t *testing.T) {
		out, err := service.Portal().GetDetailData(ctx, "go-dev-1790056410", "127.0.0.1")
		if err != nil {
			t.Fatalf("GetDetailData returned error: %v", err)
		}
		if out.Og == nil {
			t.Fatalf("Expected Og to be populated")
		}
		if out.Og.Type != "article" {
			t.Errorf("Expected og:type to be article, got %s", out.Og.Type)
		}
		if out.Og.Title == "" {
			t.Errorf("Expected og:title to be non-empty")
		}
		if len(out.Breadcrumbs) < 2 {
			t.Errorf("Expected at least 2 breadcrumb items, got %d", len(out.Breadcrumbs))
		}
		if out.Breadcrumbs[0].Name != "首页" {
			t.Errorf("Expected first breadcrumb to be 首页, got %s", out.Breadcrumbs[0].Name)
		}
		t.Logf("Detail SEO & Open Graph verified: Title=%s, OgTitle=%s, BreadcrumbsCount=%d",
			out.Seo.Title, out.Og.Title, len(out.Breadcrumbs))
	})

	// 4. 测试分类列表页面的 Breadcrumbs 与子分类
	t.Run("CategoryBreadcrumbsAndSubterms", func(t *testing.T) {
		out, err := service.Portal().GetCategoryData(ctx, "uncategorized", 1, 10)
		if err != nil {
			t.Fatalf("GetCategoryData returned error: %v", err)
		}
		if out.Og == nil {
			t.Fatalf("Expected Og to be populated for category")
		}
		if len(out.Breadcrumbs) < 2 {
			t.Errorf("Expected at least 2 breadcrumb items for category, got %d", len(out.Breadcrumbs))
		}
		t.Logf("Category SEO & Breadcrumbs verified: Term=%s, BreadcrumbsCount=%d",
			out.Term.Name, len(out.Breadcrumbs))
	})
}
