package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Site = cSite{}

type cSite struct{}

// Search 站点列表分页查询
func (c *cSite) Search(ctx context.Context, req *v1.SiteSearchReq) (res *v1.SiteSearchRes, err error) {
	out, err := service.Site().Search(ctx, model.SiteSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Keyword:  req.Keyword,
		SiteCode: req.SiteCode,
		Domain:   req.Domain,
		Status:   req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.SiteSearchRes{SiteSearchOutput: out}, nil
}

// Get 获取站点详情
func (c *cSite) Get(ctx context.Context, req *v1.SiteGetReq) (res *v1.SiteGetRes, err error) {
	out, err := service.Site().Get(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.SiteGetRes{SiteItem: out}, nil
}

// Create 创建站点
func (c *cSite) Create(ctx context.Context, req *v1.SiteCreateReq) (res *v1.SiteCreateRes, err error) {
	newId, err := service.Site().Create(ctx, model.SiteCreateInput{
		SiteName:   req.SiteName,
		SiteCode:   req.SiteCode,
		Domain:     req.Domain,
		Domains:    req.Domains,
		SiteLogo:   req.SiteLogo,
		Favicon:    req.Favicon,
		Timezone:   req.Timezone,
		Language:   req.Language,
		TemplateId: req.TemplateId,
		Config:     req.Config,
		Status:     req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.SiteCreateRes{Id: newId}, nil
}

// Update 更新站点
func (c *cSite) Update(ctx context.Context, req *v1.SiteUpdateReq) (res *v1.SiteUpdateRes, err error) {
	err = service.Site().Update(ctx, model.SiteUpdateInput{
		Id:         req.Id,
		SiteName:   req.SiteName,
		SiteCode:   req.SiteCode,
		Domain:     req.Domain,
		Domains:    req.Domains,
		SiteLogo:   req.SiteLogo,
		Favicon:    req.Favicon,
		Timezone:   req.Timezone,
		Language:   req.Language,
		TemplateId: req.TemplateId,
		Config:     req.Config,
		Status:     req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.SiteUpdateRes{}, nil
}

// Delete 删除站点
func (c *cSite) Delete(ctx context.Context, req *v1.SiteDeleteReq) (res *v1.SiteDeleteRes, err error) {
	if err := service.Site().Delete(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.SiteDeleteRes{}, nil
}
