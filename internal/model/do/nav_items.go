// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// NavItems is the golang structure of table nav_items for DAO operations like Where/Data.
type NavItems struct {
	g.Meta    `orm:"table:nav_items, do:true"`
	Id        any         //
	MenuId    any         // 所属菜单集
	ParentId  any         // 父级ID（0代表顶级）
	Title     any         // 菜单显示标题
	LinkType  any         // 链接类型：custom自定义/content内容/term分类
	LinkValue any         // 链接目标值（自定义URL 或 content_id/term_id）
	OpenType  any         // 打开方式：0本窗口，1新窗口
	Icon      any         // 小图标CSS类
	IsActive  any         // 是否启用：1是，0否
	Sort      any         // 排序权重
	CreatedAt *gtime.Time //
	UpdatedAt *gtime.Time //
}
