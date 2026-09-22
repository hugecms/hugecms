package site

import (
	"context"

	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sSite struct{}

func init() {
	service.RegisterSite(New())
}

func New() service.ISite {
	return &sSite{}
}

// Search 分页查询站点
func (s *sSite) Search(ctx context.Context, in model.SiteSearchInput) (*model.SiteSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.Sites.Ctx(ctx)
	if in.Keyword != "" {
		m = m.Where("site_name LIKE ? OR site_code LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}
	if in.SiteCode != "" {
		m = m.Where("site_code", in.SiteCode)
	}
	if in.Domain != "" {
		m = m.Where("domain", in.Domain)
	}
	if in.Status != nil {
		m = m.Where("status", *in.Status)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var sites []entity.Sites
	if err := m.Page(in.Page, in.PageSize).OrderAsc("id").Scan(&sites); err != nil {
		return nil, err
	}

	list := make([]model.SiteItem, 0, len(sites))
	for _, site := range sites {
		list = append(list, model.SiteItem{
			Id:         int64(site.Id),
			SiteName:   site.SiteName,
			SiteCode:   site.SiteCode,
			Domain:     site.Domain,
			Domains:    site.Domains,
			SiteLogo:   site.SiteLogo,
			Favicon:    site.Favicon,
			Timezone:   site.Timezone,
			Language:   site.Language,
			TemplateId: int64(site.TemplateId),
			Config:     site.Config,
			Status:     int(site.Status),
			CreatedAt:  site.CreatedAt,
			UpdatedAt:  site.UpdatedAt,
		})
	}

	return &model.SiteSearchOutput{
		List:  list,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// Get 获取站点详情
func (s *sSite) Get(ctx context.Context, id int64) (*model.SiteItem, error) {
	var site entity.Sites
	if err := dao.Sites.Ctx(ctx).WherePri(id).Scan(&site); err != nil {
		return nil, err
	}
	if site.Id == 0 {
		return nil, gerror.New("站点不存在")
	}

	return &model.SiteItem{
		Id:         int64(site.Id),
		SiteName:   site.SiteName,
		SiteCode:   site.SiteCode,
		Domain:     site.Domain,
		Domains:    site.Domains,
		SiteLogo:   site.SiteLogo,
		Favicon:    site.Favicon,
		Timezone:   site.Timezone,
		Language:   site.Language,
		TemplateId: int64(site.TemplateId),
		Config:     site.Config,
		Status:     int(site.Status),
		CreatedAt:  site.CreatedAt,
		UpdatedAt:  site.UpdatedAt,
	}, nil
}

// GetByDomain 根据域名查找匹配站点
func (s *sSite) GetByDomain(ctx context.Context, domain string) (*model.SiteItem, error) {
	var site entity.Sites
	// 先查主域名匹配
	err := dao.Sites.Ctx(ctx).Where("domain", domain).Where("status", 1).Scan(&site)
	if err != nil {
		return nil, err
	}

	// 若未匹配主域名，查询附加域名 JSON 数组中包含该域名
	if site.Id == 0 {
		err = dao.Sites.Ctx(ctx).
			Where("JSON_CONTAINS(domains, ?)", `"`+domain+`"`).
			Where("status", 1).
			Scan(&site)
		if err != nil {
			return nil, err
		}
	}

	if site.Id == 0 {
		return nil, gerror.New("未找到匹配该域名的站点")
	}

	return &model.SiteItem{
		Id:         int64(site.Id),
		SiteName:   site.SiteName,
		SiteCode:   site.SiteCode,
		Domain:     site.Domain,
		Domains:    site.Domains,
		SiteLogo:   site.SiteLogo,
		Favicon:    site.Favicon,
		Timezone:   site.Timezone,
		Language:   site.Language,
		TemplateId: int64(site.TemplateId),
		Config:     site.Config,
		Status:     int(site.Status),
		CreatedAt:  site.CreatedAt,
		UpdatedAt:  site.UpdatedAt,
	}, nil
}

// Create 创建新站点
func (s *sSite) Create(ctx context.Context, in model.SiteCreateInput) (int64, error) {
	count, err := dao.Sites.Ctx(ctx).Where("site_code", in.SiteCode).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("站点代码已存在")
	}

	if in.Domains == "" {
		in.Domains = "[]"
	}
	if in.Config == "" {
		in.Config = "{}"
	}
	if in.Timezone == "" {
		in.Timezone = "Asia/Shanghai"
	}
	if in.Language == "" {
		in.Language = "zh-CN"
	}

	now := gtime.Now()
	res, err := dao.Sites.Ctx(ctx).Data(g.Map{
		"site_name":   in.SiteName,
		"site_code":   in.SiteCode,
		"domain":      in.Domain,
		"domains":     in.Domains,
		"site_logo":   in.SiteLogo,
		"favicon":     in.Favicon,
		"timezone":    in.Timezone,
		"language":    in.Language,
		"template_id": in.TemplateId,
		"config":      in.Config,
		"status":      in.Status,
		"created_at":  now,
		"updated_at":  now,
	}).Insert()
	if err != nil {
		return 0, err
	}

	return res.LastInsertId()
}

// Update 更新站点
func (s *sSite) Update(ctx context.Context, in model.SiteUpdateInput) error {
	count, err := dao.Sites.Ctx(ctx).Where("site_code", in.SiteCode).WhereNot("id", in.Id).Count()
	if err != nil {
		return err
	}
	if count > 0 {
		return gerror.New("站点代码已被占用")
	}

	if in.Domains == "" {
		in.Domains = "[]"
	}
	if in.Config == "" {
		in.Config = "{}"
	}

	now := gtime.Now()
	_, err = dao.Sites.Ctx(ctx).WherePri(in.Id).Data(g.Map{
		"site_name":   in.SiteName,
		"site_code":   in.SiteCode,
		"domain":      in.Domain,
		"domains":     in.Domains,
		"site_logo":   in.SiteLogo,
		"favicon":     in.Favicon,
		"timezone":    in.Timezone,
		"language":    in.Language,
		"template_id": in.TemplateId,
		"config":      in.Config,
		"status":      in.Status,
		"updated_at":  now,
	}).Update()
	return err
}

// Delete 删除站点
func (s *sSite) Delete(ctx context.Context, id int64) error {
	totalSites, err := dao.Sites.Ctx(ctx).Count()
	if err != nil {
		return err
	}
	if totalSites <= 1 {
		return gerror.New("系统中至少需要保留一个站点，无法删除")
	}

	_, err = dao.Sites.Ctx(ctx).WherePri(id).Delete()
	return err
}
