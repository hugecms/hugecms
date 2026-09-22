package audit_log

import (
	"context"

	"github.com/gogf/gf/v2/encoding/gjson"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gctx"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sAuditLog struct{}

func init() {
	service.RegisterAuditLog(New())
}

func New() service.IAuditLog {
	return &sAuditLog{}
}

// Search 分页查询审计日志
func (s *sAuditLog) Search(ctx context.Context, in model.AuditLogSearchInput) (*model.AuditLogSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 20
	}

	m := dao.AuditLogs.Ctx(ctx)
	if in.EventType != "" {
		m = m.Where("event_type", in.EventType)
	}
	if in.TargetType != "" {
		m = m.Where("target_type", in.TargetType)
	}
	if in.UserId > 0 {
		m = m.Where("user_id", in.UserId)
	}
	if in.Keyword != "" {
		m = m.Where("user_name LIKE ? OR target_name LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}
	if in.StartTime != "" {
		m = m.WhereGTE("created_at", in.StartTime)
	}
	if in.EndTime != "" {
		m = m.WhereLTE("created_at", in.EndTime)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var logs []entity.AuditLogs
	if err := m.Page(in.Page, in.PageSize).OrderDesc("id").Scan(&logs); err != nil {
		return nil, err
	}

	list := make([]model.AuditLogItem, 0, len(logs))
	for _, l := range logs {
		list = append(list, model.AuditLogItem{
			Id:              int64(l.Id),
			UserId:          int64(l.UserId),
			UserName:        l.UserName,
			ClientIp:        l.ClientIp,
			UserAgent:       l.UserAgent,
			RequestId:       l.RequestId,
			EventType:       l.EventType,
			TargetType:      l.TargetType,
			TargetId:        l.TargetId,
			TargetName:      l.TargetName,
			OldValue:        l.OldValue,
			NewValue:        l.NewValue,
			OperationResult: int(l.OperationResult),
			ErrorMessage:    l.ErrorMessage,
			CreatedAt:       l.CreatedAt,
		})
	}

	return &model.AuditLogSearchOutput{
		List:  list,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// Get 获取审计日志详情
func (s *sAuditLog) Get(ctx context.Context, id int64) (*model.AuditLogItem, error) {
	var l entity.AuditLogs
	if err := dao.AuditLogs.Ctx(ctx).WherePri(id).Scan(&l); err != nil {
		return nil, err
	}
	if l.Id == 0 {
		return nil, gerror.New("审计日志不存在")
	}

	return &model.AuditLogItem{
		Id:              int64(l.Id),
		UserId:          int64(l.UserId),
		UserName:        l.UserName,
		ClientIp:        l.ClientIp,
		UserAgent:       l.UserAgent,
		RequestId:       l.RequestId,
		EventType:       l.EventType,
		TargetType:      l.TargetType,
		TargetId:        l.TargetId,
		TargetName:      l.TargetName,
		OldValue:        l.OldValue,
		NewValue:        l.NewValue,
		OperationResult: int(l.OperationResult),
		ErrorMessage:    l.ErrorMessage,
		CreatedAt:       l.CreatedAt,
	}, nil
}

// Record 异步记录审计日志（不阻塞主业务流程）
func (s *sAuditLog) Record(ctx context.Context, in model.AuditLogRecordInput) {
	var (
		oldJson string
		newJson string
	)
	if in.OldValue != nil {
		if s, ok := in.OldValue.(string); ok {
			oldJson = s
		} else {
			oldJson = gjson.MustEncodeString(in.OldValue)
		}
	}
	if in.NewValue != nil {
		if s, ok := in.NewValue.(string); ok {
			newJson = s
		} else {
			newJson = gjson.MustEncodeString(in.NewValue)
		}
	}

	// 异步写入日志
	go func(input model.AuditLogRecordInput, oldV, newV string) {
		bgCtx := gctx.NeverDone(context.Background())
		data := g.Map{
			"user_id":          input.UserId,
			"user_name":        input.UserName,
			"client_ip":        input.ClientIp,
			"user_agent":       input.UserAgent,
			"request_id":       input.RequestId,
			"event_type":       input.EventType,
			"target_type":      input.TargetType,
			"target_id":        input.TargetId,
			"target_name":      input.TargetName,
			"operation_result": input.OperationResult,
			"error_message":    input.ErrorMessage,
			"created_at":       gtime.Now(),
		}
		if oldV != "" {
			data["old_value"] = oldV
		} else {
			data["old_value"] = nil
		}
		if newV != "" {
			data["new_value"] = newV
		} else {
			data["new_value"] = nil
		}

		_, err := dao.AuditLogs.Ctx(bgCtx).Data(data).Insert()
		if err != nil {
			g.Log().Errorf(bgCtx, "写入审计日志失败: %v", err)
		}
	}(in, oldJson, newJson)
}
