// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// StatisticsDaily is the golang structure of table statistics_daily for DAO operations like Where/Data.
type StatisticsDaily struct {
	g.Meta            `orm:"table:statistics_daily, do:true"`
	Id                any         //
	StatDate          *gtime.Time // 统计日期
	NewContents       any         // 新增内容数
	PublishedContents any         // 发布内容数
	TotalContents     any         // 累计内容总数
	TotalViews        any         // 全站浏览量
	NewComments       any         // 新增评论数
	NewUsers          any         // 新增注册用户数
	ActiveUsers       any         // 活跃用户数（登录/操作）
	TotalUsers        any         // 累计注册用户数
	CreatedAt         *gtime.Time //
	UpdatedAt         *gtime.Time //
}
