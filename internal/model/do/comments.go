// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Comments is the golang structure of table comments for DAO operations like Where/Data.
type Comments struct {
	g.Meta        `orm:"table:comments, do:true"`
	Id            any         //
	ContentId     any         // 关联内容主表ID（全模型通用）
	UserId        any         // 评论者用户ID（NULL表示游客）
	ParentId      any         // 父评论ID（0=顶级评论，支持楼中楼）
	ReplyToUserId any         // 被回复用户ID（渲染"回复@xxx"用）
	AuthorName    any         // 评论者昵称（游客填写；登录用户冗余，防销号后无记录）
	AuthorEmail   any         // 评论者邮箱（游客填写，用于头像/回复通知）
	AuthorUrl     any         // 评论者主页URL
	Content       any         // 评论内容（纯文本；敏感词/反垃圾由插件钩子处理）
	Ip            any         // 评论者IP（反垃圾由插件处理）
	UserAgent     any         // 评论者UA
	Status        any         // 状态：pending待审核/approved已通过/spam垃圾/trash回收站
	LikeCount     any         // 点赞数
	CreatedAt     *gtime.Time //
	UpdatedAt     *gtime.Time //
}
