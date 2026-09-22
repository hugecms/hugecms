// =================================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// =================================================================================

package do

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"
)

// FormSubmissions is the golang structure of table form_submissions for DAO operations like Where/Data.
type FormSubmissions struct {
	g.Meta         `orm:"table:form_submissions, do:true"`
	Id             any         //
	FormId         any         // 关联表单模板ID
	SubmissionData any         // 用户提交的具体表单数据（JSON）
	SubmitterIp    any         // 提交者IP
	UserAgent      any         // 提交者UA
	CreatedAt      *gtime.Time //
}
