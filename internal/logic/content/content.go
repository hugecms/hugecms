package content

import (
	"context"
	"fmt"
	"time"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
	"github.com/gogf/gf/v2/util/grand"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sContent struct{}

func init() {
	service.RegisterContent(New())
}

func New() service.IContent {
	return &sContent{}
}

// Search 分页多维检索内容
func (s *sContent) Search(ctx context.Context, in model.ContentSearchInput) (*model.ContentSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.Contents.Ctx(ctx)

	if in.ModelId > 0 {
		m = m.Where("model_id", in.ModelId)
	}
	if in.Status != "" {
		m = m.Where("status", in.Status)
	}
	if in.AuditStatus != "" {
		m = m.Where("audit_status", in.AuditStatus)
	}
	if in.Visibility != "" {
		m = m.Where("visibility", in.Visibility)
	}
	if in.AuthorId > 0 {
		m = m.Where("author_id", in.AuthorId)
	}
	if in.IsTop != nil {
		m = m.Where("is_top", *in.IsTop)
	}
	if in.Keyword != "" {
		m = m.Where("title LIKE ? OR slug LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}
	if in.TermId > 0 {
		subQuery := dao.TermRelationships.Ctx(ctx).Fields("content_id").Where("term_id", in.TermId)
		m = m.WhereIn("id", subQuery)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var contents []entity.Contents
	err = m.Page(in.Page, in.PageSize).
		OrderDesc("is_top").
		OrderDesc("sort").
		OrderDesc("id").
		Scan(&contents)
	if err != nil {
		return nil, err
	}

	contentIds := make([]uint64, 0, len(contents))
	modelIds := make([]uint64, 0, len(contents))
	authorIds := make([]uint64, 0, len(contents))
	for _, c := range contents {
		contentIds = append(contentIds, c.Id)
		modelIds = append(modelIds, c.ModelId)
		if c.AuthorId > 0 {
			authorIds = append(authorIds, c.AuthorId)
		}
	}

	// 预加载模型名称
	modelMap := make(map[uint64]string)
	if len(modelIds) > 0 {
		var models []entity.ContentModels
		_ = dao.ContentModels.Ctx(ctx).WhereIn("id", modelIds).Scan(&models)
		for _, cm := range models {
			modelMap[cm.Id] = cm.Name
		}
	}

	// 预加载作者名称
	authorMap := make(map[uint64]string)
	if len(authorIds) > 0 {
		type UserRow struct {
			Id   uint64 `json:"id"`
			Name string `json:"name"`
		}
		var users []UserRow
		_ = dao.Users.Ctx(ctx).Fields("id, name").WhereIn("id", authorIds).Scan(&users)
		for _, u := range users {
			authorMap[u.Id] = u.Name
		}
	}

	// 预加载分类标签名
	termMap := make(map[uint64][]string)
	if len(contentIds) > 0 {
		type TermRelRow struct {
			ContentId uint64 `json:"content_id"`
			Name      string `json:"name"`
		}
		var termRows []TermRelRow
		_ = dao.TermRelationships.Ctx(ctx).
			Fields("term_relationships.content_id, terms.name").
			LeftJoin("terms", "terms.id = term_relationships.term_id").
			WhereIn("term_relationships.content_id", contentIds).
			Scan(&termRows)
		for _, tr := range termRows {
			termMap[tr.ContentId] = append(termMap[tr.ContentId], tr.Name)
		}
	}

	list := make([]model.ContentListItem, 0, len(contents))
	for _, c := range contents {
		list = append(list, model.ContentListItem{
			Id:           int64(c.Id),
			ModelId:      int64(c.ModelId),
			ModelName:    modelMap[c.ModelId],
			Title:        c.Title,
			Slug:         c.Slug,
			AuthorId:     int64(c.AuthorId),
			AuthorName:   authorMap[c.AuthorId],
			Status:       c.Status,
			Visibility:   c.Visibility,
			Views:        int64(c.Views),
			CommentCount: int(c.CommentCount),
			Sort:         c.Sort,
			IsTop:        int(c.IsTop),
			PublishedAt:  c.PublishedAt,
			AuditStatus:  c.AuditStatus,
			AuditRemark:  c.AuditRemark,
			AuditorId:    int64(c.AuditorId),
			AuditedAt:    c.AuditedAt,
			TermNames:    termMap[c.Id],
			CreatedAt:    c.CreatedAt,
			UpdatedAt:    c.UpdatedAt,
		})
	}

	return &model.ContentSearchOutput{
		List:  list,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// Get 获取内容详情（聚合主表、动态模型数据、分类与SEO）
func (s *sContent) Get(ctx context.Context, id int64) (*model.ContentDetailOutput, error) {
	var c entity.Contents
	if err := dao.Contents.Ctx(ctx).WherePri(id).Scan(&c); err != nil {
		return nil, err
	}
	if c.Id == 0 {
		return nil, gerror.New("内容不存在")
	}

	var cModel entity.ContentModels
	_ = dao.ContentModels.Ctx(ctx).WherePri(c.ModelId).Scan(&cModel)

	// 1. 读取动态数据表 data_{alias}
	fieldsData := make(map[string]interface{})
	if cModel.TableName != "" {
		// 查出该模型的字段映射 (column_name -> field_name)
		var modelFields []entity.ModelFields
		_ = dao.ModelFields.Ctx(ctx).Where("model_id", c.ModelId).Scan(&modelFields)
		colToField := make(map[string]string)
		for _, mf := range modelFields {
			colToField[mf.ColumnName] = mf.FieldName
		}

		dataRow, _ := g.DB().Model(cModel.TableName).Ctx(ctx).Where("content_id", id).One()
		if !dataRow.IsEmpty() {
			for col, val := range dataRow.Map() {
				if fieldName, ok := colToField[col]; ok {
					fieldsData[fieldName] = val
				} else {
					fieldsData[col] = val
				}
			}
		}
	}

	// 2. 读取分类 ID 列表
	termIdVars, _ := dao.TermRelationships.Ctx(ctx).Where("content_id", id).Array("term_id")
	termIds := make([]int64, 0, len(termIdVars))
	for _, v := range termIdVars {
		termIds = append(termIds, v.Int64())
	}

	// 3. 读取 SEO 元数据
	var seo entity.SeoMeta
	_ = dao.SeoMeta.Ctx(ctx).Where("target_type", "content").Where("target_id", id).Scan(&seo)
	var seoItem *model.SeoMetaItem
	if seo.Id > 0 {
		seoItem = &model.SeoMetaItem{
			Id:           int64(seo.Id),
			TargetType:   seo.TargetType,
			TargetId:     int64(seo.TargetId),
			Title:        seo.Title,
			Keywords:     seo.Keywords,
			Description:  seo.Description,
			CanonicalUrl: seo.CanonicalUrl,
			Robots:       seo.Robots,
			CreatedAt:    seo.CreatedAt,
			UpdatedAt:    seo.UpdatedAt,
		}
	}

	// 作者名称
	var authorName string
	if c.AuthorId > 0 {
		authorVal, _ := dao.Users.Ctx(ctx).WherePri(c.AuthorId).Value("name")
		authorName = authorVal.String()
	}

	return &model.ContentDetailOutput{
		Id:           int64(c.Id),
		ModelId:      int64(c.ModelId),
		ModelAlias:   cModel.Alias,
		Title:        c.Title,
		Slug:         c.Slug,
		AuthorId:     int64(c.AuthorId),
		AuthorName:   authorName,
		Status:       c.Status,
		Visibility:   c.Visibility,
		Password:     c.Password,
		Views:        int64(c.Views),
		CommentCount: int(c.CommentCount),
		Sort:         c.Sort,
		IsTop:        int(c.IsTop),
		PublishedAt:  c.PublishedAt,
		AuditStatus:  c.AuditStatus,
		AuditRemark:  c.AuditRemark,
		Fields:       fieldsData,
		TermIds:      termIds,
		SeoMeta:      seoItem,
		CreatedAt:    c.CreatedAt,
		UpdatedAt:    c.UpdatedAt,
	}, nil
}

// Save 新建或更新内容主业务（三维状态机流转与动态数据表同步）
func (s *sContent) Save(ctx context.Context, in model.ContentSaveInput) (int64, error) {
	var cModel entity.ContentModels
	if err := dao.ContentModels.Ctx(ctx).WherePri(in.ModelId).Scan(&cModel); err != nil {
		return 0, err
	}
	if cModel.Id == 0 {
		return 0, gerror.New("所属内容模型不存在")
	}

	// 校验 slug 唯一性
	if in.Slug == "" {
		in.Slug = fmt.Sprintf("%d-%s", time.Now().Unix(), grand.S(6))
	}
	slugCount, err := dao.Contents.Ctx(ctx).Where("slug", in.Slug).WhereNot("id", in.Id).Count()
	if err != nil {
		return 0, err
	}
	if slugCount > 0 {
		return 0, gerror.New("URL别名(slug)已被占用，请使用其他别名")
	}

	// 状态机处理
	localCtx := service.Context().Get(ctx)
	var currentUserId int64
	hasPublishPerm := false
	if localCtx != nil && localCtx.User != nil {
		currentUserId = localCtx.User.Id
		hasPublishPerm = localCtx.User.IsSuper || localCtx.User.HasPermission("content:publish")
	}

	if in.Status == "" {
		in.Status = "draft"
	}
	if in.Visibility == "" {
		in.Visibility = "public"
	}

	var (
		auditStatus = "pending"
		publishedAt = in.PublishedAt
		now         = gtime.Now()
	)

	// 若拥有发布权限且目标状态为 published，自动免审通过
	if hasPublishPerm && in.Status == "published" {
		auditStatus = "approved"
		if publishedAt == nil {
			publishedAt = now
		}
	} else if in.Status == "draft" {
		auditStatus = "pending"
	}

	var contentId = in.Id
	err = g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		mainData := g.Map{
			"model_id":     in.ModelId,
			"title":        in.Title,
			"slug":         in.Slug,
			"status":       in.Status,
			"visibility":   in.Visibility,
			"password":     in.Password,
			"sort":         in.Sort,
			"is_top":       in.IsTop,
			"published_at": publishedAt,
			"audit_status": auditStatus,
			"updated_at":   now,
		}

		if in.Id > 0 {
			_, err := dao.Contents.Ctx(ctx).TX(tx).WherePri(in.Id).Data(mainData).Update()
			if err != nil {
				return err
			}
		} else {
			mainData["author_id"] = currentUserId
			mainData["views"] = 0
			mainData["comment_count"] = 0
			mainData["created_at"] = now
			res, err := dao.Contents.Ctx(ctx).TX(tx).Data(mainData).Insert()
			if err != nil {
				return err
			}
			contentId, err = res.LastInsertId()
			if err != nil {
				return err
			}
		}

		// 1. 同步保存物理动态模型表行 data_{alias}
		if cModel.TableName != "" {
			var modelFields []entity.ModelFields
			_ = dao.ModelFields.Ctx(ctx).TX(tx).Where("model_id", in.ModelId).Scan(&modelFields)

			fieldToCol := make(map[string]string)
			for _, mf := range modelFields {
				fieldToCol[mf.FieldName] = mf.ColumnName
			}

			dynamicData := g.Map{
				"content_id": contentId,
				"updated_at": now,
			}
			for fName, fVal := range in.Fields {
				if col, ok := fieldToCol[fName]; ok {
					dynamicData[col] = fVal
				}
			}

			// 检查动态数据行是否存在
			existsCount, err := g.DB().Model(cModel.TableName).Ctx(ctx).TX(tx).Where("content_id", contentId).Count()
			if err != nil {
				return err
			}

			if existsCount > 0 {
				_, err = g.DB().Model(cModel.TableName).Ctx(ctx).TX(tx).Where("content_id", contentId).Data(dynamicData).Update()
			} else {
				dynamicData["created_at"] = now
				_, err = g.DB().Model(cModel.TableName).Ctx(ctx).TX(tx).Data(dynamicData).Insert()
			}
			if err != nil {
				return fmt.Errorf("保存动态模型数据行失败: %w", err)
			}
		}

		// 2. 同步分类关联关系 term_relationships
		if in.TermIds != nil {
			// 先查出当前关联的旧分类，以便重算计数
			oldTermVars, _ := dao.TermRelationships.Ctx(ctx).TX(tx).Where("content_id", contentId).Array("term_id")
			_, err = dao.TermRelationships.Ctx(ctx).TX(tx).Where("content_id", contentId).Delete()
			if err != nil {
				return err
			}

			for idx, termId := range in.TermIds {
				_, err = dao.TermRelationships.Ctx(ctx).TX(tx).Data(g.Map{
					"content_id": contentId,
					"term_id":    termId,
					"sort":       idx + 1,
					"created_at": now,
				}).Insert()
				if err != nil {
					return err
				}
			}

			// 联动重算新旧分类内容数
			for _, v := range oldTermVars {
				_ = service.Taxonomy().Recount(ctx, v.Int64())
			}
			for _, termId := range in.TermIds {
				_ = service.Taxonomy().Recount(ctx, termId)
			}
		}

		// 3. 同步多态 SEO 元数据 seo_meta
		if in.SeoMeta != nil {
			_, _ = dao.SeoMeta.Ctx(ctx).TX(tx).
				Where("target_type", "content").
				Where("target_id", contentId).
				Delete()

			_, err = dao.SeoMeta.Ctx(ctx).TX(tx).Data(g.Map{
				"target_type":   "content",
				"target_id":     contentId,
				"title":         in.SeoMeta.Title,
				"keywords":      in.SeoMeta.Keywords,
				"description":   in.SeoMeta.Description,
				"canonical_url": in.SeoMeta.CanonicalUrl,
				"robots":        in.SeoMeta.Robots,
				"created_at":    now,
				"updated_at":    now,
			}).Insert()
			if err != nil {
				return err
			}
		}

		return nil
	})
	if err != nil {
		return 0, err
	}

	// 事务提交后联动重算分类内容计数
	if in.TermIds != nil {
		for _, termId := range in.TermIds {
			_ = service.Taxonomy().Recount(ctx, termId)
		}
	}

	return contentId, nil
}

// Audit 内容审核流转
func (s *sContent) Audit(ctx context.Context, in model.ContentAuditInput) error {
	var c entity.Contents
	if err := dao.Contents.Ctx(ctx).WherePri(in.Id).Scan(&c); err != nil {
		return err
	}
	if c.Id == 0 {
		return gerror.New("内容不存在")
	}

	localCtx := service.Context().Get(ctx)
	var auditorId int64
	if localCtx != nil && localCtx.User != nil {
		auditorId = localCtx.User.Id
	}

	now := gtime.Now()
	updateData := g.Map{
		"audit_status": in.AuditStatus,
		"audit_remark": in.AuditRemark,
		"auditor_id":   auditorId,
		"audited_at":   now,
		"updated_at":   now,
	}

	if in.AuditStatus == "approved" {
		// 审核通过：若处于 pending 状态，立即置为 published
		if c.Status == "pending" || c.Status == "draft" {
			updateData["status"] = "published"
			if c.PublishedAt == nil {
				updateData["published_at"] = now
			}
		}
	} else if in.AuditStatus == "rejected" {
		// 审核驳回：退回草稿
		updateData["status"] = "draft"
	}

	err := g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		_, err := dao.Contents.Ctx(ctx).TX(tx).WherePri(in.Id).Data(updateData).Update()
		return err
	})
	if err != nil {
		return err
	}

	// 事务提交后联动重算分类内容计数
	termVars, _ := dao.TermRelationships.Ctx(ctx).Where("content_id", in.Id).Array("term_id")
	for _, v := range termVars {
		_ = service.Taxonomy().Recount(ctx, v.Int64())
	}

	return nil
}

// ChangeStatus 快速流转内容状态（published, archived, draft 等）
func (s *sContent) ChangeStatus(ctx context.Context, in model.ContentStatusInput) error {
	var c entity.Contents
	if err := dao.Contents.Ctx(ctx).WherePri(in.Id).Scan(&c); err != nil {
		return err
	}
	if c.Id == 0 {
		return gerror.New("内容不存在")
	}

	now := gtime.Now()
	updateData := g.Map{
		"status":     in.Status,
		"updated_at": now,
	}
	if in.Status == "published" && c.PublishedAt == nil {
		updateData["published_at"] = now
	}

	err := g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		_, err := dao.Contents.Ctx(ctx).TX(tx).WherePri(in.Id).Data(updateData).Update()
		return err
	})
	if err != nil {
		return err
	}

	// 事务提交后联动重算分类内容计数
	termVars, _ := dao.TermRelationships.Ctx(ctx).Where("content_id", in.Id).Array("term_id")
	for _, v := range termVars {
		_ = service.Taxonomy().Recount(ctx, v.Int64())
	}

	return nil
}

// Trash 移入回收站
func (s *sContent) Trash(ctx context.Context, id int64) error {
	localCtx := service.Context().Get(ctx)
	var deletedBy int64
	if localCtx != nil && localCtx.User != nil {
		deletedBy = localCtx.User.Id
	}
	return service.RecycleBin().SnapshotAndTrash(ctx, id, deletedBy)
}

// PublishScheduled 自动发布到期且审核通过的内容
func (s *sContent) PublishScheduled(ctx context.Context) (int, error) {
	now := gtime.Now()
	var contents []entity.Contents
	err := dao.Contents.Ctx(ctx).
		Where("status", "pending").
		Where("audit_status", "approved").
		WhereLTE("published_at", now).
		Scan(&contents)
	if err != nil {
		return 0, err
	}

	count := 0
	for _, c := range contents {
		err := s.ChangeStatus(ctx, model.ContentStatusInput{
			Id:     int64(c.Id),
			Status: "published",
		})
		if err == nil {
			count++
		}
	}
	return count, nil
}

