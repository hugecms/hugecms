// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// ContentsDao is the data access object for the table contents.
type ContentsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  ContentsColumns    // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// ContentsColumns defines and stores column names for the table contents.
type ContentsColumns struct {
	Id           string //
	ModelId      string // 所属模型ID
	Title        string // 内容标题
	Slug         string // URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）
	AuthorId     string // 发布者用户ID
	Status       string // 状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站
	Visibility   string // 可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）
	Password     string // 密码保护口令（visibility=password 时使用，哈希存储）
	Views        string // 浏览量计数
	CommentCount string // 评论数（审核通过的冗余计数，避免列表页逐条COUNT）
	Sort         string // 手动排序权重（数值越大越靠前）
	IsTop        string // 是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）
	PublishedAt  string // 计划/实际发布时间
	AuditStatus  string // 审核状态：pending待审核/approved通过/rejected驳回
	AuditRemark  string // 审核备注（驳回原因）
	AuditorId    string // 审核人ID
	AuditedAt    string // 审核时间
	CreatedAt    string //
	UpdatedAt    string //
}

// contentsColumns holds the columns for the table contents.
var contentsColumns = ContentsColumns{
	Id:           "id",
	ModelId:      "model_id",
	Title:        "title",
	Slug:         "slug",
	AuthorId:     "author_id",
	Status:       "status",
	Visibility:   "visibility",
	Password:     "password",
	Views:        "views",
	CommentCount: "comment_count",
	Sort:         "sort",
	IsTop:        "is_top",
	PublishedAt:  "published_at",
	AuditStatus:  "audit_status",
	AuditRemark:  "audit_remark",
	AuditorId:    "auditor_id",
	AuditedAt:    "audited_at",
	CreatedAt:    "created_at",
	UpdatedAt:    "updated_at",
}

// NewContentsDao creates and returns a new DAO object for table data access.
func NewContentsDao(handlers ...gdb.ModelHandler) *ContentsDao {
	return &ContentsDao{
		group:    "default",
		table:    "contents",
		columns:  contentsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *ContentsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *ContentsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *ContentsDao) Columns() ContentsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *ContentsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *ContentsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *ContentsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
