// ================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// You can delete these comments if you wish manually maintain this interface file.
// ================================================================================

package service

import (
	"context"
	"hugecms/internal/model"
)

type (
	IAuditLog interface {
		// Search 分页查询审计日志
		Search(ctx context.Context, in model.AuditLogSearchInput) (*model.AuditLogSearchOutput, error)
		// Get 获取审计日志详情
		Get(ctx context.Context, id int64) (*model.AuditLogItem, error)
		// Record 异步记录审计日志（不阻塞主业务流程）
		Record(ctx context.Context, in model.AuditLogRecordInput)
	}
)

var (
	localAuditLog IAuditLog
)

func AuditLog() IAuditLog {
	if localAuditLog == nil {
		panic("implement not found for interface IAuditLog, forgot register?")
	}
	return localAuditLog
}

func RegisterAuditLog(i IAuditLog) {
	localAuditLog = i
}
