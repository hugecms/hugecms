package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Comment = cComment{}

type cComment struct{}

func (c *cComment) Search(ctx context.Context, req *v1.CommentSearchReq) (res *v1.CommentSearchRes, err error) {
	out, err := service.Comment().Search(ctx, model.CommentSearchInput{
		Page:      req.Page,
		PageSize:  req.PageSize,
		ContentId: req.ContentId,
		Status:    req.Status,
		Keyword:   req.Keyword,
	})
	if err != nil {
		return nil, err
	}
	return &v1.CommentSearchRes{CommentSearchOutput: out}, nil
}

func (c *cComment) Audit(ctx context.Context, req *v1.CommentAuditReq) (res *v1.CommentAuditRes, err error) {
	if req.CommentAuditInput == nil {
		return &v1.CommentAuditRes{}, nil
	}
	err = service.Comment().Audit(ctx, *req.CommentAuditInput)
	if err != nil {
		return nil, err
	}
	return &v1.CommentAuditRes{}, nil
}

func (c *cComment) Delete(ctx context.Context, req *v1.CommentDeleteReq) (res *v1.CommentDeleteRes, err error) {
	if err := service.Comment().Delete(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.CommentDeleteRes{}, nil
}
