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
	IRecycleBin interface {
		// Search 分页查询回收站条目
		Search(ctx context.Context, in model.RecycleBinSearchInput) (*model.RecycleBinSearchOutput, error)
		// SnapshotAndTrash 内容删除进回收站：完整快照写入与状态置为 trash
		SnapshotAndTrash(ctx context.Context, contentId int64, deletedBy int64) error
		// Restore 从回收站恢复内容（逆序重建主行、模型动态数据表与全部关联）
		Restore(ctx context.Context, recycleId int64) (int64, error)
		// Purge 彻底物理清除（包括级联关联与多态 SEO 元数据）
		Purge(ctx context.Context, recycleId int64) error
	}
)

var (
	localRecycleBin IRecycleBin
)

func RecycleBin() IRecycleBin {
	if localRecycleBin == nil {
		panic("implement not found for interface IRecycleBin, forgot register?")
	}
	return localRecycleBin
}

func RegisterRecycleBin(i IRecycleBin) {
	localRecycleBin = i
}
