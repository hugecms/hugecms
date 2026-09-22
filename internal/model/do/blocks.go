// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Blocks is the golang structure of table blocks for DAO operations like Where/Data.
type Blocks struct {
	g.Meta    `orm:"table:blocks, do:true"`
	Id        any         //
	BlockName any         // 区块名称
	BlockType any         // 区块类型：header/footer/banner/content/sidebar/custom
	Content   any         // 区块内容（HTML/JSON）
	Css       any         // 自定义CSS样式
	Js        any         // 自定义JS脚本
	IsGlobal  any         // 是否全局区块（全站复用）：1是，0否
	Status    any         // 状态：0停用，1启用
	CreatedAt *gtime.Time //
	UpdatedAt *gtime.Time //
}
