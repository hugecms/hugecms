package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Content = cContent{}

type cContent struct{}

func (c *cContent) Search(ctx context.Context, req *v1.ContentSearchReq) (res *v1.ContentSearchRes, err error) {
	out, err := service.Content().Search(ctx, model.ContentSearchInput{
		Page:        req.Page,
		PageSize:    req.PageSize,
		ModelId:     req.ModelId,
		Keyword:     req.Keyword,
		Status:      req.Status,
		AuditStatus: req.AuditStatus,
		Visibility:  req.Visibility,
		TermId:      req.TermId,
		AuthorId:    req.AuthorId,
		IsTop:       req.IsTop,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ContentSearchRes{ContentSearchOutput: out}, nil
}

func (c *cContent) Get(ctx context.Context, req *v1.ContentGetReq) (res *v1.ContentGetRes, err error) {
	out, err := service.Content().Get(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.ContentGetRes{ContentDetailOutput: out}, nil
}

func (c *cContent) Save(ctx context.Context, req *v1.ContentSaveReq) (res *v1.ContentSaveRes, err error) {
	if req.ContentSaveInput == nil {
		return &v1.ContentSaveRes{}, nil
	}
	id, err := service.Content().Save(ctx, *req.ContentSaveInput)
	if err != nil {
		return nil, err
	}
	return &v1.ContentSaveRes{Id: id}, nil
}

func (c *cContent) Audit(ctx context.Context, req *v1.ContentAuditReq) (res *v1.ContentAuditRes, err error) {
	if req.ContentAuditInput == nil {
		return &v1.ContentAuditRes{}, nil
	}
	err = service.Content().Audit(ctx, *req.ContentAuditInput)
	if err != nil {
		return nil, err
	}
	return &v1.ContentAuditRes{}, nil
}

func (c *cContent) Status(ctx context.Context, req *v1.ContentStatusReq) (res *v1.ContentStatusRes, err error) {
	if req.ContentStatusInput == nil {
		return &v1.ContentStatusRes{}, nil
	}
	err = service.Content().ChangeStatus(ctx, *req.ContentStatusInput)
	if err != nil {
		return nil, err
	}
	return &v1.ContentStatusRes{}, nil
}

func (c *cContent) Trash(ctx context.Context, req *v1.ContentTrashReq) (res *v1.ContentTrashRes, err error) {
	if err := service.Content().Trash(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.ContentTrashRes{}, nil
}
