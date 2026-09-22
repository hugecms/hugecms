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
	IMarketing interface {
		SearchPositions(ctx context.Context, in model.AdPositionSearchInput) (*model.AdPositionSearchOutput, error)
		GetPositionById(ctx context.Context, id uint64) (*model.AdPositionItem, error)
		CreatePosition(ctx context.Context, in model.AdPositionCreateInput) (uint64, error)
		UpdatePosition(ctx context.Context, in model.AdPositionUpdateInput) error
		DeletePositions(ctx context.Context, ids []uint64) error
		SearchAds(ctx context.Context, in model.AdSearchInput) (*model.AdSearchOutput, error)
		GetAdById(ctx context.Context, id uint64) (*model.AdItem, error)
		CreateAd(ctx context.Context, in model.AdCreateInput) (uint64, error)
		UpdateAd(ctx context.Context, in model.AdUpdateInput) error
		DeleteAds(ctx context.Context, ids []uint64) error
		RecordAdDisplay(ctx context.Context, id uint64) error
		RecordAdClick(ctx context.Context, id uint64) error
		GetActiveAdsByPositionCode(ctx context.Context, code string) ([]model.AdItem, error)
		SearchFriendLinks(ctx context.Context, in model.FriendLinkSearchInput) (*model.FriendLinkSearchOutput, error)
		GetFriendLinkById(ctx context.Context, id uint64) (*model.FriendLinkItem, error)
		CreateFriendLink(ctx context.Context, in model.FriendLinkCreateInput) (uint64, error)
		UpdateFriendLink(ctx context.Context, in model.FriendLinkUpdateInput) error
		DeleteFriendLinks(ctx context.Context, ids []uint64) error
		GetActiveFriendLinks(ctx context.Context, category string) ([]model.FriendLinkItem, error)
		SearchShortLinks(ctx context.Context, in model.ShortLinkSearchInput) (*model.ShortLinkSearchOutput, error)
		GetShortLinkById(ctx context.Context, id uint64) (*model.ShortLinkItem, error)
		CreateShortLink(ctx context.Context, in model.ShortLinkCreateInput) (uint64, error)
		UpdateShortLink(ctx context.Context, in model.ShortLinkUpdateInput) error
		DeleteShortLinks(ctx context.Context, ids []uint64) error
		ResolveAndClick(ctx context.Context, in model.ShortLinkResolveInput) (*model.ShortLinkResolveOutput, error)
		SearchPushQueue(ctx context.Context, in model.ContentPushQueueSearchInput) (*model.ContentPushQueueSearchOutput, error)
		RetryPush(ctx context.Context, id uint64) error
		DeletePushQueue(ctx context.Context, ids []uint64) error
	}
)

var (
	localMarketing IMarketing
)

func Marketing() IMarketing {
	if localMarketing == nil {
		panic("implement not found for interface IMarketing, forgot register?")
	}
	return localMarketing
}

func RegisterMarketing(i IMarketing) {
	localMarketing = i
}
