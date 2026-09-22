// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// RecycleBin is the golang structure of table recycle_bin for DAO operations like Where/Data.
type RecycleBin struct {
	g.Meta        `orm:"table:recycle_bin, do:true"`
	Id            any         //
	DeletedBy     any         // 删除人用户ID
	TargetType    any         // 原对象类型：content/term/attachment/user/form_submission/comment
	TargetId      any         // 原对象ID
	OriginalData  any         // 删除前的全量数据快照（JSON）
	RestoreData   any         // 恢复时所需的数据映射（如恢复时需新建ID）
	RetentionDays any         // 保留天数（超时由 Scheduler 物理清除）
	CreatedAt     *gtime.Time // 删除时间
	ExpireAt      *gtime.Time // 过期时间（虚拟生成列）
}
