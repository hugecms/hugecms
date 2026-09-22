package form

import (
	"context"
	"encoding/json"
	"fmt"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
	"github.com/gogf/gf/v2/util/gconv"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/do"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sForm struct{}

func init() {
	service.RegisterForm(New())
}

func New() service.IForm {
	return &sForm{}
}

// ================= Form Templates =================

func (s *sForm) SearchTemplates(ctx context.Context, in model.FormTemplateSearchInput) (*model.FormTemplateSearchOutput, error) {
	m := dao.FormTemplates.Ctx(ctx)
	if in.Keyword != "" {
		m = m.WhereLike(dao.FormTemplates.Columns().Name, "%"+in.Keyword+"%")
	}
	if in.Alias != "" {
		m = m.Where(dao.FormTemplates.Columns().Alias, in.Alias)
	}
	if in.IsActive != nil {
		m = m.Where(dao.FormTemplates.Columns().IsActive, *in.IsActive)
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

	var items []entity.FormTemplates
	if err = m.Page(page, size).OrderDesc(dao.FormTemplates.Columns().Id).Scan(&items); err != nil {
		return nil, err
	}

	list := make([]model.FormTemplateItem, len(items))
	for i, item := range items {
		list[i] = model.FormTemplateItem{
			Id:             item.Id,
			Name:           item.Name,
			Alias:          item.Alias,
			FieldsConfig:   item.FieldsConfig,
			SubmitCount:    item.SubmitCount,
			IsActive:       item.IsActive,
			SuccessMessage: item.SuccessMessage,
			CreatedAt:      item.CreatedAt,
			UpdatedAt:      item.UpdatedAt,
		}
	}

	return &model.FormTemplateSearchOutput{
		List:  list,
		Total: total,
		Page:  page,
		Size:  size,
	}, nil
}

func (s *sForm) GetTemplateById(ctx context.Context, id uint64) (*model.FormTemplateItem, error) {
	var item entity.FormTemplates
	err := dao.FormTemplates.Ctx(ctx).WherePri(id).Scan(&item)
	if err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("表单模板不存在")
	}

	return &model.FormTemplateItem{
		Id:             item.Id,
		Name:           item.Name,
		Alias:          item.Alias,
		FieldsConfig:   item.FieldsConfig,
		SubmitCount:    item.SubmitCount,
		IsActive:       item.IsActive,
		SuccessMessage: item.SuccessMessage,
		CreatedAt:      item.CreatedAt,
		UpdatedAt:      item.UpdatedAt,
	}, nil
}

func (s *sForm) CreateTemplate(ctx context.Context, in model.FormTemplateCreateInput) (uint64, error) {
	if in.Alias == "" {
		return 0, gerror.New("表单标识不能为空")
	}

	count, err := dao.FormTemplates.Ctx(ctx).Where(dao.FormTemplates.Columns().Alias, in.Alias).Count()
	if err != nil {
		return 0, err
	}
	if count > 0 {
		return 0, gerror.New(fmt.Sprintf("表单标识 '%s' 已存在", in.Alias))
	}

	fieldsConfig := in.FieldsConfig
	if fieldsConfig == "" {
		fieldsConfig = "[]"
	}
	successMsg := in.SuccessMessage
	if successMsg == "" {
		successMsg = "提交成功！"
	}

	id, err := dao.FormTemplates.Ctx(ctx).InsertAndGetId(do.FormTemplates{
		Name:           in.Name,
		Alias:          in.Alias,
		FieldsConfig:   fieldsConfig,
		SubmitCount:    0,
		IsActive:       in.IsActive,
		SuccessMessage: successMsg,
	})
	if err != nil {
		return 0, err
	}

	return uint64(id), nil
}

func (s *sForm) UpdateTemplate(ctx context.Context, in model.FormTemplateUpdateInput) error {
	var item entity.FormTemplates
	err := dao.FormTemplates.Ctx(ctx).WherePri(in.Id).Scan(&item)
	if err != nil {
		return err
	}
	if item.Id == 0 {
		return gerror.New("表单模板不存在")
	}

	if in.Alias != "" && in.Alias != item.Alias {
		count, err := dao.FormTemplates.Ctx(ctx).
			Where(dao.FormTemplates.Columns().Alias, in.Alias).
			WhereNot(dao.FormTemplates.Columns().Id, in.Id).
			Count()
		if err != nil {
			return err
		}
		if count > 0 {
			return gerror.New(fmt.Sprintf("表单标识 '%s' 已被占用", in.Alias))
		}
	}

	updateData := g.Map{
		dao.FormTemplates.Columns().Name:      in.Name,
		dao.FormTemplates.Columns().IsActive:  in.IsActive,
		dao.FormTemplates.Columns().UpdatedAt: gtime.Now(),
	}
	if in.Alias != "" {
		updateData[dao.FormTemplates.Columns().Alias] = in.Alias
	}
	if in.FieldsConfig != "" {
		updateData[dao.FormTemplates.Columns().FieldsConfig] = in.FieldsConfig
	}
	if in.SuccessMessage != "" {
		updateData[dao.FormTemplates.Columns().SuccessMessage] = in.SuccessMessage
	}

	_, err = dao.FormTemplates.Ctx(ctx).WherePri(in.Id).Update(updateData)
	return err
}

func (s *sForm) DeleteTemplates(ctx context.Context, ids []uint64) error {
	if len(ids) == 0 {
		return nil
	}
	return dao.FormTemplates.Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		// 删除提交记录
		if _, err := dao.FormSubmissions.Ctx(ctx).TX(tx).WhereIn(dao.FormSubmissions.Columns().FormId, ids).Delete(); err != nil {
			return err
		}
		// 删除模板
		_, err := dao.FormTemplates.Ctx(ctx).TX(tx).WhereIn(dao.FormTemplates.Columns().Id, ids).Delete()
		return err
	})
}

// ================= Form Submissions =================

func (s *sForm) Submit(ctx context.Context, in model.FormSubmitInput) (*model.FormSubmitOutput, error) {
	m := dao.FormTemplates.Ctx(ctx)
	if in.FormId > 0 {
		m = m.WherePri(in.FormId)
	} else if in.FormAlias != "" {
		m = m.Where(dao.FormTemplates.Columns().Alias, in.FormAlias)
	} else {
		return nil, gerror.New("请指定表单ID或标识")
	}

	var tpl entity.FormTemplates
	if err := m.Scan(&tpl); err != nil {
		return nil, err
	}
	if tpl.Id == 0 {
		return nil, gerror.New("表单不存在")
	}
	if tpl.IsActive != 1 {
		return nil, gerror.New("该表单已停用，暂不接受提交")
	}

	// 字段规则校验 (如果配置了 required 字段)
	if tpl.FieldsConfig != "" && tpl.FieldsConfig != "[]" {
		var fields []struct {
			Name     string `json:"name"`
			Title    string `json:"title"`
			Required bool   `json:"required"`
		}
		if err := json.Unmarshal([]byte(tpl.FieldsConfig), &fields); err == nil {
			for _, f := range fields {
				if f.Required {
					val, ok := in.SubmissionData[f.Name]
					if !ok || val == nil || gconv.String(val) == "" {
						title := f.Title
						if title == "" {
							title = f.Name
						}
						return nil, gerror.New(fmt.Sprintf("'%s' 为必填项", title))
					}
				}
			}
		}
	}

	subDataJson, err := json.Marshal(in.SubmissionData)
	if err != nil {
		return nil, gerror.New("提交数据格式异常")
	}

	var subId int64
	err = dao.FormTemplates.Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		id, err := dao.FormSubmissions.Ctx(ctx).TX(tx).InsertAndGetId(do.FormSubmissions{
			FormId:         tpl.Id,
			SubmissionData: string(subDataJson),
			SubmitterIp:    in.SubmitterIp,
			UserAgent:      in.UserAgent,
		})
		if err != nil {
			return err
		}
		subId = id

		// 原子递增 submit_count
		_, err = dao.FormTemplates.Ctx(ctx).TX(tx).
			WherePri(tpl.Id).
			Increment(dao.FormTemplates.Columns().SubmitCount, 1)
		return err
	})

	if err != nil {
		return nil, err
	}

	msg := tpl.SuccessMessage
	if msg == "" {
		msg = "提交成功！"
	}

	return &model.FormSubmitOutput{
		Id:             uint64(subId),
		SuccessMessage: msg,
	}, nil
}

func (s *sForm) SearchSubmissions(ctx context.Context, in model.FormSubmissionSearchInput) (*model.FormSubmissionSearchOutput, error) {
	m := dao.FormSubmissions.Ctx(ctx)
	if in.FormId > 0 {
		m = m.Where(dao.FormSubmissions.Columns().FormId, in.FormId)
	}
	if in.SubmitterIp != "" {
		m = m.WhereLike(dao.FormSubmissions.Columns().SubmitterIp, "%"+in.SubmitterIp+"%")
	}
	if in.CreatedAt != "" {
		m = m.WhereLike(dao.FormSubmissions.Columns().CreatedAt, in.CreatedAt+"%")
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

	var items []entity.FormSubmissions
	if err = m.Page(page, size).OrderDesc(dao.FormSubmissions.Columns().Id).Scan(&items); err != nil {
		return nil, err
	}

	// 预加载表单名称
	formIdMap := make(map[uint64]string)
	for _, item := range items {
		formIdMap[item.FormId] = ""
	}
	if len(formIdMap) > 0 {
		var fIds []uint64
		for fId := range formIdMap {
			fIds = append(fIds, fId)
		}
		var tpls []entity.FormTemplates
		_ = dao.FormTemplates.Ctx(ctx).WhereIn(dao.FormTemplates.Columns().Id, fIds).Scan(&tpls)
		for _, tpl := range tpls {
			formIdMap[tpl.Id] = tpl.Name
		}
	}

	list := make([]model.FormSubmissionItem, len(items))
	for i, item := range items {
		list[i] = model.FormSubmissionItem{
			Id:             item.Id,
			FormId:         item.FormId,
			FormName:       formIdMap[item.FormId],
			SubmissionData: item.SubmissionData,
			SubmitterIp:    item.SubmitterIp,
			UserAgent:      item.UserAgent,
			CreatedAt:      item.CreatedAt,
		}
	}

	return &model.FormSubmissionSearchOutput{
		List:  list,
		Total: total,
		Page:  page,
		Size:  size,
	}, nil
}

func (s *sForm) GetSubmissionById(ctx context.Context, id uint64) (*model.FormSubmissionItem, error) {
	var item entity.FormSubmissions
	err := dao.FormSubmissions.Ctx(ctx).WherePri(id).Scan(&item)
	if err != nil {
		return nil, err
	}
	if item.Id == 0 {
		return nil, gerror.New("提交记录不存在")
	}

	var formName string
	var tpl entity.FormTemplates
	if err := dao.FormTemplates.Ctx(ctx).WherePri(item.FormId).Scan(&tpl); err == nil {
		formName = tpl.Name
	}

	return &model.FormSubmissionItem{
		Id:             item.Id,
		FormId:         item.FormId,
		FormName:       formName,
		SubmissionData: item.SubmissionData,
		SubmitterIp:    item.SubmitterIp,
		UserAgent:      item.UserAgent,
		CreatedAt:      item.CreatedAt,
	}, nil
}

func (s *sForm) DeleteSubmissions(ctx context.Context, ids []uint64) error {
	if len(ids) == 0 {
		return nil
	}
	_, err := dao.FormSubmissions.Ctx(ctx).WhereIn(dao.FormSubmissions.Columns().Id, ids).Delete()
	return err
}
