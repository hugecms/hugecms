// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// RecycleBin is the golang structure for table recycle_bin.
type RecycleBin struct {
	Id            uint64      `json:"id"            orm:"id"             ` //
	DeletedBy     uint64      `json:"deletedBy"     orm:"deleted_by"     ` // 删除人用户ID
	TargetType    string      `json:"targetType"    orm:"target_type"    ` // 原对象类型：content/term/attachment/user/form_submission/comment
	TargetId      string      `json:"targetId"      orm:"target_id"      ` // 原对象ID
	OriginalData  string      `json:"originalData"  orm:"original_data"  ` // 删除前的全量数据快照（JSON）
	RestoreData   string      `json:"restoreData"   orm:"restore_data"   ` // 恢复时所需的数据映射（如恢复时需新建ID）
	RetentionDays uint        `json:"retentionDays" orm:"retention_days" ` // 保留天数（超时由 Scheduler 物理清除）
	CreatedAt     *gtime.Time `json:"createdAt"     orm:"created_at"     ` // 删除时间
	ExpireAt      *gtime.Time `json:"expireAt"      orm:"expire_at"      ` // 过期时间（虚拟生成列）
}
