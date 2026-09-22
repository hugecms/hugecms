// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// FormTemplates is the golang structure for table form_templates.
type FormTemplates struct {
	Id             uint64      `json:"id"             orm:"id"              ` //
	Name           string      `json:"name"           orm:"name"            ` // 表单名称（如：在线报名表）
	Alias          string      `json:"alias"          orm:"alias"           ` // 表单标识（用于代码调用）
	FieldsConfig   string      `json:"fieldsConfig"   orm:"fields_config"   ` // 字段配置（JSON数组）：字段名、类型、校验规则、选项等
	SubmitCount    uint        `json:"submitCount"    orm:"submit_count"    ` // 提交次数统计
	IsActive       uint        `json:"isActive"       orm:"is_active"       ` // 是否启用：1是，0否
	SuccessMessage string      `json:"successMessage" orm:"success_message" ` // 提交成功提示语
	CreatedAt      *gtime.Time `json:"createdAt"      orm:"created_at"      ` //
	UpdatedAt      *gtime.Time `json:"updatedAt"      orm:"updated_at"      ` //
}
