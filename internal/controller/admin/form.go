package admin

import (
	"context"

	v1 "hugecms/api/admin/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Form = cForm{}

type cForm struct{}

// ================= Form Templates =================

func (c *cForm) SearchTemplates(ctx context.Context, req *v1.FormTemplateSearchReq) (res *v1.FormTemplateSearchRes, err error) {
	out, err := service.Form().SearchTemplates(ctx, model.FormTemplateSearchInput{
		Page:     req.Page,
		PageSize: req.PageSize,
		Keyword:  req.Keyword,
		Alias:    req.Alias,
		IsActive: req.IsActive,
	})
	if err != nil {
		return nil, err
	}
	return &v1.FormTemplateSearchRes{
		List:  out.List,
		Total: out.Total,
		Page:  out.Page,
		Size:  out.Size,
	}, nil
}

func (c *cForm) ShowTemplate(ctx context.Context, req *v1.FormTemplateShowReq) (res *v1.FormTemplateShowRes, err error) {
	item, err := service.Form().GetTemplateById(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.FormTemplateShowRes{FormTemplateItem: item}, nil
}

func (c *cForm) CreateTemplate(ctx context.Context, req *v1.FormTemplateCreateReq) (res *v1.FormTemplateCreateRes, err error) {
	id, err := service.Form().CreateTemplate(ctx, model.FormTemplateCreateInput{
		Name:           req.Name,
		Alias:          req.Alias,
		FieldsConfig:   req.FieldsConfig,
		IsActive:       req.IsActive,
		SuccessMessage: req.SuccessMessage,
	})
	if err != nil {
		return nil, err
	}
	return &v1.FormTemplateCreateRes{Id: id}, nil
}

func (c *cForm) UpdateTemplate(ctx context.Context, req *v1.FormTemplateUpdateReq) (res *v1.FormTemplateUpdateRes, err error) {
	err = service.Form().UpdateTemplate(ctx, model.FormTemplateUpdateInput{
		Id:             req.Id,
		Name:           req.Name,
		Alias:          req.Alias,
		FieldsConfig:   req.FieldsConfig,
		IsActive:       req.IsActive,
		SuccessMessage: req.SuccessMessage,
	})
	if err != nil {
		return nil, err
	}
	return &v1.FormTemplateUpdateRes{}, nil
}

func (c *cForm) DeleteTemplates(ctx context.Context, req *v1.FormTemplateDeleteReq) (res *v1.FormTemplateDeleteRes, err error) {
	err = service.Form().DeleteTemplates(ctx, req.Ids)
	if err != nil {
		return nil, err
	}
	return &v1.FormTemplateDeleteRes{}, nil
}

// ================= Form Submissions =================

func (c *cForm) SearchSubmissions(ctx context.Context, req *v1.FormSubmissionSearchReq) (res *v1.FormSubmissionSearchRes, err error) {
	out, err := service.Form().SearchSubmissions(ctx, model.FormSubmissionSearchInput{
		Page:        req.Page,
		PageSize:    req.PageSize,
		FormId:      req.FormId,
		SubmitterIp: req.SubmitterIp,
		CreatedAt:   req.CreatedAt,
	})
	if err != nil {
		return nil, err
	}
	return &v1.FormSubmissionSearchRes{
		List:  out.List,
		Total: out.Total,
		Page:  out.Page,
		Size:  out.Size,
	}, nil
}

func (c *cForm) ShowSubmission(ctx context.Context, req *v1.FormSubmissionShowReq) (res *v1.FormSubmissionShowRes, err error) {
	item, err := service.Form().GetSubmissionById(ctx, req.Id)
	if err != nil {
		return nil, err
	}
	return &v1.FormSubmissionShowRes{FormSubmissionItem: item}, nil
}

func (c *cForm) DeleteSubmissions(ctx context.Context, req *v1.FormSubmissionDeleteReq) (res *v1.FormSubmissionDeleteRes, err error) {
	err = service.Form().DeleteSubmissions(ctx, req.Ids)
	if err != nil {
		return nil, err
	}
	return &v1.FormSubmissionDeleteRes{}, nil
}
