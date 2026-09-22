// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Redirects is the golang structure for table redirects.
type Redirects struct {
	Id         uint64      `json:"id"         orm:"id"          ` //
	SourcePath string      `json:"sourcePath" orm:"source_path" ` // 来源路径（站内相对路径，以 / 开头）
	TargetPath string      `json:"targetPath" orm:"target_path" ` // 目标路径（相对路径或完整URL）
	StatusCode uint        `json:"statusCode" orm:"status_code" ` // HTTP状态码：301永久重定向，302临时重定向
	Hits       uint64      `json:"hits"       orm:"hits"        ` // 命中次数（冗余计数，事务内维护）
	LastHitAt  *gtime.Time `json:"lastHitAt"  orm:"last_hit_at" ` // 最后命中时间
	Remark     string      `json:"remark"     orm:"remark"      ` // 备注（如：slug 改版、栏目迁移）
	Status     uint        `json:"status"     orm:"status"      ` // 状态：0停用，1启用
	CreatedAt  *gtime.Time `json:"createdAt"  orm:"created_at"  ` //
	UpdatedAt  *gtime.Time `json:"updatedAt"  orm:"updated_at"  ` //
}
