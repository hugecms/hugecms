// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// AuditLogsDao is the data access object for the table audit_logs.
type AuditLogsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  AuditLogsColumns   // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// AuditLogsColumns defines and stores column names for the table audit_logs.
type AuditLogsColumns struct {
	Id              string //
	UserId          string // 操作用户ID
	UserName        string // 操作用户名（冗余，防用户被删后无记录）
	ClientIp        string // 客户端IP（支持IPv6）
	UserAgent       string // 客户端UA信息
	RequestId       string // 请求追踪ID（关联一次请求的所有日志）
	EventType       string // 事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT
	TargetType      string // 目标类型：content/term/user/attachment/config/comment/form_submission
	TargetId        string // 目标ID（可能是数字或UUID）
	TargetName      string // 目标名称（冗余，便于展示）
	OldValue        string // 修改前的数据快照（JSON）
	NewValue        string // 修改后的数据快照（JSON）
	OperationResult string // 操作结果：0失败，1成功
	ErrorMessage    string // 失败时的错误信息
	CreatedAt       string // 创建时间（毫秒精度，只增不改）
}

// auditLogsColumns holds the columns for the table audit_logs.
var auditLogsColumns = AuditLogsColumns{
	Id:              "id",
	UserId:          "user_id",
	UserName:        "user_name",
	ClientIp:        "client_ip",
	UserAgent:       "user_agent",
	RequestId:       "request_id",
	EventType:       "event_type",
	TargetType:      "target_type",
	TargetId:        "target_id",
	TargetName:      "target_name",
	OldValue:        "old_value",
	NewValue:        "new_value",
	OperationResult: "operation_result",
	ErrorMessage:    "error_message",
	CreatedAt:       "created_at",
}

// NewAuditLogsDao creates and returns a new DAO object for table data access.
func NewAuditLogsDao(handlers ...gdb.ModelHandler) *AuditLogsDao {
	return &AuditLogsDao{
		group:    "default",
		table:    "audit_logs",
		columns:  auditLogsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *AuditLogsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *AuditLogsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *AuditLogsDao) Columns() AuditLogsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *AuditLogsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *AuditLogsDao) Ctx(ctx context.Context) *gdb.Model {
	model := dao.DB().Model(dao.table)
	for _, handler := range dao.handlers {
		model = handler(model)
	}
	return model.Safe().Ctx(ctx)
}

// Transaction wraps the transaction logic using function f.
// It rolls back the transaction and returns the error if function f returns a non-nil error.
// It commits the transaction and returns nil if function f returns nil.
//
// Note: Do not commit or roll back the transaction in function f,
// as it is automatically handled by this function.
func (dao *AuditLogsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
