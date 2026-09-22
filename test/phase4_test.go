package test

import (
	"context"
	"fmt"
	"testing"
	"time"

	_ "hugecms/internal/logic"

	_ "github.com/gogf/gf/contrib/drivers/mysql/v2"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gctx"

	"hugecms/internal/model"
	"hugecms/internal/service"
)

func TestPhase4ContentCore(t *testing.T) {
	ctx := gctx.New()

	// 模拟登录超级管理员上下文
	localCtx := &model.Context{
		User: &model.ContextUser{
			Id:      1,
			Email:   "admin@example.com",
			Name:    "管理员",
			IsSuper: true,
		},
	}
	ctx = context.WithValue(ctx, model.ContextKey, localCtx)

	// ==================== 1. 测试动态模型与物理字段 DDL ====================
	modelAlias := fmt.Sprintf("job_%d", time.Now().Unix())
	modelId, err := service.ContentModel().Create(ctx, model.ContentModelCreateInput{
		Name:          "招聘模型",
		Alias:         modelAlias,
		Description:   "自动化测试招聘模型",
		IsCommentable: 1,
		Status:        1,
	})
	if err != nil {
		t.Fatalf("ContentModel.Create failed: %v", err)
	}
	t.Logf("Created ContentModel ID: %d, Alias: %s", modelId, modelAlias)

	// 验证物理数据表 data_{alias} 是否创建成功
	tableName := "data_" + modelAlias
	tbCount, err := g.DB().GetArray(ctx, fmt.Sprintf("SHOW TABLES LIKE '%s'", tableName))
	if err != nil || len(tbCount) == 0 {
		t.Fatalf("Dynamic table %s was not created: %v", tableName, err)
	}

	// 动态添加字段
	salaryFieldId, err := service.ModelField().Create(ctx, model.ModelFieldCreateInput{
		ModelId:    modelId,
		FieldName:  "salary",
		FieldLabel: "薪资范围",
		FieldType:  "text",
	})
	if err != nil {
		t.Fatalf("ModelField.Create salary failed: %v", err)
	}

	descFieldId, err := service.ModelField().Create(ctx, model.ModelFieldCreateInput{
		ModelId:    modelId,
		FieldName:  "job_desc",
		FieldLabel: "职位描述",
		FieldType:  "rich_text",
	})
	if err != nil {
		t.Fatalf("ModelField.Create job_desc failed: %v", err)
	}
	t.Logf("Created dynamic fields: salary(%d), job_desc(%d)", salaryFieldId, descFieldId)

	// 验证物理表列是否存在
	cols, err := g.DB().GetArray(ctx, fmt.Sprintf("SHOW COLUMNS FROM `%s` LIKE 'field_%d'", tableName, salaryFieldId))
	if err != nil || len(cols) == 0 {
		t.Fatalf("Physical column field_%d not found in %s", salaryFieldId, tableName)
	}

	// ==================== 2. 测试分类体系 (Taxonomies & Terms) ====================
	taxAlias := fmt.Sprintf("tax_%d", time.Now().Unix())
	taxId, err := service.Taxonomy().SaveTaxonomy(ctx, model.TaxonomySaveInput{
		Name:           "岗位类别",
		Alias:          taxAlias,
		ModelId:        modelId,
		IsHierarchical: 1,
	})
	if err != nil {
		t.Fatalf("Taxonomy.SaveTaxonomy failed: %v", err)
	}

	termId, err := service.Taxonomy().SaveTerm(ctx, model.TermSaveInput{
		TaxonomyId: taxId,
		Name:       "后端研发",
		Slug:       fmt.Sprintf("backend_%d", time.Now().Unix()),
	})
	if err != nil {
		t.Fatalf("Taxonomy.SaveTerm failed: %v", err)
	}
	t.Logf("Created Taxonomy %d, Term %d", taxId, termId)

	// ==================== 3. 测试内容主业务与三维状态机 ====================
	contentSlug := fmt.Sprintf("go-dev-%d", time.Now().Unix())
	contentId, err := service.Content().Save(ctx, model.ContentSaveInput{
		ModelId:    modelId,
		Title:      "GoFrame 高级工程师招聘",
		Slug:       contentSlug,
		Status:     "published",
		Visibility: "public",
		Fields: map[string]interface{}{
			"salary":   "25k-40k",
			"job_desc": "熟练掌握 GoFrame、MySQL、Redis 高并发微服务架构",
		},
		TermIds: []int64{termId},
		SeoMeta: &model.SeoMetaSaveInput{
			Title:       "GoFrame 高级工程师招聘 - HugeCMS",
			Keywords:    "Go,GoFrame,Golang",
			Description: "急聘资深 GoFrame 工程师",
		},
	})
	if err != nil {
		t.Fatalf("Content.Save failed: %v", err)
	}
	t.Logf("Created Content ID: %d", contentId)

	// 验证内容详情与动态表数据反查
	detail, err := service.Content().Get(ctx, contentId)
	if err != nil {
		t.Fatalf("Content.Get failed: %v", err)
	}
	if detail.Title != "GoFrame 高级工程师招聘" || detail.AuditStatus != "approved" {
		t.Fatalf("Content detail unexpected: %+v", detail)
	}
	if detail.Fields["salary"] != "25k-40k" {
		t.Fatalf("Dynamic field salary mismatch: %v", detail.Fields["salary"])
	}
	if detail.SeoMeta == nil || detail.SeoMeta.Keywords != "Go,GoFrame,Golang" {
		t.Fatalf("SeoMeta not saved properly: %+v", detail.SeoMeta)
	}

	// 验证分类内容计数 content_count 是否已更新为 1
	termItem, err := service.Taxonomy().GetTerm(ctx, termId)
	if err != nil || termItem.ContentCount != 1 {
		t.Fatalf("Expected term content_count=1, got %d, err=%v", termItem.ContentCount, err)
	}

	// ==================== 4. 测试评论与评论数联动 ====================
	commentId, err := service.Comment().Create(ctx, model.CommentCreateInput{
		ContentId:  contentId,
		AuthorName: "求职者小张",
		Content:    "请问支持远程办公吗？",
		Ip:         "127.0.0.1",
	})
	if err != nil {
		t.Fatalf("Comment.Create failed: %v", err)
	}

	// 审核通过评论
	err = service.Comment().Audit(ctx, model.CommentAuditInput{
		Ids:    []int64{commentId},
		Status: "approved",
	})
	if err != nil {
		t.Fatalf("Comment.Audit failed: %v", err)
	}

	// 校验 content.comment_count 自动递增为 1
	detailAfterComment, _ := service.Content().Get(ctx, contentId)
	if detailAfterComment.CommentCount != 1 {
		t.Fatalf("Expected comment_count=1, got %d", detailAfterComment.CommentCount)
	}

	// ==================== 5. 测试回收站完整快照、软删除与幂等恢复 ====================
	// 5.1 软删除进回收站
	err = service.Content().Trash(ctx, contentId)
	if err != nil {
		t.Fatalf("Content.Trash failed: %v", err)
	}

	// 校验内容状态变为 trash，分类计数减为 0
	detailTrashed, _ := service.Content().Get(ctx, contentId)
	if detailTrashed.Status != "trash" {
		t.Fatalf("Expected status=trash, got %s", detailTrashed.Status)
	}
	termAfterTrash, _ := service.Taxonomy().GetTerm(ctx, termId)
	if termAfterTrash.ContentCount != 0 {
		t.Fatalf("Expected term content_count=0 after trash, got %d", termAfterTrash.ContentCount)
	}

	// 查找回收站记录
	recycleList, err := service.RecycleBin().Search(ctx, model.RecycleBinSearchInput{
		TargetType: "content",
	})
	if err != nil || recycleList.Total == 0 {
		t.Fatalf("RecycleBin.Search failed or empty: %v", err)
	}

	var targetRecycleId int64
	for _, r := range recycleList.List {
		if r.TargetId == fmt.Sprintf("%d", contentId) {
			targetRecycleId = r.Id
			break
		}
	}
	if targetRecycleId == 0 {
		t.Fatalf("Could not find recycle bin entry for content %d", contentId)
	}

	// 5.2 测试恢复
	restoredId, err := service.RecycleBin().Restore(ctx, targetRecycleId)
	if err != nil {
		t.Fatalf("RecycleBin.Restore failed: %v", err)
	}
	if restoredId != contentId {
		t.Fatalf("Expected restored ID %d, got %d", contentId, restoredId)
	}

	detailRestored, _ := service.Content().Get(ctx, contentId)
	if detailRestored.Status != "published" {
		t.Fatalf("Expected restored status=published, got %s", detailRestored.Status)
	}
	termAfterRestore, _ := service.Taxonomy().GetTerm(ctx, termId)
	if termAfterRestore.ContentCount != 1 {
		t.Fatalf("Expected term content_count=1 after restore, got %d", termAfterRestore.ContentCount)
	}

	// 5.3 测试彻底清除 Purge
	err = service.Content().Trash(ctx, contentId)
	if err != nil {
		t.Fatalf("Content.Trash 2 failed: %v", err)
	}
	recycleList2, _ := service.RecycleBin().Search(ctx, model.RecycleBinSearchInput{
		TargetType: "content",
	})
	for _, r := range recycleList2.List {
		if r.TargetId == fmt.Sprintf("%d", contentId) {
			targetRecycleId = r.Id
			break
		}
	}

	err = service.RecycleBin().Purge(ctx, targetRecycleId)
	if err != nil {
		t.Fatalf("RecycleBin.Purge failed: %v", err)
	}

	// 验证内容主表与动态数据表已物理清除
	purgedContent, _ := service.Content().Get(ctx, contentId)
	if purgedContent != nil {
		t.Fatalf("Content should be physically purged, but still found")
	}

	// ==================== 6. 清理测试资源 ====================
	_ = service.Taxonomy().DeleteTerm(ctx, termId)
	_ = service.Taxonomy().DeleteTaxonomy(ctx, taxId)
	_ = service.ContentModel().Delete(ctx, modelId)

	t.Log("Phase 4 Content Core integration tests passed successfully!")
}
