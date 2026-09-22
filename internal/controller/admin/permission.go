package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Permission = cPermission{}

type cPermission struct{}

// Tree 获取权限树状结构
func (c *cPermission) Tree(ctx context.Context, req *v1.PermissionTreeReq) (res *v1.PermissionTreeRes, err error) {
	out, err := service.Permission().Tree(ctx, model.PermissionSearchInput{
		Module:  req.Module,
		Keyword: req.Keyword,
	})
	if err != nil {
		return nil, err
	}
	return &v1.PermissionTreeRes{PermissionTreeOutput: out}, nil
}

// Get 获取权限详情
func (c *cPermission) Get(ctx context.Context, req *v1.PermissionGetReq) (res *v1.PermissionGetRes, err error) {
	out, err := service.Permission().Get(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.PermissionGetRes{PermissionDetailOutput: out}, nil
}

// Create 创建权限节点
func (c *cPermission) Create(ctx context.Context, req *v1.PermissionCreateReq) (res *v1.PermissionCreateRes, err error) {
	newId, err := service.Permission().Create(ctx, model.PermissionCreateInput{
		ParentId:    req.ParentId,
		Name:        req.Name,
		Code:        req.Code,
		Module:      req.Module,
		Description: req.Description,
		Sort:        req.Sort,
	})
	if err != nil {
		return nil, err
	}
	return &v1.PermissionCreateRes{Id: newId}, nil
}

// Update 更新权限节点
func (c *cPermission) Update(ctx context.Context, req *v1.PermissionUpdateReq) (res *v1.PermissionUpdateRes, err error) {
	err = service.Permission().Update(ctx, model.PermissionUpdateInput{
		Id:          req.Id,
		ParentId:    req.ParentId,
		Name:        req.Name,
		Code:        req.Code,
		Module:      req.Module,
		Description: req.Description,
		Sort:        req.Sort,
	})
	if err != nil {
		return nil, err
	}
	return &v1.PermissionUpdateRes{}, nil
}

// Delete 删除权限节点
func (c *cPermission) Delete(ctx context.Context, req *v1.PermissionDeleteReq) (res *v1.PermissionDeleteRes, err error) {
	if err := service.Permission().Delete(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.PermissionDeleteRes{}, nil
}
