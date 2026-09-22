package auth

import (
	"context"
	"fmt"

	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/net/ghttp"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
	"hugecms/utility/jwt"
	"hugecms/utility/password"
)

type sAuth struct{}

func init() {
	service.RegisterAuth(New())
}

func New() service.IAuth {
	return &sAuth{}
}

// Login 用户登录验证并签发 JWT
func (s *sAuth) Login(ctx context.Context, in model.AuthLoginInput) (*model.AuthLoginOutput, error) {
	var user *entity.Users
	err := dao.Users.Ctx(ctx).Where("email", in.Email).Scan(&user)
	if err != nil {
		return nil, err
	}
	if user == nil {
		return nil, gerror.New("用户不存在或密码错误")
	}
	if user.Status != 1 {
		return nil, gerror.New("该账号已被禁用，请联系管理员")
	}

	// 密码校验 (原生兼容 Laravel Hash::make / bcrypt)
	if !password.Verify(user.Password, in.Password) {
		return nil, gerror.New("用户不存在或密码错误")
	}

	// 获取用户身份与权限
	ctxUser, err := s.GetContextUser(ctx, int64(user.Id))
	if err != nil {
		return nil, err
	}

	// 签发 JWT
	token, expireAt, err := jwt.GenerateToken(ctx, int64(user.Id), user.Email, user.Name, ctxUser.IsSuper)
	if err != nil {
		return nil, fmt.Errorf("签发Token失败: %w", err)
	}

	// 更新最后登录时间与IP
	r := ghttp.RequestFromCtx(ctx)
	clientIp := ""
	if r != nil {
		clientIp = r.GetClientIp()
	}
	_, _ = dao.Users.Ctx(ctx).WherePri(user.Id).Data(g.Map{
		"last_login_time": gtime.Now(),
		"last_login_ip":   clientIp,
	}).Update()

	return &model.AuthLoginOutput{
		Token:    token,
		ExpireAt: expireAt,
		User:     ctxUser,
	}, nil
}

// GetContextUser 获取用户完整的上下文信息（角色、权限代码、数据范围）
func (s *sAuth) GetContextUser(ctx context.Context, userId int64) (*model.ContextUser, error) {
	var user *entity.Users
	if err := dao.Users.Ctx(ctx).WherePri(userId).Scan(&user); err != nil {
		return nil, err
	}
	if user == nil {
		return nil, gerror.New("用户不存在")
	}

	// 查询绑定的角色与数据范围
	type UserRoleRow struct {
		RoleId    int64  `json:"role_id"`
		RoleAlias string `json:"role_alias"`
		DataScope string `json:"data_scope"`
	}
	var roleRows []UserRoleRow
	err := dao.UserRoles.Ctx(ctx).
		Fields("user_roles.role_id, roles.alias as role_alias, user_roles.data_scope").
		LeftJoin("roles", "roles.id = user_roles.role_id").
		Where("user_roles.user_id", userId).
		Scan(&roleRows)
	if err != nil {
		return nil, err
	}

	var (
		roleIds   []int64
		roles     []string
		dataScope = "self"
		isSuper   = false
	)

	for _, r := range roleRows {
		roleIds = append(roleIds, r.RoleId)
		roles = append(roles, r.RoleAlias)
		if r.RoleAlias == "super_admin" {
			isSuper = true
			dataScope = "all"
		} else if r.DataScope == "all" {
			dataScope = "all"
		}
	}

	// 查询所有拥有的权限代码
	var permissions []string
	if len(roleIds) > 0 {
		permVars, err := dao.RolePermissions.Ctx(ctx).
			Fields("permissions.code").
			LeftJoin("permissions", "permissions.id = role_permissions.permission_id").
			WhereIn("role_permissions.role_id", roleIds).
			Where("role_permissions.is_denied", 0).
			Array("code")
		if err != nil {
			return nil, err
		}
		for _, v := range permVars {
			if code := v.String(); code != "" {
				permissions = append(permissions, code)
			}
		}
	}

	return &model.ContextUser{
		Id:          int64(user.Id),
		Email:       user.Email,
		Name:        user.Name,
		IsSuper:     isSuper,
		RoleIds:     roleIds,
		Roles:       roles,
		Permissions: permissions,
		DataScope:   dataScope,
	}, nil
}

// GetUserInfo 获取用户个人信息详情
func (s *sAuth) GetUserInfo(ctx context.Context, userId int64) (*model.AuthUserInfoOutput, error) {
	ctxUser, err := s.GetContextUser(ctx, userId)
	if err != nil {
		return nil, err
	}

	var user *entity.Users
	if err := dao.Users.Ctx(ctx).WherePri(userId).Scan(&user); err != nil {
		return nil, err
	}

	return &model.AuthUserInfoOutput{
		Id:          int64(user.Id),
		Name:        user.Name,
		Email:       user.Email,
		Avatar:      user.Avatar,
		Status:      int(user.Status),
		Roles:       ctxUser.Roles,
		Permissions: ctxUser.Permissions,
		CreatedAt:   user.CreatedAt,
	}, nil
}
