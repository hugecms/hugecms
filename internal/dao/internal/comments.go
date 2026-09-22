// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// CommentsDao is the data access object for the table comments.
type CommentsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  CommentsColumns    // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// CommentsColumns defines and stores column names for the table comments.
type CommentsColumns struct {
	Id            string //
	ContentId     string // 关联内容主表ID（全模型通用）
	UserId        string // 评论者用户ID（NULL表示游客）
	ParentId      string // 父评论ID（0=顶级评论，支持楼中楼）
	ReplyToUserId string // 被回复用户ID（渲染"回复@xxx"用）
	AuthorName    string // 评论者昵称（游客填写；登录用户冗余，防销号后无记录）
	AuthorEmail   string // 评论者邮箱（游客填写，用于头像/回复通知）
	AuthorUrl     string // 评论者主页URL
	Content       string // 评论内容（纯文本；敏感词/反垃圾由插件钩子处理）
	Ip            string // 评论者IP（反垃圾由插件处理）
	UserAgent     string // 评论者UA
	Status        string // 状态：pending待审核/approved已通过/spam垃圾/trash回收站
	LikeCount     string // 点赞数
	CreatedAt     string //
	UpdatedAt     string //
}

// commentsColumns holds the columns for the table comments.
var commentsColumns = CommentsColumns{
	Id:            "id",
	ContentId:     "content_id",
	UserId:        "user_id",
	ParentId:      "parent_id",
	ReplyToUserId: "reply_to_user_id",
	AuthorName:    "author_name",
	AuthorEmail:   "author_email",
	AuthorUrl:     "author_url",
	Content:       "content",
	Ip:            "ip",
	UserAgent:     "user_agent",
	Status:        "status",
	LikeCount:     "like_count",
	CreatedAt:     "created_at",
	UpdatedAt:     "updated_at",
}

// NewCommentsDao creates and returns a new DAO object for table data access.
func NewCommentsDao(handlers ...gdb.ModelHandler) *CommentsDao {
	return &CommentsDao{
		group:    "default",
		table:    "comments",
		columns:  commentsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *CommentsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *CommentsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *CommentsDao) Columns() CommentsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *CommentsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *CommentsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *CommentsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
