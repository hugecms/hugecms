// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// FriendLinks is the golang structure for table friend_links.
type FriendLinks struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	Category     string      `json:"category"     orm:"category"      ` // 链接分类（如：合作伙伴、友情链接）
	SiteName     string      `json:"siteName"     orm:"site_name"     ` // 网站名称
	SiteUrl      string      `json:"siteUrl"      orm:"site_url"      ` // 网站URL
	LogoUrl      string      `json:"logoUrl"      orm:"logo_url"      ` // 网站Logo URL
	Description  string      `json:"description"  orm:"description"   ` // 网站描述
	ContactEmail string      `json:"contactEmail" orm:"contact_email" ` // 联系人邮箱
	Sort         int         `json:"sort"         orm:"sort"          ` // 排序权重
	Status       uint        `json:"status"       orm:"status"        ` // 状态：0待审核，1已审核，2已拒绝
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` //
	UpdatedAt    *gtime.Time `json:"updatedAt"    orm:"updated_at"    ` //
}
