// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// AdPositions is the golang structure for table ad_positions.
type AdPositions struct {
	Id          uint64      `json:"id"          orm:"id"          ` //
	Name        string      `json:"name"        orm:"name"        ` // 广告位名称（如：首页Banner）
	Code        string      `json:"code"        orm:"code"        ` // 广告位代码（如：home_banner，模板调用用）
	Width       uint        `json:"width"       orm:"width"       ` // 建议宽度（像素）
	Height      uint        `json:"height"      orm:"height"      ` // 建议高度（像素）
	AdType      string      `json:"adType"      orm:"ad_type"     ` // 支持的广告类型：image/text/video/html
	MaxCount    uint        `json:"maxCount"    orm:"max_count"   ` // 该广告位最多展示广告数量
	Description string      `json:"description" orm:"description" ` // 广告位描述
	Status      uint        `json:"status"      orm:"status"      ` // 状态：0停用，1启用
	CreatedAt   *gtime.Time `json:"createdAt"   orm:"created_at"  ` //
	UpdatedAt   *gtime.Time `json:"updatedAt"   orm:"updated_at"  ` //
}
