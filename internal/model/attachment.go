package model

import (
	"github.com/gogf/gf/v2/net/ghttp"
)

// AttachmentUploadInput 附件上传输入 DTO
type AttachmentUploadInput struct {
	File       *ghttp.UploadFile // 上传的文件对象
	UploaderId int64             // 上传者ID
}

// AttachmentUploadOutput 附件上传输出 DTO
type AttachmentUploadOutput struct {
	Id            int64  `json:"id"`
	FileName      string `json:"file_name"`
	FilePath      string `json:"file_path"`
	FileUrl       string `json:"file_url"`
	FileSize      int64  `json:"file_size"`
	MimeType      string `json:"mime_type"`
	StorageDriver string `json:"storage_driver"`
}
