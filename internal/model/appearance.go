package model

import "github.com/gogf/gf/v2/os/gtime"

// ==================== 导航菜单 ====================

type NavMenuItem struct {
	Id          int64       `json:"id"`
	Name        string      `json:"name"`
	Alias       string      `json:"alias"`
	Description string      `json:"description"`
	CreatedAt   *gtime.Time `json:"created_at"`
	UpdatedAt   *gtime.Time `json:"updated_at"`
}

type NavMenuSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"page_size"`
	Keyword  string `json:"keyword"`
}

type NavMenuSearchOutput struct {
	List  []NavMenuItem `json:"list"`
	Total int           `json:"total"`
	Page  int           `json:"page"`
	Size  int           `json:"size"`
}

type NavMenuCreateInput struct {
	Name        string `json:"name" v:"required|length:2,50#请输入菜单名称|名称长度为2-50位"`
	Alias       string `json:"alias" v:"required|regex:^[a-z0-9_]+$#请输入菜单标识|标识仅支持小写字母数字下划线"`
	Description string `json:"description"`
}

type NavMenuUpdateInput struct {
	Id          int64  `json:"id" v:"required#菜单ID不能为空"`
	Name        string `json:"name" v:"required|length:2,50#请输入菜单名称|名称长度为2-50位"`
	Alias       string `json:"alias" v:"required|regex:^[a-z0-9_]+$#请输入菜单标识|标识仅支持小写字母数字下划线"`
	Description string `json:"description"`
}

// NavItemTreeNode 导航条目树节点
type NavItemTreeNode struct {
	Id        int64             `json:"id"`
	MenuId    int64             `json:"menu_id"`
	ParentId  int64             `json:"parent_id"`
	Title     string            `json:"title"`
	LinkType  string            `json:"link_type"`
	LinkValue string            `json:"link_value"`
	OpenType  int               `json:"open_type"`
	Icon      string            `json:"icon"`
	IsActive  int               `json:"is_active"`
	Sort      int               `json:"sort"`
	Children  []NavItemTreeNode `json:"children,omitempty"`
	CreatedAt *gtime.Time       `json:"created_at"`
}

type NavItemSaveInput struct {
	Id        int64  `json:"id"`
	MenuId    int64  `json:"menu_id" v:"required#所属菜单不能为空"`
	ParentId  int64  `json:"parent_id"`
	Title     string `json:"title" v:"required#显示标题不能为空"`
	LinkType  string `json:"link_type" d:"custom"`
	LinkValue string `json:"link_value" v:"required#链接目标值不能为空"`
	OpenType  int    `json:"open_type"`
	Icon      string `json:"icon"`
	IsActive  int    `json:"is_active" d:"1"`
	Sort      int    `json:"sort"`
}

// ==================== 页面区块 Blocks ====================

type BlockItem struct {
	Id        int64       `json:"id"`
	BlockName string      `json:"block_name"`
	BlockType string      `json:"block_type"`
	Content   string      `json:"content"`
	Css       string      `json:"css"`
	Js        string      `json:"js"`
	IsGlobal  int         `json:"is_global"`
	Status    int         `json:"status"`
	CreatedAt *gtime.Time `json:"created_at"`
	UpdatedAt *gtime.Time `json:"updated_at"`
}

type BlockSearchInput struct {
	Page      int    `json:"page"`
	PageSize  int    `json:"page_size"`
	Keyword   string `json:"keyword"`
	BlockType string `json:"block_type"`
	Status    *int   `json:"status"`
}

type BlockSearchOutput struct {
	List  []BlockItem `json:"list"`
	Total int         `json:"total"`
	Page  int         `json:"page"`
	Size  int         `json:"size"`
}

type BlockSaveInput struct {
	Id        int64  `json:"id"`
	BlockName string `json:"block_name" v:"required|length:2,100#请输入区块名称|名称长度为2-100位"`
	BlockType string `json:"block_type" v:"required#区块类型不能为空"`
	Content   string `json:"content"`
	Css       string `json:"css"`
	Js        string `json:"js"`
	IsGlobal  int    `json:"is_global"`
	Status    int    `json:"status" d:"1"`
}

// ==================== 页面模板 Page Templates ====================

type PageTemplateItem struct {
	Id           int64       `json:"id"`
	TemplateName string      `json:"template_name"`
	TemplateCode string      `json:"template_code"`
	Category     string      `json:"category"`
	PreviewImage string      `json:"preview_image"`
	Content      string      `json:"content"`
	IsDefault    int         `json:"is_default"`
	IsSystem     int         `json:"is_system"`
	Status       int         `json:"status"`
	CreatedAt    *gtime.Time `json:"created_at"`
	UpdatedAt    *gtime.Time `json:"updated_at"`
}

type PageTemplateSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"page_size"`
	Keyword  string `json:"keyword"`
	Category string `json:"category"`
	Status   *int   `json:"status"`
}

type PageTemplateSearchOutput struct {
	List  []PageTemplateItem `json:"list"`
	Total int                `json:"total"`
	Page  int                `json:"page"`
	Size  int                `json:"size"`
}

type PageTemplateSaveInput struct {
	Id           int64  `json:"id"`
	TemplateName string `json:"template_name" v:"required|length:2,100#请输入模板名称|模板名称长度为2-100位"`
	TemplateCode string `json:"template_code" v:"required|regex:^[a-zA-Z0-9_-]+$#请输入模板代码|模板代码仅支持英文字母数字下划线减号"`
	Category     string `json:"category" v:"required#类别不能为空"`
	PreviewImage string `json:"preview_image"`
	Content      string `json:"content"`
	IsDefault    int    `json:"is_default"`
	Status       int    `json:"status" d:"1"`
}
