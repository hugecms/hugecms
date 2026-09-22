// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// ContentModels is the golang structure for table content_models.
type ContentModels struct {
	Id            uint64      `json:"id"            orm:"id"             ` //
	Name          string      `json:"name"          orm:"name"           ` // 模型名称（显示用，如：招聘信息）
	Alias         string      `json:"alias"         orm:"alias"          ` // 模型别名（代码/URL用，如：recruitment）
	TableName     string      `json:"tableName"     orm:"table_name"     ` // 对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）
	Description   string      `json:"description"   orm:"description"    ` // 模型描述
	IsSystem      uint        `json:"isSystem"      orm:"is_system"      ` // 是否系统内置：1是（不可删除），0否
	IsCommentable uint        `json:"isCommentable" orm:"is_commentable" ` // 是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）
	Status        uint        `json:"status"        orm:"status"         ` // 状态：0停用，1启用
	Sort          int         `json:"sort"          orm:"sort"           ` // 排序权重
	CreatedAt     *gtime.Time `json:"createdAt"     orm:"created_at"     ` //
	UpdatedAt     *gtime.Time `json:"updatedAt"     orm:"updated_at"     ` //
}
