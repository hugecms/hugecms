package recycle_bin

import (
	"context"
	"encoding/json"
	"fmt"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/encoding/gjson"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sRecycleBin struct{}

func init() {
	service.RegisterRecycleBin(New())
}

func New() service.IRecycleBin {
	return &sRecycleBin{}
}

// Search 分页查询回收站条目
func (s *sRecycleBin) Search(ctx context.Context, in model.RecycleBinSearchInput) (*model.RecycleBinSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.RecycleBin.Ctx(ctx)
	if in.TargetType != "" {
		m = m.Where("target_type", in.TargetType)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var list []entity.RecycleBin
	if err := m.Page(in.Page, in.PageSize).OrderDesc("id").Scan(&list); err != nil {
		return nil, err
	}

	// 提取删除人名称与快照标题
	userIds := make([]uint64, 0, len(list))
	for _, r := range list {
		if r.DeletedBy > 0 {
			userIds = append(userIds, r.DeletedBy)
		}
	}
	userNameMap := make(map[uint64]string)
	if len(userIds) > 0 {
		type UserName struct {
			Id   uint64 `json:"id"`
			Name string `json:"name"`
		}
		var users []UserName
		_ = dao.Users.Ctx(ctx).Fields("id, name").WhereIn("id", userIds).Scan(&users)
		for _, u := range users {
			userNameMap[u.Id] = u.Name
		}
	}

	items := make([]model.RecycleBinItem, 0, len(list))
	for _, r := range list {
		title := ""
		if r.TargetType == "content" {
			j, err := gjson.DecodeToJson(r.OriginalData)
			if err == nil {
				title = j.Get("content.title").String()
			}
		}

		items = append(items, model.RecycleBinItem{
			Id:            int64(r.Id),
			DeletedBy:     int64(r.DeletedBy),
			DeletedByName: userNameMap[r.DeletedBy],
			TargetType:    r.TargetType,
			TargetId:      r.TargetId,
			TargetTitle:   title,
			RetentionDays: int(r.RetentionDays),
			CreatedAt:     r.CreatedAt,
			ExpireAt:      r.ExpireAt,
		})
	}

	return &model.RecycleBinSearchOutput{
		List:  items,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// SnapshotAndTrash 内容删除进回收站：完整快照写入与状态置为 trash
func (s *sRecycleBin) SnapshotAndTrash(ctx context.Context, contentId int64, deletedBy int64) error {
	err := g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		var content entity.Contents
		if err := dao.Contents.Ctx(ctx).TX(tx).WherePri(contentId).LockUpdate().Scan(&content); err != nil {
			return err
		}
		if content.Id == 0 {
			return gerror.New("内容不存在")
		}

		// 1. 获取物理主表与动态模型表行
		contentRow, err := dao.Contents.Ctx(ctx).TX(tx).WherePri(contentId).One()
		if err != nil || contentRow.IsEmpty() {
			return gerror.New("内容不存在")
		}
		contentMap := contentRow.Map()

		var (
			dataRow   gdb.Record
			tableName string
		)
		var cModel entity.ContentModels
		_ = dao.ContentModels.Ctx(ctx).TX(tx).WherePri(content.ModelId).Scan(&cModel)
		if cModel.TableName != "" {
			tableName = cModel.TableName
			dataRow, _ = g.DB().Model(tableName).Ctx(ctx).TX(tx).Where("content_id", contentId).One()
		}

		// 2. 获取级联关联数据（保留数据库真实列名）
		commentRows, _ := dao.Comments.Ctx(ctx).TX(tx).Where("content_id", contentId).All()
		termRelRows, _ := dao.TermRelationships.Ctx(ctx).TX(tx).Where("content_id", contentId).All()
		attachRelRows, _ := dao.AttachmentRelations.Ctx(ctx).TX(tx).Where("content_id", contentId).All()
		seoMetaRow, _ := dao.SeoMeta.Ctx(ctx).TX(tx).Where("target_type", "content").Where("target_id", contentId).One()

		var dataMap map[string]interface{}
		if !dataRow.IsEmpty() {
			dataMap = dataRow.Map()
		}

		commentsList := commentRows.List()
		termRelsList := termRelRows.List()
		attachRelsList := attachRelRows.List()
		var seoMetaMap map[string]interface{}
		if !seoMetaRow.IsEmpty() {
			seoMetaMap = seoMetaRow.Map()
		}

		snapshot := model.RecycleBinSnapshot{
			Content: contentMap,
			Data:    dataMap,
			Relations: model.RecycleBinSnapshotRel{
				Comments:            commentsList,
				TermRelationships:   termRelsList,
				AttachmentRelations: attachRelsList,
				SeoMeta:             seoMetaMap,
			},
		}

		snapshotJson, err := json.Marshal(snapshot)
		if err != nil {
			return err
		}

		// 3. 写入或刷新 recycle_bin 记录
		var existing entity.RecycleBin
		_ = dao.RecycleBin.Ctx(ctx).TX(tx).
			Where("target_type", "content").
			Where("target_id", contentId).
			Scan(&existing)

		now := gtime.Now()
		if existing.Id > 0 {
			_, err = dao.RecycleBin.Ctx(ctx).TX(tx).WherePri(existing.Id).Data(g.Map{
				"deleted_by":    deletedBy,
				"original_data": string(snapshotJson),
				"created_at":    now,
			}).Update()
		} else {
			_, err = dao.RecycleBin.Ctx(ctx).TX(tx).Data(g.Map{
				"deleted_by":     deletedBy,
				"target_type":    "content",
				"target_id":      fmt.Sprintf("%d", contentId),
				"original_data":  string(snapshotJson),
				"retention_days": 30,
				"created_at":     now,
			}).Insert()
		}
		if err != nil {
			return err
		}

		// 4. 将内容状态标记为 trash
		_, err = dao.Contents.Ctx(ctx).TX(tx).WherePri(contentId).Data(g.Map{
			"status":     "trash",
			"updated_at": now,
		}).Update()
		if err != nil {
			return err
		}

		return nil
	})
	if err != nil {
		return err
	}

	// 事务提交后执行分类内容计数重算
	termVars, _ := dao.TermRelationships.Ctx(ctx).Where("content_id", contentId).Array("term_id")
	for _, v := range termVars {
		_ = service.Taxonomy().Recount(ctx, v.Int64())
	}

	return nil
}

// Restore 从回收站恢复内容（逆序重建主行、模型动态数据表与全部关联）
func (s *sRecycleBin) Restore(ctx context.Context, recycleId int64) (int64, error) {
	var restoredContentId int64

	err := g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		var record entity.RecycleBin
		if err := dao.RecycleBin.Ctx(ctx).TX(tx).WherePri(recycleId).LockUpdate().Scan(&record); err != nil {
			return err
		}
		if record.Id == 0 {
			return gerror.New("回收站记录不存在")
		}
		if record.TargetType != "content" {
			return gerror.New("暂仅支持 content 类型的恢复")
		}

		var snapshot model.RecycleBinSnapshot
		if err := json.Unmarshal([]byte(record.OriginalData), &snapshot); err != nil {
			return fmt.Errorf("解析回收站快照失败: %w", err)
		}

		contentRow := snapshot.Content
		rawContentId, ok := contentRow["id"]
		if !ok {
			return gerror.New("快照数据不完整（缺少 content.id），无法恢复")
		}
		contentId := int64(rawContentId.(float64))
		restoredContentId = contentId

		// 检查 contents 主表行是否仍在
		var existingContent entity.Contents
		_ = dao.Contents.Ctx(ctx).TX(tx).WherePri(contentId).Scan(&existingContent)

		if existingContent.Id > 0 {
			// 软删除未物理清除：仅翻回快照记录的原状态
			origStatus := "draft"
			if s, ok := contentRow["status"].(string); ok && s != "" && s != "trash" {
				origStatus = s
			}
			_, err := dao.Contents.Ctx(ctx).TX(tx).WherePri(contentId).Data(g.Map{
				"status":     origStatus,
				"updated_at": gtime.Now(),
			}).Update()
			if err != nil {
				return err
			}
		} else {
			// 物理行已清除：按快照完整重建
			slug, _ := contentRow["slug"].(string)
			if slug != "" {
				// 校验 slug 唯一性，若冲突追加 -r{id} 后缀
				count, err := dao.Contents.Ctx(ctx).TX(tx).Where("slug", slug).Count()
				if err != nil {
					return err
				}
				if count > 0 {
					slug = fmt.Sprintf("%s-r%d", slug, contentId)
					contentRow["slug"] = slug
				}
			}

			// 插入主表
			_, err := dao.Contents.Ctx(ctx).TX(tx).Data(contentRow).Insert()
			if err != nil {
				return fmt.Errorf("重建内容主行失败: %w", err)
			}

			// 重建动态模型物理数据表 data_{alias}
			if len(snapshot.Data) > 0 {
				modelId := int64(contentRow["model_id"].(float64))
				var cModel entity.ContentModels
				_ = dao.ContentModels.Ctx(ctx).TX(tx).WherePri(modelId).Scan(&cModel)
				if cModel.TableName != "" {
					_, err = g.DB().Model(cModel.TableName).Ctx(ctx).TX(tx).Data(snapshot.Data).Insert()
					if err != nil {
						return fmt.Errorf("重建模型动态数据行失败: %w", err)
					}
				}
			}

			// 幂等防御：多态表（seo_meta）无外键不随级联清除，可能残留同目标记录，先清后插
			_, _ = dao.SeoMeta.Ctx(ctx).TX(tx).
				Where("target_type", "content").
				Where("target_id", contentId).
				Delete()

			if len(snapshot.Relations.SeoMeta) > 0 {
				_, _ = dao.SeoMeta.Ctx(ctx).TX(tx).Data(snapshot.Relations.SeoMeta).Insert()
			}

			for _, c := range snapshot.Relations.Comments {
				_, _ = dao.Comments.Ctx(ctx).TX(tx).Data(c).Insert()
			}
			for _, tr := range snapshot.Relations.TermRelationships {
				_, _ = dao.TermRelationships.Ctx(ctx).TX(tx).Data(tr).Insert()
			}
			for _, ar := range snapshot.Relations.AttachmentRelations {
				_, _ = dao.AttachmentRelations.Ctx(ctx).TX(tx).Data(ar).Insert()
			}
		}

		// 清理回收站记录
		_, err := dao.RecycleBin.Ctx(ctx).TX(tx).WherePri(recycleId).Delete()
		return err
	})
	if err != nil {
		return 0, err
	}

	// 事务提交后执行分类内容计数重算
	termVars, _ := dao.TermRelationships.Ctx(ctx).Where("content_id", restoredContentId).Array("term_id")
	for _, v := range termVars {
		_ = service.Taxonomy().Recount(ctx, v.Int64())
	}

	return restoredContentId, nil
}

// Purge 彻底物理清除（包括级联关联与多态 SEO 元数据）
func (s *sRecycleBin) Purge(ctx context.Context, recycleId int64) error {
	var affectedTermIds []int64

	err := g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		var record entity.RecycleBin
		if err := dao.RecycleBin.Ctx(ctx).TX(tx).WherePri(recycleId).LockUpdate().Scan(&record); err != nil {
			return err
		}
		if record.Id == 0 {
			return gerror.New("回收站记录不存在")
		}

		if record.TargetType == "content" {
			var snapshot model.RecycleBinSnapshot
			if err := json.Unmarshal([]byte(record.OriginalData), &snapshot); err == nil {
				if contentIdVal, ok := snapshot.Content["id"]; ok {
					contentId := int64(contentIdVal.(float64))

					// 收集分类 ID
					for _, tr := range snapshot.Relations.TermRelationships {
						if termIdVal, ok := tr["term_id"]; ok {
							affectedTermIds = append(affectedTermIds, int64(termIdVal.(float64)))
						}
					}

					// 1. 多态表 seo_meta 无外键，显式物理删除
					_, _ = dao.SeoMeta.Ctx(ctx).TX(tx).
						Where("target_type", "content").
						Where("target_id", contentId).
						Delete()

					// 2. 删除主表行（通过外键级联清除 data_{alias}、评论、分类关系与附件关系）
					_, _ = dao.Contents.Ctx(ctx).TX(tx).WherePri(contentId).Delete()
				}
			}
		}

		// 删除回收站记录本体
		_, err := dao.RecycleBin.Ctx(ctx).TX(tx).WherePri(recycleId).Delete()
		return err
	})
	if err != nil {
		return err
	}

	// 事务提交后执行分类内容计数重算
	for _, termId := range affectedTermIds {
		_ = service.Taxonomy().Recount(ctx, termId)
	}

	return nil
}

// PurgeExpired 批量彻底清除所有超过保留期的回收站记录
func (s *sRecycleBin) PurgeExpired(ctx context.Context) (int, error) {
	ids, err := dao.RecycleBin.Ctx(ctx).
		WhereLT(dao.RecycleBin.Columns().ExpireAt, gtime.Now()).
		Array(dao.RecycleBin.Columns().Id)
	if err != nil {
		return 0, err
	}

	count := 0
	for _, idVal := range ids {
		id := idVal.Int64()
		if err := s.Purge(ctx, id); err == nil {
			count++
		}
	}
	return count, nil
}

