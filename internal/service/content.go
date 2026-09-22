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
	IContent interface {
		// Search 分页多维检索内容
		Search(ctx context.Context, in model.ContentSearchInput) (*model.ContentSearchOutput, error)
		// Get 获取内容详情（聚合主表、动态模型数据、分类与SEO）
		Get(ctx context.Context, id int64) (*model.ContentDetailOutput, error)
		// Save 新建或更新内容主业务（三维状态机流转与动态数据表同步）
		Save(ctx context.Context, in model.ContentSaveInput) (int64, error)
		// Audit 内容审核流转
		Audit(ctx context.Context, in model.ContentAuditInput) error
		// ChangeStatus 快速流转内容状态（published, archived, draft 等）
		ChangeStatus(ctx context.Context, in model.ContentStatusInput) error
		// Trash 移入回收站
		Trash(ctx context.Context, id int64) error
	}
)

var (
	localContent IContent
)

func Content() IContent {
	if localContent == nil {
		panic("implement not found for interface IContent, forgot register?")
	}
	return localContent
}

func RegisterContent(i IContent) {
	localContent = i
}
