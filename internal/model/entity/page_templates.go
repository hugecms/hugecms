// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// PageTemplates is the golang structure for table page_templates.
type PageTemplates struct {
	Id           uint64      `json:"id"           orm:"id"            ` //
	TemplateName string      `json:"templateName" orm:"template_name" ` // 模板名称
	TemplateCode string      `json:"templateCode" orm:"template_code" ` // 模板代码（唯一标识）
	Category     string      `json:"category"     orm:"category"      ` // 类别：page页面/post文章/term分类模板
	PreviewImage string      `json:"previewImage" orm:"preview_image" ` // 预览图URL
	Content      string      `json:"content"      orm:"content"       ` // 模板内容（HTML/JSON结构）
	IsDefault    uint        `json:"isDefault"    orm:"is_default"    ` // 是否默认模板
	IsSystem     uint        `json:"isSystem"     orm:"is_system"     ` // 是否系统内置
	Status       uint        `json:"status"       orm:"status"        ` // 状态：0停用，1启用
	CreatedAt    *gtime.Time `json:"createdAt"    orm:"created_at"    ` //
	UpdatedAt    *gtime.Time `json:"updatedAt"    orm:"updated_at"    ` //
}
