package model

import "github.com/gogf/gf/v2/os/gtime"

type StatisticsDailyItem struct {
	Id                uint64      `json:"id"`
	StatDate          *gtime.Time `json:"stat_date"`
	NewContents       uint        `json:"new_contents"`
	PublishedContents uint        `json:"published_contents"`
	TotalContents     uint        `json:"total_contents"`
	TotalViews        uint64      `json:"total_views"`
	NewComments       uint        `json:"new_comments"`
	NewUsers          uint        `json:"new_users"`
	ActiveUsers       uint        `json:"active_users"`
	TotalUsers        uint        `json:"total_users"`
	CreatedAt         *gtime.Time `json:"created_at"`
	UpdatedAt         *gtime.Time `json:"updated_at"`
}

type StatisticsDailySearchInput struct {
	StartDate string `json:"start_date"`
	EndDate   string `json:"end_date"`
	Limit     int    `json:"limit"`
}

type StatisticsDailySearchOutput struct {
	List []StatisticsDailyItem `json:"list"`
}

type StatisticsDailyAggregateInput struct {
	Date string `json:"date"` // format YYYY-MM-DD, defaults to yesterday if empty
}

type StatisticsDailyAggregateOutput struct {
	StatDate          string `json:"stat_date"`
	NewContents       uint   `json:"new_contents"`
	PublishedContents uint   `json:"published_contents"`
	TotalContents     uint   `json:"total_contents"`
	TotalViews        uint64 `json:"total_views"`
	NewComments       uint   `json:"new_comments"`
	NewUsers          uint   `json:"new_users"`
	ActiveUsers       uint   `json:"active_users"`
	TotalUsers        uint   `json:"total_users"`
}
