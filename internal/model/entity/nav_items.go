// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// NavItems is the golang structure for table nav_items.
type NavItems struct {
	Id        uint64      `json:"id"        orm:"id"         ` //
	MenuId    uint64      `json:"menuId"    orm:"menu_id"    ` // 所属菜单集
	ParentId  uint64      `json:"parentId"  orm:"parent_id"  ` // 父级ID（0代表顶级）
	Title     string      `json:"title"     orm:"title"      ` // 菜单显示标题
	LinkType  string      `json:"linkType"  orm:"link_type"  ` // 链接类型：custom自定义/content内容/term分类
	LinkValue string      `json:"linkValue" orm:"link_value" ` // 链接目标值（自定义URL 或 content_id/term_id）
	OpenType  uint        `json:"openType"  orm:"open_type"  ` // 打开方式：0本窗口，1新窗口
	Icon      string      `json:"icon"      orm:"icon"       ` // 小图标CSS类
	IsActive  uint        `json:"isActive"  orm:"is_active"  ` // 是否启用：1是，0否
	Sort      int         `json:"sort"      orm:"sort"       ` // 排序权重
	CreatedAt *gtime.Time `json:"createdAt" orm:"created_at" ` //
	UpdatedAt *gtime.Time `json:"updatedAt" orm:"updated_at" ` //
}
