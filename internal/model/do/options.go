// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// Options is the golang structure of table options for DAO operations like Where/Data.
type Options struct {
	g.Meta      `orm:"table:options, do:true"`
	Id          any         //
	OptionKey   any         // 配置键名（storage_config/smtp_config/comment_config等）
	OptionValue any         // 配置值（支持JSON复杂结构）
	Autoload    any         // 启动时自动加载：0否，1是（配合 Laravel Cache 预热）
	CreatedAt   *gtime.Time //
	UpdatedAt   *gtime.Time //
}
