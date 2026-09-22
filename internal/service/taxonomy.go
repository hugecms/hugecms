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
	ITaxonomy interface {
		// SearchTaxonomy 分页查询分类法
		SearchTaxonomy(ctx context.Context, in model.TaxonomySearchInput) (*model.TaxonomySearchOutput, error)
		// GetTaxonomy 获取分类法详情
		GetTaxonomy(ctx context.Context, id int64) (*model.TaxonomyItem, error)
		// SaveTaxonomy 保存分类法（新增或更新）
		SaveTaxonomy(ctx context.Context, in model.TaxonomySaveInput) (int64, error)
		// DeleteTaxonomy 删除分类法
		DeleteTaxonomy(ctx context.Context, id int64) error
		// GetTermTree 获取分类项树
		GetTermTree(ctx context.Context, in model.TermTreeInput) ([]model.TermTreeNode, error)
		// GetTerm 获取单个分类项详情
		GetTerm(ctx context.Context, id int64) (*model.TermItem, error)
		// SaveTerm 新增或更新分类项
		SaveTerm(ctx context.Context, in model.TermSaveInput) (int64, error)
		// DeleteTerm 删除分类项（级联删除子分类与关联关系）
		DeleteTerm(ctx context.Context, id int64) error
		// Recount 重算指定分类项下已发布内容数量
		Recount(ctx context.Context, termId int64) error
	}
)

var (
	localTaxonomy ITaxonomy
)

func Taxonomy() ITaxonomy {
	if localTaxonomy == nil {
		panic("implement not found for interface ITaxonomy, forgot register?")
	}
	return localTaxonomy
}

func RegisterTaxonomy(i ITaxonomy) {
	localTaxonomy = i
}
