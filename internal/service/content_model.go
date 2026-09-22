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
	IContentModel interface {
		// Search 分页查询内容模型
		Search(ctx context.Context, in model.ContentModelSearchInput) (*model.ContentModelSearchOutput, error)
		// Get 获取模型详情
		Get(ctx context.Context, id int64) (*model.ContentModelItem, error)
		// GetByAlias 根据别名获取模型
		GetByAlias(ctx context.Context, alias string) (*model.ContentModelItem, error)
		// Create 创建内容模型并自动生成物理数据表 data_{alias}
		Create(ctx context.Context, in model.ContentModelCreateInput) (int64, error)
		// Update 更新内容模型配置（table_name 与 alias 固化不可修改）
		Update(ctx context.Context, in model.ContentModelUpdateInput) error
		// Delete 删除内容模型
		Delete(ctx context.Context, id int64) error
	}
)

var (
	localContentModel IContentModel
)

func ContentModel() IContentModel {
	if localContentModel == nil {
		panic("implement not found for interface IContentModel, forgot register?")
	}
	return localContentModel
}

func RegisterContentModel(i IContentModel) {
	localContentModel = i
}
