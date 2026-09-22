// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// ModelFields is the golang structure of table model_fields for DAO operations like Where/Data.
type ModelFields struct {
	g.Meta          `orm:"table:model_fields, do:true"`
	Id              any         //
	ModelId         any         // 所属模型ID
	FieldName       any         // 字段业务英文名（如：salary）
	ColumnName      any         // 物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）
	FieldLabel      any         // 字段显示标签（如：薪资范围）
	FieldType       any         // 字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json
	ColumnType      any         // 数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json
	DefaultValue    any         // 默认值
	IsRequired      any         // 是否必填：0否，1是
	IsUnique        any         // 值是否唯一：0否，1是
	ValidationRules any         // 校验规则（JSON），如：{"max":100,"regex":"^[A-Z]"}
	ExtraConfig     any         // 额外配置（如select选项：{"options":["男","女"]}）
	SortOrder       any         // 表单显示排序
	CreatedAt       *gtime.Time //
	UpdatedAt       *gtime.Time //
}
