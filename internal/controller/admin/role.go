package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Role = cRole{}

type cRole struct{}

// Search 角色列表分页查询
func (c *cRole) Search(ctx context.Context, req *v1.RoleSearchReq) (res *v1.RoleSearchRes, err error) {
	out, err := service.Role().Search(ctx, model.RoleSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Keyword:  req.Keyword,
	})
	if err != nil {
		return nil, err
	}
	return &v1.RoleSearchRes{RoleSearchOutput: out}, nil
}

// Get 获取角色详情
func (c *cRole) Get(ctx context.Context, req *v1.RoleGetReq) (res *v1.RoleGetRes, err error) {
	out, err := service.Role().Get(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.RoleGetRes{RoleDetailOutput: out}, nil
}

// Create 创建角色
func (c *cRole) Create(ctx context.Context, req *v1.RoleCreateReq) (res *v1.RoleCreateRes, err error) {
	newId, err := service.Role().Create(ctx, model.RoleCreateInput{
		Name:          req.Name,
		Alias:         req.Alias,
		Description:   req.Description,
		PermissionIds: req.PermissionIds,
	})
	if err != nil {
		return nil, err
	}
	return &v1.RoleCreateRes{Id: newId}, nil
}

// Update 更新角色
func (c *cRole) Update(ctx context.Context, req *v1.RoleUpdateReq) (res *v1.RoleUpdateRes, err error) {
	err = service.Role().Update(ctx, model.RoleUpdateInput{
		Id:            req.Id,
		Name:          req.Name,
		Alias:         req.Alias,
		Description:   req.Description,
		PermissionIds: req.PermissionIds,
	})
	if err != nil {
		return nil, err
	}
	return &v1.RoleUpdateRes{}, nil
}

// Delete 删除角色
func (c *cRole) Delete(ctx context.Context, req *v1.RoleDeleteReq) (res *v1.RoleDeleteRes, err error) {
	if err := service.Role().Delete(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.RoleDeleteRes{}, nil
}
