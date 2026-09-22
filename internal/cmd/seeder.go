package cmd

import (
	"context"
	"fmt"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gcmd"
	"github.com/gogf/gf/v2/os/gtime"
	"golang.org/x/crypto/bcrypt"

	"hugecms/internal/dao"
)

var (
	Seeder = gcmd.Command{
		Name:  "seeder",
		Usage: "seeder",
		Brief: "seed initial CMS data (users, roles, permissions, models, options)",
		Func: func(ctx context.Context, parser *gcmd.Parser) (err error) {
			return runSeeder(ctx)
		},
	}
)

func init() {
	Main.AddCommand(&Seeder)
}

func runSeeder(ctx context.Context) error {
	g.Log().Info(ctx, "Starting CMS database seeder...")

	err := g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		now := gtime.Now()

		// 1. 管理员与内置角色
		pwdHash, err := bcrypt.GenerateFromPassword([]byte("password"), bcrypt.DefaultCost)
		if err != nil {
			return err
		}

		userCount, err := dao.Users.Ctx(ctx).TX(tx).WherePri(1).Count()
		if err != nil {
			return err
		}
		if userCount == 0 {
			_, err = dao.Users.Ctx(ctx).TX(tx).Data(g.Map{
				"id":                1,
				"name":              "管理员",
				"email":             "admin@example.com",
				"password":          string(pwdHash),
				"email_verified_at": now,
				"status":            1,
				"created_at":        now,
				"updated_at":        now,
			}).Insert()
			if err != nil {
				return fmt.Errorf("seed user failed: %w", err)
			}
		}

		roles := []g.Map{
			{"id": 1, "name": "超级管理员", "alias": "super_admin", "is_system": 1, "description": "拥有全部权限，不受数据范围限制", "created_at": now, "updated_at": now},
			{"id": 2, "name": "编辑", "alias": "editor", "is_system": 1, "description": "管理全站内容、分类、评论、外观与推广，不能管理用户和系统", "created_at": now, "updated_at": now},
			{"id": 3, "name": "作者", "alias": "author", "is_system": 1, "description": "仅能撰写和编辑自己的内容（data_scope=self），提交待审核", "created_at": now, "updated_at": now},
			{"id": 4, "name": "审核员", "alias": "auditor", "is_system": 1, "description": "负责内容与评论的审核，无编辑权限", "created_at": now, "updated_at": now},
		}
		for _, r := range roles {
			_, _ = dao.Roles.Ctx(ctx).TX(tx).Data(r).Save()
		}

		// 2. 权限树（9个一级模块 + 45个操作项，共54条）
		type Action struct {
			Name string
			Code string
			Sort int
		}
		type Module struct {
			Name     string
			Code     string
			Children []Action
		}

		tree := []Module{
			{Name: "内容管理", Code: "content", Children: []Action{
				{"内容查看", "content:view", 10},
				{"内容新增", "content:create", 20},
				{"内容编辑", "content:edit", 30},
				{"内容删除", "content:delete", 40},
				{"内容发布/下线", "content:publish", 50},
				{"内容审核", "content:audit", 60},
				{"版本回滚", "content:revision", 70},
				{"模型查看", "model:view", 80},
				{"模型与字段管理", "model:manage", 90},
				{"SEO设置", "seo:meta", 100},
				{"重定向管理", "redirect:manage", 110},
			}},
			{Name: "分类管理", Code: "taxonomy", Children: []Action{
				{"分类查看", "taxonomy:view", 10},
				{"分类管理", "taxonomy:manage", 20},
			}},
			{Name: "评论管理", Code: "comment", Children: []Action{
				{"评论查看", "comment:view", 10},
				{"评论审核", "comment:moderate", 20},
				{"评论删除", "comment:delete", 30},
			}},
			{Name: "媒体管理", Code: "attachment", Children: []Action{
				{"媒体库查看", "attachment:view", 10},
				{"上传附件", "attachment:upload", 20},
				{"删除附件", "attachment:delete", 30},
			}},
			{Name: "外观管理", Code: "appearance", Children: []Action{
				{"模板查看", "template:view", 10},
				{"模板管理", "template:manage", 20},
				{"区块查看", "block:view", 30},
				{"区块管理", "block:manage", 40},
				{"菜单查看", "menu:view", 50},
				{"菜单管理", "menu:manage", 60},
			}},
			{Name: "表单管理", Code: "form", Children: []Action{
				{"表单查看", "form:view", 10},
				{"表单管理", "form:manage", 20},
				{"表单数据查看", "form:submission", 30},
			}},
			{Name: "用户权限", Code: "user", Children: []Action{
				{"用户查看", "user:view", 10},
				{"用户新增", "user:create", 20},
				{"用户编辑", "user:edit", 30},
				{"用户删除", "user:delete", 40},
				{"角色查看", "role:view", 50},
				{"角色管理", "role:manage", 60},
			}},
			{Name: "系统管理", Code: "system", Children: []Action{
				{"系统设置", "option:manage", 10},
				{"审计日志", "audit:view", 20},
				{"回收站", "recycle:manage", 30},
				{"站点管理", "site:manage", 60},
				{"数据统计", "statistics:view", 100},
			}},
			{Name: "推广管理", Code: "marketing", Children: []Action{
				{"广告查看", "ad:view", 10},
				{"广告管理", "ad:manage", 20},
				{"友链查看", "friend_link:view", 30},
				{"友链管理", "friend_link:manage", 40},
				{"短链管理", "short_link:manage", 50},
				{"内容推送", "push:manage", 60},
			}},
		}

		codeToId := make(map[string]int64)
		permId := int64(0)
		moduleSort := 100
		for _, mod := range tree {
			permId++
			parentPermId := permId
			pData := g.Map{
				"id":         parentPermId,
				"parent_id":  0,
				"name":       mod.Name,
				"code":       mod.Code,
				"module":     mod.Code,
				"sort":       moduleSort,
				"created_at": now,
				"updated_at": now,
			}
			_, _ = dao.Permissions.Ctx(ctx).TX(tx).Data(pData).Save()
			codeToId[mod.Code] = parentPermId
			moduleSort += 100

			for _, act := range mod.Children {
				permId++
				cData := g.Map{
					"id":         permId,
					"parent_id":  parentPermId,
					"name":       act.Name,
					"code":       act.Code,
					"module":     mod.Code,
					"sort":       act.Sort,
					"created_at": now,
					"updated_at": now,
				}
				_, _ = dao.Permissions.Ctx(ctx).TX(tx).Data(cData).Save()
				codeToId[act.Code] = permId
			}
		}

		// 3. 角色-权限关联
		editorCodes := []string{
			"content:view", "content:create", "content:edit", "content:delete", "content:publish",
			"content:audit", "content:revision", "model:view", "seo:meta", "redirect:manage",
			"taxonomy:view", "taxonomy:manage",
			"comment:view", "comment:moderate", "comment:delete",
			"attachment:view", "attachment:upload", "attachment:delete",
			"template:view", "template:manage", "block:view", "block:manage",
			"menu:view", "menu:manage",
			"recycle:manage", "statistics:view",
			"ad:view", "ad:manage", "friend_link:view", "friend_link:manage",
			"short_link:manage", "push:manage",
		}
		authorCodes := []string{
			"content:view", "content:create", "content:edit",
			"taxonomy:view", "comment:view",
			"attachment:view", "attachment:upload",
		}
		auditorCodes := []string{
			"content:view", "content:audit",
			"comment:view", "comment:moderate",
			"statistics:view",
		}

		// 超级管理员绑定全部权限
		for _, pid := range codeToId {
			_, _ = dao.RolePermissions.Ctx(ctx).TX(tx).Data(g.Map{
				"role_id":       1,
				"permission_id": pid,
				"is_denied":     0,
				"created_at":    now,
			}).Save()
		}

		for _, code := range editorCodes {
			if pid, ok := codeToId[code]; ok {
				_, _ = dao.RolePermissions.Ctx(ctx).TX(tx).Data(g.Map{"role_id": 2, "permission_id": pid, "is_denied": 0, "created_at": now}).Save()
			}
		}
		for _, code := range authorCodes {
			if pid, ok := codeToId[code]; ok {
				_, _ = dao.RolePermissions.Ctx(ctx).TX(tx).Data(g.Map{"role_id": 3, "permission_id": pid, "is_denied": 0, "created_at": now}).Save()
			}
		}
		for _, code := range auditorCodes {
			if pid, ok := codeToId[code]; ok {
				_, _ = dao.RolePermissions.Ctx(ctx).TX(tx).Data(g.Map{"role_id": 4, "permission_id": pid, "is_denied": 0, "created_at": now}).Save()
			}
		}

		// 管理员绑定超管角色
		_, _ = dao.UserRoles.Ctx(ctx).TX(tx).Data(g.Map{
			"user_id":    1,
			"role_id":    1,
			"data_scope": "all",
			"created_at": now,
		}).Save()

		// 4. 默认文章内容模型与字段
		_, _ = dao.ContentModels.Ctx(ctx).TX(tx).Data(g.Map{
			"id":             1,
			"name":           "文章",
			"alias":          "article",
			"table_name":     "data_article",
			"description":    "系统内置的文章模型，对标 WordPress Post",
			"is_system":      1,
			"is_commentable": 1,
			"status":         1,
			"sort":           100,
			"created_at":     now,
			"updated_at":     now,
		}).Save()

		fields := []g.Map{
			{"id": 1, "model_id": 1, "field_name": "summary", "column_name": "field_1", "field_label": "文章摘要", "field_type": "text", "column_type": "varchar(500)", "default_value": "", "is_required": 0, "is_unique": 0, "sort_order": 10, "created_at": now, "updated_at": now},
			{"id": 2, "model_id": 1, "field_name": "content", "column_name": "field_2", "field_label": "正文内容", "field_type": "rich_text", "column_type": "longtext", "default_value": nil, "is_required": 1, "is_unique": 0, "sort_order": 20, "created_at": now, "updated_at": now},
			{"id": 3, "model_id": 1, "field_name": "cover_image", "column_name": "field_3", "field_label": "封面图", "field_type": "image", "column_type": "varchar(255)", "default_value": "", "is_required": 0, "is_unique": 0, "sort_order": 30, "created_at": now, "updated_at": now},
		}
		for _, f := range fields {
			_, _ = dao.ModelFields.Ctx(ctx).TX(tx).Data(f).Save()
		}

		// 5. 默认分类法与分类项
		_, _ = dao.Taxonomies.Ctx(ctx).TX(tx).Data(g.Map{
			"id":              1,
			"name":            "文章分类",
			"alias":           "category",
			"model_id":        1,
			"is_hierarchical": 1,
			"description":     "层级分类目录（绑定文章模型）",
			"created_at":      now,
			"updated_at":      now,
		}).Save()
		_, _ = dao.Taxonomies.Ctx(ctx).TX(tx).Data(g.Map{
			"id":              2,
			"name":            "标签",
			"alias":           "tag",
			"model_id":        1,
			"is_hierarchical": 0,
			"description":     "非层级标签（绑定文章模型）",
			"created_at":      now,
			"updated_at":      now,
		}).Save()

		_, _ = dao.Terms.Ctx(ctx).TX(tx).Data(g.Map{
			"id":          1,
			"taxonomy_id": 1,
			"name":        "未分类",
			"slug":        "uncategorized",
			"parent_id":   0,
			"description": "默认分类，无法删除（对标 WP 默认分类）",
			"sort":        0,
			"created_at":  now,
			"updated_at":  now,
		}).Save()

		// 6. 默认主站点、菜单与页面模板
		_, _ = dao.Sites.Ctx(ctx).TX(tx).Data(g.Map{
			"id":         1,
			"site_name":  "主站",
			"site_code":  "main",
			"domain":     "localhost",
			"timezone":   "Asia/Shanghai",
			"language":   "zh_CN",
			"status":     1,
			"created_at": now,
			"updated_at": now,
		}).Save()

		_, _ = dao.NavMenus.Ctx(ctx).TX(tx).Data(g.Map{"id": 1, "name": "主导航", "alias": "main_nav", "description": "顶部主导航", "created_at": now, "updated_at": now}).Save()
		_, _ = dao.NavMenus.Ctx(ctx).TX(tx).Data(g.Map{"id": 2, "name": "底部导航", "alias": "footer_nav", "description": "页脚导航", "created_at": now, "updated_at": now}).Save()

		_, _ = dao.NavItems.Ctx(ctx).TX(tx).Data(g.Map{"id": 1, "menu_id": 1, "parent_id": 0, "title": "首页", "link_type": "custom", "link_value": "/", "open_type": 0, "sort": 100, "is_active": 1, "created_at": now, "updated_at": now}).Save()
		_, _ = dao.NavItems.Ctx(ctx).TX(tx).Data(g.Map{"id": 2, "menu_id": 1, "parent_id": 0, "title": "未分类", "link_type": "term", "link_value": "1", "open_type": 0, "sort": 200, "is_active": 1, "created_at": now, "updated_at": now}).Save()

		_, _ = dao.PageTemplates.Ctx(ctx).TX(tx).Data(g.Map{"id": 1, "template_name": "默认文章模板", "template_code": "post-default", "category": "post", "content": `{"layout":"single","sidebar":true}`, "is_default": 1, "is_system": 1, "status": 1, "created_at": now, "updated_at": now}).Save()
		_, _ = dao.PageTemplates.Ctx(ctx).TX(tx).Data(g.Map{"id": 2, "template_name": "默认页面模板", "template_code": "page-default", "category": "page", "content": `{"layout":"single","sidebar":false}`, "is_default": 1, "is_system": 1, "status": 1, "created_at": now, "updated_at": now}).Save()

		// 7. 全局系统配置 options
		options := []g.Map{
			{"option_key": "site_info", "option_value": `{"slogan":"基于 GoFrame 的类 WordPress 建站系统","icp_number":""}`, "autoload": 1, "created_at": now, "updated_at": now},
			{"option_key": "permalink", "option_value": `{"content":"/{slug}","term":"/{taxonomy_alias}/{slug}"}`, "autoload": 1, "created_at": now, "updated_at": now},
			{"option_key": "comment_config", "option_value": `{"require_moderation":true,"guest_allowed":true,"guest_must_fill":["name","email"],"max_links":0}`, "autoload": 1, "created_at": now, "updated_at": now},
			{"option_key": "seo_defaults", "option_value": `{"robots":"index,follow","sitemap_enabled":true,"title_separator":"-"}`, "autoload": 1, "created_at": now, "updated_at": now},
			{"option_key": "storage_config", "option_value": `{"driver":"local"}`, "autoload": 1, "created_at": now, "updated_at": now},
			{"option_key": "smtp_config", "option_value": `{"driver":"log"}`, "autoload": 0, "created_at": now, "updated_at": now},
			{"option_key": "theme", "option_value": "default", "autoload": 1, "created_at": now, "updated_at": now},
		}
		for _, opt := range options {
			_, _ = dao.Options.Ctx(ctx).TX(tx).Data(opt).Save()
		}

		return nil
	})

	if err != nil {
		g.Log().Errorf(ctx, "CMS Seeder failed: %v", err)
		return err
	}

	g.Log().Info(ctx, "CMS Seeder completed successfully! Default admin: admin@example.com / password")
	return nil
}
