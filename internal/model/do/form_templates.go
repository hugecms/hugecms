// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// FormTemplates is the golang structure of table form_templates for DAO operations like Where/Data.
type FormTemplates struct {
	g.Meta         `orm:"table:form_templates, do:true"`
	Id             any         //
	Name           any         // 表单名称（如：在线报名表）
	Alias          any         // 表单标识（用于代码调用）
	FieldsConfig   any         // 字段配置（JSON数组）：字段名、类型、校验规则、选项等
	SubmitCount    any         // 提交次数统计
	IsActive       any         // 是否启用：1是，0否
	SuccessMessage any         // 提交成功提示语
	CreatedAt      *gtime.Time //
	UpdatedAt      *gtime.Time //
}
