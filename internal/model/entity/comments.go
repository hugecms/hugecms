// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Comments is the golang structure for table comments.
type Comments struct {
	Id            uint64      `json:"id"            orm:"id"               ` //
	ContentId     uint64      `json:"contentId"     orm:"content_id"       ` // 关联内容主表ID（全模型通用）
	UserId        uint64      `json:"userId"        orm:"user_id"          ` // 评论者用户ID（NULL表示游客）
	ParentId      uint64      `json:"parentId"      orm:"parent_id"        ` // 父评论ID（0=顶级评论，支持楼中楼）
	ReplyToUserId uint64      `json:"replyToUserId" orm:"reply_to_user_id" ` // 被回复用户ID（渲染"回复@xxx"用）
	AuthorName    string      `json:"authorName"    orm:"author_name"      ` // 评论者昵称（游客填写；登录用户冗余，防销号后无记录）
	AuthorEmail   string      `json:"authorEmail"   orm:"author_email"     ` // 评论者邮箱（游客填写，用于头像/回复通知）
	AuthorUrl     string      `json:"authorUrl"     orm:"author_url"       ` // 评论者主页URL
	Content       string      `json:"content"       orm:"content"          ` // 评论内容（纯文本；敏感词/反垃圾由插件钩子处理）
	Ip            string      `json:"ip"            orm:"ip"               ` // 评论者IP（反垃圾由插件处理）
	UserAgent     string      `json:"userAgent"     orm:"user_agent"       ` // 评论者UA
	Status        string      `json:"status"        orm:"status"           ` // 状态：pending待审核/approved已通过/spam垃圾/trash回收站
	LikeCount     uint        `json:"likeCount"     orm:"like_count"       ` // 点赞数
	CreatedAt     *gtime.Time `json:"createdAt"     orm:"created_at"       ` //
	UpdatedAt     *gtime.Time `json:"updatedAt"     orm:"updated_at"       ` //
}
