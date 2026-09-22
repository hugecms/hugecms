package model

import "github.com/gogf/gf/v2/os/gtime"

// ==================== 分类法 Taxonomies ====================

type TaxonomyItem struct {
	Id             int64       `json:"id"`
	Name           string      `json:"name"`
	Alias          string      `json:"alias"`
	ModelId        int64       `json:"model_id"`
	IsHierarchical int         `json:"is_hierarchical"`
	Description    string      `json:"description"`
	CreatedAt      *gtime.Time `json:"created_at"`
	UpdatedAt      *gtime.Time `json:"updated_at"`
}

type TaxonomySearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"page_size"`
	ModelId  *int64 `json:"model_id"`
	Keyword  string `json:"keyword"`
}

type TaxonomySearchOutput struct {
	List  []TaxonomyItem `json:"list"`
	Total int            `json:"total"`
	Page  int            `json:"page"`
	Size  int            `json:"size"`
}

type TaxonomySaveInput struct {
	Id             int64  `json:"id"`
	Name           string `json:"name" v:"required|length:2,50#请输入分类法名称|名称长度2-50位"`
	Alias          string `json:"alias" v:"required|regex:^[a-z0-9_]+$#请输入分类法别名|别名仅支持小写字母数字下划线"`
	ModelId        int64  `json:"model_id"`
	IsHierarchical int    `json:"is_hierarchical" d:"1"`
	Description    string `json:"description"`
}

// ==================== 词条项 Terms ====================

type TermItem struct {
	Id           int64       `json:"id"`
	TaxonomyId   int64       `json:"taxonomy_id"`
	Name         string      `json:"name"`
	Slug         string      `json:"slug"`
	ParentId     int64       `json:"parent_id"`
	Description  string      `json:"description"`
	Sort         int         `json:"sort"`
	ContentCount int         `json:"content_count"`
	CreatedAt    *gtime.Time `json:"created_at"`
	UpdatedAt    *gtime.Time `json:"updated_at"`
}

type TermTreeNode struct {
	Id           int64          `json:"id"`
	TaxonomyId   int64          `json:"taxonomy_id"`
	Name         string         `json:"name"`
	Slug         string         `json:"slug"`
	ParentId     int64          `json:"parent_id"`
	Description  string         `json:"description"`
	Sort         int            `json:"sort"`
	ContentCount int            `json:"content_count"`
	Children     []TermTreeNode `json:"children,omitempty"`
	CreatedAt    *gtime.Time    `json:"created_at"`
}

type TermTreeInput struct {
	TaxonomyId int64  `json:"taxonomy_id" v:"required#分类法ID不能为空"`
	Keyword    string `json:"keyword"`
}

type TermSaveInput struct {
	Id          int64  `json:"id"`
	TaxonomyId  int64  `json:"taxonomy_id" v:"required#分类法ID不能为空"`
	Name        string `json:"name" v:"required|length:1,50#请输入分类名称|名称长度1-50位"`
	Slug        string `json:"slug" v:"required#别名不能为空"`
	ParentId    int64  `json:"parent_id" d:"0"`
	Description string `json:"description"`
	Sort        int    `json:"sort" d:"0"`
}
