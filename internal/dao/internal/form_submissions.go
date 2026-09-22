// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// FormSubmissionsDao is the data access object for the table form_submissions.
type FormSubmissionsDao struct {
	table    string                 // table is the underlying table name of the DAO.
	group    string                 // group is the database configuration group name of the current DAO.
	columns  FormSubmissionsColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler     // handlers for customized model modification.
}

// FormSubmissionsColumns defines and stores column names for the table form_submissions.
type FormSubmissionsColumns struct {
	Id             string //
	FormId         string // 关联表单模板ID
	SubmissionData string // 用户提交的具体表单数据（JSON）
	SubmitterIp    string // 提交者IP
	UserAgent      string // 提交者UA
	CreatedAt      string //
}

// formSubmissionsColumns holds the columns for the table form_submissions.
var formSubmissionsColumns = FormSubmissionsColumns{
	Id:             "id",
	FormId:         "form_id",
	SubmissionData: "submission_data",
	SubmitterIp:    "submitter_ip",
	UserAgent:      "user_agent",
	CreatedAt:      "created_at",
}

// NewFormSubmissionsDao creates and returns a new DAO object for table data access.
func NewFormSubmissionsDao(handlers ...gdb.ModelHandler) *FormSubmissionsDao {
	return &FormSubmissionsDao{
		group:    "default",
		table:    "form_submissions",
		columns:  formSubmissionsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *FormSubmissionsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *FormSubmissionsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *FormSubmissionsDao) Columns() FormSubmissionsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *FormSubmissionsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *FormSubmissionsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *FormSubmissionsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
