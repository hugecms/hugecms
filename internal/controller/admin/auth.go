package admin

import (
	"context"

	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/util/gconv"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Auth = cAuth{}

type cAuth struct{}

// Login 管理员登录
func (c *cAuth) Login(ctx context.Context, req *v1.AuthLoginReq) (res *v1.AuthLoginRes, err error) {
	out, err := service.Auth().Login(ctx, model.AuthLoginInput{
		Email:    req.Email,
		Password: req.Password,
	})
	if err != nil {
		return nil, err
	}

	res = &v1.AuthLoginRes{
		Token:    out.Token,
		ExpireAt: out.ExpireAt,
		User: v1.UserInfo{
			Id:      out.User.Id,
			Name:    out.User.Name,
			Email:   out.User.Email,
			IsSuper: out.User.IsSuper,
			Roles:   out.User.Roles,
		},
	}
	return res, nil
}

// Logout 退出登录
func (c *cAuth) Logout(ctx context.Context, req *v1.AuthLogoutReq) (res *v1.AuthLogoutRes, err error) {
	return &v1.AuthLogoutRes{}, nil
}

// Info 获取当前登录用户信息与权限列表
func (c *cAuth) Info(ctx context.Context, req *v1.AuthInfoReq) (res *v1.AuthInfoRes, err error) {
	localCtx := service.Context().Get(ctx)
	if localCtx == nil || localCtx.User == nil {
		return nil, gerror.New("未登录或登录态已失效")
	}

	info, err := service.Auth().GetUserInfo(ctx, localCtx.User.Id)
	if err != nil {
		return nil, err
	}

	res = &v1.AuthInfoRes{}
	err = gconv.Scan(info, res)
	return res, err
}
