-- ==========================================================================
-- HugeCMS MySQL Database Schema
-- Generated from archive/database/migrations
-- Character Set: utf8mb4 / Collation: utf8mb4_unicode_ci / Engine: InnoDB
-- ==========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '显示昵称',
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `avatar` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像URL',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0禁用，1启用',
  `last_login_ip` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '最后登录IP（支持IPv6）',
  `last_login_time` DATETIME NULL DEFAULT NULL COMMENT '最后登录时间',
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `reset_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

-- --------------------------------------------------------------------------
-- Table structure for `user_meta`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `user_meta`;
CREATE TABLE `user_meta` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT '关联用户ID',
  `meta_key` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '元数据键名',
  `meta_value` LONGTEXT NULL DEFAULT NULL COMMENT '元数据值（JSON或序列化数据）',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_meta_user_id_meta_key_unique` (`user_id`, `meta_key`),
  CONSTRAINT `user_meta_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户元数据表';

-- --------------------------------------------------------------------------
-- Table structure for `roles`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL COMMENT '角色名称（如：主编、运营）',
  `alias` VARCHAR(50) NOT NULL COMMENT '角色标识（如：chief_editor）',
  `is_system` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否系统内置（不可删除）：1是，0否',
  `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '角色描述',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_alias_unique` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色表';

-- --------------------------------------------------------------------------
-- Table structure for `permissions`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级权限ID（0表示顶级）',
  `name` VARCHAR(50) NOT NULL COMMENT '权限名称（如：文章编辑）',
  `code` VARCHAR(100) NOT NULL COMMENT '权限代码（如：content:article:edit）',
  `module` VARCHAR(30) NOT NULL DEFAULT 'content' COMMENT '所属模块（分组展示用）',
  `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '权限描述',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_code_unique` (`code`),
  KEY `permissions_parent_id_index` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限表';

-- --------------------------------------------------------------------------
-- Table structure for `role_permissions`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` BIGINT UNSIGNED NOT NULL COMMENT '角色ID',
  `permission_id` BIGINT UNSIGNED NOT NULL COMMENT '权限ID',
  `is_denied` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=允许，1=拒绝（拒绝优先，覆盖性授权）',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`, `permission_id`),
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色权限关联表';

-- --------------------------------------------------------------------------
-- Table structure for `user_roles`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT '用户ID',
  `role_id` BIGINT UNSIGNED NOT NULL COMMENT '角色ID',
  `data_scope` VARCHAR(20) NOT NULL DEFAULT 'self' COMMENT '数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`, `role_id`),
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户角色关联表';

-- --------------------------------------------------------------------------
-- Table structure for `content_models`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `content_models`;
CREATE TABLE `content_models` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL COMMENT '模型名称（显示用，如：招聘信息）',
  `alias` VARCHAR(50) NOT NULL COMMENT '模型别名（代码/URL用，如：recruitment）',
  `table_name` VARCHAR(50) NOT NULL COMMENT '对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）',
  `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '模型描述',
  `is_system` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否系统内置：1是（不可删除），0否',
  `is_commentable` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0停用，1启用',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '排序权重',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_models_alias_unique` (`alias`),
  UNIQUE KEY `content_models_table_name_unique` (`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容模型表';

-- --------------------------------------------------------------------------
-- Table structure for `model_fields`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `model_fields`;
CREATE TABLE `model_fields` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `model_id` BIGINT UNSIGNED NOT NULL COMMENT '所属模型ID',
  `field_name` VARCHAR(60) NOT NULL COMMENT '字段业务英文名（如：salary）',
  `column_name` VARCHAR(60) NOT NULL COMMENT '物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）',
  `field_label` VARCHAR(100) NOT NULL COMMENT '字段显示标签（如：薪资范围）',
  `field_type` VARCHAR(30) NOT NULL COMMENT '字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json',
  `column_type` VARCHAR(30) NOT NULL DEFAULT 'varchar(255)' COMMENT '数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json',
  `default_value` TEXT NULL DEFAULT NULL COMMENT '默认值',
  `is_required` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否必填：0否，1是',
  `is_unique` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '值是否唯一：0否，1是',
  `validation_rules` JSON NULL DEFAULT NULL COMMENT '校验规则（JSON），如：{\"max\":100,\"regex\":\"^[A-Z]\"}',
  `extra_config` JSON NULL DEFAULT NULL COMMENT '额外配置（如select选项：{\"options\":[\"男\",\"女\"]}）',
  `sort_order` INT NOT NULL DEFAULT 0 COMMENT '表单显示排序',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `model_fields_model_id_field_name_unique` (`model_id`, `field_name`),
  UNIQUE KEY `model_fields_model_id_column_name_unique` (`model_id`, `column_name`),
  CONSTRAINT `model_fields_model_id_foreign` FOREIGN KEY (`model_id`) REFERENCES `content_models` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='模型字段表';

-- --------------------------------------------------------------------------
-- Table structure for `contents`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `contents`;
CREATE TABLE `contents` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `model_id` BIGINT UNSIGNED NOT NULL COMMENT '所属模型ID',
  `title` VARCHAR(200) NOT NULL COMMENT '内容标题',
  `slug` VARCHAR(200) NOT NULL COMMENT 'URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）',
  `author_id` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '发布者用户ID',
  `status` VARCHAR(20) NOT NULL DEFAULT 'draft' COMMENT '状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站',
  `visibility` VARCHAR(20) NOT NULL DEFAULT 'public' COMMENT '可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）',
  `password` VARCHAR(255) NULL DEFAULT NULL COMMENT '密码保护口令（visibility=password 时使用，哈希存储）',
  `views` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '浏览量计数',
  `comment_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论数（审核通过的冗余计数，避免列表页逐条COUNT）',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '手动排序权重（数值越大越靠前）',
  `is_top` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）',
  `published_at` DATETIME NULL DEFAULT NULL COMMENT '计划/实际发布时间',
  `audit_status` VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT '审核状态：pending待审核/approved通过/rejected驳回',
  `audit_remark` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '审核备注（驳回原因）',
  `auditor_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT '审核人ID',
  `audited_at` DATETIME NULL DEFAULT NULL COMMENT '审核时间',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contents_slug_unique` (`slug`),
  KEY `contents_model_id_status_published_at_index` (`model_id`, `status`, `published_at`),
  KEY `contents_author_id_status_index` (`author_id`, `status`),
  KEY `contents_published_at_index` (`published_at`),
  CONSTRAINT `contents_model_id_foreign` FOREIGN KEY (`model_id`) REFERENCES `content_models` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容主表';

-- --------------------------------------------------------------------------
-- Table structure for `data_article`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `data_article`;
CREATE TABLE `data_article` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` BIGINT UNSIGNED NOT NULL COMMENT '关联内容主表ID（一对一）',
  `field_1` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '文章摘要（text）',
  `field_2` LONGTEXT NULL DEFAULT NULL COMMENT '正文内容（rich_text）',
  `field_3` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '封面图，存附件ID或URL（image）',
  `_extra` JSON NULL DEFAULT NULL COMMENT '预留JSON扩展字段（未建模数据兜底）',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_article_content_id_unique` (`content_id`),
  CONSTRAINT `data_article_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章模型数据表';

-- --------------------------------------------------------------------------
-- Table structure for `comments`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` BIGINT UNSIGNED NOT NULL COMMENT '关联内容主表ID（全模型通用）',
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT '评论者用户ID（NULL表示游客）',
  `parent_id` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '父评论ID（0=顶级评论，支持楼中楼）',
  `reply_to_user_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT '被回复用户ID（渲染\"回复@xxx\"用）',
  `author_name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '评论者昵称（游客填写；登录用户冗余，防销号后无记录）',
  `author_email` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '评论者邮箱（游客填写，用于头像/回复通知）',
  `author_url` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '评论者主页URL',
  `content` TEXT NOT NULL COMMENT '评论内容（纯文本；敏感词/反垃圾由插件钩子处理）',
  `ip` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '评论者IP（反垃圾由插件处理）',
  `user_agent` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '评论者UA',
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT '状态：pending待审核/approved已通过/spam垃圾/trash回收站',
  `like_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '点赞数',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_content_id_status_created_at_index` (`content_id`, `status`, `created_at`),
  KEY `comments_parent_id_index` (`parent_id`),
  KEY `comments_user_id_index` (`user_id`),
  KEY `comments_status_created_at_index` (`status`, `created_at`),
  CONSTRAINT `comments_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='评论表';

-- --------------------------------------------------------------------------
-- Table structure for `taxonomies`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `taxonomies`;
CREATE TABLE `taxonomies` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL COMMENT '分类法名称（如：文章分类、产品系列）',
  `alias` VARCHAR(50) NOT NULL COMMENT '分类法别名（如：article_cat）',
  `model_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT '绑定的模型ID（NULL表示全局分类）',
  `is_hierarchical` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否支持层级：1是（分类目录），0否（标签）',
  `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '描述',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taxonomies_alias_unique` (`alias`),
  KEY `taxonomies_model_id_index` (`model_id`),
  CONSTRAINT `taxonomies_model_id_foreign` FOREIGN KEY (`model_id`) REFERENCES `content_models` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类法表';

-- --------------------------------------------------------------------------
-- Table structure for `terms`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `terms`;
CREATE TABLE `terms` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `taxonomy_id` BIGINT UNSIGNED NOT NULL COMMENT '所属分类法ID',
  `name` VARCHAR(100) NOT NULL COMMENT '分类项名称（如：科技、体育）',
  `slug` VARCHAR(100) NOT NULL COMMENT '分类项别名（URL友好）',
  `parent_id` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID（0代表顶级）',
  `description` TEXT NULL DEFAULT NULL COMMENT '分类项描述',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '排序权重',
  `content_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `terms_taxonomy_id_slug_unique` (`taxonomy_id`, `slug`),
  KEY `terms_parent_id_index` (`parent_id`),
  CONSTRAINT `terms_taxonomy_id_foreign` FOREIGN KEY (`taxonomy_id`) REFERENCES `taxonomies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类项表';

-- --------------------------------------------------------------------------
-- Table structure for `term_relationships`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `term_relationships`;
CREATE TABLE `term_relationships` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` BIGINT UNSIGNED NOT NULL COMMENT '内容主表ID',
  `term_id` BIGINT UNSIGNED NOT NULL COMMENT '分类项ID',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '该内容在此分类下的自定义排序',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `term_relationships_content_id_term_id_unique` (`content_id`, `term_id`),
  KEY `term_relationships_term_id_index` (`term_id`),
  CONSTRAINT `term_relationships_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `term_relationships_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容分类关联表';

-- --------------------------------------------------------------------------
-- Table structure for `nav_menus`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `nav_menus`;
CREATE TABLE `nav_menus` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL COMMENT '菜单名称（如：主导航）',
  `alias` VARCHAR(50) NOT NULL COMMENT '菜单标识（如：main_nav）',
  `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '描述',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nav_menus_alias_unique` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单集表';

-- --------------------------------------------------------------------------
-- Table structure for `nav_items`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `nav_items`;
CREATE TABLE `nav_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` BIGINT UNSIGNED NOT NULL COMMENT '所属菜单集',
  `parent_id` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID（0代表顶级）',
  `title` VARCHAR(100) NOT NULL COMMENT '菜单显示标题',
  `link_type` VARCHAR(20) NOT NULL DEFAULT 'custom' COMMENT '链接类型：custom自定义/content内容/term分类',
  `link_value` VARCHAR(255) NOT NULL COMMENT '链接目标值（自定义URL 或 content_id/term_id）',
  `open_type` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '打开方式：0本窗口，1新窗口',
  `icon` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '小图标CSS类',
  `is_active` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用：1是，0否',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '排序权重',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nav_items_menu_id_parent_id_index` (`menu_id`, `parent_id`),
  CONSTRAINT `nav_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `nav_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单项表';

-- --------------------------------------------------------------------------
-- Table structure for `attachments`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `attachments`;
CREATE TABLE `attachments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uploader_id` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传者ID',
  `file_name` VARCHAR(255) NOT NULL COMMENT '原始文件名',
  `file_path` VARCHAR(255) NOT NULL COMMENT '物理存储相对路径',
  `storage_driver` VARCHAR(20) NOT NULL DEFAULT 'local' COMMENT '存储驱动：local/oss/cos/s3',
  `storage_bucket` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '存储桶名称（仅云存储有效）',
  `cdn_url` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'CDN加速访问URL',
  `file_size` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（字节）',
  `mime_type` VARCHAR(100) NOT NULL COMMENT 'MIME类型（如：image/jpeg）',
  `width` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片宽度（仅图片）',
  `height` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片高度（仅图片）',
  `alt_text` VARCHAR(255) NULL DEFAULT NULL COMMENT 'SEO替代文本',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_uploader_id_index` (`uploader_id`),
  KEY `attachments_mime_type_index` (`mime_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='附件表';

-- --------------------------------------------------------------------------
-- Table structure for `attachment_relations`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `attachment_relations`;
CREATE TABLE `attachment_relations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `attachment_id` BIGINT UNSIGNED NOT NULL COMMENT '附件ID',
  `content_id` BIGINT UNSIGNED NOT NULL COMMENT '关联的内容ID',
  `field_key` VARCHAR(60) NOT NULL DEFAULT 'content' COMMENT '关联到内容的哪个字段（如：封面图、详情图集）',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '在该内容下的排序',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attachment_relations_attachment_id_content_id_field_key_unique` (`attachment_id`, `content_id`, `field_key`),
  KEY `attachment_relations_content_id_index` (`content_id`),
  CONSTRAINT `attachment_relations_attachment_id_foreign` FOREIGN KEY (`attachment_id`) REFERENCES `attachments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attachment_relations_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容附件关联表';

-- --------------------------------------------------------------------------
-- Table structure for `seo_meta`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `seo_meta`;
CREATE TABLE `seo_meta` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `target_type` VARCHAR(20) NOT NULL COMMENT '目标类型：content/term/custom_page',
  `target_id` BIGINT UNSIGNED NOT NULL COMMENT '对应的目标实体ID',
  `title` VARCHAR(200) NOT NULL DEFAULT '' COMMENT 'SEO标题（浏览器Tab显示）',
  `keywords` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'SEO关键词（逗号分隔）',
  `description` VARCHAR(500) NOT NULL DEFAULT '' COMMENT 'SEO描述（搜索结果展示）',
  `canonical_url` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '权威链接（防止重复页）',
  `robots` VARCHAR(100) NOT NULL DEFAULT 'index,follow' COMMENT '机器人抓取策略',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_meta_target_type_target_id_unique` (`target_type`, `target_id`),
  KEY `seo_meta_target_id_index` (`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='SEO元数据表';

-- --------------------------------------------------------------------------
-- Table structure for `page_templates`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `page_templates`;
CREATE TABLE `page_templates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_name` VARCHAR(100) NOT NULL COMMENT '模板名称',
  `template_code` VARCHAR(50) NOT NULL COMMENT '模板代码（唯一标识）',
  `category` VARCHAR(30) NOT NULL DEFAULT 'page' COMMENT '类别：page页面/post文章/term分类模板',
  `preview_image` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '预览图URL',
  `content` LONGTEXT NULL DEFAULT NULL COMMENT '模板内容（HTML/JSON结构）',
  `is_default` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否默认模板',
  `is_system` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否系统内置',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0停用，1启用',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_templates_template_code_unique` (`template_code`),
  KEY `page_templates_category_index` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='页面模板表';

-- --------------------------------------------------------------------------
-- Table structure for `blocks`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `blocks`;
CREATE TABLE `blocks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `block_name` VARCHAR(100) NOT NULL COMMENT '区块名称',
  `block_type` VARCHAR(30) NOT NULL COMMENT '区块类型：header/footer/banner/content/sidebar/custom',
  `content` LONGTEXT NOT NULL COMMENT '区块内容（HTML/JSON）',
  `css` TEXT NULL DEFAULT NULL COMMENT '自定义CSS样式',
  `js` TEXT NULL DEFAULT NULL COMMENT '自定义JS脚本',
  `is_global` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否全局区块（全站复用）：1是，0否',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0停用，1启用',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blocks_block_type_index` (`block_type`),
  KEY `blocks_is_global_index` (`is_global`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='区块表';

-- --------------------------------------------------------------------------
-- Table structure for `form_templates`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `form_templates`;
CREATE TABLE `form_templates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL COMMENT '表单名称（如：在线报名表）',
  `alias` VARCHAR(50) NOT NULL COMMENT '表单标识（用于代码调用）',
  `fields_config` JSON NOT NULL COMMENT '字段配置（JSON数组）：字段名、类型、校验规则、选项等',
  `submit_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '提交次数统计',
  `is_active` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用：1是，0否',
  `success_message` VARCHAR(255) NOT NULL DEFAULT '提交成功！' COMMENT '提交成功提示语',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `form_templates_alias_unique` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='表单模板表';

-- --------------------------------------------------------------------------
-- Table structure for `form_submissions`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `form_submissions`;
CREATE TABLE `form_submissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_id` BIGINT UNSIGNED NOT NULL COMMENT '关联表单模板ID',
  `submission_data` JSON NOT NULL COMMENT '用户提交的具体表单数据（JSON）',
  `submitter_ip` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '提交者IP',
  `user_agent` VARCHAR(255) NULL DEFAULT NULL COMMENT '提交者UA',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `form_submissions_form_id_index` (`form_id`),
  KEY `form_submissions_created_at_index` (`created_at`),
  CONSTRAINT `form_submissions_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `form_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='表单提交表';

-- --------------------------------------------------------------------------
-- Table structure for `ad_positions`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `ad_positions`;
CREATE TABLE `ad_positions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL COMMENT '广告位名称（如：首页Banner）',
  `code` VARCHAR(50) NOT NULL COMMENT '广告位代码（如：home_banner，模板调用用）',
  `width` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '建议宽度（像素）',
  `height` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '建议高度（像素）',
  `ad_type` VARCHAR(20) NOT NULL DEFAULT 'image' COMMENT '支持的广告类型：image/text/video/html',
  `max_count` INT UNSIGNED NOT NULL DEFAULT 1 COMMENT '该广告位最多展示广告数量',
  `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '广告位描述',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0停用，1启用',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ad_positions_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='广告位表';

-- --------------------------------------------------------------------------
-- Table structure for `ads`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `ads`;
CREATE TABLE `ads` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `position_id` BIGINT UNSIGNED NOT NULL COMMENT '所属广告位ID',
  `title` VARCHAR(100) NOT NULL COMMENT '广告标题',
  `ad_type` VARCHAR(20) NOT NULL DEFAULT 'image' COMMENT '广告类型：image/text/video/html',
  `cover_image` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '广告图片/视频封面URL',
  `content` TEXT NULL DEFAULT NULL COMMENT '广告内容（纯文本或HTML代码）',
  `link_url` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '广告跳转链接',
  `link_target` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '打开方式：0本窗口，1新窗口',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '展示排序（数值越小越靠前）',
  `start_time` DATETIME NULL DEFAULT NULL COMMENT '投放开始时间（NULL表示立即开始）',
  `end_time` DATETIME NULL DEFAULT NULL COMMENT '投放结束时间（NULL表示永久，过期由时间判断）',
  `display_limit` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '展示次数上限（0不限）',
  `click_limit` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '点击次数上限（0不限）',
  `display_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '实际展示次数',
  `click_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '实际点击次数',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0停用，1启用',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ads_position_id_status_index` (`position_id`, `status`),
  KEY `ads_start_time_end_time_index` (`start_time`, `end_time`),
  CONSTRAINT `ads_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `ad_positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='广告表';

-- --------------------------------------------------------------------------
-- Table structure for `friend_links`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `friend_links`;
CREATE TABLE `friend_links` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(50) NOT NULL DEFAULT 'default' COMMENT '链接分类（如：合作伙伴、友情链接）',
  `site_name` VARCHAR(100) NOT NULL COMMENT '网站名称',
  `site_url` VARCHAR(255) NOT NULL COMMENT '网站URL',
  `logo_url` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '网站Logo URL',
  `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '网站描述',
  `contact_email` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '联系人邮箱',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '排序权重',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态：0待审核，1已审核，2已拒绝',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `friend_links_status_index` (`status`),
  KEY `friend_links_category_index` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='友情链接表';

-- --------------------------------------------------------------------------
-- Table structure for `content_push_queue`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `content_push_queue`;
CREATE TABLE `content_push_queue` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` BIGINT UNSIGNED NOT NULL COMMENT '被推送的内容ID',
  `push_type` VARCHAR(30) NOT NULL COMMENT '推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss',
  `push_data` JSON NULL DEFAULT NULL COMMENT '推送数据的最终形态（预处理后JSON）',
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT '状态：pending/processing/success/failed（执行与重试走 Laravel 队列）',
  `retry_count` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '已重试次数',
  `max_retries` TINYINT UNSIGNED NOT NULL DEFAULT 3 COMMENT '最大重试次数',
  `error_message` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '失败时的错误信息',
  `finished_at` DATETIME NULL DEFAULT NULL COMMENT '完成时间',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_push_queue_status_index` (`status`),
  KEY `content_push_queue_content_id_index` (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容推送记录表';

-- --------------------------------------------------------------------------
-- Table structure for `short_links`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `short_links`;
CREATE TABLE `short_links` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `short_code` VARCHAR(20) NOT NULL COMMENT '短链代码（如：abc123）',
  `target_url` VARCHAR(500) NOT NULL COMMENT '原始目标URL',
  `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '链接标题/备注',
  `click_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '点击次数',
  `qr_code_path` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '二维码图片存储路径',
  `expire_at` DATETIME NULL DEFAULT NULL COMMENT '过期时间（NULL永不过期）',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0停用，1启用',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_links_short_code_unique` (`short_code`),
  KEY `short_links_expire_at_index` (`expire_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='短链接表';

-- --------------------------------------------------------------------------
-- Table structure for `short_link_clicks`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `short_link_clicks`;
CREATE TABLE `short_link_clicks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `short_link_id` BIGINT UNSIGNED NOT NULL COMMENT '短链接ID',
  `click_ip` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '点击者IP',
  `user_agent` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '浏览器UA',
  `referer` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '来源页',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `short_link_clicks_short_link_id_index` (`short_link_id`),
  KEY `short_link_clicks_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='短链接点击明细表';

-- --------------------------------------------------------------------------
-- Table structure for `statistics_daily`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `statistics_daily`;
CREATE TABLE `statistics_daily` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `stat_date` DATE NOT NULL COMMENT '统计日期',
  `new_contents` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '新增内容数',
  `published_contents` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '发布内容数',
  `total_contents` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '累计内容总数',
  `total_views` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '全站浏览量',
  `new_comments` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '新增评论数',
  `new_users` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '新增注册用户数',
  `active_users` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '活跃用户数（登录/操作）',
  `total_users` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '累计注册用户数',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `statistics_daily_stat_date_unique` (`stat_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='每日统计表';

-- --------------------------------------------------------------------------
-- Table structure for `options`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `option_key` VARCHAR(100) NOT NULL COMMENT '配置键名（storage_config/smtp_config/comment_config等）',
  `option_value` LONGTEXT NOT NULL COMMENT '配置值（支持JSON复杂结构）',
  `autoload` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '启动时自动加载：0否，1是（配合 Laravel Cache 预热）',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `options_option_key_unique` (`option_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='全局配置表';

-- --------------------------------------------------------------------------
-- Table structure for `content_revisions`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `content_revisions`;
CREATE TABLE `content_revisions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` BIGINT UNSIGNED NOT NULL COMMENT '内容主表ID',
  `author_id` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改人',
  `revision_data` JSON NOT NULL COMMENT '修改时的全量数据快照（JSON）',
  `remark` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '修改备注',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修订时间',
  PRIMARY KEY (`id`),
  KEY `content_revisions_content_id_index` (`content_id`),
  CONSTRAINT `content_revisions_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容版本表';

-- --------------------------------------------------------------------------
-- Table structure for `recycle_bin`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `recycle_bin`;
CREATE TABLE `recycle_bin` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `deleted_by` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除人用户ID',
  `target_type` VARCHAR(30) NOT NULL COMMENT '原对象类型：content/term/attachment/user/form_submission/comment',
  `target_id` VARCHAR(64) NOT NULL COMMENT '原对象ID',
  `original_data` JSON NOT NULL COMMENT '删除前的全量数据快照（JSON）',
  `restore_data` JSON NULL DEFAULT NULL COMMENT '恢复时所需的数据映射（如恢复时需新建ID）',
  `retention_days` INT UNSIGNED NOT NULL DEFAULT 30 COMMENT '保留天数（超时由 Scheduler 物理清除）',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '删除时间',
  `expire_at` DATETIME GENERATED ALWAYS AS (DATE_ADD(created_at, INTERVAL retention_days DAY)) VIRTUAL COMMENT '过期时间（虚拟生成列）',
  PRIMARY KEY (`id`),
  KEY `recycle_bin_target_type_target_id_index` (`target_type`, `target_id`),
  KEY `recycle_bin_expire_at_index` (`expire_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='回收站表';

-- --------------------------------------------------------------------------
-- Table structure for `audit_logs`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT '操作用户ID',
  `user_name` VARCHAR(60) NOT NULL DEFAULT '' COMMENT '操作用户名（冗余，防用户被删后无记录）',
  `client_ip` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '客户端IP（支持IPv6）',
  `user_agent` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '客户端UA信息',
  `request_id` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '请求追踪ID（关联一次请求的所有日志）',
  `event_type` VARCHAR(50) NOT NULL COMMENT '事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT',
  `target_type` VARCHAR(30) NOT NULL COMMENT '目标类型：content/term/user/attachment/config/comment/form_submission',
  `target_id` VARCHAR(64) NOT NULL COMMENT '目标ID（可能是数字或UUID）',
  `target_name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '目标名称（冗余，便于展示）',
  `old_value` JSON NULL DEFAULT NULL COMMENT '修改前的数据快照（JSON）',
  `new_value` JSON NULL DEFAULT NULL COMMENT '修改后的数据快照（JSON）',
  `operation_result` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '操作结果：0失败，1成功',
  `error_message` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '失败时的错误信息',
  `created_at` TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) COMMENT '创建时间（毫秒精度，只增不改）',
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_index` (`user_id`),
  KEY `audit_logs_target_type_target_id_index` (`target_type`, `target_id`),
  KEY `audit_logs_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='审计日志表';

-- --------------------------------------------------------------------------
-- Table structure for `sites`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_name` VARCHAR(100) NOT NULL COMMENT '站点名称',
  `site_code` VARCHAR(50) NOT NULL COMMENT '站点代码（子域名或标识）',
  `domain` VARCHAR(200) NOT NULL COMMENT '主域名（如：www.example.com）',
  `domains` JSON NULL DEFAULT NULL COMMENT '附加域名列表（JSON数组）',
  `site_logo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '站点Logo',
  `favicon` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '站点图标',
  `timezone` VARCHAR(50) NOT NULL DEFAULT 'Asia/Shanghai' COMMENT '时区',
  `language` VARCHAR(10) NOT NULL DEFAULT 'zh_CN' COMMENT '默认语言',
  `template_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT '当前使用的模板ID（关联 page_templates，逻辑关联）',
  `config` JSON NULL DEFAULT NULL COMMENT '站点配置（SEO默认值、社交分享等）',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0停用，1启用',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sites_site_code_unique` (`site_code`),
  UNIQUE KEY `sites_domain_unique` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='站点表';

-- --------------------------------------------------------------------------
-- Table structure for `redirects`
-- --------------------------------------------------------------------------
DROP TABLE IF EXISTS `redirects`;
CREATE TABLE `redirects` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `source_path` VARCHAR(500) NOT NULL COMMENT '来源路径（站内相对路径，以 / 开头）',
  `target_path` VARCHAR(500) NOT NULL COMMENT '目标路径（相对路径或完整URL）',
  `status_code` SMALLINT UNSIGNED NOT NULL DEFAULT 301 COMMENT 'HTTP状态码：301永久重定向，302临时重定向',
  `hits` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '命中次数（冗余计数，事务内维护）',
  `last_hit_at` DATETIME NULL DEFAULT NULL COMMENT '最后命中时间',
  `remark` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '备注（如：slug 改版、栏目迁移）',
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0停用，1启用',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `redirects_source_path_unique` (`source_path`),
  KEY `redirects_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='重定向表';

SET FOREIGN_KEY_CHECKS = 1;
