# 数据表设计技术文档

> 全库现状：17 个迁移文件（4 个框架 + 13 个业务域）/ 116 张业务表（含 users 主表）+ 1 视图（另有 8 张 Laravel 框架表：password_reset_tokens/sessions/cache×2/jobs×3/notifications），`migrate:fresh` 全部通过。
> 数据库蓝本：ECShop 2.7.3（逐表映射见 [ecshop-2.7.3-tables-domain-mapping.md](ecshop-2.7.3-tables-domain-mapping.md)），并按现代电商与微信私域场景重构扩展。

## 一、业务域全景

迁移文件按业务域分组，一个域一个文件，位于 `database/migrations/`。

| 域 | 迁移文件 | 表 |
|---|---|---|
| 用户 | `051854_create_user_tables.php` | user_profiles、user_addresses、user_favorites、user_sms_codes、user_socials、user_ranks、user_accounts、user_account_logs、user_point_logs、user_withdraw_orders、user_browsing_histories、user_sign_ins（users 由基础迁移创建） |
| 管理 | `051855_create_admin_tables.php` | admin_roles、admin_users、admin_user_roles、admin_permissions、admin_role_permissions |
| 系统 | `051856_create_system_tables.php` | system_settings、system_attachments、system_dicts、system_dict_items、system_regions、system_login_logs、system_operation_logs、system_sensitive_words、system_sms_logs、system_ip_blacklists、system_apps、system_risk_rules、system_risk_records |
| 站点 | `051857_create_website_tables.php` | site_ad_positions、site_ads、site_navs、site_links、site_search_keywords、site_daily_statistics |
| 内容 | `051903_create_content_tables.php` | content_categories、content_articles、content_article_bodies、content_tags、content_article_tags、content_comments、content_comment_likes、content_pages、content_topics、content_topic_items |
| 商品 | `051904_create_product_tables.php` | product_categories、product_brands、products、product_skus、product_types、product_attributes、product_attribute_values、product_member_prices、product_volume_prices、product_stock_logs、product_reviews、product_stock_reminders |
| 订单 | `051905_create_order_tables.php` | orders、order_items、order_promotions、order_carts、order_shipments、order_shipment_items、order_refunds、order_refund_items、order_refund_logs、order_logs、order_invoices |
| 营销 | `054330_create_marketing_tables.php` | marketing_coupons、marketing_user_coupons、marketing_promotions、marketing_promotion_scopes、marketing_promotion_ladders、marketing_flash_sales、marketing_flash_sale_items、marketing_flash_sale_logs、marketing_group_buy_activities、marketing_group_buy_items、marketing_group_buy_teams、marketing_group_buy_team_members、marketing_assist_activities、marketing_assist_records、marketing_assist_helpers、marketing_bargain_activities、marketing_bargain_items、marketing_bargain_records、marketing_bargain_helpers、marketing_distribution_commission_rules、marketing_distributors、marketing_distribution_relations、marketing_distribution_commissions |
| 物流 | `054331_create_logistics_tables.php` | shipping_methods、shipping_areas、shipping_area_regions、shipping_shipment_tracks |
| 消息 | 框架迁移 `0001_01_01_000003_create_notifications_table.php` | notifications（Laravel Notification database channel，无自定义表；邮件走 Notification 邮件通道） |
| 支付 | `055447_create_payment_tables.php` | payments、payment_channels、payment_callbacks |
| 客服 | `092639_create_chat_tables.php` | chat_sessions、chat_messages、chat_services、chat_quick_replies、chat_bot_rules、chat_faqs |
| 微信 | `093942_create_wechat_tables.php` | wechat_fans、wechat_templates、wechat_subscriptions、wechat_messages、wechat_qrcodes、wechat_broadcasts、视图 v_notify_touch_logs |

## 二、设计规范（全库约定）

1. **域前缀命名**：表名 = 域前缀_业务名（user_ / system_ / site_ / content_ / product_ / order_ / marketing_ / shipping_ / payment_ / chat_ / wechat_），域内一目了然，跨域引用显式。
2. **索引命名**：`{uk|idx}_{表全名}_{字段}`，唯一键 uk_、普通索引 idx_，表名用全名不缩写。
3. **时间字段**：可变更业务表用 `timestamps()`（created_at/updated_at）；只追加型记录（日志/流水/明细）仅 `create_time`（useCurrent），语义即"不可变"。
4. **软删除**：资金单据与主数据加 softDeletes（orders、payments、order_refunds、user_addresses、products、product_skus 等），物理删除禁用；纯流水表靠归档治理。
5. **无外键**：全库不加 FK，一致性由应用层保证——状态机条件更新（`where status=旧值`）+ 资金/库存流水可追溯，避免外键锁与归档/分库受阻。
6. **快照哲学**：历史单据记当时值，不随主表变化——订单收货人与价格、评价商品名、分销绑定层级、推送 openid、退换货金额。
7. **JSON 字段**：统一用 `json` 类型列（products.images、refund_logs.images 等），配合 Eloquent array/object cast；范围圈选类多值不走 JSON——需按索引反查命中的一律拆子表（如 marketing_promotion_scopes，下单热路径反查活动命中）。
8. **金额**：`decimal(10,2)`；比例类 `decimal(5,2)`（百分数）或 `decimal(3,1)`（折扣率）。
9. **枚举**：tinyint + 注释枚举（注释即文档），需要运营可配时升级为字典表（system_dicts）。

## 三、核心机制

### 1. 库存与防超卖

- 常规商品：**库存唯一真相在 SKU**（`product_skus.stock/locked_stock`），可售 = stock - locked_stock；下单锁定用条件更新（`where stock - locked_stock >= n`）防并发超卖，支付成功核减、取消/超时释放；`product_stock_logs` 保留全量事后追溯。所有商品至少一条 SKU（单规格商品建默认 SKU），SPU 不存库存。
- 秒杀：**双层校验**——活动配额 `sold_stock` 原子自增（`where sold_stock + n <= total_stock`）同时锁定 SKU 真实库存（`where stock - locked_stock >= n`），任一失败整单回滚；支付成功核减 SKU `stock` 并释放 `locked_stock`，订单取消/超时回补 `sold_stock` 与 `locked_stock`。活动创建时校验 `total_stock <= SKU 可售库存`；`marketing_flash_sale_logs` 记 before/after 与订单号，超卖纠纷可审计。
- 到货提醒：`product_stock_reminders` 一用户一 SKU 一条（唯一键 upsert），补货后走触达降级链路。

### 2. 支付幂等（三层）

1. 回调层：`payment_callbacks` 按 `unique(channel, notify_id)` 拦第三方重发，全量留痕供对账与重放；
2. 支付单层：`payments.payment_no` 唯一 + 状态机条件更新（`where status=0`）防重复置成功；支付单记录 `expire_time`/`closed_at`，超时关单以条件更新置 `status=3`（已关闭）；
3. 业务层：订单/充值入账同样以状态字段条件更新，保证幂等；**下单幂等**由 `orders.request_token` 唯一约束保障（多 NULL 互不冲突），重复提交返回已存在订单。

`payments` 与业务解耦：`biz_type/biz_id` 同时服务订单支付与余额充值；`appid` 记录发起端，多端回调可定位。`payment_channels` 按 `(code, appid)` 配置——同一渠道、不同应用可用不同商户号。

### 3. 优惠拆分与退款返还

`order_promotions` 逐笔记录订单优惠明细（type：coupon / points / promotion，含名称快照与 related_id），使 `orders.discount_amount` 可拆解、每场活动真实让利可聚合。退款时按明细返还：券回 `marketing_user_coupons`（status 重置）、积分回用户（user_point_logs 流水）、佣金按保护期状态冲销（见资金机制）。优惠券发放以 `marketing_coupons.received_count` 条件更新（`received_count < total`）防超发，与用户券插入同事务；券有效期支持固定区间与"领取后 N 天"（`valid_type`/`valid_days`）。

### 4. 资金与积分

资金以独立账户 `user_accounts` 为准（balance 可负=欠款不可消费/提现，frozen 为提现在途；可用余额 = balance - frozen），users 表仅存积分余额。任何资金变动走 `UserAccountService` 唯一写路径：条件更新守卫（`UPDATE ... WHERE`，受影响行数=0 即业务规则失败）+ 落 `user_account_logs` 双快照流水（balance_after/frozen_after），`SUM(amount) === balance` 恒可对账。
提现走 `user_withdraw_orders` 状态机（10待审核→20审核通过→30打款中→40成功；10→50拒绝；30→60失败回退），申请即冻结、拒绝/失败解冻回退、成功冻结核销；打款回调以 `WHERE status=30` 条件更新幂等，`uk(channel, channel_order_no)` 防重复打款（多个 NULL 天然互不冲突）。充值不设申请单：在线支付走 `payments`（biz_type=recharge）回调入账，人工到账走 `adjust` 调账，`user_account_logs` 全程留痕。
佣金按 `marketing_distribution_commission_rules`（商品覆盖>分类默认>system_settings 全局兜底）计佣：订单完成后建待结算记录（settle_time=完成时间+保护期），到期结算入账；保护期内退款置失效（未动资金），已结算退款落负额冲销记录（reversal_of_id 回指原记录，唯一索引即冲销幂等键）并扣回入账（可扣成欠款），分销员 total/settled_commission 计数同步双扣。
积分流水独立 `user_point_logs`（有符号 + points_after 快照），users.points 为余额。风控由 `system_risk_rules`（condition_expr JSON 参数化）+ `system_risk_records`（命中留痕、申诉四态）承载，提现等资金动作前置检查。

### 5. 微信多端与触达降级

- 账号归一：小程序/公众号/开放平台授权统一落 `user_socials`（platform 约定值），UnionID 跨端打通；粉丝资产独立 `wechat_fans`（user_id 注册后回填）。
- 全表 appid 隔离：openid、模板、订阅授权、二维码均按 appid 唯一，支持多公众号/小程序矩阵，换绑不串数据；`system_apps` 为 appid 字典与应用注册中心（secret 加密存储）。
- 触达降级链路：微信（模板消息/一次性订阅消息）→ 站内信（Laravel notifications）→ 短信；`wechat_subscriptions` 是订阅消息次数账本（授权加一、发送减一）；视图 `v_notify_touch_logs` 统一三渠道触达记录（status 语义归一），零侵入。

### 6. 客服 IM 与机器人分流

实时推送由 GatewayWorker 承载，表只做持久化。会话分配：空闲优先（`chat_services.current_sessions < max_sessions` 且在线）；机器人关键词规则（chat_bot_rules，sort 升序命中）先答，转人工规则兜底；分配/转接等流转事件以 system 消息落 chat_messages，全程可追溯；`user_last_read_id/server_last_read_id` 会话级已读水位。

### 7. 裂变玩法（拼团/助力/砍价）

- 拼团：开团/参团以 `marketing_group_buy_team_members` 的 `uk(team_id,user_id)`（一团一单）+ `uk(order_no)`（一单一团）双重唯一防刷防重；`member_count` 与成员表同事务维护，人齐即条件更新 1→2（已成团），超时扫描 1→3（解散）；成团价按 SKU 配置（group_buy_items），下单快照进订单项 price，库存走常规防超卖。
- 助力：`marketing_assist_records` `uk(activity_id,user_id)` 一人一条参与记录，`helper_count` 条件自增达标触发发奖；发奖幂等靠状态条件更新（1→2 完成态不重复发）；奖励两类——券写 `marketing_user_coupons`、积分走 `users.points` 条件更新 + `user_point_logs` 流水。
- 砍价：`marketing_bargain_records.current_price` 每刀条件更新（`where current_price - cut >= floor_price`）防并发超砍，到底价 1→2 才可下单；`uk(order_no)` NULL 不参与唯一约束（未下单的砍价单互不冲突，下单回填后防一单多砍）；超时扫描 1→4（超时失败）。
- 防刷：助力/帮砍入口挂 `RiskService` 黑名单检查；超时扫描为 Service 方法（expireDue*），定时任务注册待调度接入。

### 8. 快照与删除策略

- **订单快照**：`order_items` 记录 `sku_no`/`sku_name`/`sku_specs`/`sku_image` 与商品名/图，历史订单可完整还原购买规格（商品改名/改价/下架不影响）。
- **地址快照**：`orders` 与 `user_addresses` 均存结构化省市区 + 行政区划码（物流面单用），`receiver_region` 保留合并展示；下单时从用户地址整体快照。
- **营销活动删除**：禁止物理删除有引用的活动（团/参与记录/用户券/订单优惠引用），删除前做引用守卫，有引用时改为停用（`status=0`）。仓内不引入软删除列——Repository 基于 Query Builder，无全局作用域，半吊子软删除会漏过滤。


## 四、可运营性设计

- **配置分层**：`.env`（基础设施）→ `system_apps`（应用身份/密钥）→ `payment_channels`（按端支付配置）→ `system_settings`（全局运营配置）。
- **统计分层**：明细在各业务表，`site_daily_statistics` 只做每日汇总加速层，口径变更可重算。
- **归因闭环**：带参二维码（wechat_qrcodes）记扫码/注册双计数 → 分享行为（marketing_share_records，只追加）→ 分销绑定（relations 快照）→ 订单归因（orders.attributed_user_id/attribution_type）→ 佣金流水（commissions），分享转化与渠道 ROI 可算。
- **审计完备**：订单日志、售后协商日志、操作日志、支付回调、库存流水、秒杀流水——事后一切可解释。
