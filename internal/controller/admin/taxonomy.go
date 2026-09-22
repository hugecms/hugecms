package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Taxonomy = cTaxonomy{}

type cTaxonomy struct{}

// Search 分页查询分类法
func (c *cTaxonomy) Search(ctx context.Context, req *v1.TaxonomySearchReq) (res *v1.TaxonomySearchRes, err error) {
	out, err := service.Taxonomy().SearchTaxonomy(ctx, model.TaxonomySearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		ModelId:  req.ModelId,
		Keyword:  req.Keyword,
	})
	if err != nil {
		return nil, err
	}
	return &v1.TaxonomySearchRes{TaxonomySearchOutput: out}, nil
}

// Get 获取分类法详情
func (c *cTaxonomy) Get(ctx context.Context, req *v1.TaxonomyGetReq) (res *v1.TaxonomyGetRes, err error) {
	out, err := service.Taxonomy().GetTaxonomy(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.TaxonomyGetRes{TaxonomyItem: out}, nil
}

// Save 保存分类法
func (c *cTaxonomy) Save(ctx context.Context, req *v1.TaxonomySaveReq) (res *v1.TaxonomySaveRes, err error) {
	id, err := service.Taxonomy().SaveTaxonomy(ctx, model.TaxonomySaveInput{
		Id:             req.Id,
		Name:           req.Name,
		Alias:          req.Alias,
		ModelId:        req.ModelId,
		IsHierarchical: req.IsHierarchical,
		Description:    req.Description,
	})
	if err != nil {
		return nil, err
	}
	return &v1.TaxonomySaveRes{Id: id}, nil
}

// Delete 删除分类法
func (c *cTaxonomy) Delete(ctx context.Context, req *v1.TaxonomyDeleteReq) (res *v1.TaxonomyDeleteRes, err error) {
	if err := service.Taxonomy().DeleteTaxonomy(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.TaxonomyDeleteRes{}, nil
}

// Tree 获取词条树
func (c *cTaxonomy) Tree(ctx context.Context, req *v1.TermTreeReq) (res *v1.TermTreeRes, err error) {
	tree, err := service.Taxonomy().GetTermTree(ctx, model.TermTreeInput{
		TaxonomyId: req.TaxonomyId,
		Keyword:    req.Keyword,
	})
	if err != nil {
		return nil, err
	}
	return &v1.TermTreeRes{Tree: tree}, nil
}

// GetTerm 获取单个词条详情
func (c *cTaxonomy) GetTerm(ctx context.Context, req *v1.TermGetReq) (res *v1.TermGetRes, err error) {
	out, err := service.Taxonomy().GetTerm(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.TermGetRes{TermItem: out}, nil
}

// SaveTerm 保存词条
func (c *cTaxonomy) SaveTerm(ctx context.Context, req *v1.TermSaveReq) (res *v1.TermSaveRes, err error) {
	id, err := service.Taxonomy().SaveTerm(ctx, model.TermSaveInput{
		Id:          req.Id,
		TaxonomyId:  req.TaxonomyId,
		Name:        req.Name,
		Slug:        req.Slug,
		ParentId:    req.ParentId,
		Description: req.Description,
		Sort:        req.Sort,
	})
	if err != nil {
		return nil, err
	}
	return &v1.TermSaveRes{Id: id}, nil
}

// DeleteTerm 删除词条
func (c *cTaxonomy) DeleteTerm(ctx context.Context, req *v1.TermDeleteReq) (res *v1.TermDeleteRes, err error) {
	if err := service.Taxonomy().DeleteTerm(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.TermDeleteRes{}, nil
}
