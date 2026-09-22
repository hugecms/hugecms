// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Taxonomies is the golang structure for table taxonomies.
type Taxonomies struct {
	Id             uint64      `json:"id"             orm:"id"              ` //
	Name           string      `json:"name"           orm:"name"            ` // 分类法名称（如：文章分类、产品系列）
	Alias          string      `json:"alias"          orm:"alias"           ` // 分类法别名（如：article_cat）
	ModelId        uint64      `json:"modelId"        orm:"model_id"        ` // 绑定的模型ID（NULL表示全局分类）
	IsHierarchical uint        `json:"isHierarchical" orm:"is_hierarchical" ` // 是否支持层级：1是（分类目录），0否（标签）
	Description    string      `json:"description"    orm:"description"     ` // 描述
	CreatedAt      *gtime.Time `json:"createdAt"      orm:"created_at"      ` //
	UpdatedAt      *gtime.Time `json:"updatedAt"      orm:"updated_at"      ` //
}
