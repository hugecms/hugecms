// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// ModelFields is the golang structure for table model_fields.
type ModelFields struct {
	Id              uint64      `json:"id"              orm:"id"               ` //
	ModelId         uint64      `json:"modelId"         orm:"model_id"         ` // 所属模型ID
	FieldName       string      `json:"fieldName"       orm:"field_name"       ` // 字段业务英文名（如：salary）
	ColumnName      string      `json:"columnName"      orm:"column_name"      ` // 物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）
	FieldLabel      string      `json:"fieldLabel"      orm:"field_label"      ` // 字段显示标签（如：薪资范围）
	FieldType       string      `json:"fieldType"       orm:"field_type"       ` // 字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json
	ColumnType      string      `json:"columnType"      orm:"column_type"      ` // 数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json
	DefaultValue    string      `json:"defaultValue"    orm:"default_value"    ` // 默认值
	IsRequired      uint        `json:"isRequired"      orm:"is_required"      ` // 是否必填：0否，1是
	IsUnique        uint        `json:"isUnique"        orm:"is_unique"        ` // 值是否唯一：0否，1是
	ValidationRules string      `json:"validationRules" orm:"validation_rules" ` // 校验规则（JSON），如：{"max":100,"regex":"^[A-Z]"}
	ExtraConfig     string      `json:"extraConfig"     orm:"extra_config"     ` // 额外配置（如select选项：{"options":["男","女"]}）
	SortOrder       int         `json:"sortOrder"       orm:"sort_order"       ` // 表单显示排序
	CreatedAt       *gtime.Time `json:"createdAt"       orm:"created_at"       ` //
	UpdatedAt       *gtime.Time `json:"updatedAt"       orm:"updated_at"       ` //
}
