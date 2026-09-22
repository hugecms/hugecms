package portal

import (
	"context"

	"github.com/gogf/gf/v2/net/ghttp"

	v1 "hugecms/api/portal/v1"
	"hugecms/internal/model"
	"hugecms/internal/service"
)

var ShortLink = cShortLink{}

type cShortLink struct{}

func (c *cShortLink) Redirect(ctx context.Context, req *v1.ShortLinkRedirectReq) (res *v1.ShortLinkRedirectRes, err error) {
	r := ghttp.RequestFromCtx(ctx)
	clientIp := ""
	userAgent := ""
	referer := ""
	if r != nil {
		clientIp = r.GetClientIp()
		userAgent = r.UserAgent()
		referer = r.Referer()
	}

	out, err := service.Marketing().ResolveAndClick(ctx, model.ShortLinkResolveInput{
		ShortCode: req.Code,
		ClickIp:   clientIp,
		UserAgent: userAgent,
		Referer:   referer,
	})
	if err != nil {
		return nil, err
	}

	if r != nil {
		r.Response.RedirectTo(out.TargetUrl)
	}

	return &v1.ShortLinkRedirectRes{}, nil
}
