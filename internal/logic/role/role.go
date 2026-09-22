package role

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sRole struct{}

func init() {
	service.RegisterRole(New())
}

func New() service.IRole {
	return &sRole{}
}

// Search 分页查询角色列表
func (s *sRole) Search(ctx context.Context, in model.RoleSearchInput) (*model.RoleSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.Roles.Ctx(ctx)
	if in.Keyword != "" {
		m = m.Where("name LIKE ? OR alias LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var roles []entity.Roles
	err = m.Page(in.Page, in.PageSize).OrderAsc("id").Scan(&roles)
	if err != nil {
		return nil, err
	}

	list := make([]model.RoleListItem, 0, len(roles))
	for _, r := range roles {
		list = append(list, model.RoleListItem{
			Id:          int64(r.Id),
			Name:        r.Name,
			Alias:       r.Alias,
			IsSystem:    int(r.IsSystem),
			Description: r.Description,
			CreatedAt:   r.CreatedAt,
			UpdatedAt:   r.UpdatedAt,
		})
	}

	return &model.RoleSearchOutput{
		List:  list,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// Create 新增角色并绑定权限列表
func (s *sRole) Create(ctx context.Context, in model.RoleCreateInput) (int64, error) {
	count, err := dao.Roles.Ctx(ctx).Where("alias", in.Alias).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("角色标识已存在")
	}

	var newRoleId int64
	err = g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		now := gtime.Now()
		res, err := dao.Roles.Ctx(ctx).TX(tx).Data(g.Map{
			"name":        in.Name,
			"alias":       in.Alias,
			"description": in.Description,
			"is_system":   0,
			"created_at":  now,
			"updated_at":  now,
		}).Insert()
		if err != nil {
			return err
		}

		newRoleId, err = res.LastInsertId()
		if err != nil {
			return err
		}

		// 绑定权限
		for _, permId := range in.PermissionIds {
			_, err = dao.RolePermissions.Ctx(ctx).TX(tx).Data(g.Map{
				"role_id":       newRoleId,
				"permission_id": permId,
				"is_denied":     0,
				"created_at":    now,
			}).Insert()
			if err != nil {
				return err
			}
		}
		return nil
	})

	return newRoleId, err
}

// Update 更新角色信息与权限节点
func (s *sRole) Update(ctx context.Context, in model.RoleUpdateInput) error {
	var role entity.Roles
	if err := dao.Roles.Ctx(ctx).WherePri(in.Id).Scan(&role); err != nil {
		return err
	}
	if role.Id == 0 {
		return gerror.New("角色不存在")
	}

	// 系统内置角色不可更改别名
	if role.IsSystem == 1 && role.Alias != in.Alias {
		return gerror.New("系统内置角色标识不可更改")
	}

	// 别名唯一性校验
	count, err := dao.Roles.Ctx(ctx).Where("alias", in.Alias).WhereNot("id", in.Id).Count()
	if err != nil {
		return err
	}
	if count > 0 {
		return gerror.New("该角色标识已被占用")
	}

	return g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		now := gtime.Now()
		_, err := dao.Roles.Ctx(ctx).TX(tx).WherePri(in.Id).Data(g.Map{
			"name":        in.Name,
			"alias":       in.Alias,
			"description": in.Description,
			"updated_at":  now,
		}).Update()
		if err != nil {
			return err
		}

		// 重新授权
		if in.PermissionIds != nil {
			_, err = dao.RolePermissions.Ctx(ctx).TX(tx).Where("role_id", in.Id).Delete()
			if err != nil {
				return err
			}
			for _, permId := range in.PermissionIds {
				_, err = dao.RolePermissions.Ctx(ctx).TX(tx).Data(g.Map{
					"role_id":       in.Id,
					"permission_id": permId,
					"is_denied":     0,
					"created_at":    now,
				}).Insert()
				if err != nil {
					return err
				}
			}
		}
		return nil
	})
}

// Get 获取角色详情与关联权限 ID
func (s *sRole) Get(ctx context.Context, id int64) (*model.RoleDetailOutput, error) {
	var role entity.Roles
	if err := dao.Roles.Ctx(ctx).WherePri(id).Scan(&role); err != nil {
		return nil, err
	}
	if role.Id == 0 {
		return nil, gerror.New("角色不存在")
	}

	permVars, err := dao.RolePermissions.Ctx(ctx).
		Where("role_id", id).
		Where("is_denied", 0).
		Array("permission_id")
	if err != nil {
		return nil, err
	}

	permIds := make([]int64, 0, len(permVars))
	for _, v := range permVars {
		permIds = append(permIds, v.Int64())
	}

	return &model.RoleDetailOutput{
		Id:            int64(role.Id),
		Name:          role.Name,
		Alias:         role.Alias,
		IsSystem:      int(role.IsSystem),
		Description:   role.Description,
		PermissionIds: permIds,
		CreatedAt:     role.CreatedAt,
	}, nil
}

// Delete 删除角色（系统内置角色不可删除）
func (s *sRole) Delete(ctx context.Context, id int64) error {
	var role entity.Roles
	if err := dao.Roles.Ctx(ctx).WherePri(id).Scan(&role); err != nil {
		return err
	}
	if role.Id == 0 {
		return gerror.New("角色不存在")
	}
	if role.IsSystem == 1 {
		return gerror.New("系统内置角色不可删除")
	}

	_, err := dao.Roles.Ctx(ctx).WherePri(id).Delete()
	return err
}
