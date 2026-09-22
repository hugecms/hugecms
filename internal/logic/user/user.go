package user

import (
	"context"
	"fmt"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
	"hugecms/utility/password"
)

type sUser struct{}

func init() {
	service.RegisterUser(New())
}

func New() service.IUser {
	return &sUser{}
}

// Search 分页查询用户列表
func (s *sUser) Search(ctx context.Context, in model.UserSearchInput) (*model.UserSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.Users.Ctx(ctx)

	if in.Keyword != "" {
		m = m.Where("name LIKE ? OR email LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}
	if in.Status != nil {
		m = m.Where("status", *in.Status)
	}
	if in.RoleId > 0 {
		subQuery := dao.UserRoles.Ctx(ctx).Fields("user_id").Where("role_id", in.RoleId)
		m = m.WhereIn("id", subQuery)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var users []entity.Users
	err = m.Page(in.Page, in.PageSize).OrderDesc("id").Scan(&users)
	if err != nil {
		return nil, err
	}

	// 批量加载用户关联的角色别名
	userIds := make([]uint64, 0, len(users))
	for _, u := range users {
		userIds = append(userIds, u.Id)
	}

	userRoleMap := make(map[uint64][]string)
	if len(userIds) > 0 {
		type Row struct {
			UserId uint64 `json:"user_id"`
			Alias  string `json:"alias"`
		}
		var rows []Row
		_ = dao.UserRoles.Ctx(ctx).
			Fields("user_roles.user_id, roles.alias").
			LeftJoin("roles", "roles.id = user_roles.role_id").
			WhereIn("user_roles.user_id", userIds).
			Scan(&rows)
		for _, r := range rows {
			userRoleMap[r.UserId] = append(userRoleMap[r.UserId], r.Alias)
		}
	}

	list := make([]model.UserListItem, 0, len(users))
	for _, u := range users {
		list = append(list, model.UserListItem{
			Id:            int64(u.Id),
			Name:          u.Name,
			Email:         u.Email,
			Avatar:        u.Avatar,
			Status:        int(u.Status),
			Roles:         userRoleMap[u.Id],
			LastLoginIp:   u.LastLoginIp,
			LastLoginTime: u.LastLoginTime,
			CreatedAt:     u.CreatedAt,
		})
	}

	return &model.UserSearchOutput{
		List:  list,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// Create 新增用户并分配角色
func (s *sUser) Create(ctx context.Context, in model.UserCreateInput) (int64, error) {
	// 校验邮箱唯一性
	count, err := dao.Users.Ctx(ctx).Where("email", in.Email).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("该邮箱已被注册使用")
	}

	// 密码哈希
	if in.Password == "" {
		in.Password = "123456" // 默认密码
	}
	pwdHash, err := password.Hash(in.Password)
	if err != nil {
		return 0, fmt.Errorf("密码加密失败: %w", err)
	}

	var newUserId int64
	err = g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		now := gtime.Now()
		res, err := dao.Users.Ctx(ctx).TX(tx).Data(g.Map{
			"name":       in.Name,
			"email":      in.Email,
			"password":   pwdHash,
			"avatar":     in.Avatar,
			"status":     in.Status,
			"created_at": now,
			"updated_at": now,
		}).Insert()
		if err != nil {
			return err
		}
		newUserId, err = res.LastInsertId()
		if err != nil {
			return err
		}

		// 绑定角色
		dataScope := in.DataScope
		if dataScope == "" {
			dataScope = "self"
		}
		for _, roleId := range in.RoleIds {
			_, err = dao.UserRoles.Ctx(ctx).TX(tx).Data(g.Map{
				"user_id":    newUserId,
				"role_id":    roleId,
				"data_scope": dataScope,
				"created_at": now,
			}).Insert()
			if err != nil {
				return err
			}
		}
		return nil
	})

	return newUserId, err
}

// Update 更新用户资料、密码与角色
func (s *sUser) Update(ctx context.Context, in model.UserUpdateInput) error {
	var user entity.Users
	if err := dao.Users.Ctx(ctx).WherePri(in.Id).Scan(&user); err != nil {
		return err
	}
	if user.Id == 0 {
		return gerror.New("用户不存在")
	}

	// 校验邮箱唯一性
	count, err := dao.Users.Ctx(ctx).Where("email", in.Email).WhereNot("id", in.Id).Count()
	if err != nil {
		return err
	}
	if count > 0 {
		return gerror.New("该邮箱已被其他账号占用")
	}

	updateData := g.Map{
		"name":       in.Name,
		"email":      in.Email,
		"avatar":     in.Avatar,
		"status":     in.Status,
		"updated_at": gtime.Now(),
	}

	// 仅当传入新密码时才重置密码
	if in.Password != "" {
		pwdHash, err := password.Hash(in.Password)
		if err != nil {
			return err
		}
		updateData["password"] = pwdHash
	}

	return g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		_, err := dao.Users.Ctx(ctx).TX(tx).WherePri(in.Id).Data(updateData).Update()
		if err != nil {
			return err
		}

		// 重置角色关联
		if in.RoleIds != nil {
			_, err = dao.UserRoles.Ctx(ctx).TX(tx).Where("user_id", in.Id).Delete()
			if err != nil {
				return err
			}
			now := gtime.Now()
			dataScope := in.DataScope
			if dataScope == "" {
				dataScope = "self"
			}
			for _, roleId := range in.RoleIds {
				_, err = dao.UserRoles.Ctx(ctx).TX(tx).Data(g.Map{
					"user_id":    in.Id,
					"role_id":    roleId,
					"data_scope": dataScope,
					"created_at": now,
				}).Insert()
				if err != nil {
					return err
				}
			}
		}
		return nil
	})
}

// Get 获取用户详细信息
func (s *sUser) Get(ctx context.Context, id int64) (*model.UserDetailOutput, error) {
	var user entity.Users
	if err := dao.Users.Ctx(ctx).WherePri(id).Scan(&user); err != nil {
		return nil, err
	}
	if user.Id == 0 {
		return nil, gerror.New("用户不存在")
	}

	type RoleRow struct {
		RoleId    int64  `json:"role_id"`
		Alias     string `json:"alias"`
		DataScope string `json:"data_scope"`
	}
	var roleRows []RoleRow
	_ = dao.UserRoles.Ctx(ctx).
		Fields("user_roles.role_id, roles.alias, user_roles.data_scope").
		LeftJoin("roles", "roles.id = user_roles.role_id").
		Where("user_roles.user_id", id).
		Scan(&roleRows)

	var (
		roleIds   []int64
		roles     []string
		dataScope = "self"
	)
	for _, r := range roleRows {
		roleIds = append(roleIds, r.RoleId)
		roles = append(roles, r.Alias)
		if r.DataScope == "all" {
			dataScope = "all"
		}
	}

	return &model.UserDetailOutput{
		Id:            int64(user.Id),
		Name:          user.Name,
		Email:         user.Email,
		Avatar:        user.Avatar,
		Status:        int(user.Status),
		RoleIds:       roleIds,
		Roles:         roles,
		DataScope:     dataScope,
		LastLoginIp:   user.LastLoginIp,
		LastLoginTime: user.LastLoginTime,
		CreatedAt:     user.CreatedAt,
	}, nil
}

// Delete 删除用户（禁止删除超级管理员 ID:1）
func (s *sUser) Delete(ctx context.Context, id int64) error {
	if id == 1 {
		return gerror.New("内置超级管理员账号禁止删除")
	}

	localCtx := service.Context().Get(ctx)
	if localCtx != nil && localCtx.User != nil && localCtx.User.Id == id {
		return gerror.New("不能删除当前登录账号")
	}

	_, err := dao.Users.Ctx(ctx).WherePri(id).Delete()
	return err
}
