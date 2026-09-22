package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Marketing = cMarketing{}

type cMarketing struct{}

// ================= Ad Positions =================

func (c *cMarketing) SearchPositions(ctx context.Context, req *v1.AdPositionSearchReq) (res *v1.AdPositionSearchRes, err error) {
	out, err := service.Marketing().SearchPositions(ctx, model.AdPositionSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Keyword:  req.Keyword,
		Status:   req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.AdPositionSearchRes{
		List:  out.List,
		Total: out.Total,
		Page:  out.Page,
		Size:  out.Size,
	}, nil
}

func (c *cMarketing) ShowPosition(ctx context.Context, req *v1.AdPositionShowReq) (res *v1.AdPositionShowRes, err error) {
	item, err := service.Marketing().GetPositionById(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.AdPositionShowRes{AdPositionItem: item}, nil
}

func (c *cMarketing) CreatePosition(ctx context.Context, req *v1.AdPositionCreateReq) (res *v1.AdPositionCreateRes, err error) {
	id, err := service.Marketing().CreatePosition(ctx, model.AdPositionCreateInput{
		Name:        req.Name,
		Code:        req.Code,
		Width:       req.Width,
		Height:      req.Height,
		AdType:      req.AdType,
		MaxCount:    req.MaxCount,
		Description: req.Description,
		Status:      req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.AdPositionCreateRes{Id: id}, nil
}

func (c *cMarketing) UpdatePosition(ctx context.Context, req *v1.AdPositionUpdateReq) (res *v1.AdPositionUpdateRes, err error) {
	err = service.Marketing().UpdatePosition(ctx, model.AdPositionUpdateInput{
		Id:          req.Id,
		Name:        req.Name,
		Code:        req.Code,
		Width:       req.Width,
		Height:      req.Height,
		AdType:      req.AdType,
		MaxCount:    req.MaxCount,
		Description: req.Description,
		Status:      req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.AdPositionUpdateRes{}, nil
}

func (c *cMarketing) DeletePositions(ctx context.Context, req *v1.AdPositionDeleteReq) (res *v1.AdPositionDeleteRes, err error) {
	err = service.Marketing().DeletePositions(ctx, req.Ids)
	if err != nil {
		return nil, err
	}
	return &v1.AdPositionDeleteRes{}, nil
}

// ================= Ads =================

func (c *cMarketing) SearchAds(ctx context.Context, req *v1.AdSearchReq) (res *v1.AdSearchRes, err error) {
	out, err := service.Marketing().SearchAds(ctx, model.AdSearchInput{
		Page:       req.Page,
		PageSize:   req.PageSize,
		PositionId: req.PositionId,
		Keyword:    req.Keyword,
		Status:     req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.AdSearchRes{
		List:  out.List,
		Total: out.Total,
		Page:  out.Page,
		Size:  out.Size,
	}, nil
}

func (c *cMarketing) ShowAd(ctx context.Context, req *v1.AdShowReq) (res *v1.AdShowRes, err error) {
	item, err := service.Marketing().GetAdById(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.AdShowRes{AdItem: item}, nil
}

func (c *cMarketing) CreateAd(ctx context.Context, req *v1.AdCreateReq) (res *v1.AdCreateRes, err error) {
	id, err := service.Marketing().CreateAd(ctx, model.AdCreateInput{
		PositionId:   req.PositionId,
		Title:        req.Title,
		AdType:       req.AdType,
		CoverImage:   req.CoverImage,
		Content:      req.Content,
		LinkUrl:      req.LinkUrl,
		LinkTarget:   req.LinkTarget,
		Sort:         req.Sort,
		StartTime:    req.StartTime,
		EndTime:      req.EndTime,
		DisplayLimit: req.DisplayLimit,
		ClickLimit:   req.ClickLimit,
		Status:       req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.AdCreateRes{Id: id}, nil
}

func (c *cMarketing) UpdateAd(ctx context.Context, req *v1.AdUpdateReq) (res *v1.AdUpdateRes, err error) {
	err = service.Marketing().UpdateAd(ctx, model.AdUpdateInput{
		Id:           req.Id,
		PositionId:   req.PositionId,
		Title:        req.Title,
		AdType:       req.AdType,
		CoverImage:   req.CoverImage,
		Content:      req.Content,
		LinkUrl:      req.LinkUrl,
		LinkTarget:   req.LinkTarget,
		Sort:         req.Sort,
		StartTime:    req.StartTime,
		EndTime:      req.EndTime,
		DisplayLimit: req.DisplayLimit,
		ClickLimit:   req.ClickLimit,
		Status:       req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.AdUpdateRes{}, nil
}

func (c *cMarketing) DeleteAds(ctx context.Context, req *v1.AdDeleteReq) (res *v1.AdDeleteRes, err error) {
	err = service.Marketing().DeleteAds(ctx, req.Ids)
	if err != nil {
		return nil, err
	}
	return &v1.AdDeleteRes{}, nil
}

// ================= Friend Links =================

func (c *cMarketing) SearchFriendLinks(ctx context.Context, req *v1.FriendLinkSearchReq) (res *v1.FriendLinkSearchRes, err error) {
	out, err := service.Marketing().SearchFriendLinks(ctx, model.FriendLinkSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Category: req.Category,
		Keyword:  req.Keyword,
		Status:   req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.FriendLinkSearchRes{
		List:  out.List,
		Total: out.Total,
		Page:  out.Page,
		Size:  out.Size,
	}, nil
}

func (c *cMarketing) ShowFriendLink(ctx context.Context, req *v1.FriendLinkShowReq) (res *v1.FriendLinkShowRes, err error) {
	item, err := service.Marketing().GetFriendLinkById(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.FriendLinkShowRes{FriendLinkItem: item}, nil
}

func (c *cMarketing) CreateFriendLink(ctx context.Context, req *v1.FriendLinkCreateReq) (res *v1.FriendLinkCreateRes, err error) {
	id, err := service.Marketing().CreateFriendLink(ctx, model.FriendLinkCreateInput{
		Category:     req.Category,
		SiteName:     req.SiteName,
		SiteUrl:      req.SiteUrl,
		LogoUrl:      req.LogoUrl,
		Description:  req.Description,
		ContactEmail: req.ContactEmail,
		Sort:         req.Sort,
		Status:       req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.FriendLinkCreateRes{Id: id}, nil
}

func (c *cMarketing) UpdateFriendLink(ctx context.Context, req *v1.FriendLinkUpdateReq) (res *v1.FriendLinkUpdateRes, err error) {
	err = service.Marketing().UpdateFriendLink(ctx, model.FriendLinkUpdateInput{
		Id:           req.Id,
		Category:     req.Category,
		SiteName:     req.SiteName,
		SiteUrl:      req.SiteUrl,
		LogoUrl:      req.LogoUrl,
		Description:  req.Description,
		ContactEmail: req.ContactEmail,
		Sort:         req.Sort,
		Status:       req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.FriendLinkUpdateRes{}, nil
}

func (c *cMarketing) DeleteFriendLinks(ctx context.Context, req *v1.FriendLinkDeleteReq) (res *v1.FriendLinkDeleteRes, err error) {
	err = service.Marketing().DeleteFriendLinks(ctx, req.Ids)
	if err != nil {
		return nil, err
	}
	return &v1.FriendLinkDeleteRes{}, nil
}

// ================= Short Links =================

func (c *cMarketing) SearchShortLinks(ctx context.Context, req *v1.ShortLinkSearchReq) (res *v1.ShortLinkSearchRes, err error) {
	out, err := service.Marketing().SearchShortLinks(ctx, model.ShortLinkSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Keyword:  req.Keyword,
		Status:   req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ShortLinkSearchRes{
		List:  out.List,
		Total: out.Total,
		Page:  out.Page,
		Size:  out.Size,
	}, nil
}

func (c *cMarketing) ShowShortLink(ctx context.Context, req *v1.ShortLinkShowReq) (res *v1.ShortLinkShowRes, err error) {
	item, err := service.Marketing().GetShortLinkById(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.ShortLinkShowRes{ShortLinkItem: item}, nil
}

func (c *cMarketing) CreateShortLink(ctx context.Context, req *v1.ShortLinkCreateReq) (res *v1.ShortLinkCreateRes, err error) {
	id, err := service.Marketing().CreateShortLink(ctx, model.ShortLinkCreateInput{
		ShortCode:  req.ShortCode,
		TargetUrl:  req.TargetUrl,
		Title:      req.Title,
		QrCodePath: req.QrCodePath,
		ExpireAt:   req.ExpireAt,
		Status:     req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ShortLinkCreateRes{Id: id}, nil
}

func (c *cMarketing) UpdateShortLink(ctx context.Context, req *v1.ShortLinkUpdateReq) (res *v1.ShortLinkUpdateRes, err error) {
	err = service.Marketing().UpdateShortLink(ctx, model.ShortLinkUpdateInput{
		Id:         req.Id,
		ShortCode:  req.ShortCode,
		TargetUrl:  req.TargetUrl,
		Title:      req.Title,
		QrCodePath: req.QrCodePath,
		ExpireAt:   req.ExpireAt,
		Status:     req.Status,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ShortLinkUpdateRes{}, nil
}

func (c *cMarketing) DeleteShortLinks(ctx context.Context, req *v1.ShortLinkDeleteReq) (res *v1.ShortLinkDeleteRes, err error) {
	err = service.Marketing().DeleteShortLinks(ctx, req.Ids)
	if err != nil {
		return nil, err
	}
	return &v1.ShortLinkDeleteRes{}, nil
}

// ================= Content Push Queue =================

func (c *cMarketing) SearchPushQueue(ctx context.Context, req *v1.ContentPushQueueSearchReq) (res *v1.ContentPushQueueSearchRes, err error) {
	out, err := service.Marketing().SearchPushQueue(ctx, model.ContentPushQueueSearchInput{
		Page:      req.Page,
		PageSize:  req.PageSize,
		PushType:  req.PushType,
		Status:    req.Status,
		ContentId: req.ContentId,
	})
	if err != nil {
		return nil, err
	}
	return &v1.ContentPushQueueSearchRes{
		List:  out.List,
		Total: out.Total,
		Page:  out.Page,
		Size:  out.Size,
	}, nil
}

func (c *cMarketing) RetryPush(ctx context.Context, req *v1.ContentPushQueueRetryReq) (res *v1.ContentPushQueueRetryRes, err error) {
	err = service.Marketing().RetryPush(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.ContentPushQueueRetryRes{}, nil
}

func (c *cMarketing) DeletePushQueue(ctx context.Context, req *v1.ContentPushQueueDeleteReq) (res *v1.ContentPushQueueDeleteRes, err error) {
	err = service.Marketing().DeletePushQueue(ctx, req.Ids)
	if err != nil {
		return nil, err
	}
	return &v1.ContentPushQueueDeleteRes{}, nil
}

// ================= Daily Statistics =================

func (c *cMarketing) SearchStatisticsDaily(ctx context.Context, req *v1.StatisticsDailySearchReq) (res *v1.StatisticsDailySearchRes, err error) {
	out, err := service.Statistics().Search(ctx, model.StatisticsDailySearchInput{
		StartDate: req.StartDate,
		EndDate:   req.EndDate,
		Limit:     req.Limit,
	})
	if err != nil {
		return nil, err
	}
	return &v1.StatisticsDailySearchRes{List: out.List}, nil
}

func (c *cMarketing) AggregateStatisticsDaily(ctx context.Context, req *v1.StatisticsDailyAggregateReq) (res *v1.StatisticsDailyAggregateRes, err error) {
	out, err := service.Statistics().Aggregate(ctx, model.StatisticsDailyAggregateInput{Date: req.Date})
	if err != nil {
		return nil, err
	}
	return &v1.StatisticsDailyAggregateRes{StatisticsDailyAggregateOutput: out}, nil
}
