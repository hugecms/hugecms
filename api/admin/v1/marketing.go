package v1

import (
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/model"
)

// ================= Ad Positions =================

type AdPositionSearchReq struct {
	g.Meta   `path:"/adPosition/search" method:"post" tags:"广告位管理" summary:"查询广告位列表"`
	Page     int    `json:"page" d:"1" v:"min:1#页码必须大于0" dc:"当前页码"`
	PageSize int    `json:"pageSize" d:"10" v:"max:100#每页最多100条" dc:"每页条数"`
	Keyword  string `json:"keyword" dc:"名称关键字"`
	Status   *uint  `json:"status" dc:"状态：0停用，1启用"`
}

type AdPositionSearchRes struct {
	List  []model.AdPositionItem `json:"list" dc:"列表数据"`
	Total int                    `json:"total" dc:"总记录数"`
	Page  int                    `json:"page" dc:"当前页码"`
	Size  int                    `json:"size" dc:"每页条数"`
}

type AdPositionShowReq struct {
	g.Meta `path:"/adPosition/show" method:"get" tags:"广告位管理" summary:"获取广告位详情"`
	Id     uint64 `json:"id" in:"query" v:"required#ID不能为空" dc:"广告位ID"`
}

type AdPositionShowRes struct {
	*model.AdPositionItem
}

type AdPositionCreateReq struct {
	g.Meta      `path:"/adPosition/store" method:"post" tags:"广告位管理" summary:"创建广告位"`
	Name        string `json:"name" v:"required#名称不能为空" dc:"广告位名称"`
	Code        string `json:"code" v:"required|regex:^[a-z][a-z0-9_]{0,39}$#代码必须由字母开头且不超过40字符" dc:"广告位标识代码"`
	Width       uint   `json:"width" dc:"建议宽度"`
	Height      uint   `json:"height" dc:"建议高度"`
	AdType      string `json:"adType" d:"image" dc:"类型"`
	MaxCount    uint   `json:"maxCount" d:"5" dc:"最大展示数量"`
	Description string `json:"description" dc:"描述"`
	Status      uint   `json:"status" d:"1" dc:"状态：0停用，1启用"`
}

type AdPositionCreateRes struct {
	Id uint64 `json:"id" dc:"新广告位ID"`
}

type AdPositionUpdateReq struct {
	g.Meta      `path:"/adPosition/update" method:"put" tags:"广告位管理" summary:"更新广告位"`
	Id          uint64 `json:"id" in:"query" v:"required#ID不能为空" dc:"广告位ID"`
	Name        string `json:"name" v:"required#名称不能为空" dc:"广告位名称"`
	Code        string `json:"code" dc:"广告位代码"`
	Width       uint   `json:"width" dc:"建议宽度"`
	Height      uint   `json:"height" dc:"建议高度"`
	AdType      string `json:"adType" dc:"类型"`
	MaxCount    uint   `json:"maxCount" dc:"最大展示数量"`
	Description string `json:"description" dc:"描述"`
	Status      uint   `json:"status" dc:"状态"`
}

type AdPositionUpdateRes struct{}

type AdPositionDeleteReq struct {
	g.Meta `path:"/adPosition/destroy" method:"post" tags:"广告位管理" summary:"删除广告位"`
	Ids    []uint64 `json:"ids" v:"required#ID列表不能为空" dc:"待删除ID列表"`
}

type AdPositionDeleteRes struct{}

// ================= Ads =================

type AdSearchReq struct {
	g.Meta     `path:"/ad/search" method:"post" tags:"广告管理" summary:"查询广告列表"`
	Page       int    `json:"page" d:"1" v:"min:1#页码必须大于0" dc:"当前页码"`
	PageSize   int    `json:"pageSize" d:"10" v:"max:100#每页最多100条" dc:"每页条数"`
	PositionId uint64 `json:"positionId" dc:"广告位ID"`
	Keyword    string `json:"keyword" dc:"标题关键字"`
	Status     *uint  `json:"status" dc:"状态"`
}

type AdSearchRes struct {
	List  []model.AdItem `json:"list" dc:"列表数据"`
	Total int            `json:"total" dc:"总记录数"`
	Page  int            `json:"page" dc:"当前页码"`
	Size  int            `json:"size" dc:"每页条数"`
}

type AdShowReq struct {
	g.Meta `path:"/ad/show" method:"get" tags:"广告管理" summary:"获取广告详情"`
	Id     uint64 `json:"id" in:"query" v:"required#ID不能为空" dc:"广告ID"`
}

type AdShowRes struct {
	*model.AdItem
}

type AdCreateReq struct {
	g.Meta       `path:"/ad/store" method:"post" tags:"广告管理" summary:"创建广告"`
	PositionId   uint64      `json:"positionId" v:"required#广告位不能为空" dc:"广告位ID"`
	Title        string      `json:"title" v:"required#标题不能为空" dc:"广告标题"`
	AdType       string      `json:"adType" d:"image" dc:"类型"`
	CoverImage   string      `json:"coverImage" dc:"封面图片URL"`
	Content      string      `json:"content" dc:"广告内容"`
	LinkUrl      string      `json:"linkUrl" dc:"链接地址"`
	LinkTarget   uint        `json:"linkTarget" d:"1" dc:"打开方式：0当前窗口，1新窗口"`
	Sort         int         `json:"sort" d:"0" dc:"排序"`
	StartTime    *gtime.Time `json:"startTime" dc:"投放开始时间"`
	EndTime      *gtime.Time `json:"endTime" dc:"投放结束时间"`
	DisplayLimit uint        `json:"displayLimit" d:"0" dc:"展示上限"`
	ClickLimit   uint        `json:"clickLimit" d:"0" dc:"点击上限"`
	Status       uint        `json:"status" d:"1" dc:"状态"`
}

type AdCreateRes struct {
	Id uint64 `json:"id" dc:"广告ID"`
}

type AdUpdateReq struct {
	g.Meta       `path:"/ad/update" method:"put" tags:"广告管理" summary:"更新广告"`
	Id           uint64      `json:"id" in:"query" v:"required#ID不能为空" dc:"广告ID"`
	PositionId   uint64      `json:"positionId" v:"required#广告位不能为空" dc:"广告位ID"`
	Title        string      `json:"title" v:"required#标题不能为空" dc:"广告标题"`
	AdType       string      `json:"adType" dc:"类型"`
	CoverImage   string      `json:"coverImage" dc:"封面图片URL"`
	Content      string      `json:"content" dc:"广告内容"`
	LinkUrl      string      `json:"linkUrl" dc:"链接地址"`
	LinkTarget   uint        `json:"linkTarget" dc:"打开方式"`
	Sort         int         `json:"sort" dc:"排序"`
	StartTime    *gtime.Time `json:"startTime" dc:"投放开始时间"`
	EndTime      *gtime.Time `json:"endTime" dc:"投放结束时间"`
	DisplayLimit uint        `json:"displayLimit" dc:"展示上限"`
	ClickLimit   uint        `json:"clickLimit" dc:"点击上限"`
	Status       uint        `json:"status" dc:"状态"`
}

type AdUpdateRes struct{}

type AdDeleteReq struct {
	g.Meta `path:"/ad/destroy" method:"post" tags:"广告管理" summary:"删除广告"`
	Ids    []uint64 `json:"ids" v:"required#ID列表不能为空" dc:"待删除ID列表"`
}

type AdDeleteRes struct{}

// ================= Friend Links =================

type FriendLinkSearchReq struct {
	g.Meta   `path:"/friendLink/search" method:"post" tags:"友情链接管理" summary:"查询友链列表"`
	Page     int    `json:"page" d:"1" v:"min:1#页码必须大于0" dc:"当前页码"`
	PageSize int    `json:"pageSize" d:"10" v:"max:100#每页最多100条" dc:"每页条数"`
	Category string `json:"category" dc:"分类"`
	Keyword  string `json:"keyword" dc:"名称关键字"`
	Status   *uint  `json:"status" dc:"状态：0待审，1通过，2拒绝"`
}

type FriendLinkSearchRes struct {
	List  []model.FriendLinkItem `json:"list" dc:"列表数据"`
	Total int                    `json:"total" dc:"总记录数"`
	Page  int                    `json:"page" dc:"当前页码"`
	Size  int                    `json:"size" dc:"每页条数"`
}

type FriendLinkShowReq struct {
	g.Meta `path:"/friendLink/show" method:"get" tags:"友情链接管理" summary:"获取友链详情"`
	Id     uint64 `json:"id" in:"query" v:"required#ID不能为空" dc:"友链ID"`
}

type FriendLinkShowRes struct {
	*model.FriendLinkItem
}

type FriendLinkCreateReq struct {
	g.Meta       `path:"/friendLink/store" method:"post" tags:"友情链接管理" summary:"创建友链"`
	Category     string `json:"category" d:"友情链接" dc:"分类"`
	SiteName     string `json:"siteName" v:"required#站点名称不能为空" dc:"网站名称"`
	SiteUrl      string `json:"siteUrl" v:"required|url#网站URL格式错误" dc:"网站URL"`
	LogoUrl      string `json:"logoUrl" dc:"Logo URL"`
	Description  string `json:"description" dc:"网站描述"`
	ContactEmail string `json:"contactEmail" dc:"联系邮箱"`
	Sort         int    `json:"sort" d:"0" dc:"排序"`
	Status       uint   `json:"status" d:"1" dc:"状态：0待审，1通过，2拒绝"`
}

type FriendLinkCreateRes struct {
	Id uint64 `json:"id" dc:"友链ID"`
}

type FriendLinkUpdateReq struct {
	g.Meta       `path:"/friendLink/update" method:"put" tags:"友情链接管理" summary:"更新友链"`
	Id           uint64 `json:"id" in:"query" v:"required#ID不能为空" dc:"友链ID"`
	Category     string `json:"category" dc:"分类"`
	SiteName     string `json:"siteName" v:"required#站点名称不能为空" dc:"网站名称"`
	SiteUrl      string `json:"siteUrl" v:"required|url#网站URL格式错误" dc:"网站URL"`
	LogoUrl      string `json:"logoUrl" dc:"Logo URL"`
	Description  string `json:"description" dc:"网站描述"`
	ContactEmail string `json:"contactEmail" dc:"联系邮箱"`
	Sort         int    `json:"sort" dc:"排序"`
	Status       uint   `json:"status" dc:"状态"`
}

type FriendLinkUpdateRes struct{}

type FriendLinkDeleteReq struct {
	g.Meta `path:"/friendLink/destroy" method:"post" tags:"友情链接管理" summary:"删除友链"`
	Ids    []uint64 `json:"ids" v:"required#ID列表不能为空" dc:"待删除ID列表"`
}

type FriendLinkDeleteRes struct{}

// ================= Short Links =================

type ShortLinkSearchReq struct {
	g.Meta   `path:"/shortLink/search" method:"post" tags:"短链接管理" summary:"查询短链列表"`
	Page     int    `json:"page" d:"1" v:"min:1#页码必须大于0" dc:"当前页码"`
	PageSize int    `json:"pageSize" d:"10" v:"max:100#每页最多100条" dc:"每页条数"`
	Keyword  string `json:"keyword" dc:"标题或代码"`
	Status   *uint  `json:"status" dc:"状态"`
}

type ShortLinkSearchRes struct {
	List  []model.ShortLinkItem `json:"list" dc:"列表数据"`
	Total int                   `json:"total" dc:"总记录数"`
	Page  int                   `json:"page" dc:"当前页码"`
	Size  int                   `json:"size" dc:"每页条数"`
}

type ShortLinkShowReq struct {
	g.Meta `path:"/shortLink/show" method:"get" tags:"短链接管理" summary:"获取短链详情"`
	Id     uint64 `json:"id" in:"query" v:"required#ID不能为空" dc:"短链ID"`
}

type ShortLinkShowRes struct {
	*model.ShortLinkItem
}

type ShortLinkCreateReq struct {
	g.Meta     `path:"/shortLink/store" method:"post" tags:"短链接管理" summary:"创建短链"`
	ShortCode  string      `json:"shortCode" dc:"短码（留空自动生成）"`
	TargetUrl  string      `json:"targetUrl" v:"required|url#目标URL格式不正确" dc:"跳转目标URL"`
	Title      string      `json:"title" dc:"备注标题"`
	QrCodePath string      `json:"qrCodePath" dc:"二维码图片路径"`
	ExpireAt   *gtime.Time `json:"expireAt" dc:"过期时间"`
	Status     uint        `json:"status" d:"1" dc:"状态"`
}

type ShortLinkCreateRes struct {
	Id uint64 `json:"id" dc:"短链ID"`
}

type ShortLinkUpdateReq struct {
	g.Meta     `path:"/shortLink/update" method:"put" tags:"短链接管理" summary:"更新短链"`
	Id         uint64      `json:"id" in:"query" v:"required#ID不能为空" dc:"短链ID"`
	ShortCode  string      `json:"shortCode" dc:"短码"`
	TargetUrl  string      `json:"targetUrl" v:"required|url#目标URL格式不正确" dc:"跳转目标URL"`
	Title      string      `json:"title" dc:"备注标题"`
	QrCodePath string      `json:"qrCodePath" dc:"二维码图片路径"`
	ExpireAt   *gtime.Time `json:"expireAt" dc:"过期时间"`
	Status     uint        `json:"status" dc:"状态"`
}

type ShortLinkUpdateRes struct{}

type ShortLinkDeleteReq struct {
	g.Meta `path:"/shortLink/destroy" method:"post" tags:"短链接管理" summary:"删除短链"`
	Ids    []uint64 `json:"ids" v:"required#ID列表不能为空" dc:"待删除ID列表"`
}

type ShortLinkDeleteRes struct{}

// ================= Content Push Queue =================

type ContentPushQueueSearchReq struct {
	g.Meta    `path:"/contentPushQueue/search" method:"post" tags:"内容推送队列" summary:"查询推送任务列表"`
	Page      int    `json:"page" d:"1" v:"min:1#页码必须大于0" dc:"当前页码"`
	PageSize  int    `json:"pageSize" d:"10" v:"max:100#每页最多100条" dc:"每页条数"`
	PushType  string `json:"pushType" dc:"推送平台"`
	Status    string `json:"status" dc:"任务状态"`
	ContentId uint64 `json:"contentId" dc:"内容ID"`
}

type ContentPushQueueSearchRes struct {
	List  []model.ContentPushQueueItem `json:"list" dc:"列表数据"`
	Total int                          `json:"total" dc:"总记录数"`
	Page  int                          `json:"page" dc:"当前页码"`
	Size  int                          `json:"size" dc:"每页条数"`
}

type ContentPushQueueRetryReq struct {
	g.Meta `path:"/contentPushQueue/retry" method:"post" tags:"内容推送队列" summary:"重试推送任务"`
	Id     uint64 `json:"id" v:"required#ID不能为空" dc:"任务ID"`
}

type ContentPushQueueRetryRes struct{}

type ContentPushQueueDeleteReq struct {
	g.Meta `path:"/contentPushQueue/destroy" method:"post" tags:"内容推送队列" summary:"删除推送任务"`
	Ids    []uint64 `json:"ids" v:"required#ID列表不能为空" dc:"待删除ID列表"`
}

type ContentPushQueueDeleteRes struct{}

// ================= Daily Statistics =================

type StatisticsDailySearchReq struct {
	g.Meta    `path:"/statisticsDaily/search" method:"post" tags:"运营统计" summary:"查询每日运营统计列表"`
	StartDate string `json:"startDate" dc:"开始日期(YYYY-MM-DD)"`
	EndDate   string `json:"endDate" dc:"结束日期(YYYY-MM-DD)"`
	Limit     int    `json:"limit" d:"30" dc:"条数"`
}

type StatisticsDailySearchRes struct {
	List []model.StatisticsDailyItem `json:"list" dc:"统计数据列表"`
}

type StatisticsDailyAggregateReq struct {
	g.Meta `path:"/statisticsDaily/aggregate" method:"post" tags:"运营统计" summary:"手工触发指定日期的统计聚合"`
	Date   string `json:"date" dc:"统计日期(YYYY-MM-DD，留空则默认昨天)"`
}

type StatisticsDailyAggregateRes struct {
	*model.StatisticsDailyAggregateOutput
}
