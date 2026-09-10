# HugeCMS 开发约定

> 本文档约定数据库层无法表达、但直接影响正确性的业务规则。所有内容域代码（Model / Service / Job）实现前必读。
> 关联设计：`docs/1.md`~`10.md`（数据库演进）、`database/migrations/`（36 张表）、`database/seeders/CmsSeeder.php`。

---

## 一、删除与恢复（回收站快照完整性）

### 问题背景

`contents` 的关联表（comments、term_relationships、attachment_relations、seo_meta）外键均为 `CASCADE`。若"删除"仅快照主表 + 模型数据表（`data_{alias}`）行，则**彻底删除时关联数据被级联物理清除，恢复后内容残缺**（评论丢失、分类归属丢失、SEO 丢失）。

### 约定

1. **软删除（进回收站）**：`contents.status = 'trash'`，同时向 `recycle_bin` 写入完整快照。快照 JSON 结构：

```jsonc
{
  "content": { /* contents 主表行 */ },
  "data":    { /* 模型数据表 data_{alias} 行（含 field_* 全部字段） */ },
  "relations": {
    "comments":             [ /* comments 行 */ ],
    "term_relationships":   [ /* ... */ ],
    "attachment_relations": [ /* ... */ ],
    "seo_meta":             { /* ... */ }
  }
}
```

2. **恢复**：按快照逆序重建——主行（可沿用原 ID）→ 模型数据表行 → 各关联表。恢复前校验 slug 唯一性，冲突时追加后缀并提示。
3. **物理清除**（回收站过期 / 手动彻底删除）：先删关联表（或依赖级联），再删模型数据表行与主表，最后删 recycle_bin 记录。**注意：多态表（seo_meta 等）无外键、不随级联清除，必须显式删除**（参考实现 `RecycleBinService::purge`）；快照重建同理需先清理同目标残留记录保证幂等。
4. `nav_items.link_value` 直接存 content_id（无外键、不级联）：恢复后菜单自动恢复指向；物理删除后菜单悬空，由"菜单健康检查"命令标记失效项。
5. 评论、附件等其他实体的删除复用同一模式：**凡有 CASCADE 关联的实体，快照必须覆盖级联范围**。

---

## 二、内容状态机（status × audit_status × visibility）

三个字段职责不同，禁止混用：

| 字段 | 职责 | 值域 |
|---|---|---|
| `status` | 发布流程状态 | draft / pending(定时待发) / published / archived / trash |
| `audit_status` | 审核流程状态 | pending / approved / rejected |
| `visibility` | 访问可见性 | public / password / private |

### 流转规则

```
新建 → draft
draft ──提交审核──→ [audit_status=pending]
audit_status=approved ──立即发布──→ status=published, published_at=now
audit_status=approved ──定时发布──→ status=pending（Scheduler 到点置 published）
audit_status=rejected → 退回 draft（audit_remark 记录原因）
published ──下线──→ archived ──重新发布──→ published
任意状态 ──进回收站──→ status=trash（恢复回原状态）
```

约束：
- 前台可见的完备条件：`status=published AND audit_status=approved AND visibility!=private`（password 需校验口令）。
- 拥有 `content:publish` 权限的角色（编辑及以上）可跳过审核：保存即 `audit_status=approved`（应用层判定，数据库默认值仍为 pending）。
- 修改已发布内容默认产生新修订（content_revisions），不直接覆盖。

---

## 三、附件引用双路径约定

附件被引用有两条路径，语义不同：

| 路径 | 用途 | 引用跟踪 | 删除保护 |
|---|---|---|---|
| `attachment_relations` 表 | 多值字段：图集、详情组图 | 有（含 field_key、排序） | 有：删除附件前 COUNT 校验 |
| 字段直存（如 `data_{alias}.field_3` 存附件 ID/URL） | 单值字段：封面图、logo | 无 | 无：删除前需应用层扫描字段引用 |

约定：
1. 新建模型字段时，**多值图片类字段走 relations 表**（field_type=image + `extra_config={"multiple":true}`），单值封面可直存。
2. 附件删除流程：① 查 `attachment_relations` 引用计数 → ② 扫描 `model_fields` 中 field_type=image 的单值字段（按 attachment id 检索）→ ③ 均为 0 才允许物理删除，否则提示引用位置。
3. 内容删除进回收站时，快照须包含 attachment_relations（见约定一），但**不快照附件本体**——附件独立于内容生命周期。

---

## 四、搜索方案决策

**不在 MySQL 上建 FULLTEXT 索引**（默认解析器不支持中文分词，ngram parser 效果与运维成本不划算）。

- MVP 阶段：`contents.title` + `LIKE '%关键词%'`（数据量 < 10 万可接受），配合 `(model_id, status)` 索引。
- 正式阶段：**Laravel Scout + Meilisearch**（中文分词友好、部署轻量、零表结构变更）。可索引列：title、summary（field 映射）、正文纯文本摘要。
- 切换时新建 Scout 索引 + 全量导入命令，无回滚风险。

---

## 五、钩子 / 扩展点规划（未来插件系统的基础）

不建插件表、不做插件市场，但**核心流程从第一天就走 Laravel Event**，保证未来插件可订阅。首批事件契约：

| 事件名 | 触发点 | 载荷 |
|---|---|---|
| `ContentSaving` / `ContentSaved` | 内容保存前/后 | Content 实体、变更字段 |
| `ContentPublishing` / `ContentPublished` | 发布前/后 | Content 实体 |
| `CommentPosting` / `CommentPosted` | 评论入库前/后 | Comment 载荷（Posting 可 veto 拦截） |
| `FormValidating` / `FormSubmitted` | 表单校验/提交 | 表单模板、提交数据 |
| `UserRegistered` | 注册成功 | User |
| `AttachmentUploaded` | 上传完成 | Attachment |
| `RenderingHead` / `RenderingFooter` / `RenderingContent` | 模板渲染输出点 | 输出流（可追加 HTML） |
| `RouteResolving` | 路由解析（含 slug 查找、redirects 命中） | Request |

实现约束：
- 事件类统一放 `app/Events/`，监听器放 `app/Listeners/`；`RenderingXxx` 走队列需谨慎（影响 TTFB）。
- 未来插件系统 =监听器的数据库注册表 + 加载器，现有事件契约无需变更。
- **插件化边界**：运营增长类表（ads / short_links / content_push_queue / friend_links）保留在核心库，作为插件机制的首批可插拔模块；站内信、部门、多语言、敏感词、IP黑名单、开放API、导入导出任务、健康告警共 14 张表已精简移除，需要时以独立迁移按需恢复（含对应权限码与后台菜单）。

---

## 六、应用架构注意事项（落地必查清单）

1. **动态表 × Eloquent**：模型数据表按 `data_{alias}` 命名（如 `data_article`），无固定 Model。实现 `DynamicContent` 工厂：`DynamicContent::forModel(ContentModel $m)` 返回绑定 `table_name` 的匿名 Model 实例；`model_fields` 定义全量缓存（Laravel Cache，模型变更时失效）。**命名与不可变规则**：物理表名在模型创建时由 alias 生成并写入 `table_name`，此后不可变（alias 变更不联动 RENAME TABLE）；alias 校验 `^[a-z][a-z0-9_]{0,39}$`（加 `data_` 前缀 ≤ 45 字符，远低于 MySQL 64 位标识符上限）。
2. **冗余计数事务化**：`comment_count / content_count(terms) / submit_count / display_count / hits / click_count` 的增减必须包在数据库事务或队列 Job 中串行执行，防并发漂移；`terms.content_count` 统计口径为 `status=published AND audit_status=approved`，并提供 `terms:recount` 全量重算命令兜底。
3. **审计写放大**：`audit_logs.old_value / new_value` 只存**变更字段的 diff**；longtext 字段超 64KB 截断存摘要 + 哈希指纹。
4. **超管旁路**：`Auth::viaRequest` 后注册 `Gate::before(fn ($user) => $user->isSuperAdmin() ? true : null)`，不依赖"超管被授予全部权限"的种子数据——新增权限码时零回填。
5. **浏览量防刷**：`views` 裸计数不可信。Redis SETNX `view:{content_id}:{ip_hash}`（TTL 24h）去重 → 计数累加 Redis Hash → 定时批量回写 `contents.views`。
6. **部署清单**：`queue:work`（supervisor 守护）、`schedule:run`（单条 cron，每分钟）、如用 Redis 队列加 `horizon`；`php artisan migrate` 前置备份。
7. **回收站清理 / 统计聚合 / 定时发布** 三个 Scheduler 任务见约定一、二；统计任务幂等（按日 upsert `statistics_daily`）。
