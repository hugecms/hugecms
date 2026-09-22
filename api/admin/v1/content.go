package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

type ContentSearchReq struct {
	g.Meta      `path:"/contents" method:"get" tags:"内容管理" summary:"多维分页检索内容列表"`
	Page        int    `json:"page" in:"query" d:"1"`
	PageSize    int    `json:"page_size" in:"query" d:"10"`
	ModelId     int64  `json:"model_id" in:"query"`
	Keyword     string `json:"keyword" in:"query"`
	Status      string `json:"status" in:"query"`       // draft/pending/published/archived/trash
	AuditStatus string `json:"audit_status" in:"query"` // pending/approved/rejected
	Visibility  string `json:"visibility" in:"query"`   // public/password/private
	TermId      int64  `json:"term_id" in:"query"`
	AuthorId    int64  `json:"author_id" in:"query"`
	IsTop       *int   `json:"is_top" in:"query"`
}

type ContentSearchRes struct {
	*model.ContentSearchOutput
}

type ContentGetReq struct {
	g.Meta `path:"/contents/{id}" method:"get" tags:"内容管理" summary:"获取内容聚合详情"`
	Id     int64 `json:"id" in:"path" v:"required#内容ID不能为空"`
}

type ContentGetRes struct {
	*model.ContentDetailOutput
}

type ContentSaveReq struct {
	g.Meta `path:"/contents" method:"post" tags:"内容管理" summary:"新建或更新内容"`
	*model.ContentSaveInput
}

type ContentSaveRes struct {
	Id int64 `json:"id"`
}

type ContentAuditReq struct {
	g.Meta `path:"/contents/audit" method:"post" tags:"内容管理" summary:"审核内容流转"`
	*model.ContentAuditInput
}

type ContentAuditRes struct{}

type ContentStatusReq struct {
	g.Meta `path:"/contents/status" method:"post" tags:"内容管理" summary:"快速变更内容状态"`
	*model.ContentStatusInput
}

type ContentStatusRes struct{}

type ContentTrashReq struct {
	g.Meta `path:"/contents/{id}" method:"delete" tags:"内容管理" summary:"删除内容入回收站"`
	Id     int64 `json:"id" in:"path" v:"required#内容ID不能为空"`
}

type ContentTrashRes struct{}
