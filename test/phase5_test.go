package test

import (
	"context"
	"fmt"
	"testing"
	"time"

	_ "hugecms/internal/logic"

	_ "github.com/gogf/gf/contrib/drivers/mysql/v2"
	"github.com/gogf/gf/v2/os/gctx"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/do"
	"hugecms/internal/service"
)

func TestPhase5ExtensionsAndOps(t *testing.T) {
	ctx := gctx.New()

	// 模拟超级管理员上下文
	localCtx := &model.Context{
		User: &model.ContextUser{
			Id:      1,
			Email:   "admin@example.com",
			Name:    "管理员",
			IsSuper: true,
		},
	}
	ctx = context.WithValue(ctx, model.ContextKey, localCtx)

	// ==================== 1. 表单模块测试 ====================
	t.Run("FormModule", func(t *testing.T) {
		formAlias := fmt.Sprintf("signup_%d", time.Now().Unix())
		fieldsCfg := `[{"name":"username","title":"姓名","required":true},{"name":"email","title":"邮箱","required":true}]`

		// 1.1 创建表单模板
		tplId, err := service.Form().CreateTemplate(ctx, model.FormTemplateCreateInput{
			Name:           "在线报名测试",
			Alias:          formAlias,
			FieldsConfig:   fieldsCfg,
			IsActive:       1,
			SuccessMessage: "感谢您的报名！",
		})
		if err != nil {
			t.Fatalf("Form.CreateTemplate failed: %v", err)
		}
		t.Logf("Created FormTemplate ID: %d", tplId)

		// 1.2 必填校验拦截测试
		_, err = service.Form().Submit(ctx, model.FormSubmitInput{
			FormId: tplId,
			SubmissionData: map[string]interface{}{
				"email": "test@example.com",
			},
			SubmitterIp: "127.0.0.1",
			UserAgent:   "Go-Test-Agent",
		})
		if err == nil {
			t.Fatalf("Expected validation error for missing required 'username', got nil")
		}

		// 1.3 正常提交测试
		subOut, err := service.Form().Submit(ctx, model.FormSubmitInput{
			FormId: tplId,
			SubmissionData: map[string]interface{}{
				"username": "张三",
				"email":    "zhangsan@example.com",
			},
			SubmitterIp: "127.0.0.1",
			UserAgent:   "Go-Test-Agent",
		})
		if err != nil {
			t.Fatalf("Form.Submit failed: %v", err)
		}
		if subOut.SuccessMessage != "感谢您的报名！" {
			t.Fatalf("Unexpected success message: %s", subOut.SuccessMessage)
		}

		// 1.4 验证模板 submit_count 是否原子自增
		tpl, err := service.Form().GetTemplateById(ctx, tplId)
		if err != nil {
			t.Fatalf("Form.GetTemplateById failed: %v", err)
		}
		if tpl.SubmitCount != 1 {
			t.Fatalf("Expected submit_count to be 1, got %d", tpl.SubmitCount)
		}

		// 1.5 验证提交记录检索
		subList, err := service.Form().SearchSubmissions(ctx, model.FormSubmissionSearchInput{
			FormId: tplId,
		})
		if err != nil {
			t.Fatalf("Form.SearchSubmissions failed: %v", err)
		}
		if subList.Total != 1 {
			t.Fatalf("Expected 1 submission, got %d", subList.Total)
		}

		// 清理表单测试数据
		_ = service.Form().DeleteTemplates(ctx, []uint64{tplId})
		t.Log("FormModule tests passed.")
	})

	// ==================== 2. 营销与推广模块测试 ====================
	t.Run("MarketingModule", func(t *testing.T) {
		// 2.1 广告位与广告
		posCode := fmt.Sprintf("banner_%d", time.Now().Unix())
		posId, err := service.Marketing().CreatePosition(ctx, model.AdPositionCreateInput{
			Name:        "首页横幅测试",
			Code:        posCode,
			Width:       1200,
			Height:      300,
			AdType:      "image",
			MaxCount:    3,
			Description: "测试广告位",
			Status:      1,
		})
		if err != nil {
			t.Fatalf("CreatePosition failed: %v", err)
		}

		adId, err := service.Marketing().CreateAd(ctx, model.AdCreateInput{
			PositionId: posId,
			Title:      "测试春季大促",
			AdType:     "image",
			CoverImage: "https://example.com/banner.jpg",
			LinkUrl:    "https://example.com/promo",
			LinkTarget: 1,
			Sort:       1,
			Status:     1,
		})
		if err != nil {
			t.Fatalf("CreateAd failed: %v", err)
		}

		// 测试曝光与点击计数自增
		_ = service.Marketing().RecordAdDisplay(ctx, adId)
		_ = service.Marketing().RecordAdClick(ctx, adId)

		adItem, err := service.Marketing().GetAdById(ctx, adId)
		if err != nil {
			t.Fatalf("GetAdById failed: %v", err)
		}
		if adItem.DisplayCount != 1 || adItem.ClickCount != 1 {
			t.Fatalf("Expected display=1, click=1, got display=%d, click=%d", adItem.DisplayCount, adItem.ClickCount)
		}

		// 测试前台有效广告查询
		activeAds, err := service.Marketing().GetActiveAdsByPositionCode(ctx, posCode)
		if err != nil || len(activeAds) != 1 {
			t.Fatalf("Expected 1 active ad for position %s, got %d (err: %v)", posCode, len(activeAds), err)
		}

		// 清理广告数据
		_ = service.Marketing().DeletePositions(ctx, []uint64{posId})

		// 2.2 友情链接测试
		friendId, err := service.Marketing().CreateFriendLink(ctx, model.FriendLinkCreateInput{
			Category: "友情链接",
			SiteName: "Golang官网",
			SiteUrl:  "https://golang.org",
			Status:   0, // 待审核
		})
		if err != nil {
			t.Fatalf("CreateFriendLink failed: %v", err)
		}

		// 审核通过
		err = service.Marketing().UpdateFriendLink(ctx, model.FriendLinkUpdateInput{
			Id:       friendId,
			Category: "友情链接",
			SiteName: "Golang官网",
			SiteUrl:  "https://golang.org",
			Status:   1, // 审核通过
		})
		if err != nil {
			t.Fatalf("UpdateFriendLink failed: %v", err)
		}

		activeLinks, err := service.Marketing().GetActiveFriendLinks(ctx, "友情链接")
		if err != nil || len(activeLinks) == 0 {
			t.Fatalf("Expected active friend links, got none")
		}

		// 清理友链数据
		_ = service.Marketing().DeleteFriendLinks(ctx, []uint64{friendId})

		// 2.3 短链解析与重定向点击统计测试
		shortId, err := service.Marketing().CreateShortLink(ctx, model.ShortLinkCreateInput{
			TargetUrl: "https://hugecms.com/docs",
			Title:     "文档短链",
			Status:    1,
		})
		if err != nil {
			t.Fatalf("CreateShortLink failed: %v", err)
		}

		shortItem, err := service.Marketing().GetShortLinkById(ctx, shortId)
		if err != nil {
			t.Fatalf("GetShortLinkById failed: %v", err)
		}
		t.Logf("Generated ShortCode: %s", shortItem.ShortCode)

		// 模拟解析短链
		resolved, err := service.Marketing().ResolveAndClick(ctx, model.ShortLinkResolveInput{
			ShortCode: shortItem.ShortCode,
			ClickIp:   "127.0.0.1",
			UserAgent: "Mozilla/5.0",
			Referer:   "https://google.com",
		})
		if err != nil {
			t.Fatalf("ResolveAndClick failed: %v", err)
		}
		if resolved.TargetUrl != "https://hugecms.com/docs" {
			t.Fatalf("Expected target URL https://hugecms.com/docs, got %s", resolved.TargetUrl)
		}

		// 验证点击计数与记录插入
		updatedShort, _ := service.Marketing().GetShortLinkById(ctx, shortId)
		if updatedShort.ClickCount != 1 {
			t.Fatalf("Expected ShortLink clickCount=1, got %d", updatedShort.ClickCount)
		}

		clickCount, _ := dao.ShortLinkClicks.Ctx(ctx).Where("short_link_id", shortId).Count()
		if clickCount != 1 {
			t.Fatalf("Expected 1 click record in short_link_clicks, got %d", clickCount)
		}

		// 清理短链
		_ = service.Marketing().DeleteShortLinks(ctx, []uint64{shortId})

		t.Log("MarketingModule tests passed.")
	})

	// ==================== 3. 统计聚合幂等性测试 ====================
	t.Run("StatisticsAggregationIdempotency", func(t *testing.T) {
		testDate := "2026-09-01"

		// 首次执行聚合
		out1, err := service.Statistics().Aggregate(ctx, model.StatisticsDailyAggregateInput{Date: testDate})
		if err != nil {
			t.Fatalf("Statistics.Aggregate failed: %v", err)
		}
		if out1.StatDate != testDate {
			t.Fatalf("Expected stat_date %s, got %s", testDate, out1.StatDate)
		}

		// 重复执行同日聚合（覆盖更新，幂等）
		out2, err := service.Statistics().Aggregate(ctx, model.StatisticsDailyAggregateInput{Date: testDate})
		if err != nil {
			t.Fatalf("Second Statistics.Aggregate failed: %v", err)
		}

		// 验证数据库中该日期仅有一条记录
		recordsCount, err := dao.StatisticsDaily.Ctx(ctx).Where("stat_date", testDate).Count()
		if err != nil {
			t.Fatalf("Query statistics_daily count failed: %v", err)
		}
		if recordsCount != 1 {
			t.Fatalf("Expected exactly 1 record for date %s, got %d", testDate, recordsCount)
		}

		// 清理统计测试记录
		_, _ = dao.StatisticsDaily.Ctx(ctx).Where("stat_date", testDate).Delete()
		t.Logf("Statistics aggregation idempotency verified, count=%d", out2.TotalContents)
	})

	// ==================== 4. 回收站过期清理测试 ====================
	t.Run("RecycleBinPurgeExpired", func(t *testing.T) {
		// 插入一条已过期的回收站记录（利用虚拟生成列 expire_at = created_at + retention_days）
		pastCreatedAt := gtime.Now().Add(-48 * time.Hour)
		res, err := dao.RecycleBin.Ctx(ctx).Insert(do.RecycleBin{
			TargetType:    "content",
			TargetId:      "999999",
			OriginalData:  `{"content":{"id":999999,"title":"过期测试"},"relations":{}}`,
			RetentionDays: 1,
			CreatedAt:     pastCreatedAt,
		})
		if err != nil {
			t.Fatalf("Insert expired recycle bin record failed: %v", err)
		}
		recId, _ := res.LastInsertId()

		// 执行批量过期清除
		purgedCount, err := service.RecycleBin().PurgeExpired(ctx)
		if err != nil {
			t.Fatalf("PurgeExpired failed: %v", err)
		}
		if purgedCount < 1 {
			t.Fatalf("Expected at least 1 purged record, got %d", purgedCount)
		}

		// 检查该记录已被删除
		cnt, _ := dao.RecycleBin.Ctx(ctx).WherePri(recId).Count()
		if cnt != 0 {
			t.Fatalf("Expected expired record to be purged, but still exists")
		}
		t.Log("RecycleBin PurgeExpired test passed.")
	})

	// ==================== 5. 浏览量防刷测试 ====================
	t.Run("ViewAntiBrushing", func(t *testing.T) {
		// 创建或指定测试内容
		contentId := int64(1)

		// 第一次记录（同IP）
		res1, err := service.View().RecordView(ctx, model.ContentViewRecordInput{
			ContentId: contentId,
			Ip:        "203.0.113.195",
		})
		if err != nil {
			t.Fatalf("RecordView 1 failed: %v", err)
		}
		if res1.Ignored {
			t.Fatalf("Expected first view not to be ignored")
		}

		// 第二次记录（同IP防刷，应被拦截）
		res2, err := service.View().RecordView(ctx, model.ContentViewRecordInput{
			ContentId: contentId,
			Ip:        "203.0.113.195",
		})
		if err != nil {
			t.Fatalf("RecordView 2 failed: %v", err)
		}
		if !res2.Ignored {
			t.Fatalf("Expected second view from same IP within 24h to be ignored (anti-brushing)")
		}

		// 第三次记录（不同IP，应放行）
		res3, err := service.View().RecordView(ctx, model.ContentViewRecordInput{
			ContentId: contentId,
			Ip:        "203.0.113.196",
		})
		if err != nil {
			t.Fatalf("RecordView 3 failed: %v", err)
		}
		if res3.Ignored {
			t.Fatalf("Expected view from different IP not to be ignored")
		}

		t.Log("View anti-brushing test passed.")
	})
}
