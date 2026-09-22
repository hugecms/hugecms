package portal

import (
	"context"

	"github.com/gogf/gf/v2/net/ghttp"

	v1 "hugecms/api/portal/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var Form = cForm{}

type cForm struct{}

func (c *cForm) Submit(ctx context.Context, req *v1.FormSubmitReq) (res *v1.FormSubmitRes, err error) {
	r := ghttp.RequestFromCtx(ctx)
	clientIp := ""
	userAgent := ""
	if r != nil {
		clientIp = r.GetClientIp()
		userAgent = r.UserAgent()
	}

	out, err := service.Form().Submit(ctx, model.FormSubmitInput{
		FormId:         req.FormId,
		FormAlias:      req.FormAlias,
		SubmissionData: req.Data,
		SubmitterIp:    clientIp,
		UserAgent:      userAgent,
	})
	if err != nil {
		return nil, err
	}

	return &v1.FormSubmitRes{
		Id:      out.Id,
		Message: out.SuccessMessage,
	}, nil
}
