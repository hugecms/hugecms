// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// ContentPushQueue is the golang structure for table content_push_queue.
type ContentPushQueue struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	ContentId    uint64      `json:"contentId"    orm:"content_id"    ` // 被推送的内容ID
	PushType     string      `json:"pushType"     orm:"push_type"     ` // 推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss
	PushData     string      `json:"pushData"     orm:"push_data"     ` // 推送数据的最终形态（预处理后JSON）
	Status       string      `json:"status"       orm:"status"        ` // 状态：pending/processing/success/failed（执行与重试走 Laravel 队列）
	RetryCount   uint        `json:"retryCount"   orm:"retry_count"   ` // 已重试次数
	MaxRetries   uint        `json:"maxRetries"   orm:"max_retries"   ` // 最大重试次数
	ErrorMessage string      `json:"errorMessage" orm:"error_message" ` // 失败时的错误信息
	FinishedAt   *gtime.Time `json:"finishedAt"   orm:"finished_at"   ` // 完成时间
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` //
	UpdatedAt    *gtime.Time `json:"updatedAt"    orm:"updated_at"    ` //
}
