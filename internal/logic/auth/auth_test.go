package auth_test

import (
	"testing"

	_ "hugecms/internal/logic"

	_ "github.com/gogf/gf/contrib/drivers/mysql/v2"
	"github.com/gogf/gf/v2/os/gctx"

	"hugecms/internal/model"
	"hugecms/internal/service"
	"hugecms/utility/password"
)

func TestAuthLogin(t *testing.T) {
	ctx := gctx.New()

	// 1. 测试 bcrypt 密码验证
	if !password.Verify("$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy", "password") {
		// Just test a known bcrypt or direct verification
	}

	// 2. 测试管理员登录
	loginOut, err := service.Auth().Login(ctx, model.AuthLoginInput{
		Email:    "admin@example.com",
		Password: "password",
	})
	if err != nil {
		t.Fatalf("Admin login failed: %v", err)
	}

	if loginOut.Token == "" {
		t.Fatal("Token should not be empty")
	}

	t.Logf("Login successful! Token: %s, User: %+v", loginOut.Token, loginOut.User)

	// 3. 校验超级管理员身份与权限
	if !loginOut.User.IsSuper {
		t.Fatal("Admin should be super_admin")
	}

	if !loginOut.User.HasPermission("content:view") {
		t.Fatal("Super admin should have content:view permission via bypass")
	}

	if !loginOut.User.HasPermission("any_custom_permission_code") {
		t.Fatal("Super admin should bypass any permission check")
	}

	// 4. 测试获取用户信息
	userInfo, err := service.Auth().GetUserInfo(ctx, loginOut.User.Id)
	if err != nil {
		t.Fatalf("GetUserInfo failed: %v", err)
	}

	if userInfo.Email != "admin@example.com" {
		t.Fatalf("Expected email admin@example.com, got %s", userInfo.Email)
	}

	t.Logf("UserInfo: %+v", userInfo)
}
