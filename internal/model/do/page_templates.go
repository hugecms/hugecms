// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// PageTemplates is the golang structure of table page_templates for DAO operations like Where/Data.
type PageTemplates struct {
	g.Meta       `orm:"table:page_templates, do:true"`
	Id           any         //
	TemplateName any         // 模板名称
	TemplateCode any         // 模板代码（唯一标识）
	Category     any         // 类别：page页面/post文章/term分类模板
	PreviewImage any         // 预览图URL
	Content      any         // 模板内容（HTML/JSON结构）
	IsDefault    any         // 是否默认模板
	IsSystem     any         // 是否系统内置
	Status       any         // 状态：0停用，1启用
	CreatedAt    *gtime.Time //
	UpdatedAt    *gtime.Time //
}
