// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// FriendLinks is the golang structure of table friend_links for DAO operations like Where/Data.
type FriendLinks struct {
	g.Meta       `orm:"table:friend_links, do:true"`
	Id           any         //
	Category     any         // 链接分类（如：合作伙伴、友情链接）
	SiteName     any         // 网站名称
	SiteUrl      any         // 网站URL
	LogoUrl      any         // 网站Logo URL
	Description  any         // 网站描述
	ContactEmail any         // 联系人邮箱
	Sort         any         // 排序权重
	Status       any         // 状态：0待审核，1已审核，2已拒绝
	CreatedAt    *gtime.Time //
	UpdatedAt    *gtime.Time //
}
