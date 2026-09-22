// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Blocks is the golang structure for table blocks.
type Blocks struct {
	Id        uint64      `json:"id"        orm:"id"         ` //
	BlockName string      `json:"blockName" orm:"block_name" ` // 区块名称
	BlockType string      `json:"blockType" orm:"block_type" ` // 区块类型：header/footer/banner/content/sidebar/custom
	Content   string      `json:"content"   orm:"content"    ` // 区块内容（HTML/JSON）
	Css       string      `json:"css"       orm:"css"        ` // 自定义CSS样式
	Js        string      `json:"js"        orm:"js"         ` // 自定义JS脚本
	IsGlobal  uint        `json:"isGlobal"  orm:"is_global"  ` // 是否全局区块（全站复用）：1是，0否
	Status    uint        `json:"status"    orm:"status"     ` // 状态：0停用，1启用
	CreatedAt *gtime.Time `json:"createdAt" orm:"created_at" ` //
	UpdatedAt *gtime.Time `json:"updatedAt" orm:"updated_at" ` //
}
