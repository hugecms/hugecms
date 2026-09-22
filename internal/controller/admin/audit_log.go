package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var AuditLog = cAuditLog{}

type cAuditLog struct{}

// Search 审计日志分页查询
func (c *cAuditLog) Search(ctx context.Context, req *v1.AuditLogSearchReq) (res *v1.AuditLogSearchRes, err error) {
	out, err := service.AuditLog().Search(ctx, model.AuditLogSearchInput{
		Page:       req.Page,
		PageSize:   req.PageSize,
		EventType:  req.EventType,
		TargetType: req.TargetType,
		UserId:     req.UserId,
		Keyword:    req.Keyword,
		StartTime:  req.StartTime,
		EndTime:    req.EndTime,
	})
	if err != nil {
		return nil, err
	}
	return &v1.AuditLogSearchRes{AuditLogSearchOutput: out}, nil
}

// Get 获取审计日志详情
func (c *cAuditLog) Get(ctx context.Context, req *v1.AuditLogGetReq) (res *v1.AuditLogGetRes, err error) {
	out, err := service.AuditLog().Get(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.AuditLogGetRes{AuditLogItem: out}, nil
}
