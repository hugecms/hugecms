package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

// ==================== 菜单 NavMenus ====================

// NavMenuSearchReq 菜单列表查询
type NavMenuSearchReq struct {
	g.Meta   `path:"/appearance/menus" method:"get" tags:"外观设置" summary:"分页查询导航菜单集"`
	Page     int    `json:"page" in:"query" d:"1" dc:"页码"`
	PageSize int    `json:"page_size" in:"query" d:"10" dc:"每页条数"`
	Keyword  string `json:"keyword" in:"query" dc:"搜索关键词"`
}

type NavMenuSearchRes struct {
	*model.NavMenuSearchOutput
}

// NavMenuGetReq 菜单详情
type NavMenuGetReq struct {
	g.Meta `path:"/appearance/menus/{id}" method:"get" tags:"外观设置" summary:"获取菜单详情"`
	Id     int64 `json:"id" in:"path" v:"required#菜单ID不能为空"`
}

type NavMenuGetRes struct {
	*model.NavMenuItem
}

// NavMenuCreateReq 创建菜单
type NavMenuCreateReq struct {
	g.Meta      `path:"/appearance/menus" method:"post" tags:"外观设置" summary:"创建导航菜单集"`
	Name        string `json:"name" v:"required|length:2,50#请输入菜单名称|名称长度为2-50位"`
	Alias       string `json:"alias" v:"required|regex:^[a-z0-9_]+$#请输入菜单标识|标识仅支持小写字母数字下划线"`
	Description string `json:"description"`
}

type NavMenuCreateRes struct {
	Id int64 `json:"id" dc:"新菜单ID"`
}

// NavMenuUpdateReq 更新菜单
type NavMenuUpdateReq struct {
	g.Meta      `path:"/appearance/menus/{id}" method:"put" tags:"外观设置" summary:"更新导航菜单集"`
	Id          int64  `json:"id" in:"path" v:"required#菜单ID不能为空"`
	Name        string `json:"name" v:"required|length:2,50#请输入菜单名称|名称长度为2-50位"`
	Alias       string `json:"alias" v:"required|regex:^[a-z0-9_]+$#请输入菜单标识|标识仅支持小写字母数字下划线"`
	Description string `json:"description"`
}

type NavMenuUpdateRes struct{}

// NavMenuDeleteReq 删除菜单
type NavMenuDeleteReq struct {
	g.Meta `path:"/appearance/menus/{id}" method:"delete" tags:"外观设置" summary:"删除导航菜单集"`
	Id     int64 `json:"id" in:"path" v:"required#菜单ID不能为空"`
}

type NavMenuDeleteRes struct{}

// ==================== 菜单条目 NavItems ====================

// NavItemsTreeReq 获取指定菜单的条目树
type NavItemsTreeReq struct {
	g.Meta `path:"/appearance/menus/{menu_id}/items" method:"get" tags:"外观设置" summary:"获取指定菜单的树状条目"`
	MenuId int64 `json:"menu_id" in:"path" v:"required#菜单ID不能为空"`
}

type NavItemsTreeRes struct {
	Tree []model.NavItemTreeNode `json:"tree"`
}

// NavItemSaveReq 保存菜单条目（新增或修改）
type NavItemSaveReq struct {
	g.Meta    `path:"/appearance/menu-items" method:"post" tags:"外观设置" summary:"新增或保存菜单条目"`
	Id        int64  `json:"id" dc:"条目ID，为空则新增"`
	MenuId    int64  `json:"menu_id" v:"required#所属菜单不能为空"`
	ParentId  int64  `json:"parent_id" d:"0"`
	Title     string `json:"title" v:"required#显示标题不能为空"`
	LinkType  string `json:"link_type" d:"custom"`
	LinkValue string `json:"link_value" v:"required#链接目标值不能为空"`
	OpenType  int    `json:"open_type" d:"0"`
	Icon      string `json:"icon"`
	IsActive  int    `json:"is_active" d:"1"`
	Sort      int    `json:"sort" d:"0"`
}

type NavItemSaveRes struct {
	Id int64 `json:"id" dc:"条目ID"`
}

// NavItemDeleteReq 删除菜单条目
type NavItemDeleteReq struct {
	g.Meta `path:"/appearance/menu-items/{id}" method:"delete" tags:"外观设置" summary:"删除菜单条目"`
	Id     int64 `json:"id" in:"path" v:"required#条目ID不能为空"`
}

type NavItemDeleteRes struct{}

// ==================== 页面区块 Blocks ====================

type BlockSearchReq struct {
	g.Meta    `path:"/appearance/blocks" method:"get" tags:"外观设置" summary:"分页查询区块列表"`
	Page      int    `json:"page" in:"query" d:"1"`
	PageSize  int    `json:"page_size" in:"query" d:"10"`
	Keyword   string `json:"keyword" in:"query"`
	BlockType string `json:"block_type" in:"query"`
	Status    *int   `json:"status" in:"query"`
}

type BlockSearchRes struct {
	*model.BlockSearchOutput
}

type BlockGetReq struct {
	g.Meta `path:"/appearance/blocks/{id}" method:"get" tags:"外观设置" summary:"获取区块详情"`
	Id     int64 `json:"id" in:"path" v:"required#区块ID不能为空"`
}

type BlockGetRes struct {
	*model.BlockItem
}

type BlockSaveReq struct {
	g.Meta    `path:"/appearance/blocks" method:"post" tags:"外观设置" summary:"保存区块（新增或更新）"`
	Id        int64  `json:"id"`
	BlockName string `json:"block_name" v:"required|length:2,100#请输入区块名称|名称长度为2-100位"`
	BlockType string `json:"block_type" v:"required#区块类型不能为空"`
	Content   string `json:"content"`
	Css       string `json:"css"`
	Js        string `json:"js"`
	IsGlobal  int    `json:"is_global" d:"0"`
	Status    int    `json:"status" d:"1"`
}

type BlockSaveRes struct {
	Id int64 `json:"id"`
}

type BlockDeleteReq struct {
	g.Meta `path:"/appearance/blocks/{id}" method:"delete" tags:"外观设置" summary:"删除区块"`
	Id     int64 `json:"id" in:"path" v:"required#区块ID不能为空"`
}

type BlockDeleteRes struct{}

// ==================== 页面模板 PageTemplates ====================

type PageTemplateSearchReq struct {
	g.Meta   `path:"/appearance/templates" method:"get" tags:"外观设置" summary:"分页查询页面模板"`
	Page     int    `json:"page" in:"query" d:"1"`
	PageSize int    `json:"page_size" in:"query" d:"10"`
	Keyword  string `json:"keyword" in:"query"`
	Category string `json:"category" in:"query"`
	Status   *int   `json:"status" in:"query"`
}

type PageTemplateSearchRes struct {
	*model.PageTemplateSearchOutput
}

type PageTemplateGetReq struct {
	g.Meta `path:"/appearance/templates/{id}" method:"get" tags:"外观设置" summary:"获取页面模板详情"`
	Id     int64 `json:"id" in:"path" v:"required#模板ID不能为空"`
}

type PageTemplateGetRes struct {
	*model.PageTemplateItem
}

type PageTemplateSaveReq struct {
	g.Meta       `path:"/appearance/templates" method:"post" tags:"外观设置" summary:"保存页面模板（新增或更新）"`
	Id           int64  `json:"id"`
	TemplateName string `json:"template_name" v:"required|length:2,100#请输入模板名称|模板名称长度为2-100位"`
	TemplateCode string `json:"template_code" v:"required|regex:^[a-zA-Z0-9_-]+$#请输入模板代码|模板代码仅支持英文字母数字下划线减号"`
	Category     string `json:"category" v:"required#类别不能为空"`
	PreviewImage string `json:"preview_image"`
	Content      string `json:"content"`
	IsDefault    int    `json:"is_default" d:"0"`
	Status       int    `json:"status" d:"1"`
}

type PageTemplateSaveRes struct {
	Id int64 `json:"id"`
}

type PageTemplateDeleteReq struct {
	g.Meta `path:"/appearance/templates/{id}" method:"delete" tags:"外观设置" summary:"删除页面模板"`
	Id     int64 `json:"id" in:"path" v:"required#模板ID不能为空"`
}

type PageTemplateDeleteRes struct{}
