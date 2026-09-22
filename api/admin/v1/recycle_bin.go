package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

type RecycleBinSearchReq struct {
	g.Meta     `path:"/recycle-bin" method:"get" tags:"回收站" summary:"分页查询回收站列表"`
	Page       int    `json:"page" in:"query" d:"1"`
	PageSize   int    `json:"page_size" in:"query" d:"10"`
	TargetType string `json:"target_type" in:"query"`
	Keyword    string `json:"keyword" in:"query"`
}

type RecycleBinSearchRes struct {
	*model.RecycleBinSearchOutput
}

type RecycleBinRestoreReq struct {
	g.Meta `path:"/recycle-bin/{id}/restore" method:"post" tags:"回收站" summary:"从回收站恢复内容"`
	Id     int64 `json:"id" in:"path" v:"required#回收站记录ID不能为空"`
}

type RecycleBinRestoreRes struct {
	RestoredId int64 `json:"restored_id"`
}

type RecycleBinPurgeReq struct {
	g.Meta `path:"/recycle-bin/{id}" method:"delete" tags:"回收站" summary:"彻底物理清除内容"`
	Id     int64 `json:"id" in:"path" v:"required#回收站记录ID不能为空"`
}

type RecycleBinPurgeRes struct{}
