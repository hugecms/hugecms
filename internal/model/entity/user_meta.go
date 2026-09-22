// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// UserMeta is the golang structure for table user_meta.
type UserMeta struct {
	Id        uint64      `json:"id"        orm:"id"         ` //
	UserId    uint64      `json:"userId"    orm:"user_id"    ` // 关联用户ID
	MetaKey   string      `json:"metaKey"   orm:"meta_key"   ` // 元数据键名
	MetaValue string      `json:"metaValue" orm:"meta_value" ` // 元数据值（JSON或序列化数据）
	CreatedAt *gtime.Time `json:"createdAt" orm:"created_at" ` //
	UpdatedAt *gtime.Time `json:"updatedAt" orm:"updated_at" ` //
}
