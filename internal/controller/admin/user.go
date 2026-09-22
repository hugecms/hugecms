package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var User = cUser{}

type cUser struct{}

// Search 用户列表分页查询
func (c *cUser) Search(ctx context.Context, req *v1.UserSearchReq) (res *v1.UserSearchRes, err error) {
	out, err := service.User().Search(ctx, model.UserSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Keyword:  req.Keyword,
		Status:   req.Status,
		RoleId:   req.RoleId,
	})
	if err != nil {
		return nil, err
	}
	return &v1.UserSearchRes{UserSearchOutput: out}, nil
}

// Get 获取用户详情
func (c *cUser) Get(ctx context.Context, req *v1.UserGetReq) (res *v1.UserGetRes, err error) {
	out, err := service.User().Get(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.UserGetRes{UserDetailOutput: out}, nil
}

// Create 创建用户
func (c *cUser) Create(ctx context.Context, req *v1.UserCreateReq) (res *v1.UserCreateRes, err error) {
	newId, err := service.User().Create(ctx, model.UserCreateInput{
		Name:      req.Name,
		Email:     req.Email,
		Password:  req.Password,
		Avatar:    req.Avatar,
		Status:    req.Status,
		RoleIds:   req.RoleIds,
		DataScope: req.DataScope,
	})
	if err != nil {
		return nil, err
	}
	return &v1.UserCreateRes{Id: newId}, nil
}

// Update 更新用户
func (c *cUser) Update(ctx context.Context, req *v1.UserUpdateReq) (res *v1.UserUpdateRes, err error) {
	err = service.User().Update(ctx, model.UserUpdateInput{
		Id:        req.Id,
		Name:      req.Name,
		Email:     req.Email,
		Password:  req.Password,
		Avatar:    req.Avatar,
		Status:    req.Status,
		RoleIds:   req.RoleIds,
		DataScope: req.DataScope,
	})
	if err != nil {
		return nil, err
	}
	return &v1.UserUpdateRes{}, nil
}

// Delete 删除用户
func (c *cUser) Delete(ctx context.Context, req *v1.UserDeleteReq) (res *v1.UserDeleteRes, err error) {
	if err := service.User().Delete(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.UserDeleteRes{}, nil
}
