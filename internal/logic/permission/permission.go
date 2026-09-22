package permission

import (
	"context"

	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sPermission struct{}

func init() {
	service.RegisterPermission(New())
}

func New() service.IPermission {
	return &sPermission{}
}

// Tree 获取权限树状结构
func (s *sPermission) Tree(ctx context.Context, in model.PermissionSearchInput) (*model.PermissionTreeOutput, error) {
	m := dao.Permissions.Ctx(ctx)
	if in.Module != "" {
		m = m.Where("module", in.Module)
	}
	if in.Keyword != "" {
		m = m.Where("name LIKE ? OR code LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}

	var list []entity.Permissions
	if err := m.OrderAsc("sort").OrderAsc("id").Scan(&list); err != nil {
		return nil, err
	}

	tree := buildTree(list, 0)
	return &model.PermissionTreeOutput{
		Tree: tree,
	}, nil
}

func buildTree(items []entity.Permissions, parentId uint64) []model.PermissionTreeNode {
	var nodes []model.PermissionTreeNode
	for _, item := range items {
		if item.ParentId == parentId {
			children := buildTree(items, item.Id)
			node := model.PermissionTreeNode{
				Id:          int64(item.Id),
				ParentId:    int64(item.ParentId),
				Name:        item.Name,
				Code:        item.Code,
				Module:      item.Module,
				Description: item.Description,
				Sort:        item.Sort,
				Children:    children,
				CreatedAt:   item.CreatedAt,
			}
			nodes = append(nodes, node)
		}
	}
	return nodes
}

// Get 获取权限详情
func (s *sPermission) Get(ctx context.Context, id int64) (*model.PermissionDetailOutput, error) {
	var perm entity.Permissions
	if err := dao.Permissions.Ctx(ctx).WherePri(id).Scan(&perm); err != nil {
		return nil, err
	}
	if perm.Id == 0 {
		return nil, gerror.New("权限不存在")
	}

	return &model.PermissionDetailOutput{
		Id:          int64(perm.Id),
		ParentId:    int64(perm.ParentId),
		Name:        perm.Name,
		Code:        perm.Code,
		Module:      perm.Module,
		Description: perm.Description,
		Sort:        perm.Sort,
		CreatedAt:   perm.CreatedAt,
		UpdatedAt:   perm.UpdatedAt,
	}, nil
}

// Create 创建权限节点
func (s *sPermission) Create(ctx context.Context, in model.PermissionCreateInput) (int64, error) {
	count, err := dao.Permissions.Ctx(ctx).Where("code", in.Code).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("权限标识已存在")
	}

	now := gtime.Now()
	res, err := dao.Permissions.Ctx(ctx).Data(g.Map{
		"parent_id":   in.ParentId,
		"name":        in.Name,
		"code":        in.Code,
		"module":      in.Module,
		"description": in.Description,
		"sort":        in.Sort,
		"created_at":  now,
		"updated_at":  now,
	}).Insert()
	if err != nil {
		return 0, err
	}

	return res.LastInsertId()
}

// Update 更新权限节点
func (s *sPermission) Update(ctx context.Context, in model.PermissionUpdateInput) error {
	if in.Id == in.ParentId {
		return gerror.New("父级权限不能为自己")
	}

	count, err := dao.Permissions.Ctx(ctx).Where("code", in.Code).WhereNot("id", in.Id).Count()
	if err != nil {
		return err
	}
	if count > 0 {
		return gerror.New("权限标识已被占用")
	}

	now := gtime.Now()
	_, err = dao.Permissions.Ctx(ctx).WherePri(in.Id).Data(g.Map{
		"parent_id":   in.ParentId,
		"name":        in.Name,
		"code":        in.Code,
		"module":      in.Module,
		"description": in.Description,
		"sort":        in.Sort,
		"updated_at":  now,
	}).Update()
	return err
}

// Delete 删除权限节点
func (s *sPermission) Delete(ctx context.Context, id int64) error {
	// 检查是否有子权限
	childCount, err := dao.Permissions.Ctx(ctx).Where("parent_id", id).Count()
	if err != nil {
		return err
	}
	if childCount > 0 {
		return gerror.New("该权限下存在子节点，无法直接删除")
	}

	// 级联清理角色权限关联
	_, err = dao.RolePermissions.Ctx(ctx).Where("permission_id", id).Delete()
	if err != nil {
		return err
	}

	_, err = dao.Permissions.Ctx(ctx).WherePri(id).Delete()
	return err
}
