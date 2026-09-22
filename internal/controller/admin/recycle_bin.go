package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var RecycleBin = cRecycleBin{}

type cRecycleBin struct{}

func (c *cRecycleBin) Search(ctx context.Context, req *v1.RecycleBinSearchReq) (res *v1.RecycleBinSearchRes, err error) {
	out, err := service.RecycleBin().Search(ctx, model.RecycleBinSearchInput{
		Page:       req.Page,
		PageSize:   req.PageSize,
		TargetType: req.TargetType,
		Keyword:    req.Keyword,
	})
	if err != nil {
		return nil, err
	}
	return &v1.RecycleBinSearchRes{RecycleBinSearchOutput: out}, nil
}

func (c *cRecycleBin) Restore(ctx context.Context, req *v1.RecycleBinRestoreReq) (res *v1.RecycleBinRestoreRes, err error) {
	restoredId, err := service.RecycleBin().Restore(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.RecycleBinRestoreRes{RestoredId: restoredId}, nil
}

func (c *cRecycleBin) Purge(ctx context.Context, req *v1.RecycleBinPurgeReq) (res *v1.RecycleBinPurgeRes, err error) {
	if err := service.RecycleBin().Purge(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.RecycleBinPurgeRes{}, nil
}
