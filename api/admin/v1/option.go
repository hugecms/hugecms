package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

// OptionSearchReq 查询配置列表
type OptionSearchReq struct {
	g.Meta    `path:"/options" method:"get" tags:"配置管理" summary:"查询配置列表"`
	Page      int    `json:"page" in:"query" d:"1" dc:"页码"`
	PageSize  int    `json:"page_size" in:"query" d:"20" dc:"每页条数"`
	OptionKey string `json:"option_key" in:"query" dc:"配置键名模糊搜索"`
}

// OptionSearchRes 查询配置响应
type OptionSearchRes struct {
	*model.OptionSearchOutput
}

// OptionGetReq 获取指定配置键值
type OptionGetReq struct {
	g.Meta `path:"/options/{key}" method:"get" tags:"配置管理" summary:"获取指定配置项"`
	Key    string `json:"key" in:"path" v:"required#配置键名不能为空" dc:"配置键名"`
}

// OptionGetRes 获取指定配置键值响应
type OptionGetRes struct {
	Key   string `json:"key" dc:"配置键名"`
	Value string `json:"value" dc:"配置值"`
}

// OptionSaveReq 保存配置项
type OptionSaveReq struct {
	g.Meta      `path:"/options" method:"post" tags:"配置管理" summary:"保存单项配置"`
	OptionKey   string `json:"option_key" v:"required#配置键名不能为空" dc:"配置键名"`
	OptionValue string `json:"option_value" dc:"配置值"`
	Autoload    int    `json:"autoload" d:"1" dc:"是否自动加载：1是 0否"`
}

// OptionSaveRes 保存配置项响应
type OptionSaveRes struct{}

// OptionBatchSaveReq 批量保存配置
type OptionBatchSaveReq struct {
	g.Meta  `path:"/options/batch" method:"post" tags:"配置管理" summary:"批量保存配置"`
	Options map[string]string `json:"options" v:"required#配置集合不能为空" dc:"配置键值对映射"`
}

// OptionBatchSaveRes 批量保存配置响应
type OptionBatchSaveRes struct{}

// OptionDeleteReq 删除配置
type OptionDeleteReq struct {
	g.Meta `path:"/options/{key}" method:"delete" tags:"配置管理" summary:"删除配置项"`
	Key    string `json:"key" in:"path" v:"required#配置键名不能为空" dc:"配置键名"`
}

// OptionDeleteRes 删除配置响应
type OptionDeleteRes struct{}
