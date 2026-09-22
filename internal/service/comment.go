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
	IComment interface {
		// Search 分页查询评论
		Search(ctx context.Context, in model.CommentSearchInput) (*model.CommentSearchOutput, error)
		// GetTree 获取指定内容的已通过审核评论树（前台展示）
		GetTree(ctx context.Context, contentId int64) ([]model.CommentTreeNode, error)
		// Create 提交新评论
		Create(ctx context.Context, in model.CommentCreateInput) (int64, error)
		// Audit 批量审核评论并联动更新内容 comment_count
		Audit(ctx context.Context, in model.CommentAuditInput) error
		// Delete 删除评论
		Delete(ctx context.Context, id int64) error
	}
)

var (
	localComment IComment
)

func Comment() IComment {
	if localComment == nil {
		panic("implement not found for interface IComment, forgot register?")
	}
	return localComment
}

func RegisterComment(i IComment) {
	localComment = i
}
