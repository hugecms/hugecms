// ================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// You can delete these comments if you wish manually maintain this interface file.
// ================================================================================

package service

import (
	"context"
	"hugecms/internal/model"
)

type (
	IAppearance interface {
		// SearchMenu 分页查询菜单列表
		SearchMenu(ctx context.Context, in model.NavMenuSearchInput) (*model.NavMenuSearchOutput, error)
		// GetMenu 获取指定菜单详情
		GetMenu(ctx context.Context, id int64) (*model.NavMenuItem, error)
		// CreateMenu 创建新菜单
		CreateMenu(ctx context.Context, in model.NavMenuCreateInput) (int64, error)
		// UpdateMenu 更新菜单
		UpdateMenu(ctx context.Context, in model.NavMenuUpdateInput) error
		// DeleteMenu 删除菜单及关联条目
		DeleteMenu(ctx context.Context, id int64) error
		// GetMenuItemsTree 获取指定菜单条目的树状结构
		GetMenuItemsTree(ctx context.Context, menuId int64) ([]model.NavItemTreeNode, error)
		// GetMenuTreeByAlias 根据菜单标识获取树状条目（前台/模板常用）
		GetMenuTreeByAlias(ctx context.Context, alias string) ([]model.NavItemTreeNode, error)
		// SaveMenuItem 新增或更新菜单条目
		SaveMenuItem(ctx context.Context, in model.NavItemSaveInput) (int64, error)
		// DeleteMenuItem 删除菜单条目及其所有子节点
		DeleteMenuItem(ctx context.Context, id int64) error
		// SearchBlock 分页查询区块
		SearchBlock(ctx context.Context, in model.BlockSearchInput) (*model.BlockSearchOutput, error)
		// GetBlock 获取区块详情
		GetBlock(ctx context.Context, id int64) (*model.BlockItem, error)
		// SaveBlock 创建或更新区块
		SaveBlock(ctx context.Context, in model.BlockSaveInput) (int64, error)
		// DeleteBlock 删除区块
		DeleteBlock(ctx context.Context, id int64) error
		// SearchTemplate 分页查询页面模板
		SearchTemplate(ctx context.Context, in model.PageTemplateSearchInput) (*model.PageTemplateSearchOutput, error)
		// GetTemplate 获取模板详情
		GetTemplate(ctx context.Context, id int64) (*model.PageTemplateItem, error)
		// SaveTemplate 保存模板
		SaveTemplate(ctx context.Context, in model.PageTemplateSaveInput) (int64, error)
		// DeleteTemplate 删除模板
		DeleteTemplate(ctx context.Context, id int64) error
	}
)

var (
	localAppearance IAppearance
)

func Appearance() IAppearance {
	if localAppearance == nil {
		panic("implement not found for interface IAppearance, forgot register?")
	}
	return localAppearance
}

func RegisterAppearance(i IAppearance) {
	localAppearance = i
}
