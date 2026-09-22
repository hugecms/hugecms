package model

import "github.com/gogf/gf/v2/os/gtime"

// FormTemplateItem 表单模板条目
type FormTemplateItem struct {
	Id             uint64      `json:"id"`
	Name           string      `json:"name"`
	Alias          string      `json:"alias"`
	FieldsConfig   string      `json:"fields_config"`
	SubmitCount    uint        `json:"submit_count"`
	IsActive       uint        `json:"is_active"`
	SuccessMessage string      `json:"success_message"`
	CreatedAt      *gtime.Time `json:"created_at"`
	UpdatedAt      *gtime.Time `json:"updated_at"`
}

// FormTemplateSearchInput 表单模板检索入参
type FormTemplateSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"page_size"`
	Keyword  string `json:"keyword"`
	Alias    string `json:"alias"`
	IsActive *uint  `json:"is_active"`
}

// FormTemplateSearchOutput 表单模板检索出参
type FormTemplateSearchOutput struct {
	List  []FormTemplateItem `json:"list"`
	Total int                `json:"total"`
	Page  int                `json:"page"`
	Size  int                `json:"size"`
}

// FormTemplateCreateInput 创建表单模板入参
type FormTemplateCreateInput struct {
	Name           string `json:"name"`
	Alias          string `json:"alias"`
	FieldsConfig   string `json:"fields_config"`
	IsActive       uint   `json:"is_active"`
	SuccessMessage string `json:"success_message"`
}

// FormTemplateUpdateInput 更新表单模板入参
type FormTemplateUpdateInput struct {
	Id             uint64 `json:"id"`
	Name           string `json:"name"`
	Alias          string `json:"alias"`
	FieldsConfig   string `json:"fields_config"`
	IsActive       uint   `json:"is_active"`
	SuccessMessage string `json:"success_message"`
}

// FormSubmissionItem 表单提交条目
type FormSubmissionItem struct {
	Id             uint64      `json:"id"`
	FormId         uint64      `json:"form_id"`
	FormName       string      `json:"form_name"`
	SubmissionData string      `json:"submission_data"`
	SubmitterIp    string      `json:"submitter_ip"`
	UserAgent      string      `json:"user_agent"`
	CreatedAt      *gtime.Time `json:"created_at"`
}

// FormSubmissionSearchInput 表单提交检索入参
type FormSubmissionSearchInput struct {
	Page        int    `json:"page"`
	PageSize    int    `json:"page_size"`
	FormId      uint64 `json:"form_id"`
	SubmitterIp string `json:"submitter_ip"`
	CreatedAt   string `json:"created_at"`
}

// FormSubmissionSearchOutput 表单提交检索出参
type FormSubmissionSearchOutput struct {
	List  []FormSubmissionItem `json:"list"`
	Total int                  `json:"total"`
	Page  int                  `json:"page"`
	Size  int                  `json:"size"`
}

// FormSubmitInput 前台表单提交入参
type FormSubmitInput struct {
	FormId         uint64                 `json:"form_id"`
	FormAlias      string                 `json:"form_alias"`
	SubmissionData map[string]interface{} `json:"submission_data"`
	SubmitterIp    string                 `json:"submitter_ip"`
	UserAgent      string                 `json:"user_agent"`
}

// FormSubmitOutput 前台表单提交出参
type FormSubmitOutput struct {
	Id             uint64 `json:"id"`
	SuccessMessage string `json:"success_message"`
}
