// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// StatisticsDaily is the golang structure for table statistics_daily.
type StatisticsDaily struct {
	Id                uint64      `json:"id"                orm:"id"                 ` //
	StatDate          *gtime.Time `json:"statDate"          orm:"stat_date"          ` // 统计日期
	NewContents       uint        `json:"newContents"       orm:"new_contents"       ` // 新增内容数
	PublishedContents uint        `json:"publishedContents" orm:"published_contents" ` // 发布内容数
	TotalContents     uint        `json:"totalContents"     orm:"total_contents"     ` // 累计内容总数
	TotalViews        uint64      `json:"totalViews"        orm:"total_views"        ` // 全站浏览量
	NewComments       uint        `json:"newComments"       orm:"new_comments"       ` // 新增评论数
	NewUsers          uint        `json:"newUsers"          orm:"new_users"          ` // 新增注册用户数
	ActiveUsers       uint        `json:"activeUsers"       orm:"active_users"       ` // 活跃用户数（登录/操作）
	TotalUsers        uint        `json:"totalUsers"        orm:"total_users"        ` // 累计注册用户数
	CreatedAt         *gtime.Time `json:"createdAt"         orm:"created_at"         ` //
	UpdatedAt         *gtime.Time `json:"updatedAt"         orm:"updated_at"         ` //
}
