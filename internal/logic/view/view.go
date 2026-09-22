package view

import (
	"context"
	"fmt"
	"time"

	"github.com/gogf/gf/v2/crypto/gmd5"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gcache"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

type sView struct{}

func init() {
	service.RegisterView(New())
}

func New() service.IView {
	return &sView{}
}

// RecordView 记录内容浏览量（带24小时IP哈希去重防刷机制）
func (s *sView) RecordView(ctx context.Context, in model.ContentViewRecordInput) (*model.ContentViewRecordOutput, error) {
	if in.ContentId <= 0 {
		return &model.ContentViewRecordOutput{Ignored: true}, nil
	}

	ip := in.Ip
	if ip == "" {
		ip = "127.0.0.1"
	}

	ipHash := gmd5.MustEncryptString(ip)
	cacheKey := fmt.Sprintf("view:%d:%s", in.ContentId, ipHash)

	// 1. 优先尝试 Redis SETNX (带 24 小时过期时间)
	isNewView := false
	var redisClient *g.Var
	if cfg, err := g.Config().Get(ctx, "redis"); err == nil && !cfg.IsEmpty() {
		redisClient = cfg
	}

	if redisClient != nil {
		func() {
			defer func() {
				if r := recover(); r != nil {
					// Redis 连接失败或未正确配置
					redisClient = nil
				}
			}()
			client := g.Redis()
			if client != nil {
				val, err := client.Do(ctx, "SET", cacheKey, 1, "EX", int64(24*3600), "NX")
				if err == nil && val != nil && !val.IsNil() && val.String() == "OK" {
					isNewView = true
				}
			}
		}()
	}

	if redisClient == nil {
		// 降级使用 GoFrame gcache
		ok, err := gcache.SetIfNotExist(ctx, cacheKey, 1, 24*time.Hour)
		if err == nil && ok {
			isNewView = true
		}
	}

	if !isNewView {
		// 24小时内同一 IP 重复访问，防刷去重拦截
		var currentViews int64
		val, _ := dao.Contents.Ctx(ctx).WherePri(in.ContentId).Value(dao.Contents.Columns().Views)
		if val != nil {
			currentViews = val.Int64()
		}
		return &model.ContentViewRecordOutput{
			Views:   currentViews,
			Ignored: true,
		}, nil
	}

	// 2. 防刷通过，累加浏览量
	_, err := dao.Contents.Ctx(ctx).WherePri(in.ContentId).Increment(dao.Contents.Columns().Views, 1)
	if err != nil {
		return nil, err
	}

	var updatedViews int64
	val, _ := dao.Contents.Ctx(ctx).WherePri(in.ContentId).Value(dao.Contents.Columns().Views)
	if val != nil {
		updatedViews = val.Int64()
	}

	return &model.ContentViewRecordOutput{
		Views:   updatedViews,
		Ignored: false,
	}, nil
}
