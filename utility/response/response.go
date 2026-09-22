package response

import (
	"net/http"

	"github.com/gogf/gf/v2/net/ghttp"
)

// Response 统一响应结构
type Response struct {
	Code    int         `json:"code" dc:"状态码：0成功，其他失败"`
	Message string      `json:"message" dc:"提示消息"`
	Data    interface{} `json:"data,omitempty" dc:"响应数据"`
}

// Json 标准 JSON 输出
func Json(r *ghttp.Request, code int, message string, data ...interface{}) {
	var responseData interface{}
	if len(data) > 0 {
		responseData = data[0]
	} else {
		responseData = struct{}{}
	}
	r.Response.WriteJson(Response{
		Code:    code,
		Message: message,
		Data:    responseData,
	})
}

// JsonExit 输出 JSON 并终止后续流程
func JsonExit(r *ghttp.Request, code int, message string, data ...interface{}) {
	Json(r, code, message, data...)
	r.Exit()
}

// Success 成功返回
func Success(r *ghttp.Request, data ...interface{}) {
	Json(r, 0, "ok", data...)
}

// Error 错误返回
func Error(r *ghttp.Request, code int, message string) {
	Json(r, code, message)
}

// Unauthorized 未登录返回
func Unauthorized(r *ghttp.Request, message ...string) {
	msg := "未登录或登录已过期"
	if len(message) > 0 && message[0] != "" {
		msg = message[0]
	}
	r.Response.WriteHeader(http.StatusUnauthorized)
	JsonExit(r, 401, msg)
}

// Forbidden 无权限返回
func Forbidden(r *ghttp.Request, message ...string) {
	msg := "没有操作权限"
	if len(message) > 0 && message[0] != "" {
		msg = message[0]
	}
	r.Response.WriteHeader(http.StatusForbidden)
	JsonExit(r, 403, msg)
}
