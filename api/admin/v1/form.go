package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

// ================= Form Templates =================

type FormTemplateSearchReq struct {
	g.Meta   `path:"/formTemplate/search" method:"post" tags:"表单模板模块" summary:"查询表单模板列表"`
	Page     int    `json:"page" d:"1" v:"min:1#页码必须大于0" dc:"当前页码"`
	PageSize int    `json:"pageSize" d:"10" v:"max:100#每页最多100条" dc:"每页条数"`
	Keyword  string `json:"keyword" dc:"名称关键词"`
	Alias    string `json:"alias" dc:"表单标识"`
	IsActive *uint  `json:"isActive" dc:"启用状态"`
}

type FormTemplateSearchRes struct {
	List  []model.FormTemplateItem `json:"list" dc:"数据列表"`
	Total int                      `json:"total" dc:"总记录数"`
	Page  int                      `json:"page" dc:"当前页码"`
	Size  int                      `json:"size" dc:"每页条数"`
}

type FormTemplateShowReq struct {
	g.Meta `path:"/formTemplate/show" method:"get" tags:"表单模板模块" summary:"获取表单模板详情"`
	Id     uint64 `json:"id" in:"query" v:"required#ID不能为空" dc:"模板ID"`
}

type FormTemplateShowRes struct {
	*model.FormTemplateItem
}

type FormTemplateCreateReq struct {
	g.Meta         `path:"/formTemplate/store" method:"post" tags:"表单模板模块" summary:"创建表单模板"`
	Name           string `json:"name" v:"required#表单名称不能为空" dc:"表单名称"`
	Alias          string `json:"alias" v:"required|regex:^[a-z][a-z0-9_]{0,39}$#表单标识必须为英文字符开头且不超过40字符" dc:"表单标识"`
	FieldsConfig   string `json:"fieldsConfig" dc:"字段配置JSON"`
	IsActive       uint   `json:"isActive" d:"1" dc:"是否启用：1是，0否"`
	SuccessMessage string `json:"successMessage" dc:"提交成功提示"`
}

type FormTemplateCreateRes struct {
	Id uint64 `json:"id" dc:"新创建模板ID"`
}

type FormTemplateUpdateReq struct {
	g.Meta         `path:"/formTemplate/update" method:"put" tags:"表单模板模块" summary:"更新表单模板"`
	Id             uint64 `json:"id" in:"query" v:"required#ID不能为空" dc:"模板ID"`
	Name           string `json:"name" v:"required#表单名称不能为空" dc:"表单名称"`
	Alias          string `json:"alias" dc:"表单标识"`
	FieldsConfig   string `json:"fieldsConfig" dc:"字段配置JSON"`
	IsActive       uint   `json:"isActive" dc:"是否启用：1是，0否"`
	SuccessMessage string `json:"successMessage" dc:"提交成功提示"`
}

type FormTemplateUpdateRes struct{}

type FormTemplateDeleteReq struct {
	g.Meta `path:"/formTemplate/destroy" method:"post" tags:"表单模板模块" summary:"删除表单模板"`
	Ids    []uint64 `json:"ids" v:"required#待删除ID列表不能为空" dc:"ID列表"`
}

type FormTemplateDeleteRes struct{}

// ================= Form Submissions =================

type FormSubmissionSearchReq struct {
	g.Meta      `path:"/formSubmission/search" method:"post" tags:"表单提交模块" summary:"查询表单提交列表"`
	Page        int    `json:"page" d:"1" v:"min:1#页码必须大于0" dc:"当前页码"`
	PageSize    int    `json:"pageSize" d:"10" v:"max:100#每页最多100条" dc:"每页条数"`
	FormId      uint64 `json:"formId" dc:"表单ID"`
	SubmitterIp string `json:"submitterIp" dc:"提交者IP"`
	CreatedAt   string `json:"createdAt" dc:"创建日期"`
}

type FormSubmissionSearchRes struct {
	List  []model.FormSubmissionItem `json:"list" dc:"提交数据列表"`
	Total int                        `json:"total" dc:"总记录数"`
	Page  int                        `json:"page" dc:"当前页码"`
	Size  int                        `json:"size" dc:"每页条数"`
}

type FormSubmissionShowReq struct {
	g.Meta `path:"/formSubmission/show" method:"get" tags:"表单提交模块" summary:"获取表单提交详情"`
	Id     uint64 `json:"id" in:"query" v:"required#ID不能为空" dc:"提交ID"`
}

type FormSubmissionShowRes struct {
	*model.FormSubmissionItem
}

type FormSubmissionDeleteReq struct {
	g.Meta `path:"/formSubmission/destroy" method:"post" tags:"表单提交模块" summary:"删除表单提交记录"`
	Ids    []uint64 `json:"ids" v:"required#待删除ID列表不能为空" dc:"ID列表"`
}

type FormSubmissionDeleteRes struct{}
