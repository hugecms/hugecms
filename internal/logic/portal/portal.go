package portal

import (
	"context"
	"encoding/json"
	"fmt"
	"math"
	"strings"

	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/net/ghttp"
	"github.com/gogf/gf/v2/os/gfile"
	"github.com/gogf/gf/v2/util/gconv"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sPortal struct{}

func init() {
	service.RegisterPortal(New())
}

func New() service.IPortal {
	return &sPortal{}
}

// 获取前台公共基础数据 (站点名称、配置、主导航、友情链接)
func (s *sPortal) getCommonSiteData(ctx context.Context) (siteName string, siteInfo map[string]string, navItems []model.PortalNavMenuItem, friendLinks []model.FriendLinkItem) {
	siteName = "HugeCMS"
	siteInfo = make(map[string]string)

	var site entity.Sites
	if err := dao.Sites.Ctx(ctx).Where("status", 1).OrderAsc("id").Scan(&site); err == nil && site.Id > 0 {
		siteName = site.SiteName
	}

	var opt entity.Options
	if err := dao.Options.Ctx(ctx).Where("option_key", "site_info").Scan(&opt); err == nil && opt.OptionValue != "" {
		_ = json.Unmarshal([]byte(opt.OptionValue), &siteInfo)
	}

	// 主导航
	var menu entity.NavMenus
	_ = dao.NavMenus.Ctx(ctx).Where("alias", "main_nav").Scan(&menu)
	if menu.Id > 0 {
		var rawItems []entity.NavItems
		_ = dao.NavItems.Ctx(ctx).
			Where("menu_id", menu.Id).
			Where("is_active", 1).
			OrderAsc("sort").
			Scan(&rawItems)

		itemMap := make(map[uint64][]entity.NavItems)
		for _, item := range rawItems {
			itemMap[item.ParentId] = append(itemMap[item.ParentId], item)
		}

		for _, root := range itemMap[0] {
			target := "_self"
			if root.OpenType == 1 {
				target = "_blank"
			}
			node := model.PortalNavMenuItem{
				Id:       root.Id,
				Title:    root.Title,
				LinkType: root.LinkType,
				LinkUrl:  s.resolveNavUrl(ctx, root.LinkType, root.LinkValue),
				Target:   target,
			}
			for _, child := range itemMap[root.Id] {
				childTarget := "_self"
				if child.OpenType == 1 {
					childTarget = "_blank"
				}
				node.Children = append(node.Children, model.PortalNavMenuItem{
					Id:       child.Id,
					Title:    child.Title,
					LinkType: child.LinkType,
					LinkUrl:  s.resolveNavUrl(ctx, child.LinkType, child.LinkValue),
					Target:   childTarget,
				})
			}
			navItems = append(navItems, node)
		}
	}

	// 友情链接
	friendLinks, _ = service.Marketing().GetActiveFriendLinks(ctx, "")
	return
}

func (s *sPortal) resolveNavUrl(ctx context.Context, linkType string, linkValue string) string {
	switch linkType {
	case "content":
		var c entity.Contents
		if err := dao.Contents.Ctx(ctx).WherePri(linkValue).Scan(&c); err == nil && c.Slug != "" {
			return "/detail/" + c.Slug
		}
		return "/detail/" + linkValue
	case "term":
		var term entity.Terms
		if err := dao.Terms.Ctx(ctx).WherePri(linkValue).Scan(&term); err == nil && term.Slug != "" {
			return "/category/" + term.Slug
		}
		return "/category/" + linkValue
	default:
		if linkValue == "" {
			return "/"
		}
		return linkValue
	}
}

// GetHomeData 首页数据装配
func (s *sPortal) GetHomeData(ctx context.Context, page, size int) (*model.PortalHomeOutput, error) {
	if page <= 0 {
		page = 1
	}
	if size <= 0 {
		size = 10
	}

	siteName, siteInfo, navItems, friendLinks := s.getCommonSiteData(ctx)

	m := dao.Contents.Ctx(ctx).
		Where("status", "published").
		Where("audit_status", "approved").
		Where("visibility", "public")

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var contents []entity.Contents
	err = m.Page(page, size).
		OrderDesc("is_top").
		OrderDesc("published_at").
		OrderDesc("id").
		Scan(&contents)
	if err != nil {
		return nil, err
	}

	list := make([]model.PortalContentItem, len(contents))
	for i, c := range contents {
		list[i] = model.PortalContentItem{
			Id:           int64(c.Id),
			Title:        c.Title,
			Slug:         c.Slug,
			Views:        int64(c.Views),
			CommentCount: int(c.CommentCount),
			IsTop:        int(c.IsTop),
			PublishedAt:  c.PublishedAt,
		}
	}

	// 侧边栏分类
	var terms []entity.Terms
	_ = dao.Terms.Ctx(ctx).OrderDesc("content_count").Limit(15).Scan(&terms)
	categories := make([]model.TermItem, len(terms))
	for i, t := range terms {
		categories[i] = model.TermItem{
			Id:           int64(t.Id),
			Name:         t.Name,
			Slug:         t.Slug,
			ContentCount: int(t.ContentCount),
		}
	}

	totalPages := int(math.Ceil(float64(total) / float64(size)))
	if totalPages <= 0 {
		totalPages = 1
	}

	homeSeo := &model.SeoMetaItem{
		Title:       siteName,
		Keywords:    siteInfo["keywords"],
		Description: siteInfo["description"],
	}

	homeOg := &model.OpenGraphMeta{
		Type:        "website",
		Title:       siteName,
		Description: siteInfo["description"],
		Url:         "/",
		SiteName:    siteName,
	}

	return &model.PortalHomeOutput{
		SiteName:    siteName,
		SiteInfo:    siteInfo,
		NavItems:    navItems,
		FriendLinks: friendLinks,
		Contents:    list,
		Categories:  categories,
		Seo:         homeSeo,
		Og:          homeOg,
		Total:       total,
		Page:        page,
		Size:        size,
		TotalPages:  totalPages,
	}, nil
}

// GetCategoryData 分类列表数据装配
func (s *sPortal) GetCategoryData(ctx context.Context, slug string, page, size int) (*model.PortalCategoryOutput, error) {
	if page <= 0 {
		page = 1
	}
	if size <= 0 {
		size = 10
	}

	siteName, siteInfo, navItems, friendLinks := s.getCommonSiteData(ctx)

	// 查询 Term
	var term entity.Terms
	mTerm := dao.Terms.Ctx(ctx).Where("slug", slug)
	if gconv.Int64(slug) > 0 {
		mTerm = mTerm.WhereOr("id", slug)
	}
	if err := mTerm.Scan(&term); err != nil || term.Id == 0 {
		return nil, gerror.New("分类不存在")
	}

	var taxonomy entity.Taxonomies
	_ = dao.Taxonomies.Ctx(ctx).WherePri(term.TaxonomyId).Scan(&taxonomy)

	// 查询该分类下的内容 ID
	contentIds, err := dao.TermRelationships.Ctx(ctx).
		Where("term_id", term.Id).
		Array("content_id")
	if err != nil {
		return nil, err
	}

	var list []model.PortalContentItem
	total := 0
	if len(contentIds) > 0 {
		m := dao.Contents.Ctx(ctx).
			WhereIn("id", contentIds).
			Where("status", "published").
			Where("audit_status", "approved").
			Where("visibility", "public")

		total, _ = m.Count()
		var contents []entity.Contents
		_ = m.Page(page, size).
			OrderDesc("is_top").
			OrderDesc("published_at").
			OrderDesc("id").
			Scan(&contents)

		list = make([]model.PortalContentItem, len(contents))
		for i, c := range contents {
			list[i] = model.PortalContentItem{
				Id:           int64(c.Id),
				Title:        c.Title,
				Slug:         c.Slug,
				Views:        int64(c.Views),
				CommentCount: int(c.CommentCount),
				IsTop:        int(c.IsTop),
				PublishedAt:  c.PublishedAt,
			}
		}
	}

	totalPages := int(math.Ceil(float64(total) / float64(size)))
	if totalPages <= 0 {
		totalPages = 1
	}

	catSeo := &model.SeoMetaItem{
		Title:       term.Name + " - " + taxonomy.Name,
		Description: term.Description,
	}

	// 社交分享 Open Graph
	catOg := &model.OpenGraphMeta{
		Type:        "website",
		Title:       term.Name + " - " + siteName,
		Description: term.Description,
		Url:         fmt.Sprintf("/category/%s", term.Slug),
		SiteName:    siteName,
	}

	// 面包屑导航: 首页 -> 分类法 -> 父分类 (如有) -> 当前分类
	breadcrumbs := []model.PortalBreadcrumbItem{
		{Name: "首页", Url: "/"},
		{Name: taxonomy.Name, Url: ""},
	}
	if term.ParentId > 0 {
		var parentTerm entity.Terms
		if err := dao.Terms.Ctx(ctx).WherePri(term.ParentId).Scan(&parentTerm); err == nil && parentTerm.Id > 0 {
			breadcrumbs = append(breadcrumbs, model.PortalBreadcrumbItem{
				Name: parentTerm.Name,
				Url:  fmt.Sprintf("/category/%s", parentTerm.Slug),
			})
		}
	}
	breadcrumbs = append(breadcrumbs, model.PortalBreadcrumbItem{
		Name: term.Name,
		Url:  "",
	})

	// 查询直属子分类
	var subTermEntities []entity.Terms
	_ = dao.Terms.Ctx(ctx).
		Where("parent_id", term.Id).
		OrderAsc("sort").
		Scan(&subTermEntities)
	subTerms := make([]model.TermItem, len(subTermEntities))
	for i, st := range subTermEntities {
		subTerms[i] = model.TermItem{
			Id:           int64(st.Id),
			Name:         st.Name,
			Slug:         st.Slug,
			Description:  st.Description,
			ContentCount: int(st.ContentCount),
		}
	}

	return &model.PortalCategoryOutput{
		SiteName:    siteName,
		SiteInfo:    siteInfo,
		NavItems:    navItems,
		FriendLinks: friendLinks,
		Taxonomy: &model.TaxonomyItem{
			Id:    int64(taxonomy.Id),
			Name:  taxonomy.Name,
			Alias: taxonomy.Alias,
		},
		Term: &model.TermItem{
			Id:           int64(term.Id),
			Name:         term.Name,
			Slug:         term.Slug,
			Description:  term.Description,
			ContentCount: int(term.ContentCount),
		},
		Breadcrumbs: breadcrumbs,
		SubTerms:    subTerms,
		Contents:    list,
		Seo:         catSeo,
		Og:          catOg,
		Total:       total,
		Page:        page,
		Size:        size,
		TotalPages:  totalPages,
	}, nil
}

// GetDetailData 内容详情数据装配
func (s *sPortal) GetDetailData(ctx context.Context, slug string, clientIp string) (*model.PortalDetailOutput, error) {
	siteName, siteInfo, navItems, friendLinks := s.getCommonSiteData(ctx)

	// 查询内容
	m := dao.Contents.Ctx(ctx).Where("slug", slug)
	if gconv.Int64(slug) > 0 {
		m = m.WhereOr("id", slug)
	}

	var c entity.Contents
	if err := m.Scan(&c); err != nil || c.Id == 0 {
		return nil, gerror.New("内容不存在或已被删除")
	}

	if c.Status != "published" || c.AuditStatus != "approved" {
		return nil, gerror.New("该内容尚未发布或正在审核中")
	}

	locked := false
	if c.Visibility == "private" || c.Visibility == "password" {
		locked = true
	}

	// 浏览量防刷累加
	if !locked {
		_, _ = service.View().RecordView(ctx, model.ContentViewRecordInput{
			ContentId: int64(c.Id),
			Ip:        clientIp,
		})
		// 刷新最新浏览数
		var freshC entity.Contents
		if err := dao.Contents.Ctx(ctx).WherePri(c.Id).Scan(&freshC); err == nil {
			c.Views = freshC.Views
		}
	}

	// 查询动态模型表数据
	dynamicData := make(map[string]interface{})
	var cModel entity.ContentModels
	_ = dao.ContentModels.Ctx(ctx).WherePri(c.ModelId).Scan(&cModel)
	if cModel.TableName != "" {
		record, _ := g.DB().Model(cModel.TableName).Ctx(ctx).Where("content_id", c.Id).One()
		if !record.IsEmpty() {
			dynamicData = record.Map()
		}
	}

	// 查询 SEO 元数据
	var seo model.SeoMetaItem
	var seoEntity entity.SeoMeta
	if err := dao.SeoMeta.Ctx(ctx).
		Where("target_type", "content").
		Where("target_id", c.Id).
		Scan(&seoEntity); err == nil && seoEntity.Id > 0 {
		seo = model.SeoMetaItem{
			Id:           int64(seoEntity.Id),
			Title:        seoEntity.Title,
			Keywords:     seoEntity.Keywords,
			Description:  seoEntity.Description,
			CanonicalUrl: seoEntity.CanonicalUrl,
			Robots:       seoEntity.Robots,
		}
	}

	// 查询已审核评论
	var commentEntities []entity.Comments
	_ = dao.Comments.Ctx(ctx).
		Where("content_id", c.Id).
		Where("status", "approved").
		OrderAsc("id").
		Scan(&commentEntities)

	commentMap := make(map[uint64][]model.PortalCommentTreeItem)
	for _, com := range commentEntities {
		item := model.PortalCommentTreeItem{
			Id:         int64(com.Id),
			ParentId:   int64(com.ParentId),
			AuthorName: com.AuthorName,
			Content:    com.Content,
			CreatedAt:  com.CreatedAt,
		}
		if item.AuthorName == "" {
			item.AuthorName = "热心网友"
		}
		commentMap[com.ParentId] = append(commentMap[com.ParentId], item)
	}

	var commentTree []model.PortalCommentTreeItem
	for _, root := range commentMap[0] {
		root.Replies = commentMap[uint64(root.Id)]
		commentTree = append(commentTree, root)
	}

	// 上一篇 / 下一篇
	var prev, next entity.Contents
	_ = dao.Contents.Ctx(ctx).
		Where("status", "published").
		Where("audit_status", "approved").
		Where("id < ?", c.Id).
		OrderDesc("id").
		Limit(1).
		Scan(&prev)

	_ = dao.Contents.Ctx(ctx).
		Where("status", "published").
		Where("audit_status", "approved").
		Where("id > ?", c.Id).
		OrderAsc("id").
		Limit(1).
		Scan(&next)

	var prevItem, nextItem *model.PortalContentItem
	if prev.Id > 0 {
		prevItem = &model.PortalContentItem{
			Id:    int64(prev.Id),
			Title: prev.Title,
			Slug:  prev.Slug,
		}
	}
	if next.Id > 0 {
		nextItem = &model.PortalContentItem{
			Id:    int64(next.Id),
			Title: next.Title,
			Slug:  next.Slug,
		}
	}

	contentDetail := &model.ContentDetailOutput{
		Id:           int64(c.Id),
		ModelId:      int64(c.ModelId),
		ModelAlias:   cModel.Alias,
		Title:        c.Title,
		Slug:         c.Slug,
		AuthorId:     int64(c.AuthorId),
		Status:       c.Status,
		Visibility:   c.Visibility,
		Views:        int64(c.Views),
		CommentCount: int(c.CommentCount),
		PublishedAt:  c.PublishedAt,
		CreatedAt:    c.CreatedAt,
	}

	// 智能补全 SEO 标签：若无专门设置，则使用文章标题、摘要
	if seo.Title == "" {
		seo.Title = c.Title
	}
	summaryStr := ""
	if dynamicData["field_1"] != nil {
		summaryStr = gconv.String(dynamicData["field_1"])
	}
	if seo.Description == "" {
		seo.Description = summaryStr
	}

	// 查询所属主分类
	var catItem *model.TermItem
	var rel entity.TermRelationships
	if err := dao.TermRelationships.Ctx(ctx).Where("content_id", c.Id).Scan(&rel); err == nil && rel.TermId > 0 {
		var term entity.Terms
		if err := dao.Terms.Ctx(ctx).WherePri(rel.TermId).Scan(&term); err == nil && term.Id > 0 {
			catItem = &model.TermItem{
				Id:           int64(term.Id),
				Name:         term.Name,
				Slug:         term.Slug,
				Description:  term.Description,
				ContentCount: int(term.ContentCount),
			}
		}
	}

	// 面包屑导航: 首页 -> 分类 (如有) -> 文章标题
	breadcrumbs := []model.PortalBreadcrumbItem{
		{Name: "首页", Url: "/"},
	}
	if catItem != nil {
		breadcrumbs = append(breadcrumbs, model.PortalBreadcrumbItem{
			Name: catItem.Name,
			Url:  fmt.Sprintf("/category/%s", catItem.Slug),
		})
	}
	breadcrumbs = append(breadcrumbs, model.PortalBreadcrumbItem{
		Name: c.Title,
		Url:  "",
	})

	// 社交分享 Open Graph
	pubTimeStr := ""
	if c.PublishedAt != nil {
		pubTimeStr = c.PublishedAt.ISO8601()
	}
	coverImg := ""
	if dynamicData["cover"] != nil {
		coverImg = gconv.String(dynamicData["cover"])
	}
	og := &model.OpenGraphMeta{
		Type:        "article",
		Title:       c.Title,
		Description: summaryStr,
		Url:         fmt.Sprintf("/detail/%s", c.Slug),
		Image:       coverImg,
		SiteName:    siteName,
		PublishedAt: pubTimeStr,
	}

	return &model.PortalDetailOutput{
		SiteName:    siteName,
		SiteInfo:    siteInfo,
		NavItems:    navItems,
		FriendLinks: friendLinks,
		Content:     contentDetail,
		DynamicData: dynamicData,
		Breadcrumbs: breadcrumbs,
		Category:    catItem,
		Seo:         &seo,
		Og:          og,
		Comments:    commentTree,
		PrevContent: prevItem,
		NextContent: nextItem,
		Locked:      locked,
	}, nil
}

// PostComment 前台提交评论
func (s *sPortal) PostComment(ctx context.Context, in model.PortalCommentPostInput) error {
	if in.ContentId <= 0 {
		return gerror.New("内容ID不能为空")
	}
	if in.Content == "" {
		return gerror.New("评论内容不能为空")
	}

	_, err := service.Comment().Create(ctx, model.CommentCreateInput{
		ContentId:   in.ContentId,
		ParentId:    in.ParentId,
		UserId:      0, // 访客评论
		AuthorName:  in.AuthorName,
		AuthorEmail: in.AuthorEmail,
		Content:     in.Content,
		Ip:          in.Ip,
		UserAgent:   in.UserAgent,
	})
	return err
}

// RenderPortal 统一多主题渲染入口，具备降级回退机制
func (s *sPortal) RenderPortal(ctx context.Context, r *ghttp.Request, tplName string, data g.Map) {
	theme, _ := service.Option().Get(ctx, "theme")
	if theme == "" {
		theme = "default"
	}
	tplPath := fmt.Sprintf("%s/%s", theme, tplName)
	if !gfile.Exists(fmt.Sprintf("resource/template/%s", tplPath)) {
		tplPath = fmt.Sprintf("default/%s", tplName)
	}
	content, err := g.View().Parse(ctx, tplPath, data)
	if err != nil {
		r.Response.WriteStatusExit(500, fmt.Sprintf("template parsing failed: %v", err))
		return
	}
	r.Response.Header().Set("Content-Type", "text/html; charset=utf-8")
	r.Response.Write(content)
}

// GetSitemapXml 动态生成符合规范的 sitemap.xml
func (s *sPortal) GetSitemapXml(ctx context.Context, baseUrl string) (string, error) {
	baseUrl = strings.TrimRight(baseUrl, "/")

	var xmlBuilder strings.Builder
	xmlBuilder.WriteString(`<?xml version="1.0" encoding="UTF-8"?>` + "\n")
	xmlBuilder.WriteString(`<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">` + "\n")

	// 1. 首页
	xmlBuilder.WriteString(fmt.Sprintf("  <url>\n    <loc>%s/</loc>\n    <changefreq>daily</changefreq>\n    <priority>1.0</priority>\n  </url>\n", baseUrl))

	// 2. 全部公开分类
	var terms []entity.Terms
	_ = dao.Terms.Ctx(ctx).Scan(&terms)
	for _, term := range terms {
		if term.Slug != "" {
			xmlBuilder.WriteString(fmt.Sprintf("  <url>\n    <loc>%s/category/%s</loc>\n    <changefreq>daily</changefreq>\n    <priority>0.8</priority>\n  </url>\n", baseUrl, term.Slug))
		}
	}

	// 3. 全部已审核公开内容
	var contents []entity.Contents
	_ = dao.Contents.Ctx(ctx).
		Where("status", "published").
		Where("audit_status", "approved").
		Where("visibility", "public").
		OrderDesc("id").
		Scan(&contents)

	for _, c := range contents {
		if c.Slug != "" {
			lastmod := ""
			if c.UpdatedAt != nil {
				lastmod = c.UpdatedAt.Format("Y-m-d")
			} else if c.PublishedAt != nil {
				lastmod = c.PublishedAt.Format("Y-m-d")
			}
			lastmodXml := ""
			if lastmod != "" {
				lastmodXml = fmt.Sprintf("    <lastmod>%s</lastmod>\n", lastmod)
			}
			priority := "0.6"
			if c.IsTop == 1 {
				priority = "0.9"
			}
			xmlBuilder.WriteString(fmt.Sprintf("  <url>\n    <loc>%s/detail/%s</loc>\n%s    <changefreq>weekly</changefreq>\n    <priority>%s</priority>\n  </url>\n", baseUrl, c.Slug, lastmodXml, priority))
		}
	}

	xmlBuilder.WriteString(`</urlset>`)
	return xmlBuilder.String(), nil
}

// GetRobotsTxt 动态生成搜索引擎规范 robots.txt
func (s *sPortal) GetRobotsTxt(ctx context.Context, baseUrl string) (string, error) {
	baseUrl = strings.TrimRight(baseUrl, "/")

	var builder strings.Builder
	builder.WriteString("# Robots.txt generated automatically by HugeCMS\n")
	builder.WriteString("User-agent: *\n")
	builder.WriteString("Disallow: /api/\n")
	builder.WriteString("Disallow: /admin/\n")
	builder.WriteString("Disallow: /upload/temp/\n")
	builder.WriteString("Allow: /\n\n")
	builder.WriteString(fmt.Sprintf("Sitemap: %s/sitemap.xml\n", baseUrl))

	return builder.String(), nil
}
