package model

import "github.com/gogf/gf/v2/os/gtime"

// ModelFieldItem 模型字段项
type ModelFieldItem struct {
	Id              int64       `json:"id"`
	ModelId         int64       `json:"model_id"`
	FieldName       string      `json:"field_name"`
	ColumnName      string      `json:"column_name"`
	FieldLabel      string      `json:"field_label"`
	FieldType       string      `json:"field_type"`
	ColumnType      string      `json:"column_type"`
	DefaultValue    string      `json:"default_value"`
	IsRequired      int         `json:"is_required"`
	IsUnique        int         `json:"is_unique"`
	ValidationRules string      `json:"validation_rules"`
	ExtraConfig     string      `json:"extra_config"`
	SortOrder       int         `json:"sort_order"`
	CreatedAt       *gtime.Time `json:"created_at"`
	UpdatedAt       *gtime.Time `json:"updated_at"`
}

// ModelFieldListInput 查询模型字段列表入参
type ModelFieldListInput struct {
	ModelId int64 `json:"model_id" v:"required#模型ID不能为空"`
}

// ModelFieldCreateInput 新增模型字段入参
type ModelFieldCreateInput struct {
	ModelId         int64  `json:"model_id" v:"required#模型ID不能为空"`
	FieldName       string `json:"field_name" v:"required|regex:^[a-z][a-z0-9_]{0,49}$#请输入字段业务名称|字段业务名称必须以小写字母开头，支持小写字母数字下划线"`
	FieldLabel      string `json:"field_label" v:"required|length:1,100#请输入字段显示标签|标签长度1-100位"`
	FieldType       string `json:"field_type" v:"required#字段类型不能为空"`
	ColumnType      string `json:"column_type"`
	DefaultValue    string `json:"default_value"`
	IsRequired      int    `json:"is_required" d:"0"`
	IsUnique        int    `json:"is_unique" d:"0"`
	ValidationRules string `json:"validation_rules"`
	ExtraConfig     string `json:"extra_config"`
	SortOrder       int    `json:"sort_order" d:"0"`
}

// ModelFieldUpdateInput 更新模型字段入参
type ModelFieldUpdateInput struct {
	Id              int64  `json:"id" v:"required#字段ID不能为空"`
	FieldLabel      string `json:"field_label" v:"required|length:1,100#请输入字段显示标签|标签长度1-100位"`
	DefaultValue    string `json:"default_value"`
	IsRequired      int    `json:"is_required"`
	ValidationRules string `json:"validation_rules"`
	ExtraConfig     string `json:"extra_config"`
	SortOrder       int    `json:"sort_order"`
}
