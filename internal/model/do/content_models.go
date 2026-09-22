// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// ContentModels is the golang structure of table content_models for DAO operations like Where/Data.
type ContentModels struct {
	g.Meta        `orm:"table:content_models, do:true"`
	Id            any         //
	Name          any         // 模型名称（显示用，如：招聘信息）
	Alias         any         // 模型别名（代码/URL用，如：recruitment）
	TableName     any         // 对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）
	Description   any         // 模型描述
	IsSystem      any         // 是否系统内置：1是（不可删除），0否
	IsCommentable any         // 是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）
	Status        any         // 状态：0停用，1启用
	Sort          any         // 排序权重
	CreatedAt     *gtime.Time //
	UpdatedAt     *gtime.Time //
}
