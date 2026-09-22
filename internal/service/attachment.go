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
	IAttachment interface {
		// Upload 上传附件并持久化记录到 attachments 表
		Upload(ctx context.Context, in model.AttachmentUploadInput) (*model.AttachmentUploadOutput, error)
		// Delete 删除附件（含双路径引用跟踪校验保护）
		Delete(ctx context.Context, id int64) error
	}
)

var (
	localAttachment IAttachment
)

func Attachment() IAttachment {
	if localAttachment == nil {
		panic("implement not found for interface IAttachment, forgot register?")
	}
	return localAttachment
}

func RegisterAttachment(i IAttachment) {
	localAttachment = i
}
