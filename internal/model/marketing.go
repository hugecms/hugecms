package model

import "github.com/gogf/gf/v2/os/gtime"

// ================= Ad Positions =================

type AdPositionItem struct {
	Id          uint64      `json:"id"`
	Name        string      `json:"name"`
	Code        string      `json:"code"`
	Width       uint        `json:"width"`
	Height      uint        `json:"height"`
	AdType      string      `json:"ad_type"`
	MaxCount    uint        `json:"max_count"`
	Description string      `json:"description"`
	Status      uint        `json:"status"`
	CreatedAt   *gtime.Time `json:"created_at"`
	UpdatedAt   *gtime.Time `json:"updated_at"`
}

type AdPositionSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"page_size"`
	Keyword  string `json:"keyword"`
	Status   *uint  `json:"status"`
}

type AdPositionSearchOutput struct {
	List  []AdPositionItem `json:"list"`
	Total int              `json:"total"`
	Page  int              `json:"page"`
	Size  int              `json:"size"`
}

type AdPositionCreateInput struct {
	Name        string `json:"name"`
	Code        string `json:"code"`
	Width       uint   `json:"width"`
	Height      uint   `json:"height"`
	AdType      string `json:"ad_type"`
	MaxCount    uint   `json:"max_count"`
	Description string `json:"description"`
	Status      uint   `json:"status"`
}

type AdPositionUpdateInput struct {
	Id          uint64 `json:"id"`
	Name        string `json:"name"`
	Code        string `json:"code"`
	Width       uint   `json:"width"`
	Height      uint   `json:"height"`
	AdType      string `json:"ad_type"`
	MaxCount    uint   `json:"max_count"`
	Description string `json:"description"`
	Status      uint   `json:"status"`
}

// ================= Ads =================

type AdItem struct {
	Id           uint64      `json:"id"`
	PositionId   uint64      `json:"position_id"`
	PositionName string      `json:"position_name"`
	Title        string      `json:"title"`
	AdType       string      `json:"ad_type"`
	CoverImage   string      `json:"cover_image"`
	Content      string      `json:"content"`
	LinkUrl      string      `json:"link_url"`
	LinkTarget   uint        `json:"link_target"`
	Sort         int         `json:"sort"`
	StartTime    *gtime.Time `json:"start_time"`
	EndTime      *gtime.Time `json:"end_time"`
	DisplayLimit uint        `json:"display_limit"`
	ClickLimit   uint        `json:"click_limit"`
	DisplayCount uint        `json:"display_count"`
	ClickCount   uint        `json:"click_count"`
	Status       uint        `json:"status"`
	CreatedAt    *gtime.Time `json:"created_at"`
	UpdatedAt    *gtime.Time `json:"updated_at"`
}

type AdSearchInput struct {
	Page       int    `json:"page"`
	PageSize   int    `json:"page_size"`
	PositionId uint64 `json:"position_id"`
	Keyword    string `json:"keyword"`
	Status     *uint  `json:"status"`
}

type AdSearchOutput struct {
	List  []AdItem `json:"list"`
	Total int      `json:"total"`
	Page  int      `json:"page"`
	Size  int      `json:"size"`
}

type AdCreateInput struct {
	PositionId   uint64      `json:"position_id"`
	Title        string      `json:"title"`
	AdType       string      `json:"ad_type"`
	CoverImage   string      `json:"cover_image"`
	Content      string      `json:"content"`
	LinkUrl      string      `json:"link_url"`
	LinkTarget   uint        `json:"link_target"`
	Sort         int         `json:"sort"`
	StartTime    *gtime.Time `json:"start_time"`
	EndTime      *gtime.Time `json:"end_time"`
	DisplayLimit uint        `json:"display_limit"`
	ClickLimit   uint        `json:"click_limit"`
	Status       uint        `json:"status"`
}

type AdUpdateInput struct {
	Id           uint64      `json:"id"`
	PositionId   uint64      `json:"position_id"`
	Title        string      `json:"title"`
	AdType       string      `json:"ad_type"`
	CoverImage   string      `json:"cover_image"`
	Content      string      `json:"content"`
	LinkUrl      string      `json:"link_url"`
	LinkTarget   uint        `json:"link_target"`
	Sort         int         `json:"sort"`
	StartTime    *gtime.Time `json:"start_time"`
	EndTime      *gtime.Time `json:"end_time"`
	DisplayLimit uint        `json:"display_limit"`
	ClickLimit   uint        `json:"click_limit"`
	Status       uint        `json:"status"`
}

// ================= Friend Links =================

type FriendLinkItem struct {
	Id           uint64      `json:"id"`
	Category     string      `json:"category"`
	SiteName     string      `json:"site_name"`
	SiteUrl      string      `json:"site_url"`
	LogoUrl      string      `json:"logo_url"`
	Description  string      `json:"description"`
	ContactEmail string      `json:"contact_email"`
	Sort         int         `json:"sort"`
	Status       uint        `json:"status"` // 0 pending, 1 approved, 2 rejected
	CreatedAt    *gtime.Time `json:"created_at"`
	UpdatedAt    *gtime.Time `json:"updated_at"`
}

type FriendLinkSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"page_size"`
	Category string `json:"category"`
	Keyword  string `json:"keyword"`
	Status   *uint  `json:"status"`
}

type FriendLinkSearchOutput struct {
	List  []FriendLinkItem `json:"list"`
	Total int              `json:"total"`
	Page  int              `json:"page"`
	Size  int              `json:"size"`
}

type FriendLinkCreateInput struct {
	Category     string `json:"category"`
	SiteName     string `json:"site_name"`
	SiteUrl      string `json:"site_url"`
	LogoUrl      string `json:"logo_url"`
	Description  string `json:"description"`
	ContactEmail string `json:"contact_email"`
	Sort         int    `json:"sort"`
	Status       uint   `json:"status"`
}

type FriendLinkUpdateInput struct {
	Id           uint64 `json:"id"`
	Category     string `json:"category"`
	SiteName     string `json:"site_name"`
	SiteUrl      string `json:"site_url"`
	LogoUrl      string `json:"logo_url"`
	Description  string `json:"description"`
	ContactEmail string `json:"contact_email"`
	Sort         int    `json:"sort"`
	Status       uint   `json:"status"`
}

// ================= Short Links =================

type ShortLinkItem struct {
	Id         uint64      `json:"id"`
	ShortCode  string      `json:"short_code"`
	TargetUrl  string      `json:"target_url"`
	Title      string      `json:"title"`
	ClickCount uint        `json:"click_count"`
	QrCodePath string      `json:"qr_code_path"`
	ExpireAt   *gtime.Time `json:"expire_at"`
	Status     uint        `json:"status"`
	CreatedAt  *gtime.Time `json:"created_at"`
	UpdatedAt  *gtime.Time `json:"updated_at"`
}

type ShortLinkSearchInput struct {
	Page     int    `json:"page"`
	PageSize int    `json:"page_size"`
	Keyword  string `json:"keyword"`
	Status   *uint  `json:"status"`
}

type ShortLinkSearchOutput struct {
	List  []ShortLinkItem `json:"list"`
	Total int             `json:"total"`
	Page  int             `json:"page"`
	Size  int             `json:"size"`
}

type ShortLinkCreateInput struct {
	ShortCode  string      `json:"short_code"`
	TargetUrl  string      `json:"target_url"`
	Title      string      `json:"title"`
	QrCodePath string      `json:"qr_code_path"`
	ExpireAt   *gtime.Time `json:"expire_at"`
	Status     uint        `json:"status"`
}

type ShortLinkUpdateInput struct {
	Id         uint64      `json:"id"`
	ShortCode  string      `json:"short_code"`
	TargetUrl  string      `json:"target_url"`
	Title      string      `json:"title"`
	QrCodePath string      `json:"qr_code_path"`
	ExpireAt   *gtime.Time `json:"expire_at"`
	Status     uint        `json:"status"`
}

type ShortLinkResolveInput struct {
	ShortCode string `json:"short_code"`
	ClickIp   string `json:"click_ip"`
	UserAgent string `json:"user_agent"`
	Referer   string `json:"referer"`
}

type ShortLinkResolveOutput struct {
	TargetUrl string `json:"target_url"`
}

// ================= Content Push Queue =================

type ContentPushQueueItem struct {
	Id           uint64      `json:"id"`
	ContentId    uint64      `json:"content_id"`
	ContentTitle string      `json:"content_title"`
	PushType     string      `json:"push_type"`
	PushData     string      `json:"push_data"`
	Status       string      `json:"status"`
	RetryCount   uint        `json:"retry_count"`
	MaxRetries   uint        `json:"max_retries"`
	ErrorMessage string      `json:"error_message"`
	FinishedAt   *gtime.Time `json:"finished_at"`
	CreatedAt    *gtime.Time `json:"created_at"`
	UpdatedAt    *gtime.Time `json:"updated_at"`
}

type ContentPushQueueSearchInput struct {
	Page      int    `json:"page"`
	PageSize  int    `json:"page_size"`
	PushType  string `json:"push_type"`
	Status    string `json:"status"`
	ContentId uint64 `json:"content_id"`
}

type ContentPushQueueSearchOutput struct {
	List  []ContentPushQueueItem `json:"list"`
	Total int                    `json:"total"`
	Page  int                    `json:"page"`
	Size  int                    `json:"size"`
}

type ContentPushQueueRetryInput struct {
	Id uint64 `json:"id"`
}
