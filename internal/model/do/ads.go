// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Ads is the golang structure of table ads for DAO operations like Where/Data.
type Ads struct {
	g.Meta       `orm:"table:ads, do:true"`
	Id           any         //
	PositionId   any         // 所属广告位ID
	Title        any         // 广告标题
	AdType       any         // 广告类型：image/text/video/html
	CoverImage   any         // 广告图片/视频封面URL
	Content      any         // 广告内容（纯文本或HTML代码）
	LinkUrl      any         // 广告跳转链接
	LinkTarget   any         // 打开方式：0本窗口，1新窗口
	Sort         any         // 展示排序（数值越小越靠前）
	StartTime    *gtime.Time // 投放开始时间（NULL表示立即开始）
	EndTime      *gtime.Time // 投放结束时间（NULL表示永久，过期由时间判断）
	DisplayLimit any         // 展示次数上限（0不限）
	ClickLimit   any         // 点击次数上限（0不限）
	DisplayCount any         // 实际展示次数
	ClickCount   any         // 实际点击次数
	Status       any         // 状态：0停用，1启用
	CreatedAt    *gtime.Time //
	UpdatedAt    *gtime.Time //
}
