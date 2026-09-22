// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// AttachmentsDao is the data access object for the table attachments.
type AttachmentsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  AttachmentsColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// AttachmentsColumns defines and stores column names for the table attachments.
type AttachmentsColumns struct {
	Id            string //
	UploaderId    string // 上传者ID
	FileName      string // 原始文件名
	FilePath      string // 物理存储相对路径
	StorageDriver string // 存储驱动：local/oss/cos/s3
	StorageBucket string // 存储桶名称（仅云存储有效）
	CdnUrl        string // CDN加速访问URL
	FileSize      string // 文件大小（字节）
	MimeType      string // MIME类型（如：image/jpeg）
	Width         string // 图片宽度（仅图片）
	Height        string // 图片高度（仅图片）
	AltText       string // SEO替代文本
	Sort          string // 排序
	CreatedAt     string //
	UpdatedAt     string //
}

// attachmentsColumns holds the columns for the table attachments.
var attachmentsColumns = AttachmentsColumns{
	Id:            "id",
	UploaderId:    "uploader_id",
	FileName:      "file_name",
	FilePath:      "file_path",
	StorageDriver: "storage_driver",
	StorageBucket: "storage_bucket",
	CdnUrl:        "cdn_url",
	FileSize:      "file_size",
	MimeType:      "mime_type",
	Width:         "width",
	Height:        "height",
	AltText:       "alt_text",
	Sort:          "sort",
	CreatedAt:     "created_at",
	UpdatedAt:     "updated_at",
}

// NewAttachmentsDao creates and returns a new DAO object for table data access.
func NewAttachmentsDao(handlers ...gdb.ModelHandler) *AttachmentsDao {
	return &AttachmentsDao{
		group:    "default",
		table:    "attachments",
		columns:  attachmentsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *AttachmentsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *AttachmentsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *AttachmentsDao) Columns() AttachmentsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *AttachmentsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *AttachmentsDao) Ctx(ctx context.Context) *gdb.Model {
	model := dao.DB().Model(dao.table)
	for _, handler := range dao.handlers {
		model = handler(model)
	}
	return model.Safe().Ctx(ctx)
}

// Transaction wraps the transaction logic using function f.
// It rolls back the transaction and returns the error if function f returns a non-nil error.
// It commits the transaction and returns nil if function f returns nil.
//
// Note: Do not commit or roll back the transaction in function f,
// as it is automatically handled by this function.
func (dao *AttachmentsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
