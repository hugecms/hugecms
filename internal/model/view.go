package model

type ContentViewRecordInput struct {
	ContentId int64  `json:"content_id"`
	Ip        string `json:"ip"`
}

type ContentViewRecordOutput struct {
	Views   int64 `json:"views"`
	Ignored bool  `json:"ignored"` // true if anti-brushing blocked within 24h
}
