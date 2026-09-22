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
	IOption interface {
		// Search 分页查询配置
		Search(ctx context.Context, in model.OptionSearchInput) (*model.OptionSearchOutput, error)
		// Get 获取指定配置值，带缓存
		Get(ctx context.Context, key string) (string, error)
		// Save 保存单条配置
		Save(ctx context.Context, in model.OptionSaveInput) error
		// BatchSave 批量保存配置
		BatchSave(ctx context.Context, in model.OptionBatchSaveInput) error
		// Delete 删除配置
		Delete(ctx context.Context, key string) error
		// GetAllAutoload 获取所有自动加载的配置
		GetAllAutoload(ctx context.Context) (map[string]string, error)
	}
)

var (
	localOption IOption
)

func Option() IOption {
	if localOption == nil {
		panic("implement not found for interface IOption, forgot register?")
	}
	return localOption
}

func RegisterOption(i IOption) {
	localOption = i
}
