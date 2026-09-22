package model

import "github.com/gogf/gf/v2/os/gtime"

// RecycleBinSnapshot 完整回收站快照数据结构（符合 docs/development-conventions.md 约定）
type RecycleBinSnapshot struct {
	Content   map[string]interface{}   `json:"content"`              // contents 主表行
	Data      map[string]interface{}   `json:"data"`                 // 模型物理数据表行 data_{alias}
	Relations RecycleBinSnapshotRel    `json:"relations"`            // 级联关联全量
}

type RecycleBinSnapshotRel struct {
	Comments            []map[string]interface{} `json:"comments"`
	TermRelationships   []map[string]interface{} `json:"term_relationships"`
	AttachmentRelations []map[string]interface{} `json:"attachment_relations"`
	SeoMeta             map[string]interface{}   `json:"seo_meta,omitempty"`
}

// RecycleBinItem 回收站列表条目
type RecycleBinItem struct {
	Id            int64       `json:"id"`
	DeletedBy     int64       `json:"deleted_by"`
	DeletedByName string      `json:"deleted_by_name"`
	TargetType    string      `json:"target_type"`
	TargetId      string      `json:"target_id"`
	TargetTitle   string      `json:"target_title"`
	RetentionDays int         `json:"retention_days"`
	CreatedAt     *gtime.Time `json:"created_at"`
	ExpireAt      *gtime.Time `json:"expire_at"`
}

// RecycleBinSearchInput 回收站分页检索入参
type RecycleBinSearchInput struct {
	Page       int    `json:"page"`
	PageSize   int    `json:"page_size"`
	TargetType string `json:"target_type"`
	Keyword    string `json:"keyword"`
}

// RecycleBinSearchOutput 回收站分页检索出参
type RecycleBinSearchOutput struct {
	List  []RecycleBinItem `json:"list"`
	Total int              `json:"total"`
	Page  int              `json:"page"`
	Size  int              `json:"size"`
}
