package model

import "github.com/gogf/gf/v2/os/gtime"

// ContentListItem 内容列表项
type ContentListItem struct {
	Id           int64       `json:"id"`
	ModelId      int64       `json:"model_id"`
	ModelName    string      `json:"model_name"`
	Title        string      `json:"title"`
	Slug         string      `json:"slug"`
	AuthorId     int64       `json:"author_id"`
	AuthorName   string      `json:"author_name"`
	Status       string      `json:"status"`
	Visibility   string      `json:"visibility"`
	Views        int64       `json:"views"`
	CommentCount int         `json:"comment_count"`
	Sort         int         `json:"sort"`
	IsTop        int         `json:"is_top"`
	PublishedAt  *gtime.Time `json:"published_at"`
	AuditStatus  string      `json:"audit_status"`
	AuditRemark  string      `json:"audit_remark"`
	AuditorId    int64       `json:"auditor_id"`
	AuditedAt    *gtime.Time `json:"audited_at"`
	TermNames    []string    `json:"term_names"`
	CreatedAt    *gtime.Time `json:"created_at"`
	UpdatedAt    *gtime.Time `json:"updated_at"`
}

// ContentSearchInput 内容检索入参
type ContentSearchInput struct {
	Page        int    `json:"page"`
	PageSize    int    `json:"page_size"`
	ModelId     int64  `json:"model_id"`
	Keyword     string `json:"keyword"`
	Status      string `json:"status"`       // draft/pending/published/archived/trash
	AuditStatus string `json:"audit_status"` // pending/approved/rejected
	Visibility  string `json:"visibility"`   // public/password/private
	TermId      int64  `json:"term_id"`
	AuthorId    int64  `json:"author_id"`
	IsTop       *int   `json:"is_top"`
}

// ContentSearchOutput 内容检索出参
type ContentSearchOutput struct {
	List  []ContentListItem `json:"list"`
	Total int               `json:"total"`
	Page  int               `json:"page"`
	Size  int               `json:"size"`
}

// ContentDetailOutput 内容完整详情输出
type ContentDetailOutput struct {
	Id           int64                  `json:"id"`
	ModelId      int64                  `json:"model_id"`
	ModelAlias   string                 `json:"model_alias"`
	Title        string                 `json:"title"`
	Slug         string                 `json:"slug"`
	AuthorId     int64                  `json:"author_id"`
	AuthorName   string                 `json:"author_name"`
	Status       string                 `json:"status"`
	Visibility   string                 `json:"visibility"`
	Password     string                 `json:"password,omitempty"`
	Views        int64                  `json:"views"`
	CommentCount int                    `json:"comment_count"`
	Sort         int                    `json:"sort"`
	IsTop        int                    `json:"is_top"`
	PublishedAt  *gtime.Time            `json:"published_at"`
	AuditStatus  string                 `json:"audit_status"`
	AuditRemark  string                 `json:"audit_remark"`
	Fields       map[string]interface{} `json:"fields"` // 动态表 data_{alias} 里的所有业务字段值
	TermIds      []int64                `json:"term_ids"`
	SeoMeta      *SeoMetaItem           `json:"seo_meta,omitempty"`
	CreatedAt    *gtime.Time            `json:"created_at"`
	UpdatedAt    *gtime.Time            `json:"updated_at"`
}

// ContentSaveInput 新建/更新内容主业务入参
type ContentSaveInput struct {
	Id          int64                  `json:"id"`
	ModelId     int64                  `json:"model_id" v:"required#所属内容模型不能为空"`
	Title       string                 `json:"title" v:"required|length:1,200#请输入内容标题|标题长度限1-200字"`
	Slug        string                 `json:"slug"`
	Status      string                 `json:"status" d:"draft"`
	Visibility  string                 `json:"visibility" d:"public"`
	Password    string                 `json:"password"`
	Sort        int                    `json:"sort" d:"0"`
	IsTop       int                    `json:"is_top" d:"0"`
	PublishedAt *gtime.Time            `json:"published_at"`
	Fields      map[string]interface{} `json:"fields"`   // 动态模型数据键值对 (field_name -> value)
	TermIds     []int64                `json:"term_ids"` // 分类/标签关联 ID 列表
	SeoMeta     *SeoMetaSaveInput      `json:"seo_meta"` // SEO配置
}

// ContentAuditInput 审核处理入参
type ContentAuditInput struct {
	Id          int64  `json:"id" v:"required#内容ID不能为空"`
	AuditStatus string `json:"audit_status" v:"required|in:approved,rejected#审核状态不合法"`
	AuditRemark string `json:"audit_remark"`
}

// ContentStatusInput 变更状态入参
type ContentStatusInput struct {
	Id     int64  `json:"id" v:"required#内容ID不能为空"`
	Status string `json:"status" v:"required|in:draft,pending,published,archived,trash#状态不合法"`
}
