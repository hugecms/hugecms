// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Attachments is the golang structure for table attachments.
type Attachments struct {
	Id            uint64      `json:"id"            orm:"id"             ` //
	UploaderId    uint64      `json:"uploaderId"    orm:"uploader_id"    ` // 上传者ID
	FileName      string      `json:"fileName"      orm:"file_name"      ` // 原始文件名
	FilePath      string      `json:"filePath"      orm:"file_path"      ` // 物理存储相对路径
	StorageDriver string      `json:"storageDriver" orm:"storage_driver" ` // 存储驱动：local/oss/cos/s3
	StorageBucket string      `json:"storageBucket" orm:"storage_bucket" ` // 存储桶名称（仅云存储有效）
	CdnUrl        string      `json:"cdnUrl"        orm:"cdn_url"        ` // CDN加速访问URL
	FileSize      uint64      `json:"fileSize"      orm:"file_size"      ` // 文件大小（字节）
	MimeType      string      `json:"mimeType"      orm:"mime_type"      ` // MIME类型（如：image/jpeg）
	Width         uint        `json:"width"         orm:"width"          ` // 图片宽度（仅图片）
	Height        uint        `json:"height"        orm:"height"         ` // 图片高度（仅图片）
	AltText       string      `json:"altText"       orm:"alt_text"       ` // SEO替代文本
	Sort          int         `json:"sort"          orm:"sort"           ` // 排序
	CreatedAt     *gtime.Time `json:"createdAt"     orm:"created_at"     ` //
	UpdatedAt     *gtime.Time `json:"updatedAt"     orm:"updated_at"     ` //
}
