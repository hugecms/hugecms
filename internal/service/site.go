// ================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// You can delete these comments if you wish manually maintain this interface file.
// ================================================================================

package service

import (
	"context"
	"hugecms/internal/model"
)

type (
	ISite interface {
		// Search 分页查询站点
		Search(ctx context.Context, in model.SiteSearchInput) (*model.SiteSearchOutput, error)
		// Get 获取站点详情
		Get(ctx context.Context, id int64) (*model.SiteItem, error)
		// GetByDomain 根据域名查找匹配站点
		GetByDomain(ctx context.Context, domain string) (*model.SiteItem, error)
		// Create 创建新站点
		Create(ctx context.Context, in model.SiteCreateInput) (int64, error)
		// Update 更新站点
		Update(ctx context.Context, in model.SiteUpdateInput) error
		// Delete 删除站点
		Delete(ctx context.Context, id int64) error
	}
)

var (
	localSite ISite
)

func Site() ISite {
	if localSite == nil {
		panic("implement not found for interface ISite, forgot register?")
	}
	return localSite
}

func RegisterSite(i ISite) {
	localSite = i
}
