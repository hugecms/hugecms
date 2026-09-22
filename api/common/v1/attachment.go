package v1

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/net/ghttp"
)

// AttachmentUploadReq 附件上传请求
type AttachmentUploadReq struct {
	g.Meta `path:"/attachment/upload" method:"post" mime:"multipart/form-data" tags:"通用接口" summary:"上传附件/图片"`
	File   *ghttp.UploadFile `json:"file" type:"file" v:"required#请选择上传文件" dc:"要上传的文件"`
}

// AttachmentUploadRes 附件上传响应
type AttachmentUploadRes struct {
	Id            int64  `json:"id" dc:"附件ID"`
	FileName      string `json:"file_name" dc:"原始文件名"`
	FilePath      string `json:"file_path" dc:"相对存储路径"`
	FileUrl       string `json:"file_url" dc:"完整访问URL"`
	FileSize      int64  `json:"file_size" dc:"文件大小（字节）"`
	MimeType      string `json:"mime_type" dc:"MIME类型"`
	StorageDriver string `json:"storage_driver" dc:"存储驱动"`
}
