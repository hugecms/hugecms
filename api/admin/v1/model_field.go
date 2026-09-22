package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

type ModelFieldListReq struct {
	g.Meta  `path:"/content-models/{model_id}/fields" method:"get" tags:"模型字段" summary:"获取指定模型的全部字段"`
	ModelId int64 `json:"model_id" in:"path" v:"required#模型ID不能为空"`
}

type ModelFieldListRes struct {
	List []model.ModelFieldItem `json:"list"`
}

type ModelFieldGetReq struct {
	g.Meta `path:"/model-fields/{id}" method:"get" tags:"模型字段" summary:"获取字段详情"`
	Id     int64 `json:"id" in:"path" v:"required#字段ID不能为空"`
}

type ModelFieldGetRes struct {
	*model.ModelFieldItem
}

type ModelFieldCreateReq struct {
	g.Meta          `path:"/model-fields" method:"post" tags:"模型字段" summary:"新增字段并执行物理表DDL"`
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

type ModelFieldCreateRes struct {
	Id int64 `json:"id"`
}

type ModelFieldUpdateReq struct {
	g.Meta          `path:"/model-fields/{id}" method:"put" tags:"模型字段" summary:"更新模型字段元数据"`
	Id              int64  `json:"id" in:"path" v:"required#字段ID不能为空"`
	FieldLabel      string `json:"field_label" v:"required|length:1,100#请输入字段显示标签|标签长度1-100位"`
	DefaultValue    string `json:"default_value"`
	IsRequired      int    `json:"is_required"`
	ValidationRules string `json:"validation_rules"`
	ExtraConfig     string `json:"extra_config"`
	SortOrder       int    `json:"sort_order"`
}

type ModelFieldUpdateRes struct{}

type ModelFieldDeleteReq struct {
	g.Meta `path:"/model-fields/{id}" method:"delete" tags:"模型字段" summary:"删除模型字段并从物理表移除该列"`
	Id     int64 `json:"id" in:"path" v:"required#字段ID不能为空"`
}

type ModelFieldDeleteRes struct{}
