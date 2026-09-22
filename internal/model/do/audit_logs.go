// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// AuditLogs is the golang structure of table audit_logs for DAO operations like Where/Data.
type AuditLogs struct {
	g.Meta          `orm:"table:audit_logs, do:true"`
	Id              any         //
	UserId          any         // 操作用户ID
	UserName        any         // 操作用户名（冗余，防用户被删后无记录）
	ClientIp        any         // 客户端IP（支持IPv6）
	UserAgent       any         // 客户端UA信息
	RequestId       any         // 请求追踪ID（关联一次请求的所有日志）
	EventType       any         // 事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT
	TargetType      any         // 目标类型：content/term/user/attachment/config/comment/form_submission
	TargetId        any         // 目标ID（可能是数字或UUID）
	TargetName      any         // 目标名称（冗余，便于展示）
	OldValue        any         // 修改前的数据快照（JSON）
	NewValue        any         // 修改后的数据快照（JSON）
	OperationResult any         // 操作结果：0失败，1成功
	ErrorMessage    any         // 失败时的错误信息
	CreatedAt       *gtime.Time // 创建时间（毫秒精度，只增不改）
}
