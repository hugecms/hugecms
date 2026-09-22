package model

import "github.com/gogf/gf/v2/os/gtime"

// SeoMetaItem SEO元数据条目
type SeoMetaItem struct {
	Id           int64       `json:"id"`
	TargetType   string      `json:"target_type"`
	TargetId     int64       `json:"target_id"`
	Title        string      `json:"title"`
	Keywords     string      `json:"keywords"`
	Description  string      `json:"description"`
	CanonicalUrl string      `json:"canonical_url"`
	Robots       string      `json:"robots"`
	CreatedAt    *gtime.Time `json:"created_at"`
	UpdatedAt    *gtime.Time `json:"updated_at"`
}

// SeoMetaSaveInput 保存SEO元数据入参
type SeoMetaSaveInput struct {
	TargetType   string `json:"target_type" v:"required#目标类型不能为空"`
	TargetId     int64  `json:"target_id" v:"required#目标ID不能为空"`
	Title        string `json:"title"`
	Keywords     string `json:"keywords"`
	Description  string `json:"description"`
	CanonicalUrl string `json:"canonical_url"`
	Robots       string `json:"robots"`
}
