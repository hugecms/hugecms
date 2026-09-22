package model_field

import (
	"context"
	"fmt"
	"regexp"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sModelField struct{}

func init() {
	service.RegisterModelField(New())
}

func New() service.IModelField {
	return &sModelField{}
}

// List 获取指定模型的所有字段列表
func (s *sModelField) List(ctx context.Context, modelId int64) ([]model.ModelFieldItem, error) {
	var list []entity.ModelFields
	err := dao.ModelFields.Ctx(ctx).
		Where("model_id", modelId).
		OrderAsc("sort_order").
		OrderAsc("id").
		Scan(&list)
	if err != nil {
		return nil, err
	}

	items := make([]model.ModelFieldItem, 0, len(list))
	for _, f := range list {
		items = append(items, model.ModelFieldItem{
			Id:              int64(f.Id),
			ModelId:         int64(f.ModelId),
			FieldName:       f.FieldName,
			ColumnName:      f.ColumnName,
			FieldLabel:      f.FieldLabel,
			FieldType:       f.FieldType,
			ColumnType:      f.ColumnType,
			DefaultValue:    f.DefaultValue,
			IsRequired:      int(f.IsRequired),
			IsUnique:        int(f.IsUnique),
			ValidationRules: f.ValidationRules,
			ExtraConfig:     f.ExtraConfig,
			SortOrder:       f.SortOrder,
			CreatedAt:       f.CreatedAt,
			UpdatedAt:       f.UpdatedAt,
		})
	}
	return items, nil
}

// Get 获取单个字段详情
func (s *sModelField) Get(ctx context.Context, id int64) (*model.ModelFieldItem, error) {
	var f entity.ModelFields
	if err := dao.ModelFields.Ctx(ctx).WherePri(id).Scan(&f); err != nil {
		return nil, err
	}
	if f.Id == 0 {
		return nil, gerror.New("模型字段不存在")
	}

	return &model.ModelFieldItem{
		Id:              int64(f.Id),
		ModelId:         int64(f.ModelId),
		FieldName:       f.FieldName,
		ColumnName:      f.ColumnName,
		FieldLabel:      f.FieldLabel,
		FieldType:       f.FieldType,
		ColumnType:      f.ColumnType,
		DefaultValue:    f.DefaultValue,
		IsRequired:      int(f.IsRequired),
		IsUnique:        int(f.IsUnique),
		ValidationRules: f.ValidationRules,
		ExtraConfig:     f.ExtraConfig,
		SortOrder:       f.SortOrder,
		CreatedAt:       f.CreatedAt,
		UpdatedAt:       f.UpdatedAt,
	}, nil
}

// Create 新增模型字段并同步执行物理表 DDL
func (s *sModelField) Create(ctx context.Context, in model.ModelFieldCreateInput) (int64, error) {
	var contentModel entity.ContentModels
	if err := dao.ContentModels.Ctx(ctx).WherePri(in.ModelId).Scan(&contentModel); err != nil {
		return 0, err
	}
	if contentModel.Id == 0 {
		return 0, gerror.New("所属内容模型不存在")
	}

	// 校验业务字段英文名唯一性
	count, err := dao.ModelFields.Ctx(ctx).
		Where("model_id", in.ModelId).
		Where("field_name", in.FieldName).
		Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New("该模型下已存在同名字段")
	}

	// 计算数据库列定义
	columnType, ddlColumnDef := resolveColumnDDL(in.FieldType, in.ColumnType, in.DefaultValue)

	now := gtime.Now()
	var newFieldId int64

	err = g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		fieldData := g.Map{
			"model_id":      in.ModelId,
			"field_name":    in.FieldName,
			"column_name":   "", // 占位
			"field_label":   in.FieldLabel,
			"field_type":    in.FieldType,
			"column_type":   columnType,
			"default_value": in.DefaultValue,
			"is_required":   in.IsRequired,
			"is_unique":     in.IsUnique,
			"sort_order":    in.SortOrder,
			"created_at":    now,
			"updated_at":    now,
		}
		if in.ValidationRules != "" {
			fieldData["validation_rules"] = in.ValidationRules
		} else {
			fieldData["validation_rules"] = nil
		}
		if in.ExtraConfig != "" {
			fieldData["extra_config"] = in.ExtraConfig
		} else {
			fieldData["extra_config"] = nil
		}

		res, err := dao.ModelFields.Ctx(ctx).TX(tx).Data(fieldData).Insert()
		if err != nil {
			return err
		}

		newFieldId, err = res.LastInsertId()
		if err != nil {
			return err
		}

		columnName := fmt.Sprintf("field_%d", newFieldId)
		_, err = dao.ModelFields.Ctx(ctx).TX(tx).WherePri(newFieldId).Data(g.Map{
			"column_name": columnName,
		}).Update()
		if err != nil {
			return err
		}

		// 向对应模型数据表执行 ALTER TABLE ADD COLUMN
		if contentModel.TableName != "" {
			alterSql := fmt.Sprintf("ALTER TABLE `%s` ADD COLUMN `%s` %s COMMENT '%s'",
				contentModel.TableName, columnName, ddlColumnDef, escapeSql(in.FieldLabel))
			if _, err := tx.Exec(alterSql); err != nil {
				return fmt.Errorf("执行物理表字段同步失败: %w", err)
			}
		}

		return nil
	})

	return newFieldId, err
}

// Update 更新模型字段元数据
func (s *sModelField) Update(ctx context.Context, in model.ModelFieldUpdateInput) error {
	var f entity.ModelFields
	if err := dao.ModelFields.Ctx(ctx).WherePri(in.Id).Scan(&f); err != nil {
		return err
	}
	if f.Id == 0 {
		return gerror.New("模型字段不存在")
	}

	now := gtime.Now()
	updateData := g.Map{
		"field_label":   in.FieldLabel,
		"default_value": in.DefaultValue,
		"is_required":   in.IsRequired,
		"sort_order":    in.SortOrder,
		"updated_at":    now,
	}
	if in.ValidationRules != "" {
		updateData["validation_rules"] = in.ValidationRules
	} else {
		updateData["validation_rules"] = nil
	}
	if in.ExtraConfig != "" {
		updateData["extra_config"] = in.ExtraConfig
	} else {
		updateData["extra_config"] = nil
	}

	_, err := dao.ModelFields.Ctx(ctx).WherePri(in.Id).Data(updateData).Update()
	return err
}

// Delete 删除模型字段并同步从物理表中移除该列
func (s *sModelField) Delete(ctx context.Context, id int64) error {
	var f entity.ModelFields
	if err := dao.ModelFields.Ctx(ctx).WherePri(id).Scan(&f); err != nil {
		return err
	}
	if f.Id == 0 {
		return gerror.New("模型字段不存在")
	}

	var contentModel entity.ContentModels
	if err := dao.ContentModels.Ctx(ctx).WherePri(f.ModelId).Scan(&contentModel); err != nil {
		return err
	}

	return g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		// 从数据表中移除该物理列
		if contentModel.TableName != "" && f.ColumnName != "" {
			alterSql := fmt.Sprintf("ALTER TABLE `%s` DROP COLUMN `%s`", contentModel.TableName, f.ColumnName)
			_, _ = tx.Exec(alterSql) // 忽略列若不存在的报错
		}

		_, err := dao.ModelFields.Ctx(ctx).TX(tx).WherePri(id).Delete()
		return err
	})
}

func resolveColumnDDL(fieldType, customColType, defVal string) (columnType string, ddlDef string) {
	if customColType != "" {
		columnType = customColType
	} else {
		switch fieldType {
		case "rich_text", "markdown":
			columnType = "longtext"
		case "textarea":
			columnType = "text"
		case "number", "integer":
			columnType = "int"
		case "decimal":
			columnType = "decimal(10,2)"
		case "date", "datetime":
			columnType = "datetime"
		case "switch":
			columnType = "tinyint(1)"
		case "checkbox", "json":
			columnType = "json"
		default:
			columnType = "varchar(255)"
		}
	}

	switch {
	case columnType == "longtext":
		ddlDef = "LONGTEXT NULL DEFAULT NULL"
	case columnType == "text":
		ddlDef = "TEXT NULL DEFAULT NULL"
	case columnType == "json":
		ddlDef = "JSON NULL DEFAULT NULL"
	case columnType == "datetime":
		ddlDef = "DATETIME NULL DEFAULT NULL"
	case columnType == "int" || columnType == "integer":
		ddlDef = "INT NOT NULL DEFAULT 0"
	case columnType == "tinyint(1)":
		ddlDef = "TINYINT(1) UNSIGNED NOT NULL DEFAULT 0"
	case regexp.MustCompile(`^decimal\(\d+,\d+\)$`).MatchString(columnType):
		ddlDef = columnType + " NULL DEFAULT NULL"
	case regexp.MustCompile(`^varchar\(\d+\)$`).MatchString(columnType):
		ddlDef = columnType + " NOT NULL DEFAULT ''"
	default:
		ddlDef = "VARCHAR(255) NOT NULL DEFAULT ''"
	}
	return columnType, ddlDef
}

func escapeSql(s string) string {
	return regexp.MustCompile(`['"\\]`).ReplaceAllString(s, "")
}
