// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// Options is the golang structure for table options.
type Options struct {
	Id          uint64      `json:"id"          orm:"id"           ` //
	OptionKey   string      `json:"optionKey"   orm:"option_key"   ` // 配置键名（storage_config/smtp_config/comment_config等）
	OptionValue string      `json:"optionValue" orm:"option_value" ` // 配置值（支持JSON复杂结构）
	Autoload    uint        `json:"autoload"    orm:"autoload"     ` // 启动时自动加载：0否，1是（配合 Laravel Cache 预热）
	CreatedAt   *gtime.Time `json:"createdAt"   orm:"created_at"   ` //
	UpdatedAt   *gtime.Time `json:"updatedAt"   orm:"updated_at"   ` //
}
