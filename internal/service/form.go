// ================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// You can delete these comments if you wish manually maintain this interface file.
// ================================================================================

package service

import (
	"context"
	"hugecms/internal/model"
)

type (
	IForm interface {
		SearchTemplates(ctx context.Context, in model.FormTemplateSearchInput) (*model.FormTemplateSearchOutput, error)
		GetTemplateById(ctx context.Context, id uint64) (*model.FormTemplateItem, error)
		CreateTemplate(ctx context.Context, in model.FormTemplateCreateInput) (uint64, error)
		UpdateTemplate(ctx context.Context, in model.FormTemplateUpdateInput) error
		DeleteTemplates(ctx context.Context, ids []uint64) error
		Submit(ctx context.Context, in model.FormSubmitInput) (*model.FormSubmitOutput, error)
		SearchSubmissions(ctx context.Context, in model.FormSubmissionSearchInput) (*model.FormSubmissionSearchOutput, error)
		GetSubmissionById(ctx context.Context, id uint64) (*model.FormSubmissionItem, error)
		DeleteSubmissions(ctx context.Context, ids []uint64) error
	}
)

var (
	localForm IForm
)

func Form() IForm {
	if localForm == nil {
		panic("implement not found for interface IForm, forgot register?")
	}
	return localForm
}

func RegisterForm(i IForm) {
	localForm = i
}
