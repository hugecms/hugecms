// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// ModelFieldsDao is the data access object for the table model_fields.
type ModelFieldsDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  ModelFieldsColumns // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// ModelFieldsColumns defines and stores column names for the table model_fields.
type ModelFieldsColumns struct {
	Id              string //
	ModelId         string // 所属模型ID
	FieldName       string // 字段业务英文名（如：salary）
	ColumnName      string // 物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）
	FieldLabel      string // 字段显示标签（如：薪资范围）
	FieldType       string // 字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json
	ColumnType      string // 数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json
	DefaultValue    string // 默认值
	IsRequired      string // 是否必填：0否，1是
	IsUnique        string // 值是否唯一：0否，1是
	ValidationRules string // 校验规则（JSON），如：{"max":100,"regex":"^[A-Z]"}
	ExtraConfig     string // 额外配置（如select选项：{"options":["男","女"]}）
	SortOrder       string // 表单显示排序
	CreatedAt       string //
	UpdatedAt       string //
}

// modelFieldsColumns holds the columns for the table model_fields.
var modelFieldsColumns = ModelFieldsColumns{
	Id:              "id",
	ModelId:         "model_id",
	FieldName:       "field_name",
	ColumnName:      "column_name",
	FieldLabel:      "field_label",
	FieldType:       "field_type",
	ColumnType:      "column_type",
	DefaultValue:    "default_value",
	IsRequired:      "is_required",
	IsUnique:        "is_unique",
	ValidationRules: "validation_rules",
	ExtraConfig:     "extra_config",
	SortOrder:       "sort_order",
	CreatedAt:       "created_at",
	UpdatedAt:       "updated_at",
}

// NewModelFieldsDao creates and returns a new DAO object for table data access.
func NewModelFieldsDao(handlers ...gdb.ModelHandler) *ModelFieldsDao {
	return &ModelFieldsDao{
		group:    "default",
		table:    "model_fields",
		columns:  modelFieldsColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *ModelFieldsDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *ModelFieldsDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *ModelFieldsDao) Columns() ModelFieldsColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *ModelFieldsDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *ModelFieldsDao) Ctx(ctx context.Context) *gdb.Model {
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
func (dao *ModelFieldsDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
