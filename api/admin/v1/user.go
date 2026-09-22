package v1

import (
	"github.com/gogf/gf/v2/frame/g"

	"hugecms/internal/model"
)

// UserSearchReq 用户列表查询请求
type UserSearchReq struct {
	g.Meta   `path:"/users" method:"get" tags:"用户管理" summary:"分页查询用户列表"`
	Page     int    `json:"page" in:"query" d:"1" dc:"页码"`
	PageSize int    `json:"page_size" in:"query" d:"10" dc:"每页条数"`
	Keyword  string `json:"keyword" in:"query" dc:"搜索关键词（昵称/邮箱）"`
	Status   *int   `json:"status" in:"query" dc:"状态筛选：1启用 0禁用"`
	RoleId   int64  `json:"role_id" in:"query" dc:"角色ID筛选"`
}

// UserSearchRes 用户列表响应
type UserSearchRes struct {
	*model.UserSearchOutput
}

// UserGetReq 获取指定用户详情请求
type UserGetReq struct {
	g.Meta `path:"/users/{id}" method:"get" tags:"用户管理" summary:"获取用户详情"`
	Id     int64 `json:"id" in:"path" v:"required#用户ID不能为空" dc:"用户ID"`
}

// UserGetRes 用户详情响应
type UserGetRes struct {
	*model.UserDetailOutput
}

// UserCreateReq 创建用户请求
type UserCreateReq struct {
	g.Meta    `path:"/users" method:"post" tags:"用户管理" summary:"新增管理员/用户"`
	Name      string  `json:"name" v:"required|length:2,50#请输入用户昵称|昵称长度为2-50位" dc:"昵称"`
	Email     string  `json:"email" v:"required|email#请输入电子邮箱|邮箱格式不正确" dc:"电子邮箱"`
	Password  string  `json:"password" v:"required|length:6,32#请输入密码|密码长度为6-32位" dc:"密码"`
	Avatar    string  `json:"avatar" dc:"头像URL"`
	Status    int     `json:"status" d:"1" dc:"状态：1启用 0禁用"`
	RoleIds   []int64 `json:"role_ids" dc:"关联角色ID列表"`
	DataScope string  `json:"data_scope" d:"self" dc:"数据权限范围：all全部 self仅本人"`
}

// UserCreateRes 创建用户响应
type UserCreateRes struct {
	Id int64 `json:"id" dc:"新创建用户ID"`
}

// UserUpdateReq 更新用户请求
type UserUpdateReq struct {
	g.Meta    `path:"/users/{id}" method:"put" tags:"用户管理" summary:"更新用户信息"`
	Id        int64   `json:"id" in:"path" v:"required#用户ID不能为空" dc:"用户ID"`
	Name      string  `json:"name" v:"required|length:2,50#请输入用户昵称|昵称长度为2-50位" dc:"昵称"`
	Email     string  `json:"email" v:"required|email#请输入电子邮箱|邮箱格式不正确" dc:"电子邮箱"`
	Password  string  `json:"password" dc:"若需要修改密码则填写，长度6-32位"`
	Avatar    string  `json:"avatar" dc:"头像URL"`
	Status    int     `json:"status" dc:"状态：1启用 0禁用"`
	RoleIds   []int64 `json:"role_ids" dc:"关联角色ID列表"`
	DataScope string  `json:"data_scope" dc:"数据权限范围：all全部 self仅本人"`
}

// UserUpdateRes 更新用户响应
type UserUpdateRes struct{}

// UserDeleteReq 删除用户请求
type UserDeleteReq struct {
	g.Meta `path:"/users/{id}" method:"delete" tags:"用户管理" summary:"删除用户"`
	Id     int64 `json:"id" in:"path" v:"required#用户ID不能为空" dc:"用户ID"`
}

// UserDeleteRes 删除用户响应
type UserDeleteRes struct{}
