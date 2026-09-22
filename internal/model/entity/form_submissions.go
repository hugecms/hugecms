// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package entity

import (
	"github.com/gogf/gf/v2/os/gtime"
)

// FormSubmissions is the golang structure for table form_submissions.
type FormSubmissions struct {
	Id             uint64      `json:"id"             orm:"id"              ` //
	FormId         uint64      `json:"formId"         orm:"form_id"         ` // 关联表单模板ID
	SubmissionData string      `json:"submissionData" orm:"submission_data" ` // 用户提交的具体表单数据（JSON）
	SubmitterIp    string      `json:"submitterIp"    orm:"submitter_ip"    ` // 提交者IP
	UserAgent      string      `json:"userAgent"      orm:"user_agent"      ` // 提交者UA
	CreatedAt      *gtime.Time `json:"createdAt"      orm:"created_at"      ` //
}
