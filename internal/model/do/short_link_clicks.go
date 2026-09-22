// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// ShortLinkClicks is the golang structure of table short_link_clicks for DAO operations like Where/Data.
type ShortLinkClicks struct {
	g.Meta      `orm:"table:short_link_clicks, do:true"`
	Id          any         //
	ShortLinkId any         // 短链接ID
	ClickIp     any         // 点击者IP
	UserAgent   any         // 浏览器UA
	Referer     any         // 来源页
	CreatedAt   *gtime.Time //
}
