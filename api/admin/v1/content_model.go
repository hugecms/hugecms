package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

type ContentModelSearchReq struct {
	g.Meta   `path:"/content-models" method:"get" tags:"模型管理" summary:"分页查询内容模型列表"`
	Page     int    `json:"page" in:"query" d:"1"`
	PageSize int    `json:"page_size" in:"query" d:"10"`
	Keyword  string `json:"keyword" in:"query"`
	Status   *int   `json:"status" in:"query"`
}

type ContentModelSearchRes struct {
	*model.ContentModelSearchOutput
}

type ContentModelGetReq struct {
	g.Meta `path:"/content-models/{id}" method:"get" tags:"模型管理" summary:"获取内容模型详情"`
	Id     int64 `json:"id" in:"path" v:"required#模型ID不能为空"`
}

type ContentModelGetRes struct {
	*model.ContentModelItem
}

type ContentModelCreateReq struct {
	g.Meta        `path:"/content-models" method:"post" tags:"模型管理" summary:"创建内容模型"`
	Name          string `json:"name" v:"required|length:2,50#请输入模型名称|名称长度为2-50位"`
	Alias         string `json:"alias" v:"required|regex:^[a-z][a-z0-9_]{0,39}$#请输入模型别名|别名仅支持小写字母开头、小写字母数字下划线组合，长度1-40位"`
	Description   string `json:"description"`
	IsCommentable int    `json:"is_commentable" d:"1"`
	Status        int    `json:"status" d:"1"`
	Sort          int    `json:"sort" d:"0"`
}

type ContentModelCreateRes struct {
	Id int64 `json:"id"`
}

type ContentModelUpdateReq struct {
	g.Meta        `path:"/content-models/{id}" method:"put" tags:"模型管理" summary:"更新内容模型"`
	Id            int64  `json:"id" in:"path" v:"required#模型ID不能为空"`
	Name          string `json:"name" v:"required|length:2,50#请输入模型名称|名称长度为2-50位"`
	Description   string `json:"description"`
	IsCommentable int    `json:"is_commentable"`
	Status        int    `json:"status"`
	Sort          int    `json:"sort"`
}

type ContentModelUpdateRes struct{}

type ContentModelDeleteReq struct {
	g.Meta `path:"/content-models/{id}" method:"delete" tags:"模型管理" summary:"删除内容模型"`
	Id     int64 `json:"id" in:"path" v:"required#模型ID不能为空"`
}

type ContentModelDeleteRes struct{}
