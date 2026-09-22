package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Appearance = cAppearance{}

type cAppearance struct{}

// SearchMenu 分页查询菜单
func (c *cAppearance) SearchMenu(ctx context.Context, req *v1.NavMenuSearchReq) (res *v1.NavMenuSearchRes, err error) {
	out, err := service.Appearance().SearchMenu(ctx, model.NavMenuSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Keyword:  req.Keyword,
	})
	if err != nil {
		return nil, err
	}
	return &v1.NavMenuSearchRes{NavMenuSearchOutput: out}, nil
}

// GetMenu 获取菜单详情
func (c *cAppearance) GetMenu(ctx context.Context, req *v1.NavMenuGetReq) (res *v1.NavMenuGetRes, err error) {
	out, err := service.Appearance().GetMenu(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.NavMenuGetRes{NavMenuItem: out}, nil
}

// CreateMenu 创建菜单
func (c *cAppearance) CreateMenu(ctx context.Context, req *v1.NavMenuCreateReq) (res *v1.NavMenuCreateRes, err error) {
	newId, err := service.Appearance().CreateMenu(ctx, model.NavMenuCreateInput{
		Name:        req.Name,
		Alias:       req.Alias,
		Description: req.Description,
	})
	if err != nil {
		return nil, err
	}
	return &v1.NavMenuCreateRes{Id: newId}, nil
}

// UpdateMenu 更新菜单
func (c *cAppearance) UpdateMenu(ctx context.Context, req *v1.NavMenuUpdateReq) (res *v1.NavMenuUpdateRes, err error) {
	err = service.Appearance().UpdateMenu(ctx, model.NavMenuUpdateInput{
		Id:          req.Id,
		Name:        req.Name,
		Alias:       req.Alias,
		Description: req.Description,
	})
	if err != nil {
		return nil, err
	}
	return &v1.NavMenuUpdateRes{}, nil
}

// DeleteMenu 删除菜单
func (c *cAppearance) DeleteMenu(ctx context.Context, req *v1.NavMenuDeleteReq) (res *v1.NavMenuDeleteRes, err error) {
	if err := service.Appearance().DeleteMenu(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.NavMenuDeleteRes{}, nil
}

// GetMenuItemsTree 获取菜单条目树
func (c *cAppearance) GetMenuItemsTree(ctx context.Context, req *v1.NavItemsTreeReq) (res *v1.NavItemsTreeRes, err error) {
	tree, err := service.Appearance().GetMenuItemsTree(ctx, req.MenuId)
	if err != nil {
		return nil, err
	}
	return &v1.NavItemsTreeRes{Tree: tree}, nil
}

// SaveMenuItem 保存菜单条目
func (c *cAppearance) SaveMenuItem(ctx context.Context, req *v1.NavItemSaveReq) (res *v1.NavItemSaveRes, err error) {
	id, err := service.Appearance().SaveMenuItem(ctx, model.NavItemSaveInput{
		Id:        req.Id,
		MenuId:    req.MenuId,
		ParentId:  req.ParentId,
		Title:     req.Title,
		LinkType:  req.LinkType,
		LinkValue: req.LinkValue,
		OpenType:  req.OpenType,
		Icon:      req.Icon,
		IsActive:  req.IsActive,
		Sort:      req.Sort,
	})
	if err != nil {
		return nil, err
	}
	return &v1.NavItemSaveRes{Id: id}, nil
}

// DeleteMenuItem 删除菜单条目
func (c *cAppearance) DeleteMenuItem(ctx context.Context, req *v1.NavItemDeleteReq) (res *v1.NavItemDeleteRes, err error) {
	if err := service.Appearance().DeleteMenuItem(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.NavItemDeleteRes{}, nil
}

// SearchBlock 分页查询区块
func (c *cAppearance) SearchBlock(ctx context.Context, req *v1.BlockSearchReq) (res *v1.BlockSearchRes, err error) {
	out, err := service.Appearance().SearchBlock(ctx, model.BlockSearchInput{
		Page:      req.Page,
		PageSize:  req.PageSize,
		Keyword:   req.Keyword,
		BlockType: req.BlockType,
		Status:    req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.BlockSearchRes{BlockSearchOutput: out}, nil
}

// GetBlock 获取区块详情
func (c *cAppearance) GetBlock(ctx context.Context, req *v1.BlockGetReq) (res *v1.BlockGetRes, err error) {
	out, err := service.Appearance().GetBlock(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.BlockGetRes{BlockItem: out}, nil
}

// SaveBlock 保存区块
func (c *cAppearance) SaveBlock(ctx context.Context, req *v1.BlockSaveReq) (res *v1.BlockSaveRes, err error) {
	id, err := service.Appearance().SaveBlock(ctx, model.BlockSaveInput{
		Id:        req.Id,
		BlockName: req.BlockName,
		BlockType: req.BlockType,
		Content:   req.Content,
		Css:       req.Css,
		Js:        req.Js,
		IsGlobal:  req.IsGlobal,
		Status:    req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.BlockSaveRes{Id: id}, nil
}

// DeleteBlock 删除区块
func (c *cAppearance) DeleteBlock(ctx context.Context, req *v1.BlockDeleteReq) (res *v1.BlockDeleteRes, err error) {
	if err := service.Appearance().DeleteBlock(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.BlockDeleteRes{}, nil
}

// SearchTemplate 分页查询页面模板
func (c *cAppearance) SearchTemplate(ctx context.Context, req *v1.PageTemplateSearchReq) (res *v1.PageTemplateSearchRes, err error) {
	out, err := service.Appearance().SearchTemplate(ctx, model.PageTemplateSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Keyword:  req.Keyword,
		Category: req.Category,
		Status:   req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.PageTemplateSearchRes{PageTemplateSearchOutput: out}, nil
}

// GetTemplate 获取模板详情
func (c *cAppearance) GetTemplate(ctx context.Context, req *v1.PageTemplateGetReq) (res *v1.PageTemplateGetRes, err error) {
	out, err := service.Appearance().GetTemplate(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.PageTemplateGetRes{PageTemplateItem: out}, nil
}

// SaveTemplate 保存页面模板
func (c *cAppearance) SaveTemplate(ctx context.Context, req *v1.PageTemplateSaveReq) (res *v1.PageTemplateSaveRes, err error) {
	id, err := service.Appearance().SaveTemplate(ctx, model.PageTemplateSaveInput{
		Id:           req.Id,
		TemplateName: req.TemplateName,
		TemplateCode: req.TemplateCode,
		Category:     req.Category,
		PreviewImage: req.PreviewImage,
		Content:      req.Content,
		IsDefault:    req.IsDefault,
		Status:       req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.PageTemplateSaveRes{Id: id}, nil
}

// DeleteTemplate 删除页面模板
func (c *cAppearance) DeleteTemplate(ctx context.Context, req *v1.PageTemplateDeleteReq) (res *v1.PageTemplateDeleteRes, err error) {
	if err := service.Appearance().DeleteTemplate(ctx, req.Id); err != nil {
		return nil, err
	}
	return &v1.PageTemplateDeleteRes{}, nil
}
