package taxonomy

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sTaxonomy struct{}

func init() {
	service.RegisterTaxonomy(New())
}

func New() service.ITaxonomy {
	return &sTaxonomy{}
}

// SearchTaxonomy 分页查询分类法
func (s *sTaxonomy) SearchTaxonomy(ctx context.Context, in model.TaxonomySearchInput) (*model.TaxonomySearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.Taxonomies.Ctx(ctx)
	if in.Keyword != "" {
		m = m.Where("name LIKE ? OR alias LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}
	if in.ModelId != nil {
		m = m.Where("model_id", *in.ModelId)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var list []entity.Taxonomies
	if err := m.Page(in.Page, in.PageSize).OrderAsc("id").Scan(&list); err != nil {
		return nil, err
	}

	items := make([]model.TaxonomyItem, 0, len(list))
	for _, item := range list {
		items = append(items, model.TaxonomyItem{
			Id:             int64(item.Id),
			Name:           item.Name,
			Alias:          item.Alias,
			ModelId:        int64(item.ModelId),
			IsHierarchical: int(item.IsHierarchical),
			Description:    item.Description,
			CreatedAt:      item.CreatedAt,
			UpdatedAt:      item.UpdatedAt,
		})
	}

	return &model.TaxonomySearchOutput{
		List:  items,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// GetTaxonomy 获取分类法详情
func (s *sTaxonomy) GetTaxonomy(ctx context.Context, id int64) (*model.TaxonomyItem, error) {
	var item entity.Taxonomies
	if err := dao.Taxonomies.Ctx(ctx).WherePri(id).Scan(&item); err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("分类法不存在")
	}

	return &model.TaxonomyItem{
		Id:             int64(item.Id),
		Name:           item.Name,
		Alias:          item.Alias,
		ModelId:        int64(item.ModelId),
		IsHierarchical: int(item.IsHierarchical),
		Description:    item.Description,
		CreatedAt:      item.CreatedAt,
		UpdatedAt:      item.UpdatedAt,
	}, nil
}

// SaveTaxonomy 保存分类法（新增或更新）
func (s *sTaxonomy) SaveTaxonomy(ctx context.Context, in model.TaxonomySaveInput) (int64, error) {
	count, err := dao.Taxonomies.Ctx(ctx).Where("alias", in.Alias).WhereNot("id", in.Id).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("分类法别名已被占用")
	}

	now := gtime.Now()
	data := g.Map{
		"name":            in.Name,
		"alias":           in.Alias,
		"model_id":        in.ModelId,
		"is_hierarchical": in.IsHierarchical,
		"description":     in.Description,
		"updated_at":      now,
	}

	if in.Id > 0 {
		_, err := dao.Taxonomies.Ctx(ctx).WherePri(in.Id).Data(data).Update()
		return in.Id, err
	}

	data["created_at"] = now
	res, err := dao.Taxonomies.Ctx(ctx).Data(data).Insert()
	if err != nil {
		return 0, err
	}
	return res.LastInsertId()
}

// DeleteTaxonomy 删除分类法
func (s *sTaxonomy) DeleteTaxonomy(ctx context.Context, id int64) error {
	termCount, err := dao.Terms.Ctx(ctx).Where("taxonomy_id", id).Count()
	if err != nil {
		return err
	}
	if termCount > 0 {
		return gerror.New("该分类法下存在词条分类项，请先清空词条后再删除")
	}

	_, err = dao.Taxonomies.Ctx(ctx).WherePri(id).Delete()
	return err
}

// ==================== 词条项 Terms ====================

// GetTermTree 获取分类项树
func (s *sTaxonomy) GetTermTree(ctx context.Context, in model.TermTreeInput) ([]model.TermTreeNode, error) {
	m := dao.Terms.Ctx(ctx).Where("taxonomy_id", in.TaxonomyId)
	if in.Keyword != "" {
		m = m.Where("name LIKE ? OR slug LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}

	var list []entity.Terms
	if err := m.OrderAsc("sort").OrderAsc("id").Scan(&list); err != nil {
		return nil, err
	}

	return buildTermTree(list, 0), nil
}

func buildTermTree(items []entity.Terms, parentId uint64) []model.TermTreeNode {
	var nodes []model.TermTreeNode
	for _, item := range items {
		if item.ParentId == parentId {
			children := buildTermTree(items, item.Id)
			nodes = append(nodes, model.TermTreeNode{
				Id:           int64(item.Id),
				TaxonomyId:   int64(item.TaxonomyId),
				Name:         item.Name,
				Slug:         item.Slug,
				ParentId:     int64(item.ParentId),
				Description:  item.Description,
				Sort:         item.Sort,
				ContentCount: int(item.ContentCount),
				Children:     children,
				CreatedAt:    item.CreatedAt,
			})
		}
	}
	return nodes
}

// GetTerm 获取单个分类项详情
func (s *sTaxonomy) GetTerm(ctx context.Context, id int64) (*model.TermItem, error) {
	var item entity.Terms
	if err := dao.Terms.Ctx(ctx).WherePri(id).Scan(&item); err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("分类项不存在")
	}

	return &model.TermItem{
		Id:           int64(item.Id),
		TaxonomyId:   int64(item.TaxonomyId),
		Name:         item.Name,
		Slug:         item.Slug,
		ParentId:     int64(item.ParentId),
		Description:  item.Description,
		Sort:         item.Sort,
		ContentCount: int(item.ContentCount),
		CreatedAt:    item.CreatedAt,
		UpdatedAt:    item.UpdatedAt,
	}, nil
}

// SaveTerm 新增或更新分类项
func (s *sTaxonomy) SaveTerm(ctx context.Context, in model.TermSaveInput) (int64, error) {
	count, err := dao.Terms.Ctx(ctx).
		Where("taxonomy_id", in.TaxonomyId).
		Where("slug", in.Slug).
		WhereNot("id", in.Id).
		Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("该分类法下已存在相同别名的分类项")
	}

	now := gtime.Now()
	data := g.Map{
		"taxonomy_id": in.TaxonomyId,
		"name":        in.Name,
		"slug":        in.Slug,
		"parent_id":   in.ParentId,
		"description": in.Description,
		"sort":        in.Sort,
		"updated_at":  now,
	}

	if in.Id > 0 {
		_, err := dao.Terms.Ctx(ctx).WherePri(in.Id).Data(data).Update()
		return in.Id, err
	}

	data["content_count"] = 0
	data["created_at"] = now
	res, err := dao.Terms.Ctx(ctx).Data(data).Insert()
	if err != nil {
		return 0, err
	}
	return res.LastInsertId()
}

// DeleteTerm 删除分类项（级联删除子分类与关联关系）
func (s *sTaxonomy) DeleteTerm(ctx context.Context, id int64) error {
	return g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		// 删除与内容的关联
		_, err := dao.TermRelationships.Ctx(ctx).TX(tx).Where("term_id", id).Delete()
		if err != nil {
			return err
		}

		// 级联子项将 parent_id 置为 0 或删除
		_, err = dao.Terms.Ctx(ctx).TX(tx).Where("parent_id", id).Data(g.Map{"parent_id": 0}).Update()
		if err != nil {
			return err
		}

		_, err = dao.Terms.Ctx(ctx).TX(tx).WherePri(id).Delete()
		return err
	})
}

// Recount 重算指定分类项下已发布内容数量
func (s *sTaxonomy) Recount(ctx context.Context, termId int64) error {
	// 统计口径：status='published' AND audit_status='approved'
	count, err := dao.TermRelationships.Ctx(ctx).
		LeftJoin("contents", "contents.id = term_relationships.content_id").
		Where("term_relationships.term_id", termId).
		Where("contents.status", "published").
		Where("contents.audit_status", "approved").
		Count()
	if err != nil {
		return err
	}

	_, err = dao.Terms.Ctx(ctx).WherePri(termId).Data(g.Map{
		"content_count": count,
		"updated_at":    gtime.Now(),
	}).Update()
	return err
}
