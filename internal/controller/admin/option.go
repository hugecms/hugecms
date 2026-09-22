package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Option = cOption{}

type cOption struct{}

// Search 查询配置列表
func (c *cOption) Search(ctx context.Context, req *v1.OptionSearchReq) (res *v1.OptionSearchRes, err error) {
	out, err := service.Option().Search(ctx, model.OptionSearchInput{
		Page:      req.Page,
		PageSize:  req.PageSize,
		OptionKey: req.OptionKey,
	})
	if err != nil {
		return nil, err
	}
	return &v1.OptionSearchRes{OptionSearchOutput: out}, nil
}

// Get 获取指定配置项
func (c *cOption) Get(ctx context.Context, req *v1.OptionGetReq) (res *v1.OptionGetRes, err error) {
	val, err := service.Option().Get(ctx, req.Key)
	if err != nil {
		return nil, err
	}
	return &v1.OptionGetRes{
		Key:   req.Key,
		Value: val,
	}, nil
}

// Save 保存单项配置
func (c *cOption) Save(ctx context.Context, req *v1.OptionSaveReq) (res *v1.OptionSaveRes, err error) {
	err = service.Option().Save(ctx, model.OptionSaveInput{
		OptionKey:   req.OptionKey,
		OptionValue: req.OptionValue,
		Autoload:    req.Autoload,
	})
	if err != nil {
		return nil, err
	}
	return &v1.OptionSaveRes{}, nil
}

// BatchSave 批量保存配置
func (c *cOption) BatchSave(ctx context.Context, req *v1.OptionBatchSaveReq) (res *v1.OptionBatchSaveRes, err error) {
	err = service.Option().BatchSave(ctx, model.OptionBatchSaveInput{
		Options: req.Options,
	})
	if err != nil {
		return nil, err
	}
	return &v1.OptionBatchSaveRes{}, nil
}

// Delete 删除配置
func (c *cOption) Delete(ctx context.Context, req *v1.OptionDeleteReq) (res *v1.OptionDeleteRes, err error) {
	if err := service.Option().Delete(ctx, req.Key); err != nil {
		return nil, err
	}
	return &v1.OptionDeleteRes{}, nil
}
