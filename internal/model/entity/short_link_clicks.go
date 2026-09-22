// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// ShortLinkClicks is the golang structure for table short_link_clicks.
type ShortLinkClicks struct {
	Id          uint64      `json:"id"          orm:"id"            ` //
	ShortLinkId uint64      `json:"shortLinkId" orm:"short_link_id" ` // 短链接ID
	ClickIp     string      `json:"clickIp"     orm:"click_ip"      ` // 点击者IP
	UserAgent   string      `json:"userAgent"   orm:"user_agent"    ` // 浏览器UA
	Referer     string      `json:"referer"     orm:"referer"       ` // 来源页
	CreatedAt   *gtime.Time `json:"createdAt"   orm:"created_at"    ` //
}
