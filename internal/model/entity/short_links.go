// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// ShortLinks is the golang structure for table short_links.
type ShortLinks struct {
	Id         uint64      `json:"id"         orm:"id"           ` //
	ShortCode  string      `json:"shortCode"  orm:"short_code"   ` // 短链代码（如：abc123）
	TargetUrl  string      `json:"targetUrl"  orm:"target_url"   ` // 原始目标URL
	Title      string      `json:"title"      orm:"title"        ` // 链接标题/备注
	ClickCount uint        `json:"clickCount" orm:"click_count"  ` // 点击次数
	QrCodePath string      `json:"qrCodePath" orm:"qr_code_path" ` // 二维码图片存储路径
	ExpireAt   *gtime.Time `json:"expireAt"   orm:"expire_at"    ` // 过期时间（NULL永不过期）
	Status     uint        `json:"status"     orm:"status"       ` // 状态：0停用，1启用
	CreatedAt  *gtime.Time `json:"createdAt"  orm:"created_at"   ` //
	UpdatedAt  *gtime.Time `json:"updatedAt"  orm:"updated_at"   ` //
}
