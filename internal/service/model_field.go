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
	IModelField interface {
		// List 获取指定模型的所有字段列表
		List(ctx context.Context, modelId int64) ([]model.ModelFieldItem, error)
		// Get 获取单个字段详情
		Get(ctx context.Context, id int64) (*model.ModelFieldItem, error)
		// Create 新增模型字段并同步执行物理表 DDL
		Create(ctx context.Context, in model.ModelFieldCreateInput) (int64, error)
		// Update 更新模型字段元数据
		Update(ctx context.Context, in model.ModelFieldUpdateInput) error
		// Delete 删除模型字段并同步从物理表中移除该列
		Delete(ctx context.Context, id int64) error
	}
)

var (
	localModelField IModelField
)

func ModelField() IModelField {
	if localModelField == nil {
		panic("implement not found for interface IModelField, forgot register?")
	}
	return localModelField
}

func RegisterModelField(i IModelField) {
	localModelField = i
}
