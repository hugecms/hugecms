// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// ContentPushQueue is the golang structure of table content_push_queue for DAO operations like Where/Data.
type ContentPushQueue struct {
	g.Meta       `orm:"table:content_push_queue, do:true"`
	Id           any         //
	ContentId    any         // 被推送的内容ID
	PushType     any         // 推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss
	PushData     any         // 推送数据的最终形态（预处理后JSON）
	Status       any         // 状态：pending/processing/success/failed（执行与重试走 Laravel 队列）
	RetryCount   any         // 已重试次数
	MaxRetries   any         // 最大重试次数
	ErrorMessage any         // 失败时的错误信息
	FinishedAt   *gtime.Time // 完成时间
	CreatedAt    *gtime.Time //
	UpdatedAt    *gtime.Time //
}
