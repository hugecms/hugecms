package common

import (
	"context"

	v1 "hugecms/api/common/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Attachment = cAttachment{}

type cAttachment struct{}

// Upload 处理附件上传接口
func (c *cAttachment) Upload(ctx context.Context, req *v1.AttachmentUploadReq) (res *v1.AttachmentUploadRes, err error) {
	userId := int64(0)
	user := service.Context().Get(ctx)
	if user != nil && user.User != nil {
		userId = user.User.Id
	}

	out, err := service.Attachment().Upload(ctx, model.AttachmentUploadInput{
		File:       req.File,
		UploaderId: userId,
	})
	if err != nil {
		return nil, err
	}

	res = &v1.AttachmentUploadRes{
		Id:            out.Id,
		FileName:      out.FileName,
		FilePath:      out.FilePath,
		FileUrl:       out.FileUrl,
		FileSize:      out.FileSize,
		MimeType:      out.MimeType,
		StorageDriver: out.StorageDriver,
	}
	return res, nil
}
