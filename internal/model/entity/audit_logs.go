// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// AuditLogs is the golang structure for table audit_logs.
type AuditLogs struct {
	Id              uint64      `json:"id"              orm:"id"               ` //
	UserId          uint64      `json:"userId"          orm:"user_id"          ` // 操作用户ID
	UserName        string      `json:"userName"        orm:"user_name"        ` // 操作用户名（冗余，防用户被删后无记录）
	ClientIp        string      `json:"clientIp"        orm:"client_ip"        ` // 客户端IP（支持IPv6）
	UserAgent       string      `json:"userAgent"       orm:"user_agent"       ` // 客户端UA信息
	RequestId       string      `json:"requestId"       orm:"request_id"       ` // 请求追踪ID（关联一次请求的所有日志）
	EventType       string      `json:"eventType"       orm:"event_type"       ` // 事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT
	TargetType      string      `json:"targetType"      orm:"target_type"      ` // 目标类型：content/term/user/attachment/config/comment/form_submission
	TargetId        string      `json:"targetId"        orm:"target_id"        ` // 目标ID（可能是数字或UUID）
	TargetName      string      `json:"targetName"      orm:"target_name"      ` // 目标名称（冗余，便于展示）
	OldValue        string      `json:"oldValue"        orm:"old_value"        ` // 修改前的数据快照（JSON）
	NewValue        string      `json:"newValue"        orm:"new_value"        ` // 修改后的数据快照（JSON）
	OperationResult uint        `json:"operationResult" orm:"operation_result" ` // 操作结果：0失败，1成功
	ErrorMessage    string      `json:"errorMessage"    orm:"error_message"    ` // 失败时的错误信息
	CreatedAt       *gtime.Time `json:"createdAt"       orm:"created_at"       ` // 创建时间（毫秒精度，只增不改）
}
