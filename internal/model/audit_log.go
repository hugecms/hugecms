package model

import "github.com/gogf/gf/v2/os/gtime"

// AuditLogItem 审计日志条目
type AuditLogItem struct {
	Id              int64       `json:"id"`
	UserId          int64       `json:"user_id"`
	UserName        string      `json:"user_name"`
	ClientIp        string      `json:"client_ip"`
	UserAgent       string      `json:"user_agent"`
	RequestId       string      `json:"request_id"`
	EventType       string      `json:"event_type"`
	TargetType      string      `json:"target_type"`
	TargetId        string      `json:"target_id"`
	TargetName      string      `json:"target_name"`
	OldValue        string      `json:"old_value"`
	NewValue        string      `json:"new_value"`
	OperationResult int         `json:"operation_result"`
	ErrorMessage    string      `json:"error_message"`
	CreatedAt       *gtime.Time `json:"created_at"`
}

// AuditLogSearchInput 审计日志检索入参
type AuditLogSearchInput struct {
	Page       int    `json:"page"`
	PageSize   int    `json:"page_size"`
	EventType  string `json:"event_type"`
	TargetType string `json:"target_type"`
	UserId     int64  `json:"user_id"`
	Keyword    string `json:"keyword"`
	StartTime  string `json:"start_time"`
	EndTime    string `json:"end_time"`
}

// AuditLogSearchOutput 审计日志检索出参
type AuditLogSearchOutput struct {
	List  []AuditLogItem `json:"list"`
	Total int            `json:"total"`
	Page  int            `json:"page"`
	Size  int            `json:"size"`
}

// AuditLogRecordInput 记录审计日志入参
type AuditLogRecordInput struct {
	UserId          int64
	UserName        string
	ClientIp        string
	UserAgent       string
	RequestId       string
	EventType       string
	TargetType      string
	TargetId        string
	TargetName      string
	OldValue        interface{} // 自动转 JSON 字符串
	NewValue        interface{} // 自动转 JSON 字符串
	OperationResult int         // 1成功 0失败
	ErrorMessage    string
}
