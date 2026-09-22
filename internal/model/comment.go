package model

import "github.com/gogf/gf/v2/os/gtime"

// CommentItem 评论条目
type CommentItem struct {
	Id            int64       `json:"id"`
	ContentId     int64       `json:"content_id"`
	ContentTitle  string      `json:"content_title,omitempty"`
	UserId        int64       `json:"user_id"`
	ParentId      int64       `json:"parent_id"`
	ReplyToUserId int64       `json:"reply_to_user_id"`
	AuthorName    string      `json:"author_name"`
	AuthorEmail   string      `json:"author_email"`
	AuthorUrl     string      `json:"author_url"`
	Content       string      `json:"content"`
	Ip            string      `json:"ip"`
	UserAgent     string      `json:"user_agent"`
	Status        string      `json:"status"` // pending/approved/spam/trash
	LikeCount     int         `json:"like_count"`
	CreatedAt     *gtime.Time `json:"created_at"`
	UpdatedAt     *gtime.Time `json:"updated_at"`
}

// CommentTreeNode 评论楼中楼树节点
type CommentTreeNode struct {
	Id            int64             `json:"id"`
	ContentId     int64             `json:"content_id"`
	UserId        int64             `json:"user_id"`
	ParentId      int64             `json:"parent_id"`
	ReplyToUserId int64             `json:"reply_to_user_id"`
	AuthorName    string            `json:"author_name"`
	AuthorEmail   string            `json:"author_email"`
	AuthorUrl     string            `json:"author_url"`
	Content       string            `json:"content"`
	Status        string            `json:"status"`
	LikeCount     int               `json:"like_count"`
	Children      []CommentTreeNode `json:"children,omitempty"`
	CreatedAt     *gtime.Time       `json:"created_at"`
}

// CommentSearchInput 评论检索入参
type CommentSearchInput struct {
	Page      int    `json:"page"`
	PageSize  int    `json:"page_size"`
	ContentId int64  `json:"content_id"`
	Status    string `json:"status"`
	Keyword   string `json:"keyword"`
}

// CommentSearchOutput 评论检索出参
type CommentSearchOutput struct {
	List  []CommentItem `json:"list"`
	Total int           `json:"total"`
	Page  int           `json:"page"`
	Size  int           `json:"size"`
}

// CommentAuditInput 评论审核入参
type CommentAuditInput struct {
	Ids    []int64 `json:"ids" v:"required#请选择需要操作的评论"`
	Status string  `json:"status" v:"required|in:pending,approved,spam,trash#状态不合法"`
}

// CommentCreateInput 提交评论入参
type CommentCreateInput struct {
	ContentId     int64  `json:"content_id" v:"required#内容ID不能为空"`
	ParentId      int64  `json:"parent_id" d:"0"`
	ReplyToUserId int64  `json:"reply_to_user_id" d:"0"`
	AuthorName    string `json:"author_name" v:"required#评论人昵称不能为空"`
	AuthorEmail   string `json:"author_email"`
	AuthorUrl     string `json:"author_url"`
	Content       string `json:"content" v:"required|length:1,1000#请输入评论内容|内容长度限1-1000字"`
	Ip            string `json:"ip"`
	UserAgent     string `json:"user_agent"`
	UserId        int64  `json:"user_id"`
}
