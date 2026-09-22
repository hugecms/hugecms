// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Ads is the golang structure for table ads.
type Ads struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	PositionId   uint64      `json:"positionId"   orm:"position_id"   ` // 所属广告位ID
	Title        string      `json:"title"        orm:"title"         ` // 广告标题
	AdType       string      `json:"adType"       orm:"ad_type"       ` // 广告类型：image/text/video/html
	CoverImage   string      `json:"coverImage"   orm:"cover_image"   ` // 广告图片/视频封面URL
	Content      string      `json:"content"      orm:"content"       ` // 广告内容（纯文本或HTML代码）
	LinkUrl      string      `json:"linkUrl"      orm:"link_url"      ` // 广告跳转链接
	LinkTarget   uint        `json:"linkTarget"   orm:"link_target"   ` // 打开方式：0本窗口，1新窗口
	Sort         int         `json:"sort"         orm:"sort"          ` // 展示排序（数值越小越靠前）
	StartTime    *gtime.Time `json:"startTime"    orm:"start_time"    ` // 投放开始时间（NULL表示立即开始）
	EndTime      *gtime.Time `json:"endTime"      orm:"end_time"      ` // 投放结束时间（NULL表示永久，过期由时间判断）
	DisplayLimit uint        `json:"displayLimit" orm:"display_limit" ` // 展示次数上限（0不限）
	ClickLimit   uint        `json:"clickLimit"   orm:"click_limit"   ` // 点击次数上限（0不限）
	DisplayCount uint        `json:"displayCount" orm:"display_count" ` // 实际展示次数
	ClickCount   uint        `json:"clickCount"   orm:"click_count"   ` // 实际点击次数
	Status       uint        `json:"status"       orm:"status"        ` // 状态：0停用，1启用
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` //
	UpdatedAt    *gtime.Time `json:"updatedAt"    orm:"updated_at"    ` //
}
