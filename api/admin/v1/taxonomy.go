package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

// ==================== 分类法 Taxonomies ====================

type TaxonomySearchReq struct {
	g.Meta   `path:"/taxonomies" method:"get" tags:"分类体系" summary:"分页查询分类法"`
	Page     int    `json:"page" in:"query" d:"1"`
	PageSize int    `json:"page_size" in:"query" d:"10"`
	ModelId  *int64 `json:"model_id" in:"query"`
	Keyword  string `json:"keyword" in:"query"`
}

type TaxonomySearchRes struct {
	*model.TaxonomySearchOutput
}

type TaxonomyGetReq struct {
	g.Meta `path:"/taxonomies/{id}" method:"get" tags:"分类体系" summary:"获取分类法详情"`
	Id     int64 `json:"id" in:"path" v:"required#分类法ID不能为空"`
}

type TaxonomyGetRes struct {
	*model.TaxonomyItem
}

type TaxonomySaveReq struct {
	g.Meta         `path:"/taxonomies" method:"post" tags:"分类体系" summary:"保存分类法（新增或更新）"`
	Id             int64  `json:"id"`
	Name           string `json:"name" v:"required|length:2,50#请输入分类法名称|名称长度2-50位"`
	Alias          string `json:"alias" v:"required|regex:^[a-z0-9_]+$#请输入分类法别名|别名仅支持小写字母数字下划线"`
	ModelId        int64  `json:"model_id"`
	IsHierarchical int    `json:"is_hierarchical" d:"1"`
	Description    string `json:"description"`
}

type TaxonomySaveRes struct {
	Id int64 `json:"id"`
}

type TaxonomyDeleteReq struct {
	g.Meta `path:"/taxonomies/{id}" method:"delete" tags:"分类体系" summary:"删除分类法"`
	Id     int64 `json:"id" in:"path" v:"required#分类法ID不能为空"`
}

type TaxonomyDeleteRes struct{}

// ==================== 词条项 Terms ====================

type TermTreeReq struct {
	g.Meta      `path:"/terms/tree" method:"get" tags:"分类体系" summary:"获取分类词条树状结构"`
	TaxonomyId int64  `json:"taxonomy_id" in:"query" v:"required#分类法ID不能为空"`
	Keyword    string `json:"keyword" in:"query"`
}

type TermTreeRes struct {
	Tree []model.TermTreeNode `json:"tree"`
}

type TermGetReq struct {
	g.Meta `path:"/terms/{id}" method:"get" tags:"分类体系" summary:"获取分类词条详情"`
	Id     int64 `json:"id" in:"path" v:"required#词条ID不能为空"`
}

type TermGetRes struct {
	*model.TermItem
}

type TermSaveReq struct {
	g.Meta      `path:"/terms" method:"post" tags:"分类体系" summary:"保存分类词条（新增或更新）"`
	Id          int64  `json:"id"`
	TaxonomyId  int64  `json:"taxonomy_id" v:"required#分类法ID不能为空"`
	Name        string `json:"name" v:"required|length:1,50#请输入分类名称|名称长度1-50位"`
	Slug        string `json:"slug" v:"required#别名不能为空"`
	ParentId    int64  `json:"parent_id" d:"0"`
	Description string `json:"description"`
	Sort        int    `json:"sort" d:"0"`
}

type TermSaveRes struct {
	Id int64 `json:"id"`
}

type TermDeleteReq struct {
	g.Meta `path:"/terms/{id}" method:"delete" tags:"分类体系" summary:"删除分类词条"`
	Id     int64 `json:"id" in:"path" v:"required#词条ID不能为空"`
}

type TermDeleteRes struct{}
