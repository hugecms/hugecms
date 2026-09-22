// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Taxonomies is the golang structure of table taxonomies for DAO operations like Where/Data.
type Taxonomies struct {
	g.Meta         `orm:"table:taxonomies, do:true"`
	Id             any         //
	Name           any         // 分类法名称（如：文章分类、产品系列）
	Alias          any         // 分类法别名（如：article_cat）
	ModelId        any         // 绑定的模型ID（NULL表示全局分类）
	IsHierarchical any         // 是否支持层级：1是（分类目录），0否（标签）
	Description    any         // 描述
	CreatedAt      *gtime.Time //
	UpdatedAt      *gtime.Time //
}
