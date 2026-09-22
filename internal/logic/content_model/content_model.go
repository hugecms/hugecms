package content_model

import (
	"context"
	"fmt"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sContentModel struct{}

func init() {
	service.RegisterContentModel(New())
}

func New() service.IContentModel {
	return &sContentModel{}
}

// Search 分页查询内容模型
func (s *sContentModel) Search(ctx context.Context, in model.ContentModelSearchInput) (*model.ContentModelSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.ContentModels.Ctx(ctx)
	if in.Keyword != "" {
		m = m.Where("name LIKE ? OR alias LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}
	if in.Status != nil {
		m = m.Where("status", *in.Status)
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var list []entity.ContentModels
	if err := m.Page(in.Page, in.PageSize).OrderAsc("sort").OrderAsc("id").Scan(&list); err != nil {
		return nil, err
	}

	items := make([]model.ContentModelItem, 0, len(list))
	for _, item := range list {
		items = append(items, model.ContentModelItem{
			Id:            int64(item.Id),
			Name:          item.Name,
			Alias:         item.Alias,
			TableName:     item.TableName,
			Description:   item.Description,
			IsSystem:      int(item.IsSystem),
			IsCommentable: int(item.IsCommentable),
			Status:        int(item.Status),
			Sort:          item.Sort,
			CreatedAt:     item.CreatedAt,
			UpdatedAt:     item.UpdatedAt,
		})
	}

	return &model.ContentModelSearchOutput{
		List:  items,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// Get 获取模型详情
func (s *sContentModel) Get(ctx context.Context, id int64) (*model.ContentModelItem, error) {
	var item entity.ContentModels
	if err := dao.ContentModels.Ctx(ctx).WherePri(id).Scan(&item); err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("内容模型不存在")
	}

	return &model.ContentModelItem{
		Id:            int64(item.Id),
		Name:          item.Name,
		Alias:         item.Alias,
		TableName:     item.TableName,
		Description:   item.Description,
		IsSystem:      int(item.IsSystem),
		IsCommentable: int(item.IsCommentable),
		Status:        int(item.Status),
		Sort:          item.Sort,
		CreatedAt:     item.CreatedAt,
		UpdatedAt:     item.UpdatedAt,
	}, nil
}

// GetByAlias 根据别名获取模型
func (s *sContentModel) GetByAlias(ctx context.Context, alias string) (*model.ContentModelItem, error) {
	var item entity.ContentModels
	if err := dao.ContentModels.Ctx(ctx).Where("alias", alias).Scan(&item); err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("未找到对应内容模型")
	}

	return &model.ContentModelItem{
		Id:            int64(item.Id),
		Name:          item.Name,
		Alias:         item.Alias,
		TableName:     item.TableName,
		Description:   item.Description,
		IsSystem:      int(item.IsSystem),
		IsCommentable: int(item.IsCommentable),
		Status:        int(item.Status),
		Sort:          item.Sort,
		CreatedAt:     item.CreatedAt,
		UpdatedAt:     item.UpdatedAt,
	}, nil
}

// Create 创建内容模型并自动生成物理数据表 data_{alias}
func (s *sContentModel) Create(ctx context.Context, in model.ContentModelCreateInput) (int64, error) {
	count, err := dao.ContentModels.Ctx(ctx).Where("alias", in.Alias).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("模型别名已存在")
	}

	tableName := "data_" + in.Alias
	now := gtime.Now()
	var newId int64

	err = g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		res, err := dao.ContentModels.Ctx(ctx).TX(tx).Data(g.Map{
			"name":           in.Name,
			"alias":          in.Alias,
			"table_name":     tableName,
			"description":    in.Description,
			"is_system":      0,
			"is_commentable": in.IsCommentable,
			"status":         in.Status,
			"sort":           in.Sort,
			"created_at":     now,
			"updated_at":     now,
		}).Insert()
		if err != nil {
			return err
		}

		newId, err = res.LastInsertId()
		if err != nil {
			return err
		}

		// 创建动态物理数据表 data_{alias}
		ddl := fmt.Sprintf(`CREATE TABLE IF NOT EXISTS %s (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  content_id BIGINT UNSIGNED NOT NULL COMMENT '关联内容主表ID（一对一）',
  _extra JSON NULL DEFAULT NULL COMMENT '预留JSON扩展字段',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY %s_content_id_unique (content_id),
  CONSTRAINT %s_content_id_foreign FOREIGN KEY (content_id) REFERENCES contents (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='%s模型数据表';`,
			tableName, tableName, tableName, in.Name)

		_, err = tx.Exec(ddl)
		return err
	})

	return newId, err
}

// Update 更新内容模型配置（table_name 与 alias 固化不可修改）
func (s *sContentModel) Update(ctx context.Context, in model.ContentModelUpdateInput) error {
	var item entity.ContentModels
	if err := dao.ContentModels.Ctx(ctx).WherePri(in.Id).Scan(&item); err != nil {
		return err
	}
	if item.Id == 0 {
		return gerror.New("内容模型不存在")
	}

	now := gtime.Now()
	_, err := dao.ContentModels.Ctx(ctx).WherePri(in.Id).Data(g.Map{
		"name":           in.Name,
		"description":    in.Description,
		"is_commentable": in.IsCommentable,
		"status":         in.Status,
		"sort":           in.Sort,
		"updated_at":     now,
	}).Update()
	return err
}

// Delete 删除内容模型
func (s *sContentModel) Delete(ctx context.Context, id int64) error {
	var item entity.ContentModels
	if err := dao.ContentModels.Ctx(ctx).WherePri(id).Scan(&item); err != nil {
		return err
	}
	if item.Id == 0 {
		return gerror.New("内容模型不存在")
	}
	if item.IsSystem == 1 {
		return gerror.New("系统内置模型禁止删除")
	}

	// 校验是否仍有内容挂在该模型下
	contentCount, err := dao.Contents.Ctx(ctx).Where("model_id", id).Count()
	if err != nil {
		return err
	}
	if contentCount > 0 {
		return gerror.New("该模型下仍存在内容数据，禁止删除模型")
	}

	return g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		// 删除字段记录
		_, err := dao.ModelFields.Ctx(ctx).TX(tx).Where("model_id", id).Delete()
		if err != nil {
			return err
		}

		// 物理删除数据表
		if item.TableName != "" {
			dropDdl := fmt.Sprintf("DROP TABLE IF EXISTS `%s`", item.TableName)
			if _, err := tx.Exec(dropDdl); err != nil {
				return err
			}
		}

		_, err = dao.ContentModels.Ctx(ctx).TX(tx).WherePri(id).Delete()
		return err
	})
}
