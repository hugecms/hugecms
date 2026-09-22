package v1

import (
	"github.com/gogf/gf/v2/frame/g"
)

type FormSubmitReq struct {
	g.Meta    `path:"/form/submit" method:"post" tags:"前台表单" summary:"前台表单提交"`
	FormId    uint64                 `json:"formId" dc:"表单ID"`
	FormAlias string                 `json:"formAlias" dc:"表单标识"`
	Data      map[string]interface{} `json:"data" v:"required#提交数据不能为空" dc:"表单填写数据"`
}

type FormSubmitRes struct {
	Id      uint64 `json:"id" dc:"提交记录ID"`
	Message string `json:"message" dc:"提示消息"`
}
