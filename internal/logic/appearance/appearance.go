package appearance

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

type sAppearance struct{}

func init() {
	service.RegisterAppearance(New())
}

func New() service.IAppearance {
	return &sAppearance{}
}

// ==================== 菜单 NavMenus ====================

// SearchMenu 分页查询菜单列表
func (s *sAppearance) SearchMenu(ctx context.Context, in model.NavMenuSearchInput) (*model.NavMenuSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.NavMenus.Ctx(ctx)
	if in.Keyword != "" {
		m = m.Where("name LIKE ? OR alias LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var menus []entity.NavMenus
	if err := m.Page(in.Page, in.PageSize).OrderAsc("id").Scan(&menus); err != nil {
		return nil, err
	}

	list := make([]model.NavMenuItem, 0, len(menus))
	for _, m := range menus {
		list = append(list, model.NavMenuItem{
			Id:          int64(m.Id),
			Name:        m.Name,
			Alias:       m.Alias,
			Description: m.Description,
			CreatedAt:   m.CreatedAt,
			UpdatedAt:   m.UpdatedAt,
		})
	}

	return &model.NavMenuSearchOutput{
		List:  list,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// GetMenu 获取指定菜单详情
func (s *sAppearance) GetMenu(ctx context.Context, id int64) (*model.NavMenuItem, error) {
	var m entity.NavMenus
	if err := dao.NavMenus.Ctx(ctx).WherePri(id).Scan(&m); err != nil {
		return nil, err
	}
	if m.Id == 0 {
		return nil, gerror.New("菜单不存在")
	}
	return &model.NavMenuItem{
		Id:          int64(m.Id),
		Name:        m.Name,
		Alias:       m.Alias,
		Description: m.Description,
		CreatedAt:   m.CreatedAt,
		UpdatedAt:   m.UpdatedAt,
	}, nil
}

// CreateMenu 创建新菜单
func (s *sAppearance) CreateMenu(ctx context.Context, in model.NavMenuCreateInput) (int64, error) {
	count, err := dao.NavMenus.Ctx(ctx).Where("alias", in.Alias).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("菜单标识已存在")
	}

	now := gtime.Now()
	res, err := dao.NavMenus.Ctx(ctx).Data(g.Map{
		"name":        in.Name,
		"alias":       in.Alias,
		"description": in.Description,
		"created_at":  now,
		"updated_at":  now,
	}).Insert()
	if err != nil {
		return 0, err
	}
	return res.LastInsertId()
}

// UpdateMenu 更新菜单
func (s *sAppearance) UpdateMenu(ctx context.Context, in model.NavMenuUpdateInput) error {
	count, err := dao.NavMenus.Ctx(ctx).Where("alias", in.Alias).WhereNot("id", in.Id).Count()
	if err != nil {
		return err
	}
	if count > 0 {
		return gerror.New("菜单标识已被占用")
	}

	now := gtime.Now()
	_, err = dao.NavMenus.Ctx(ctx).WherePri(in.Id).Data(g.Map{
		"name":        in.Name,
		"alias":       in.Alias,
		"description": in.Description,
		"updated_at":  now,
	}).Update()
	return err
}

// DeleteMenu 删除菜单及关联条目
func (s *sAppearance) DeleteMenu(ctx context.Context, id int64) error {
	return g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		_, err := dao.NavItems.Ctx(ctx).TX(tx).Where("menu_id", id).Delete()
		if err != nil {
			return err
		}
		_, err = dao.NavMenus.Ctx(ctx).TX(tx).WherePri(id).Delete()
		return err
	})
}

// ==================== 菜单条目 NavItems ====================

// GetMenuItemsTree 获取指定菜单条目的树状结构
func (s *sAppearance) GetMenuItemsTree(ctx context.Context, menuId int64) ([]model.NavItemTreeNode, error) {
	var items []entity.NavItems
	err := dao.NavItems.Ctx(ctx).Where("menu_id", menuId).OrderAsc("sort").OrderAsc("id").Scan(&items)
	if err != nil {
		return nil, err
	}
	return buildNavItemTree(items, 0), nil
}

// GetMenuTreeByAlias 根据菜单标识获取树状条目（前台/模板常用）
func (s *sAppearance) GetMenuTreeByAlias(ctx context.Context, alias string) ([]model.NavItemTreeNode, error) {
	var menu entity.NavMenus
	err := dao.NavMenus.Ctx(ctx).Where("alias", alias).Scan(&menu)
	if err != nil {
		return nil, err
	}
	if menu.Id == 0 {
		return nil, gerror.New("未找到对应菜单标识")
	}

	var items []entity.NavItems
	err = dao.NavItems.Ctx(ctx).Where("menu_id", menu.Id).Where("is_active", 1).OrderAsc("sort").OrderAsc("id").Scan(&items)
	if err != nil {
		return nil, err
	}
	return buildNavItemTree(items, 0), nil
}

func buildNavItemTree(items []entity.NavItems, parentId uint64) []model.NavItemTreeNode {
	var nodes []model.NavItemTreeNode
	for _, item := range items {
		if item.ParentId == parentId {
			children := buildNavItemTree(items, item.Id)
			node := model.NavItemTreeNode{
				Id:        int64(item.Id),
				MenuId:    int64(item.MenuId),
				ParentId:  int64(item.ParentId),
				Title:     item.Title,
				LinkType:  item.LinkType,
				LinkValue: item.LinkValue,
				OpenType:  int(item.OpenType),
				Icon:      item.Icon,
				IsActive:  int(item.IsActive),
				Sort:      item.Sort,
				Children:  children,
				CreatedAt: item.CreatedAt,
			}
			nodes = append(nodes, node)
		}
	}
	return nodes
}

// SaveMenuItem 新增或更新菜单条目
func (s *sAppearance) SaveMenuItem(ctx context.Context, in model.NavItemSaveInput) (int64, error) {
	now := gtime.Now()
	data := g.Map{
		"menu_id":    in.MenuId,
		"parent_id":  in.ParentId,
		"title":      in.Title,
		"link_type":  in.LinkType,
		"link_value": in.LinkValue,
		"open_type":  in.OpenType,
		"icon":       in.Icon,
		"is_active":  in.IsActive,
		"sort":       in.Sort,
		"updated_at": now,
	}

	if in.Id > 0 {
		_, err := dao.NavItems.Ctx(ctx).WherePri(in.Id).Data(data).Update()
		return in.Id, err
	}

	data["created_at"] = now
	res, err := dao.NavItems.Ctx(ctx).Data(data).Insert()
	if err != nil {
		return 0, err
	}
	return res.LastInsertId()
}

// DeleteMenuItem 删除菜单条目及其所有子节点
func (s *sAppearance) DeleteMenuItem(ctx context.Context, id int64) error {
	var collectIds func(parentId int64) ([]int64, error)
	collectIds = func(parentId int64) ([]int64, error) {
		ids := []int64{parentId}
		childVars, err := dao.NavItems.Ctx(ctx).Where("parent_id", parentId).Array("id")
		if err != nil {
			return nil, err
		}
		for _, v := range childVars {
			subIds, err := collectIds(v.Int64())
			if err != nil {
				return nil, err
			}
			ids = append(ids, subIds...)
		}
		return ids, nil
	}

	allIds, err := collectIds(id)
	if err != nil {
		return err
	}

	_, err = dao.NavItems.Ctx(ctx).WhereIn("id", allIds).Delete()
	return err
}

// ==================== 区块 Blocks ====================

// SearchBlock 分页查询区块
func (s *sAppearance) SearchBlock(ctx context.Context, in model.BlockSearchInput) (*model.BlockSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.Blocks.Ctx(ctx)
	if in.Keyword != "" {
		m = m.Where("block_name LIKE ?", "%"+in.Keyword+"%")
	}
	if in.BlockType != "" {
		m = m.Where("block_type", in.BlockType)
	}
	if in.Status != nil {
		m = m.Where("status", *in.Status)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var blocks []entity.Blocks
	if err := m.Page(in.Page, in.PageSize).OrderAsc("id").Scan(&blocks); err != nil {
		return nil, err
	}

	list := make([]model.BlockItem, 0, len(blocks))
	for _, b := range blocks {
		list = append(list, model.BlockItem{
			Id:        int64(b.Id),
			BlockName: b.BlockName,
			BlockType: b.BlockType,
			Content:   b.Content,
			Css:       b.Css,
			Js:        b.Js,
			IsGlobal:  int(b.IsGlobal),
			Status:    int(b.Status),
			CreatedAt: b.CreatedAt,
			UpdatedAt: b.UpdatedAt,
		})
	}

	return &model.BlockSearchOutput{
		List:  list,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// GetBlock 获取区块详情
func (s *sAppearance) GetBlock(ctx context.Context, id int64) (*model.BlockItem, error) {
	var b entity.Blocks
	if err := dao.Blocks.Ctx(ctx).WherePri(id).Scan(&b); err != nil {
		return nil, err
	}
	if b.Id == 0 {
		return nil, gerror.New("区块不存在")
	}

	return &model.BlockItem{
		Id:        int64(b.Id),
		BlockName: b.BlockName,
		BlockType: b.BlockType,
		Content:   b.Content,
		Css:       b.Css,
		Js:        b.Js,
		IsGlobal:  int(b.IsGlobal),
		Status:    int(b.Status),
		CreatedAt: b.CreatedAt,
		UpdatedAt: b.UpdatedAt,
	}, nil
}

// SaveBlock 创建或更新区块
func (s *sAppearance) SaveBlock(ctx context.Context, in model.BlockSaveInput) (int64, error) {
	now := gtime.Now()
	data := g.Map{
		"block_name": in.BlockName,
		"block_type": in.BlockType,
		"content":    in.Content,
		"css":        in.Css,
		"js":         in.Js,
		"is_global":  in.IsGlobal,
		"status":     in.Status,
		"updated_at": now,
	}

	if in.Id > 0 {
		_, err := dao.Blocks.Ctx(ctx).WherePri(in.Id).Data(data).Update()
		return in.Id, err
	}

	data["created_at"] = now
	res, err := dao.Blocks.Ctx(ctx).Data(data).Insert()
	if err != nil {
		return 0, err
	}
	return res.LastInsertId()
}

// DeleteBlock 删除区块
func (s *sAppearance) DeleteBlock(ctx context.Context, id int64) error {
	_, err := dao.Blocks.Ctx(ctx).WherePri(id).Delete()
	return err
}

// ==================== 页面模板 PageTemplates ====================

// SearchTemplate 分页查询页面模板
func (s *sAppearance) SearchTemplate(ctx context.Context, in model.PageTemplateSearchInput) (*model.PageTemplateSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.PageTemplates.Ctx(ctx)
	if in.Keyword != "" {
		m = m.Where("template_name LIKE ? OR template_code LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}
	if in.Category != "" {
		m = m.Where("category", in.Category)
	}
	if in.Status != nil {
		m = m.Where("status", *in.Status)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var templates []entity.PageTemplates
	if err := m.Page(in.Page, in.PageSize).OrderAsc("id").Scan(&templates); err != nil {
		return nil, err
	}

	list := make([]model.PageTemplateItem, 0, len(templates))
	for _, t := range templates {
		list = append(list, model.PageTemplateItem{
			Id:           int64(t.Id),
			TemplateName: t.TemplateName,
			TemplateCode: t.TemplateCode,
			Category:     t.Category,
			PreviewImage: t.PreviewImage,
			Content:      t.Content,
			IsDefault:    int(t.IsDefault),
			IsSystem:     int(t.IsSystem),
			Status:       int(t.Status),
			CreatedAt:    t.CreatedAt,
			UpdatedAt:    t.UpdatedAt,
		})
	}

	return &model.PageTemplateSearchOutput{
		List:  list,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// GetTemplate 获取模板详情
func (s *sAppearance) GetTemplate(ctx context.Context, id int64) (*model.PageTemplateItem, error) {
	var t entity.PageTemplates
	if err := dao.PageTemplates.Ctx(ctx).WherePri(id).Scan(&t); err != nil {
		return nil, err
	}
	if t.Id == 0 {
		return nil, gerror.New("模板不存在")
	}

	return &model.PageTemplateItem{
		Id:           int64(t.Id),
		TemplateName: t.TemplateName,
		TemplateCode: t.TemplateCode,
		Category:     t.Category,
		PreviewImage: t.PreviewImage,
		Content:      t.Content,
		IsDefault:    int(t.IsDefault),
		IsSystem:     int(t.IsSystem),
		Status:       int(t.Status),
		CreatedAt:    t.CreatedAt,
		UpdatedAt:    t.UpdatedAt,
	}, nil
}

// SaveTemplate 保存模板
func (s *sAppearance) SaveTemplate(ctx context.Context, in model.PageTemplateSaveInput) (int64, error) {
	count, err := dao.PageTemplates.Ctx(ctx).Where("template_code", in.TemplateCode).WhereNot("id", in.Id).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("模板标识代码已存在")
	}

	now := gtime.Now()
	data := g.Map{
		"template_name": in.TemplateName,
		"template_code": in.TemplateCode,
		"category":      in.Category,
		"preview_image": in.PreviewImage,
		"content":       in.Content,
		"is_default":    in.IsDefault,
		"status":        in.Status,
		"updated_at":    now,
	}

	if in.Id > 0 {
		_, err := dao.PageTemplates.Ctx(ctx).WherePri(in.Id).Data(data).Update()
		return in.Id, err
	}

	data["is_system"] = 0
	data["created_at"] = now
	res, err := dao.PageTemplates.Ctx(ctx).Data(data).Insert()
	if err != nil {
		return 0, err
	}
	return res.LastInsertId()
}

// DeleteTemplate 删除模板
func (s *sAppearance) DeleteTemplate(ctx context.Context, id int64) error {
	var t entity.PageTemplates
	if err := dao.PageTemplates.Ctx(ctx).WherePri(id).Scan(&t); err != nil {
		return err
	}
	if t.Id == 0 {
		return gerror.New("模板不存在")
	}
	if t.IsSystem == 1 {
		return gerror.New("系统内置模板禁止删除")
	}

	_, err := dao.PageTemplates.Ctx(ctx).WherePri(id).Delete()
	return err
}
