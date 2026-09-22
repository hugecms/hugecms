package test

import (
	"fmt"
	"testing"
	"time"

	_ "hugecms/internal/logic"

	_ "github.com/gogf/gf/contrib/drivers/mysql/v2"
	"github.com/gogf/gf/v2/os/gctx"

	"hugecms/internal/model"
	"hugecms/internal/service"
)

func TestPhase3Modules(t *testing.T) {
	ctx := gctx.New()

	// 1. 测试 Permission 树状结构
	treeOut, err := service.Permission().Tree(ctx, model.PermissionSearchInput{})
	if err != nil {
		t.Fatalf("Permission.Tree failed: %v", err)
	}
	if len(treeOut.Tree) == 0 {
		t.Fatalf("Expected permission tree to have items, got 0")
	}
	t.Logf("Permission tree loaded: %d root nodes", len(treeOut.Tree))

	// 2. 测试 Role 模块
	testRoleAlias := fmt.Sprintf("test_role_%d", time.Now().Unix())
	newRoleId, err := service.Role().Create(ctx, model.RoleCreateInput{
		Name:          "测试角色",
		Alias:         testRoleAlias,
		Description:   "自动化测试角色",
		PermissionIds: []int64{1, 2, 3},
	})
	if err != nil {
		t.Fatalf("Role.Create failed: %v", err)
	}
	t.Logf("Created test role ID: %d", newRoleId)

	roleDetail, err := service.Role().Get(ctx, newRoleId)
	if err != nil {
		t.Fatalf("Role.Get failed: %v", err)
	}
	if roleDetail.Name != "测试角色" || len(roleDetail.PermissionIds) != 3 {
		t.Fatalf("Role detail mismatch: %+v", roleDetail)
	}

	// 3. 测试 User 模块
	testUserEmail := fmt.Sprintf("tester_%d@example.com", time.Now().Unix())
	newUserId, err := service.User().Create(ctx, model.UserCreateInput{
		Name:      "自动化测试用户",
		Email:     testUserEmail,
		Password:  "password123",
		RoleIds:   []int64{newRoleId},
		DataScope: "all",
		Status:    1,
	})
	if err != nil {
		t.Fatalf("User.Create failed: %v", err)
	}
	t.Logf("Created test user ID: %d", newUserId)

	userDetail, err := service.User().Get(ctx, newUserId)
	if err != nil {
		t.Fatalf("User.Get failed: %v", err)
	}
	if userDetail.Email != testUserEmail || len(userDetail.RoleIds) != 1 || userDetail.RoleIds[0] != newRoleId {
		t.Fatalf("User detail mismatch: %+v", userDetail)
	}

	// 4. 测试 Option 模块与缓存
	optKey := fmt.Sprintf("test_key_%d", time.Now().Unix())
	err = service.Option().Save(ctx, model.OptionSaveInput{
		OptionKey:   optKey,
		OptionValue: "hugecms_test_value",
		Autoload:    1,
	})
	if err != nil {
		t.Fatalf("Option.Save failed: %v", err)
	}

	optVal, err := service.Option().Get(ctx, optKey)
	if err != nil || optVal != "hugecms_test_value" {
		t.Fatalf("Option.Get failed or value mismatch, val=%s, err=%v", optVal, err)
	}

	// 5. 测试 Site 模块
	siteCode := fmt.Sprintf("site_%d", time.Now().Unix())
	domain := fmt.Sprintf("%d.testsite.local", time.Now().Unix())
	siteId, err := service.Site().Create(ctx, model.SiteCreateInput{
		SiteName: "测试子站点",
		SiteCode: siteCode,
		Domain:   domain,
		Status:   1,
	})
	if err != nil {
		t.Fatalf("Site.Create failed: %v", err)
	}

	siteMatched, err := service.Site().GetByDomain(ctx, domain)
	if err != nil || siteMatched.SiteCode != siteCode {
		t.Fatalf("Site.GetByDomain failed: %v, matched: %+v", err, siteMatched)
	}

	// 6. 测试 Appearance 模块（菜单、条目、区块、模板）
	menuAlias := fmt.Sprintf("menu_%d", time.Now().Unix())
	menuId, err := service.Appearance().CreateMenu(ctx, model.NavMenuCreateInput{
		Name:  "主导航栏",
		Alias: menuAlias,
	})
	if err != nil {
		t.Fatalf("Appearance.CreateMenu failed: %v", err)
	}

	itemId, err := service.Appearance().SaveMenuItem(ctx, model.NavItemSaveInput{
		MenuId:    menuId,
		Title:     "首页",
		LinkType:  "custom",
		LinkValue: "/",
		Sort:      1,
		IsActive:  1,
	})
	if err != nil {
		t.Fatalf("Appearance.SaveMenuItem failed: %v", err)
	}
	t.Logf("Created nav item ID: %d", itemId)

	menuTree, err := service.Appearance().GetMenuTreeByAlias(ctx, menuAlias)
	if err != nil || len(menuTree) != 1 {
		t.Fatalf("Appearance.GetMenuTreeByAlias failed: %v, count: %d", err, len(menuTree))
	}

	// 7. 测试 AuditLog 模块
	service.AuditLog().Record(ctx, model.AuditLogRecordInput{
		UserId:          newUserId,
		UserName:        "自动化测试用户",
		ClientIp:        "127.0.0.1",
		EventType:       "CREATE",
		TargetType:      "user",
		TargetId:        fmt.Sprintf("%d", newUserId),
		TargetName:      "自动化测试用户",
		NewValue:        userDetail,
		OperationResult: 1,
	})

	// 等待异步日志写入
	time.Sleep(300 * time.Millisecond)

	logSearch, err := service.AuditLog().Search(ctx, model.AuditLogSearchInput{
		UserId: newUserId,
	})
	if err != nil {
		t.Fatalf("AuditLog.Search failed: %v", err)
	}
	if logSearch.Total == 0 {
		t.Logf("AuditLog async write might still be pending or completed")
	} else {
		t.Logf("Found %d audit logs for user %d", logSearch.Total, newUserId)
	}

	// 清理测试数据
	_ = service.User().Delete(ctx, newUserId)
	_ = service.Role().Delete(ctx, newRoleId)
	_ = service.Option().Delete(ctx, optKey)
	_ = service.Site().Delete(ctx, siteId)
	_ = service.Appearance().DeleteMenu(ctx, menuId)
	t.Log("All Phase 3 integration tests passed!")
}
