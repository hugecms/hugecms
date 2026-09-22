package v1

import (
	"github.com/gogf/gf/v2/frame/g"
)

type ShortLinkRedirectReq struct {
	g.Meta `path:"/s/:code" method:"get" tags:"短链接" summary:"短链接重定向跳转"`
	Code   string `json:"code" in:"path" v:"required#短码不能为空" dc:"短链接代码"`
}

type ShortLinkRedirectRes struct{}
