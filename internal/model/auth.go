package model

import (
	"time"

	"github.com/gogf/gf/v2/os/gtime"
)

// AuthLoginInput 登录入参 DTO
type AuthLoginInput struct {
	Email    string `json:"email"`
	Password string `json:"password"`
}

// AuthLoginOutput 登录出参 DTO
type AuthLoginOutput struct {
	Token    string       `json:"token"`
	ExpireAt time.Time    `json:"expire_at"`
	User     *ContextUser `json:"user"`
}

// AuthUserInfoOutput 用户信息与权限信息 DTO
type AuthUserInfoOutput struct {
	Id          int64       `json:"id"`
	Name        string      `json:"name"`
	Email       string      `json:"email"`
	Avatar      string      `json:"avatar"`
	Status      int         `json:"status"`
	Roles       []string    `json:"roles"`
	Permissions []string    `json:"permissions"`
	CreatedAt   *gtime.Time `json:"created_at"`
}
