package model

import "github.com/gogf/gf/v2/os/gtime"

// OptionItem 配置项
type OptionItem struct {
	Id          int64       `json:"id"`
	OptionKey   string      `json:"option_key"`
	OptionValue string      `json:"option_value"`
	Autoload    int         `json:"autoload"`
	CreatedAt   *gtime.Time `json:"created_at"`
	UpdatedAt   *gtime.Time `json:"updated_at"`
}

// OptionSearchInput 查询配置入参
type OptionSearchInput struct {
	Page      int    `json:"page"`
	PageSize  int    `json:"page_size"`
	OptionKey string `json:"option_key"`
}

// OptionSearchOutput 查询配置出参
type OptionSearchOutput struct {
	List  []OptionItem `json:"list"`
	Total int          `json:"total"`
	Page  int          `json:"page"`
	Size  int          `json:"size"`
}

// OptionSaveInput 保存单个配置入参
type OptionSaveInput struct {
	OptionKey   string `json:"option_key" v:"required#配置键名不能为空"`
	OptionValue string `json:"option_value"`
	Autoload    int    `json:"autoload"`
}

// OptionBatchSaveInput 批量保存配置入参
type OptionBatchSaveInput struct {
	Options map[string]string `json:"options" v:"required#配置键值对不能为空"`
}
