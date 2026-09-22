// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Attachments is the golang structure of table attachments for DAO operations like Where/Data.
type Attachments struct {
	g.Meta        `orm:"table:attachments, do:true"`
	Id            any         //
	UploaderId    any         // 上传者ID
	FileName      any         // 原始文件名
	FilePath      any         // 物理存储相对路径
	StorageDriver any         // 存储驱动：local/oss/cos/s3
	StorageBucket any         // 存储桶名称（仅云存储有效）
	CdnUrl        any         // CDN加速访问URL
	FileSize      any         // 文件大小（字节）
	MimeType      any         // MIME类型（如：image/jpeg）
	Width         any         // 图片宽度（仅图片）
	Height        any         // 图片高度（仅图片）
	AltText       any         // SEO替代文本
	Sort          any         // 排序
	CreatedAt     *gtime.Time //
	UpdatedAt     *gtime.Time //
}
