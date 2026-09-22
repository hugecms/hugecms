// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// AdPositions is the golang structure of table ad_positions for DAO operations like Where/Data.
type AdPositions struct {
	g.Meta      `orm:"table:ad_positions, do:true"`
	Id          any         //
	Name        any         // 广告位名称（如：首页Banner）
	Code        any         // 广告位代码（如：home_banner，模板调用用）
	Width       any         // 建议宽度（像素）
	Height      any         // 建议高度（像素）
	AdType      any         // 支持的广告类型：image/text/video/html
	MaxCount    any         // 该广告位最多展示广告数量
	Description any         // 广告位描述
	Status      any         // 状态：0停用，1启用
	CreatedAt   *gtime.Time //
	UpdatedAt   *gtime.Time //
}
