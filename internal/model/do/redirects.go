// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Redirects is the golang structure of table redirects for DAO operations like Where/Data.
type Redirects struct {
	g.Meta     `orm:"table:redirects, do:true"`
	Id         any         //
	SourcePath any         // 来源路径（站内相对路径，以 / 开头）
	TargetPath any         // 目标路径（相对路径或完整URL）
	StatusCode any         // HTTP状态码：301永久重定向，302临时重定向
	Hits       any         // 命中次数（冗余计数，事务内维护）
	LastHitAt  *gtime.Time // 最后命中时间
	Remark     any         // 备注（如：slug 改版、栏目迁移）
	Status     any         // 状态：0停用，1启用
	CreatedAt  *gtime.Time //
	UpdatedAt  *gtime.Time //
}
