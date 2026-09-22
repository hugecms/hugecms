package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var ModelField = cModelField{}

type cModelField struct{}

func (c *cModelField) List(ctx context.Context, req *v1.ModelFieldListReq) (res *v1.ModelFieldListRes, err error) {
	list, err := service.ModelField().List(ctx, req.ModelId)
	if err != nil {
		return nil, err
	}
	return &v1.ModelFieldListRes{List: list}, nil
}

func (c *cModelField) Get(ctx context.Context, req *v1.ModelFieldGetReq) (res *v1.ModelFieldGetRes, err error) {
	out, err := service.ModelField().Get(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.ModelFieldGetRes{ModelFieldItem: out}, nil
}

func (c *cModelField) Create(ctx context.Context, req *v1.ModelFieldCreateReq) (res *v1.ModelFieldCreateRes, err error) {
	id, err := service.ModelField().Create(ctx, model.ModelFieldCreateInput{
		ModelId:         req.ModelId,
		FieldName:       req.FieldName,
		FieldLabel:      req.FieldLabel,
		FieldType:       req.FieldType,
		ColumnType:      req.ColumnType,
		DefaultValue:    req.DefaultValue,
		IsRequired:      req.IsRequired,
		IsUnique:        req.IsUnique,
		ValidationRules: req.ValidationRules,
		ExtraConfig:     req.ExtraConfig,
		SortOrder:       req.SortOrder,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ModelFieldCreateRes{Id: id}, nil
}

func (c *cModelField) Update(ctx context.Context, req *v1.ModelFieldUpdateReq) (res *v1.ModelFieldUpdateRes, err error) {
	err = service.ModelField().Update(ctx, model.ModelFieldUpdateInput{
		Id:              req.Id,
		FieldLabel:      req.FieldLabel,
		DefaultValue:    req.DefaultValue,
		IsRequired:      req.IsRequired,
		ValidationRules: req.ValidationRules,
		ExtraConfig:     req.ExtraConfig,
		SortOrder:       req.SortOrder,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ModelFieldUpdateRes{}, nil
}

func (c *cModelField) Delete(ctx context.Context, req *v1.ModelFieldDeleteReq) (res *v1.ModelFieldDeleteRes, err error) {
	if err := service.ModelField().Delete(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.ModelFieldDeleteRes{}, nil
}
