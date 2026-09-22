package model

import "github.com/gogf/gf/v2/os/gtime"

// PortalNavMenuItem 前台导航菜单项
type PortalNavMenuItem struct {
	Id       uint64              `json:"id"`
	Title    string              `json:"title"`
	LinkType string              `json:"link_type"`
	LinkUrl  string              `json:"link_url"`
	Target   string              `json:"target"`
	Children []PortalNavMenuItem `json:"children"`
}

// PortalContentItem 前台内容列表项
type PortalContentItem struct {
	Id           int64       `json:"id"`
	Title        string      `json:"title"`
	Slug         string      `json:"slug"`
	Summary      string      `json:"summary"`
	CoverImage   string      `json:"cover_image"`
	Views        int64       `json:"views"`
	CommentCount int         `json:"comment_count"`
	IsTop        int         `json:"is_top"`
	PublishedAt  *gtime.Time `json:"published_at"`
	AuthorName   string      `json:"author_name"`
	CategoryName string      `json:"category_name"`
	CategorySlug string      `json:"category_slug"`
}

// PortalHomeOutput 前台首页数据
type PortalHomeOutput struct {
	SiteName    string              `json:"site_name"`
	SiteInfo    map[string]string   `json:"site_info"`
	NavItems    []PortalNavMenuItem `json:"nav_items"`
	FriendLinks []FriendLinkItem    `json:"friend_links"`
	TopContents []PortalContentItem `json:"top_contents"`
	Contents    []PortalContentItem `json:"contents"`
	Categories  []TermItem          `json:"categories"`
	Total       int                 `json:"total"`
	Page        int                 `json:"page"`
	Size        int                 `json:"size"`
	TotalPages  int                 `json:"total_pages"`
}

// PortalCategoryOutput 前台分类列表数据
type PortalCategoryOutput struct {
	SiteName    string              `json:"site_name"`
	SiteInfo    map[string]string   `json:"site_info"`
	NavItems    []PortalNavMenuItem `json:"nav_items"`
	FriendLinks []FriendLinkItem    `json:"friend_links"`
	Taxonomy    *TaxonomyItem       `json:"taxonomy"`
	Term        *TermItem           `json:"term"`
	Contents    []PortalContentItem `json:"contents"`
	Total       int                 `json:"total"`
	Page        int                 `json:"page"`
	Size        int                 `json:"size"`
	TotalPages  int                 `json:"total_pages"`
}

// PortalCommentTreeItem 前台评论树条目
type PortalCommentTreeItem struct {
	Id         int64                   `json:"id"`
	ParentId   int64                   `json:"parent_id"`
	AuthorName string                  `json:"author_name"`
	Content    string                  `json:"content"`
	CreatedAt  *gtime.Time             `json:"created_at"`
	Replies    []PortalCommentTreeItem `json:"replies"`
}

// PortalDetailOutput 前台详情页数据
type PortalDetailOutput struct {
	SiteName    string                  `json:"site_name"`
	SiteInfo    map[string]string       `json:"site_info"`
	NavItems    []PortalNavMenuItem     `json:"nav_items"`
	FriendLinks []FriendLinkItem        `json:"friend_links"`
	Content     *ContentDetailOutput    `json:"content"`
	DynamicData map[string]interface{}  `json:"dynamic_data"`
	Seo         *SeoMetaItem            `json:"seo"`
	Comments    []PortalCommentTreeItem `json:"comments"`
	PrevContent *PortalContentItem      `json:"prev_content"`
	NextContent *PortalContentItem      `json:"next_content"`
	Locked      bool                    `json:"locked"`
}

// PortalCommentPostInput 前台提交评论入参
type PortalCommentPostInput struct {
	ContentId   int64  `json:"content_id"`
	ParentId    int64  `json:"parent_id"`
	AuthorName  string `json:"author_name"`
	AuthorEmail string `json:"author_email"`
	Content     string `json:"content"`
	Ip          string `json:"ip"`
	UserAgent   string `json:"user_agent"`
}
