package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var ContentModel = cContentModel{}

type cContentModel struct{}

func (c *cContentModel) Search(ctx context.Context, req *v1.ContentModelSearchReq) (res *v1.ContentModelSearchRes, err error) {
	out, err := service.ContentModel().Search(ctx, model.ContentModelSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Keyword:  req.Keyword,
		Status:   req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ContentModelSearchRes{ContentModelSearchOutput: out}, nil
}

func (c *cContentModel) Get(ctx context.Context, req *v1.ContentModelGetReq) (res *v1.ContentModelGetRes, err error) {
	out, err := service.ContentModel().Get(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.ContentModelGetRes{ContentModelItem: out}, nil
}

func (c *cContentModel) Create(ctx context.Context, req *v1.ContentModelCreateReq) (res *v1.ContentModelCreateRes, err error) {
	id, err := service.ContentModel().Create(ctx, model.ContentModelCreateInput{
		Name:          req.Name,
		Alias:         req.Alias,
		Description:   req.Description,
		IsCommentable: req.IsCommentable,
		Status:        req.Status,
		Sort:          req.Sort,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ContentModelCreateRes{Id: id}, nil
}

func (c *cContentModel) Update(ctx context.Context, req *v1.ContentModelUpdateReq) (res *v1.ContentModelUpdateRes, err error) {
	err = service.ContentModel().Update(ctx, model.ContentModelUpdateInput{
		Id:            req.Id,
		Name:          req.Name,
		Description:   req.Description,
		IsCommentable: req.IsCommentable,
		Status:        req.Status,
		Sort:          req.Sort,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ContentModelUpdateRes{}, nil
}

func (c *cContentModel) Delete(ctx context.Context, req *v1.ContentModelDeleteReq) (res *v1.ContentModelDeleteRes, err error) {
	if err := service.ContentModel().Delete(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.ContentModelDeleteRes{}, nil
}
