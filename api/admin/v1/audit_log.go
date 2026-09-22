package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

// AuditLogSearchReq 审计日志分页查询请求
type AuditLogSearchReq struct {
	g.Meta     `path:"/audit-logs" method:"get" tags:"审计日志" summary:"分页查询审计日志"`
	Page       int    `json:"page" in:"query" d:"1" dc:"页码"`
	PageSize   int    `json:"page_size" in:"query" d:"20" dc:"每页条数"`
	EventType  string `json:"event_type" in:"query" dc:"事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT"`
	TargetType string `json:"target_type" in:"query" dc:"目标类型：content/term/user/attachment/config/comment/form_submission"`
	UserId     int64  `json:"user_id" in:"query" dc:"操作人ID"`
	Keyword    string `json:"keyword" in:"query" dc:"关键词（用户名/目标名称）"`
	StartTime  string `json:"start_time" in:"query" dc:"起始时间 YYYY-MM-DD HH:mm:ss"`
	EndTime    string `json:"end_time" in:"query" dc:"截止时间 YYYY-MM-DD HH:mm:ss"`
}

// AuditLogSearchRes 审计日志查询响应
type AuditLogSearchRes struct {
	*model.AuditLogSearchOutput
}

// AuditLogGetReq 获取审计日志详情请求
type AuditLogGetReq struct {
	g.Meta `path:"/audit-logs/{id}" method:"get" tags:"审计日志" summary:"获取审计日志详情（含Old/New快照Diff）"`
	Id     int64 `json:"id" in:"path" v:"required#日志ID不能为空" dc:"日志ID"`
}

// AuditLogGetRes 审计日志详情响应
type AuditLogGetRes struct {
	*model.AuditLogItem
}
