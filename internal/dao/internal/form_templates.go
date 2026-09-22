// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// FormTemplatesDao is the data access object for the table form_templates.
type FormTemplatesDao struct {
	table    string               // table is the underlying table name of the DAO.
	group    string               // group is the database configuration group name of the current DAO.
	columns  FormTemplatesColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler   // handlers for customized model modification.
}

// FormTemplatesColumns defines and stores column names for the table form_templates.
type FormTemplatesColumns struct {
	Id             string //
	Name           string // 表单名称（如：在线报名表）
	Alias          string // 表单标识（用于代码调用）
	FieldsConfig   string // 字段配置（JSON数组）：字段名、类型、校验规则、选项等
	SubmitCount    string // 提交次数统计
	IsActive       string // 是否启用：1是，0否
	SuccessMessage string // 提交成功提示语
	CreatedAt      string //
	UpdatedAt      string //
}

// formTemplatesColumns holds the columns for the table form_templates.
var formTemplatesColumns = FormTemplatesColumns{
	Id:             "id",
	Name:           "name",
	Alias:          "alias",
	FieldsConfig:   "fields_config",
	SubmitCount:    "submit_count",
	IsActive:       "is_active",
	SuccessMessage: "success_message",
	CreatedAt:      "created_at",
	UpdatedAt:      "updated_at",
}

// NewFormTemplatesDao creates and returns a new DAO object for table data access.
func NewFormTemplatesDao(handlers ...gdb.ModelHandler) *FormTemplatesDao {
	return &FormTemplatesDao{
		group:    "default",
		table:    "form_templates",
		columns:  formTemplatesColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *FormTemplatesDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *FormTemplatesDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *FormTemplatesDao) Columns() FormTemplatesColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *FormTemplatesDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *FormTemplatesDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *FormTemplatesDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
