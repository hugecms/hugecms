package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

type CommentSearchReq struct {
	g.Meta    `path:"/comments" method:"get" tags:"评论管理" summary:"分页查询评论列表"`
	Page      int    `json:"page" in:"query" d:"1"`
	PageSize  int    `json:"page_size" in:"query" d:"10"`
	ContentId int64  `json:"content_id" in:"query"`
	Status    string `json:"status" in:"query"`
	Keyword   string `json:"keyword" in:"query"`
}

type CommentSearchRes struct {
	*model.CommentSearchOutput
}

type CommentAuditReq struct {
	g.Meta `path:"/comments/audit" method:"post" tags:"评论管理" summary:"批量审核评论"`
	*model.CommentAuditInput
}

type CommentAuditRes struct{}

type CommentDeleteReq struct {
	g.Meta `path:"/comments/{id}" method:"delete" tags:"评论管理" summary:"删除指定评论"`
	Id     int64 `json:"id" in:"path" v:"required#评论ID不能为空"`
}

type CommentDeleteRes struct{}
