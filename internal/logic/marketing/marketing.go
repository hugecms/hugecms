package marketing

import (
	"context"
	"fmt"
	"math/rand"
	"time"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/do"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sMarketing struct{}

func init() {
	service.RegisterMarketing(New())
}

func New() service.IMarketing {
	return &sMarketing{}
}

// ================= Ad Positions =================

func (s *sMarketing) SearchPositions(ctx context.Context, in model.AdPositionSearchInput) (*model.AdPositionSearchOutput, error) {
	m := dao.AdPositions.Ctx(ctx)
	if in.Keyword != "" {
		m = m.WhereLike(dao.AdPositions.Columns().Name, "%"+in.Keyword+"%")
	}
	if in.Status != nil {
		m = m.Where(dao.AdPositions.Columns().Status, *in.Status)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	page := in.Page
	if page <= 0 {
		page = 1
	}
	size := in.PageSize
	if size <= 0 {
		size = 10
	}

	var items []entity.AdPositions
	if err = m.Page(page, size).OrderDesc(dao.AdPositions.Columns().Id).Scan(&items); err != nil {
		return nil, err
	}

	list := make([]model.AdPositionItem, len(items))
	for i, item := range items {
		list[i] = model.AdPositionItem{
			Id:          item.Id,
			Name:        item.Name,
			Code:        item.Code,
			Width:       item.Width,
			Height:      item.Height,
			AdType:      item.AdType,
			MaxCount:    item.MaxCount,
			Description: item.Description,
			Status:      item.Status,
			CreatedAt:   item.CreatedAt,
			UpdatedAt:   item.UpdatedAt,
		}
	}

	return &model.AdPositionSearchOutput{
		List:  list,
		Total: total,
		Page:  page,
		Size:  size,
	}, nil
}

func (s *sMarketing) GetPositionById(ctx context.Context, id uint64) (*model.AdPositionItem, error) {
	var item entity.AdPositions
	err := dao.AdPositions.Ctx(ctx).WherePri(id).Scan(&item)
	if err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("广告位不存在")
	}
	return &model.AdPositionItem{
		Id:          item.Id,
		Name:        item.Name,
		Code:        item.Code,
		Width:       item.Width,
		Height:      item.Height,
		AdType:      item.AdType,
		MaxCount:    item.MaxCount,
		Description: item.Description,
		Status:      item.Status,
		CreatedAt:   item.CreatedAt,
		UpdatedAt:   item.UpdatedAt,
	}, nil
}

func (s *sMarketing) CreatePosition(ctx context.Context, in model.AdPositionCreateInput) (uint64, error) {
	if in.Code == "" {
		return 0, gerror.New("广告位代码不能为空")
	}
	count, err := dao.AdPositions.Ctx(ctx).Where(dao.AdPositions.Columns().Code, in.Code).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New(fmt.Sprintf("广告位代码 '%s' 已存在", in.Code))
	}

	id, err := dao.AdPositions.Ctx(ctx).InsertAndGetId(do.AdPositions{
		Name:        in.Name,
		Code:        in.Code,
		Width:       in.Width,
		Height:      in.Height,
		AdType:      in.AdType,
		MaxCount:    in.MaxCount,
		Description: in.Description,
		Status:      in.Status,
	})
	if err != nil {
		return 0, err
	}
	return uint64(id), nil
}

func (s *sMarketing) UpdatePosition(ctx context.Context, in model.AdPositionUpdateInput) error {
	var item entity.AdPositions
	err := dao.AdPositions.Ctx(ctx).WherePri(in.Id).Scan(&item)
	if err != nil {
		return err
	}
	if item.Id == 0 {
		return gerror.New("广告位不存在")
	}

	if in.Code != "" && in.Code != item.Code {
		count, err := dao.AdPositions.Ctx(ctx).
			Where(dao.AdPositions.Columns().Code, in.Code).
			WhereNot(dao.AdPositions.Columns().Id, in.Id).
			Count()
		if err != nil {
			return err
		}
		if count > 0 {
			return gerror.New(fmt.Sprintf("广告位代码 '%s' 已被占用", in.Code))
		}
	}

	_, err = dao.AdPositions.Ctx(ctx).WherePri(in.Id).Update(do.AdPositions{
		Name:        in.Name,
		Code:        in.Code,
		Width:       in.Width,
		Height:      in.Height,
		AdType:      in.AdType,
		MaxCount:    in.MaxCount,
		Description: in.Description,
		Status:      in.Status,
	})
	return err
}

func (s *sMarketing) DeletePositions(ctx context.Context, ids []uint64) error {
	if len(ids) == 0 {
		return nil
	}
	return dao.AdPositions.Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		// 删除广告位下的广告
		if _, err := dao.Ads.Ctx(ctx).TX(tx).WhereIn(dao.Ads.Columns().PositionId, ids).Delete(); err != nil {
			return err
		}
		_, err := dao.AdPositions.Ctx(ctx).TX(tx).WhereIn(dao.AdPositions.Columns().Id, ids).Delete()
		return err
	})
}

// ================= Ads =================

func (s *sMarketing) SearchAds(ctx context.Context, in model.AdSearchInput) (*model.AdSearchOutput, error) {
	m := dao.Ads.Ctx(ctx)
	if in.PositionId > 0 {
		m = m.Where(dao.Ads.Columns().PositionId, in.PositionId)
	}
	if in.Keyword != "" {
		m = m.WhereLike(dao.Ads.Columns().Title, "%"+in.Keyword+"%")
	}
	if in.Status != nil {
		m = m.Where(dao.Ads.Columns().Status, *in.Status)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	page := in.Page
	if page <= 0 {
		page = 1
	}
	size := in.PageSize
	if size <= 0 {
		size = 10
	}

	var items []entity.Ads
	if err = m.Page(page, size).OrderAsc(dao.Ads.Columns().Sort).OrderDesc(dao.Ads.Columns().Id).Scan(&items); err != nil {
		return nil, err
	}

	// 加载广告位名称
	posIdMap := make(map[uint64]string)
	for _, item := range items {
		posIdMap[item.PositionId] = ""
	}
	if len(posIdMap) > 0 {
		var pIds []uint64
		for pId := range posIdMap {
			pIds = append(pIds, pId)
		}
		var poses []entity.AdPositions
		_ = dao.AdPositions.Ctx(ctx).WhereIn(dao.AdPositions.Columns().Id, pIds).Scan(&poses)
		for _, p := range poses {
			posIdMap[p.Id] = p.Name
		}
	}

	list := make([]model.AdItem, len(items))
	for i, item := range items {
		list[i] = model.AdItem{
			Id:           item.Id,
			PositionId:   item.PositionId,
			PositionName: posIdMap[item.PositionId],
			Title:        item.Title,
			AdType:       item.AdType,
			CoverImage:   item.CoverImage,
			Content:      item.Content,
			LinkUrl:      item.LinkUrl,
			LinkTarget:   item.LinkTarget,
			Sort:         item.Sort,
			StartTime:    item.StartTime,
			EndTime:      item.EndTime,
			DisplayLimit: item.DisplayLimit,
			ClickLimit:   item.ClickLimit,
			DisplayCount: item.DisplayCount,
			ClickCount:   item.ClickCount,
			Status:       item.Status,
			CreatedAt:    item.CreatedAt,
			UpdatedAt:    item.UpdatedAt,
		}
	}

	return &model.AdSearchOutput{
		List:  list,
		Total: total,
		Page:  page,
		Size:  size,
	}, nil
}

func (s *sMarketing) GetAdById(ctx context.Context, id uint64) (*model.AdItem, error) {
	var item entity.Ads
	err := dao.Ads.Ctx(ctx).WherePri(id).Scan(&item)
	if err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("广告不存在")
	}

	var posName string
	var pos entity.AdPositions
	if err := dao.AdPositions.Ctx(ctx).WherePri(item.PositionId).Scan(&pos); err == nil {
		posName = pos.Name
	}

	return &model.AdItem{
		Id:           item.Id,
		PositionId:   item.PositionId,
		PositionName: posName,
		Title:        item.Title,
		AdType:       item.AdType,
		CoverImage:   item.CoverImage,
		Content:      item.Content,
		LinkUrl:      item.LinkUrl,
		LinkTarget:   item.LinkTarget,
		Sort:         item.Sort,
		StartTime:    item.StartTime,
		EndTime:      item.EndTime,
		DisplayLimit: item.DisplayLimit,
		ClickLimit:   item.ClickLimit,
		DisplayCount: item.DisplayCount,
		ClickCount:   item.ClickCount,
		Status:       item.Status,
		CreatedAt:    item.CreatedAt,
		UpdatedAt:    item.UpdatedAt,
	}, nil
}

func (s *sMarketing) CreateAd(ctx context.Context, in model.AdCreateInput) (uint64, error) {
	id, err := dao.Ads.Ctx(ctx).InsertAndGetId(do.Ads{
		PositionId:   in.PositionId,
		Title:        in.Title,
		AdType:       in.AdType,
		CoverImage:   in.CoverImage,
		Content:      in.Content,
		LinkUrl:      in.LinkUrl,
		LinkTarget:   in.LinkTarget,
		Sort:         in.Sort,
		StartTime:    in.StartTime,
		EndTime:      in.EndTime,
		DisplayLimit: in.DisplayLimit,
		ClickLimit:   in.ClickLimit,
		DisplayCount: 0,
		ClickCount:   0,
		Status:       in.Status,
	})
	if err != nil {
		return 0, err
	}
	return uint64(id), nil
}

func (s *sMarketing) UpdateAd(ctx context.Context, in model.AdUpdateInput) error {
	var item entity.Ads
	err := dao.Ads.Ctx(ctx).WherePri(in.Id).Scan(&item)
	if err != nil {
		return err
	}
	if item.Id == 0 {
		return gerror.New("广告不存在")
	}

	_, err = dao.Ads.Ctx(ctx).WherePri(in.Id).Update(do.Ads{
		PositionId:   in.PositionId,
		Title:        in.Title,
		AdType:       in.AdType,
		CoverImage:   in.CoverImage,
		Content:      in.Content,
		LinkUrl:      in.LinkUrl,
		LinkTarget:   in.LinkTarget,
		Sort:         in.Sort,
		StartTime:    in.StartTime,
		EndTime:      in.EndTime,
		DisplayLimit: in.DisplayLimit,
		ClickLimit:   in.ClickLimit,
		Status:       in.Status,
	})
	return err
}

func (s *sMarketing) DeleteAds(ctx context.Context, ids []uint64) error {
	if len(ids) == 0 {
		return nil
	}
	_, err := dao.Ads.Ctx(ctx).WhereIn(dao.Ads.Columns().Id, ids).Delete()
	return err
}

func (s *sMarketing) RecordAdDisplay(ctx context.Context, id uint64) error {
	_, err := dao.Ads.Ctx(ctx).WherePri(id).Increment(dao.Ads.Columns().DisplayCount, 1)
	return err
}

func (s *sMarketing) RecordAdClick(ctx context.Context, id uint64) error {
	_, err := dao.Ads.Ctx(ctx).WherePri(id).Increment(dao.Ads.Columns().ClickCount, 1)
	return err
}

func (s *sMarketing) GetActiveAdsByPositionCode(ctx context.Context, code string) ([]model.AdItem, error) {
	var pos entity.AdPositions
	err := dao.AdPositions.Ctx(ctx).Where(dao.AdPositions.Columns().Code, code).Scan(&pos)
	if err != nil || pos.Id == 0 || pos.Status != 1 {
		return nil, err
	}

	now := gtime.Now()
	m := dao.Ads.Ctx(ctx).
		Where(dao.Ads.Columns().PositionId, pos.Id).
		Where(dao.Ads.Columns().Status, 1).
		Where(fmt.Sprintf("%s IS NULL OR %s <= ?", dao.Ads.Columns().StartTime, dao.Ads.Columns().StartTime), now).
		Where(fmt.Sprintf("%s IS NULL OR %s >= ?", dao.Ads.Columns().EndTime, dao.Ads.Columns().EndTime), now)

	if pos.MaxCount > 0 {
		m = m.Limit(int(pos.MaxCount))
	}

	var items []entity.Ads
	if err := m.OrderAsc(dao.Ads.Columns().Sort).Scan(&items); err != nil {
		return nil, err
	}

	list := make([]model.AdItem, len(items))
	for i, item := range items {
		list[i] = model.AdItem{
			Id:           item.Id,
			PositionId:   item.PositionId,
			PositionName: pos.Name,
			Title:        item.Title,
			AdType:       item.AdType,
			CoverImage:   item.CoverImage,
			Content:      item.Content,
			LinkUrl:      item.LinkUrl,
			LinkTarget:   item.LinkTarget,
			Sort:         item.Sort,
			StartTime:    item.StartTime,
			EndTime:      item.EndTime,
			DisplayLimit: item.DisplayLimit,
			ClickLimit:   item.ClickLimit,
			DisplayCount: item.DisplayCount,
			ClickCount:   item.ClickCount,
			Status:       item.Status,
			CreatedAt:    item.CreatedAt,
			UpdatedAt:    item.UpdatedAt,
		}
	}
	return list, nil
}

// ================= Friend Links =================

func (s *sMarketing) SearchFriendLinks(ctx context.Context, in model.FriendLinkSearchInput) (*model.FriendLinkSearchOutput, error) {
	m := dao.FriendLinks.Ctx(ctx)
	if in.Category != "" {
		m = m.Where(dao.FriendLinks.Columns().Category, in.Category)
	}
	if in.Keyword != "" {
		m = m.WhereLike(dao.FriendLinks.Columns().SiteName, "%"+in.Keyword+"%")
	}
	if in.Status != nil {
		m = m.Where(dao.FriendLinks.Columns().Status, *in.Status)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	page := in.Page
	if page <= 0 {
		page = 1
	}
	size := in.PageSize
	if size <= 0 {
		size = 10
	}

	var items []entity.FriendLinks
	if err = m.Page(page, size).OrderAsc(dao.FriendLinks.Columns().Sort).OrderDesc(dao.FriendLinks.Columns().Id).Scan(&items); err != nil {
		return nil, err
	}

	list := make([]model.FriendLinkItem, len(items))
	for i, item := range items {
		list[i] = model.FriendLinkItem{
			Id:           item.Id,
			Category:     item.Category,
			SiteName:     item.SiteName,
			SiteUrl:      item.SiteUrl,
			LogoUrl:      item.LogoUrl,
			Description:  item.Description,
			ContactEmail: item.ContactEmail,
			Sort:         item.Sort,
			Status:       item.Status,
			CreatedAt:    item.CreatedAt,
			UpdatedAt:    item.UpdatedAt,
		}
	}

	return &model.FriendLinkSearchOutput{
		List:  list,
		Total: total,
		Page:  page,
		Size:  size,
	}, nil
}

func (s *sMarketing) GetFriendLinkById(ctx context.Context, id uint64) (*model.FriendLinkItem, error) {
	var item entity.FriendLinks
	err := dao.FriendLinks.Ctx(ctx).WherePri(id).Scan(&item)
	if err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("友情链接不存在")
	}

	return &model.FriendLinkItem{
		Id:           item.Id,
		Category:     item.Category,
		SiteName:     item.SiteName,
		SiteUrl:      item.SiteUrl,
		LogoUrl:      item.LogoUrl,
		Description:  item.Description,
		ContactEmail: item.ContactEmail,
		Sort:         item.Sort,
		Status:       item.Status,
		CreatedAt:    item.CreatedAt,
		UpdatedAt:    item.UpdatedAt,
	}, nil
}

func (s *sMarketing) CreateFriendLink(ctx context.Context, in model.FriendLinkCreateInput) (uint64, error) {
	id, err := dao.FriendLinks.Ctx(ctx).InsertAndGetId(do.FriendLinks{
		Category:     in.Category,
		SiteName:     in.SiteName,
		SiteUrl:      in.SiteUrl,
		LogoUrl:      in.LogoUrl,
		Description:  in.Description,
		ContactEmail: in.ContactEmail,
		Sort:         in.Sort,
		Status:       in.Status,
	})
	if err != nil {
		return 0, err
	}
	return uint64(id), nil
}

func (s *sMarketing) UpdateFriendLink(ctx context.Context, in model.FriendLinkUpdateInput) error {
	var item entity.FriendLinks
	err := dao.FriendLinks.Ctx(ctx).WherePri(in.Id).Scan(&item)
	if err != nil {
		return err
	}
	if item.Id == 0 {
		return gerror.New("友情链接不存在")
	}

	_, err = dao.FriendLinks.Ctx(ctx).WherePri(in.Id).Update(do.FriendLinks{
		Category:     in.Category,
		SiteName:     in.SiteName,
		SiteUrl:      in.SiteUrl,
		LogoUrl:      in.LogoUrl,
		Description:  in.Description,
		ContactEmail: in.ContactEmail,
		Sort:         in.Sort,
		Status:       in.Status,
	})
	return err
}

func (s *sMarketing) DeleteFriendLinks(ctx context.Context, ids []uint64) error {
	if len(ids) == 0 {
		return nil
	}
	_, err := dao.FriendLinks.Ctx(ctx).WhereIn(dao.FriendLinks.Columns().Id, ids).Delete()
	return err
}

func (s *sMarketing) GetActiveFriendLinks(ctx context.Context, category string) ([]model.FriendLinkItem, error) {
	m := dao.FriendLinks.Ctx(ctx).Where(dao.FriendLinks.Columns().Status, 1)
	if category != "" {
		m = m.Where(dao.FriendLinks.Columns().Category, category)
	}

	var items []entity.FriendLinks
	if err := m.OrderAsc(dao.FriendLinks.Columns().Sort).Scan(&items); err != nil {
		return nil, err
	}

	list := make([]model.FriendLinkItem, len(items))
	for i, item := range items {
		list[i] = model.FriendLinkItem{
			Id:           item.Id,
			Category:     item.Category,
			SiteName:     item.SiteName,
			SiteUrl:      item.SiteUrl,
			LogoUrl:      item.LogoUrl,
			Description:  item.Description,
			ContactEmail: item.ContactEmail,
			Sort:         item.Sort,
			Status:       item.Status,
			CreatedAt:    item.CreatedAt,
			UpdatedAt:    item.UpdatedAt,
		}
	}
	return list, nil
}

// ================= Short Links =================

const shortCodeLetters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"

func generateShortCode(length int) string {
	b := make([]byte, length)
	r := rand.New(rand.NewSource(time.Now().UnixNano()))
	for i := range b {
		b[i] = shortCodeLetters[r.Intn(len(shortCodeLetters))]
	}
	return string(b)
}

func (s *sMarketing) SearchShortLinks(ctx context.Context, in model.ShortLinkSearchInput) (*model.ShortLinkSearchOutput, error) {
	m := dao.ShortLinks.Ctx(ctx)
	if in.Keyword != "" {
		m = m.Where(m.Builder().WhereLike(dao.ShortLinks.Columns().Title, "%"+in.Keyword+"%").WhereOrLike(dao.ShortLinks.Columns().ShortCode, "%"+in.Keyword+"%"))
	}
	if in.Status != nil {
		m = m.Where(dao.ShortLinks.Columns().Status, *in.Status)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	page := in.Page
	if page <= 0 {
		page = 1
	}
	size := in.PageSize
	if size <= 0 {
		size = 10
	}

	var items []entity.ShortLinks
	if err = m.Page(page, size).OrderDesc(dao.ShortLinks.Columns().Id).Scan(&items); err != nil {
		return nil, err
	}

	list := make([]model.ShortLinkItem, len(items))
	for i, item := range items {
		list[i] = model.ShortLinkItem{
			Id:         item.Id,
			ShortCode:  item.ShortCode,
			TargetUrl:  item.TargetUrl,
			Title:      item.Title,
			ClickCount: item.ClickCount,
			QrCodePath: item.QrCodePath,
			ExpireAt:   item.ExpireAt,
			Status:     item.Status,
			CreatedAt:  item.CreatedAt,
			UpdatedAt:  item.UpdatedAt,
		}
	}

	return &model.ShortLinkSearchOutput{
		List:  list,
		Total: total,
		Page:  page,
		Size:  size,
	}, nil
}

func (s *sMarketing) GetShortLinkById(ctx context.Context, id uint64) (*model.ShortLinkItem, error) {
	var item entity.ShortLinks
	err := dao.ShortLinks.Ctx(ctx).WherePri(id).Scan(&item)
	if err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("短链接不存在")
	}

	return &model.ShortLinkItem{
		Id:         item.Id,
		ShortCode:  item.ShortCode,
		TargetUrl:  item.TargetUrl,
		Title:      item.Title,
		ClickCount: item.ClickCount,
		QrCodePath: item.QrCodePath,
		ExpireAt:   item.ExpireAt,
		Status:     item.Status,
		CreatedAt:  item.CreatedAt,
		UpdatedAt:  item.UpdatedAt,
	}, nil
}

func (s *sMarketing) CreateShortLink(ctx context.Context, in model.ShortLinkCreateInput) (uint64, error) {
	if in.TargetUrl == "" {
		return 0, gerror.New("目标跳转地址不能为空")
	}

	shortCode := in.ShortCode
	if shortCode == "" {
		// 自动生成6位短码
		for i := 0; i < 5; i++ {
			candidate := generateShortCode(6)
			cnt, _ := dao.ShortLinks.Ctx(ctx).Where(dao.ShortLinks.Columns().ShortCode, candidate).Count()
			if cnt == 0 {
				shortCode = candidate
				break
			}
		}
		if shortCode == "" {
			return 0, gerror.New("自动生成短链代码失败，请重试")
		}
	} else {
		cnt, err := dao.ShortLinks.Ctx(ctx).Where(dao.ShortLinks.Columns().ShortCode, shortCode).Count()
		if err != nil {
			return 0, err
		}
		if cnt > 0 {
			return 0, gerror.New(fmt.Sprintf("短链代码 '%s' 已存在", shortCode))
		}
	}

	id, err := dao.ShortLinks.Ctx(ctx).InsertAndGetId(do.ShortLinks{
		ShortCode:  shortCode,
		TargetUrl:  in.TargetUrl,
		Title:      in.Title,
		ClickCount: 0,
		QrCodePath: in.QrCodePath,
		ExpireAt:   in.ExpireAt,
		Status:     in.Status,
	})
	if err != nil {
		return 0, err
	}
	return uint64(id), nil
}

func (s *sMarketing) UpdateShortLink(ctx context.Context, in model.ShortLinkUpdateInput) error {
	var item entity.ShortLinks
	err := dao.ShortLinks.Ctx(ctx).WherePri(in.Id).Scan(&item)
	if err != nil {
		return err
	}
	if item.Id == 0 {
		return gerror.New("短链接不存在")
	}

	if in.ShortCode != "" && in.ShortCode != item.ShortCode {
		cnt, err := dao.ShortLinks.Ctx(ctx).
			Where(dao.ShortLinks.Columns().ShortCode, in.ShortCode).
			WhereNot(dao.ShortLinks.Columns().Id, in.Id).
			Count()
		if err != nil {
			return err
		}
		if cnt > 0 {
			return gerror.New(fmt.Sprintf("短链代码 '%s' 已被占用", in.ShortCode))
		}
	}

	_, err = dao.ShortLinks.Ctx(ctx).WherePri(in.Id).Update(do.ShortLinks{
		ShortCode:  in.ShortCode,
		TargetUrl:  in.TargetUrl,
		Title:      in.Title,
		QrCodePath: in.QrCodePath,
		ExpireAt:   in.ExpireAt,
		Status:     in.Status,
	})
	return err
}

func (s *sMarketing) DeleteShortLinks(ctx context.Context, ids []uint64) error {
	if len(ids) == 0 {
		return nil
	}
	return dao.ShortLinks.Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		if _, err := dao.ShortLinkClicks.Ctx(ctx).TX(tx).WhereIn(dao.ShortLinkClicks.Columns().ShortLinkId, ids).Delete(); err != nil {
			return err
		}
		_, err := dao.ShortLinks.Ctx(ctx).TX(tx).WhereIn(dao.ShortLinks.Columns().Id, ids).Delete()
		return err
	})
}

func (s *sMarketing) ResolveAndClick(ctx context.Context, in model.ShortLinkResolveInput) (*model.ShortLinkResolveOutput, error) {
	var item entity.ShortLinks
	err := dao.ShortLinks.Ctx(ctx).Where(dao.ShortLinks.Columns().ShortCode, in.ShortCode).Scan(&item)
	if err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("短链接不存在")
	}
	if item.Status != 1 {
		return nil, gerror.New("该短链接已被停用")
	}
	if item.ExpireAt != nil && item.ExpireAt.Before(gtime.Now()) {
		return nil, gerror.New("该短链接已过期")
	}

	// 记录点击流水与自增点击量 (异步或事务)
	_ = dao.ShortLinks.Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		_, _ = dao.ShortLinkClicks.Ctx(ctx).TX(tx).Insert(do.ShortLinkClicks{
			ShortLinkId: item.Id,
			ClickIp:     in.ClickIp,
			UserAgent:   in.UserAgent,
			Referer:     in.Referer,
		})
		_, _ = dao.ShortLinks.Ctx(ctx).TX(tx).WherePri(item.Id).Increment(dao.ShortLinks.Columns().ClickCount, 1)
		return nil
	})

	return &model.ShortLinkResolveOutput{
		TargetUrl: item.TargetUrl,
	}, nil
}

// ================= Content Push Queue =================

func (s *sMarketing) SearchPushQueue(ctx context.Context, in model.ContentPushQueueSearchInput) (*model.ContentPushQueueSearchOutput, error) {
	m := dao.ContentPushQueue.Ctx(ctx)
	if in.PushType != "" {
		m = m.Where(dao.ContentPushQueue.Columns().PushType, in.PushType)
	}
	if in.Status != "" {
		m = m.Where(dao.ContentPushQueue.Columns().Status, in.Status)
	}
	if in.ContentId > 0 {
		m = m.Where(dao.ContentPushQueue.Columns().ContentId, in.ContentId)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	page := in.Page
	if page <= 0 {
		page = 1
	}
	size := in.PageSize
	if size <= 0 {
		size = 10
	}

	var items []entity.ContentPushQueue
	if err = m.Page(page, size).OrderDesc(dao.ContentPushQueue.Columns().Id).Scan(&items); err != nil {
		return nil, err
	}

	// 加载内容标题
	contentIdMap := make(map[uint64]string)
	for _, item := range items {
		contentIdMap[item.ContentId] = ""
	}
	if len(contentIdMap) > 0 {
		var cIds []uint64
		for cId := range contentIdMap {
			cIds = append(cIds, cId)
		}
		var contents []entity.Contents
		_ = dao.Contents.Ctx(ctx).WhereIn(dao.Contents.Columns().Id, cIds).Scan(&contents)
		for _, c := range contents {
			contentIdMap[c.Id] = c.Title
		}
	}

	list := make([]model.ContentPushQueueItem, len(items))
	for i, item := range items {
		list[i] = model.ContentPushQueueItem{
			Id:           item.Id,
			ContentId:    item.ContentId,
			ContentTitle: contentIdMap[item.ContentId],
			PushType:     item.PushType,
			PushData:     item.PushData,
			Status:       item.Status,
			RetryCount:   item.RetryCount,
			MaxRetries:   item.MaxRetries,
			ErrorMessage: item.ErrorMessage,
			FinishedAt:   item.FinishedAt,
			CreatedAt:    item.CreatedAt,
			UpdatedAt:    item.UpdatedAt,
		}
	}

	return &model.ContentPushQueueSearchOutput{
		List:  list,
		Total: total,
		Page:  page,
		Size:  size,
	}, nil
}

func (s *sMarketing) RetryPush(ctx context.Context, id uint64) error {
	var item entity.ContentPushQueue
	err := dao.ContentPushQueue.Ctx(ctx).WherePri(id).Scan(&item)
	if err != nil {
		return err
	}
	if item.Id == 0 {
		return gerror.New("推送任务不存在")
	}

	_, err = dao.ContentPushQueue.Ctx(ctx).WherePri(id).Update(g.Map{
		dao.ContentPushQueue.Columns().Status:       "pending",
		dao.ContentPushQueue.Columns().ErrorMessage: "",
		dao.ContentPushQueue.Columns().RetryCount:   item.RetryCount + 1,
		dao.ContentPushQueue.Columns().UpdatedAt:    gtime.Now(),
	})
	return err
}

func (s *sMarketing) DeletePushQueue(ctx context.Context, ids []uint64) error {
	if len(ids) == 0 {
		return nil
	}
	_, err := dao.ContentPushQueue.Ctx(ctx).WhereIn(dao.ContentPushQueue.Columns().Id, ids).Delete()
	return err
}
