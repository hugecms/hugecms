// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// ShortLinks is the golang structure of table short_links for DAO operations like Where/Data.
type ShortLinks struct {
	g.Meta     `orm:"table:short_links, do:true"`
	Id         any         //
	ShortCode  any         // 短链代码（如：abc123）
	TargetUrl  any         // 原始目标URL
	Title      any         // 链接标题/备注
	ClickCount any         // 点击次数
	QrCodePath any         // 二维码图片存储路径
	ExpireAt   *gtime.Time // 过期时间（NULL永不过期）
	Status     any         // 状态：0停用，1启用
	CreatedAt  *gtime.Time //
	UpdatedAt  *gtime.Time //
}
