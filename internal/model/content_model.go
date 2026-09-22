package model

import "github.com/gogf/gf/v2/os/gtime"

// ContentModelItem 内容模型信息项
type ContentModelItem struct {
	Id            int64       `json:"id"`
	Name          string      `json:"name"`
	Alias         string      `json:"alias"`
	TableName     string      `json:"table_name"`
	Description   string      `json:"description"`
	IsSystem      int         `json:"is_system"`
	IsCommentable int         `json:"is_commentable"`
	Status        int         `json:"status"`
	Sort          int         `json:"sort"`
	CreatedAt     *gtime.Time `json:"created_at"`
	UpdatedAt     *gtime.Time `json:"updated_at"`
}

// ContentModelSearchInput 模型列表查询入参
type ContentModelSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"page_size"`
	Keyword  string `json:"keyword"`
	Status   *int   `json:"status"`
}

// ContentModelSearchOutput 模型列表查询出参
type ContentModelSearchOutput struct {
	List  []ContentModelItem `json:"list"`
	Total int                `json:"total"`
	Page  int                `json:"page"`
	Size  int                `json:"size"`
}

// ContentModelCreateInput 创建模型入参
type ContentModelCreateInput struct {
	Name          string `json:"name" v:"required|length:2,50#请输入模型名称|名称长度为2-50位"`
	Alias         string `json:"alias" v:"required|regex:^[a-z][a-z0-9_]{0,39}$#请输入模型别名|别名仅支持小写字母开头、小写字母数字下划线组合，长度1-40位"`
	Description   string `json:"description"`
	IsCommentable int    `json:"is_commentable" d:"1"`
	Status        int    `json:"status" d:"1"`
	Sort          int    `json:"sort" d:"0"`
}

// ContentModelUpdateInput 更新模型入参
type ContentModelUpdateInput struct {
	Id            int64  `json:"id" v:"required#模型ID不能为空"`
	Name          string `json:"name" v:"required|length:2,50#请输入模型名称|名称长度为2-50位"`
	Description   string `json:"description"`
	IsCommentable int    `json:"is_commentable"`
	Status        int    `json:"status"`
	Sort          int    `json:"sort"`
}
