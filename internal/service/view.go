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
	IView interface {
		// RecordView 记录内容浏览量（带24小时IP哈希去重防刷机制）
		RecordView(ctx context.Context, in model.ContentViewRecordInput) (*model.ContentViewRecordOutput, error)
	}
)

var (
	localView IView
)

func View() IView {
	if localView == nil {
		panic("implement not found for interface IView, forgot register?")
	}
	return localView
}

func RegisterView(i IView) {
	localView = i
}
