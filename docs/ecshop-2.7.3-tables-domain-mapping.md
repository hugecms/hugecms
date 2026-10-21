# ECSHOP 2.7.3 数据表领域拆分与迁移映射

> 用途：ECShop 2.7.3（88 张表，前缀 `ecs_`）→ 本系统（ECBoot）领域架构的拆分与迁移映射，供评审。
> 依据：ECShop 2.7.3 官方安装 SQL 及社区数据字典（文末参考链接）。
> 状态：核心缺口已补充迁移，支付域已独立，通知复用 Laravel 集成；按需取舍项已于 2026-10-20 复审逐项拍板，见"十三"。

## 一、总览

| 领域 | 表数 | 新系统现状 |
|---|---|---|
| 商品域 Product | 17 | `product_*` 已含属性体系与价格策略 |
| 交易域 Order | 12 | `orders` 系列已含订单日志/发货明细/退货明细，支付已独立为支付域 |
| 营销域 Marketing | 9 | 已含优惠券/促销/秒杀（含流水）/两级分销共 9 表，affiliate_log 需求已由分销覆盖（见"十三"） |
| 用户域 User | 10 | `users`/`user_*` 已含会员等级与钱包，站内通知见消息域 |
| 内容域 Content | 5 | `content_*` 已有 |
| 运营域 Website | 13 | `site_*` 已含导航 |
| 物流域 Logistics | 4 | 已建 shipping_methods/shipping_areas/shipping_area_regions/shipping_shipment_tracks |
| 支付域 Payment | 2 | 已独立为 payments/payment_channels，支持订单支付与充值 |
| 消息域 Notify | 3 | Laravel 集成 notifications + email_subscriptions，不自建模板/队列 |
| 系统域 System | 13 | `system_*` / admin 域已有 |
| 合计 | **88** | |

## 二、商品域（17）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_goods | 商品主表 | products |
| ecs_category | 商品分类 | product_categories |
| ecs_brand | 品牌 | product_brands |
| ecs_goods_type | 商品类型（属性集） | product_types |
| ecs_attribute | 属性定义 | product_attributes |
| ecs_goods_attr | 商品属性值 | product_attribute_values |
| ecs_products | 货品 SKU | product_skus |
| ecs_goods_gallery | 商品相册 | system_attachments（module=product） |
| ecs_goods_cat | 扩展分类 | 按需取舍（见十三） |
| ecs_link_goods | 关联商品 | 按需取舍（见十三） |
| ecs_group_goods | 配件组合 | 按需取舍（见十三） |
| ecs_package_goods | 超值礼包明细 | 按需取舍（见十三） |
| ecs_member_price | 会员等级价 | product_member_prices |
| ecs_volume_price | 阶梯价 | product_volume_prices |
| ecs_cat_recommend | 首页推荐分类 | product_categories.is_recommend 标记位 |
| ecs_tag | 商品标签 | content_tags 复用 |
| ecs_suppliers | 供货商 | 按需取舍（见十三） |

## 三、交易域（12）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_cart | 购物车 | order_carts |
| ecs_order_info | 订单主表 | orders |
| ecs_order_goods | 订单明细 | order_items |
| ecs_order_action | 订单操作日志 | order_logs |
| ecs_delivery_order | 发货单 | order_shipments |
| ecs_delivery_goods | 发货单商品 | order_shipment_items |
| ecs_back_order | 退货单 | order_refunds |
| ecs_back_goods | 退货商品 | order_refund_items |
| ecs_booking_goods | 缺货登记 | 按需取舍（见十三） |
| ecs_virtual_card | 虚拟卡密 | 按需取舍（见十三） |
| ecs_card | 贺卡（付费附件） | 建议废弃或转字典 |
| ecs_pack | 包装（付费附件） | 建议废弃或转字典 |

## 四、营销域（9）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_bonus_type | 红包类型 | marketing_coupons |
| ecs_user_bonus | 用户红包 | marketing_user_coupons |
| ecs_favourable_activity | 满减/折扣活动 | marketing_promotions |
| ecs_goods_activity | 拍卖/团购/夺宝 | 按需取舍（见十三） |
| ecs_auction_log | 拍卖出价记录 | 按需取舍（见十三） |
| ecs_snatch_log | 夺宝出价记录 | 按需取舍（见十三） |
| ecs_exchange_goods | 积分兑换商品 | 按需取舍（见十三） |
| ecs_wholesale | 批发方案 | 按需取舍（见十三） |
| ecs_affiliate_log | 推荐分成记录 | 按需取舍（见十三） |

## 五、用户域（10）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_users | 会员主表 | users + user_profiles（users 已含 rank_id/balance/points） |
| ecs_user_rank | 会员等级 | user_ranks |
| ecs_user_account | 会员余额/资金记录 | user_accounts（账户）+ user_account_logs（流水）；充值走 payments 回调/人工 adjust，提现走 user_withdraw_orders（原 user_fund_applications 申请单方案已废弃删除） |
| ecs_account_log | 资金/积分流水 | user_account_logs |
| ecs_user_address | 收货地址 | user_addresses |
| ecs_collect_goods | 收藏 | user_favorites |
| ecs_feedback | 留言反馈 | form_submissions（表单域） |
| ecs_reg_fields | 注册扩展字段定义 | forms/form_fields（表单域复用） |
| ecs_reg_extend_info | 注册扩展数据 | form_submissions |
| ecs_user_feed | 用户动态 | 建议废弃 |

## 六、内容域（5）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_article | 文章 | content_articles |
| ecs_article_cat | 文章分类 | content_categories |
| ecs_goods_article | 商品-文章关联 | 按需取舍（见十三） |
| ecs_topic | 专题 | content_pages（专题/单页） |
| ecs_comment | 评论（商品+文章） | content_comments（加 subject_type 区分） |

## 七、运营域（13）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_ad | 广告 | site_banners |
| ecs_ad_position | 广告位 | site_banners.position 字段 |
| ecs_ad_custom | 自定义广告播放器 | 建议废弃 |
| ecs_adsense | 广告点击统计 | 建议废弃或并入统计 |
| ecs_friend_link | 友情链接 | site_links |
| ecs_nav | 导航 | site_navs |
| ecs_template | 模板配置 | system_settings（theme 组） |
| ecs_keywords | 搜索热词 | 按需取舍（见十三） |
| ecs_searchengine | 搜索引擎来源 | 建议废弃 |
| ecs_stats | 流量统计 | 建议废弃（换统计方案） |
| ecs_vote | 投票主题 | 按需取舍（见十三） |
| ecs_vote_log | 投票记录 | 同上 |
| ecs_vote_option | 投票选项 | 同上 |

## 八、物流域（4）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_shipping | 配送方式 | shipping_methods |
| ecs_shipping_area | 配送区域（含运价） | shipping_areas（fee_config JSON） |
| ecs_area_region | 配送区域-地区关联 | shipping_area_regions |
| ecs_agency | 办事处 | 建议废弃 |

## 九、支付域（2）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_payment | 支付方式 | payment_channels（渠道配置） |
| ecs_pay_log | 支付单 | payments（通用支付单，biz_type/biz_id 关联订单或充值申请） |

## 十、消息域（3）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_mail_templates | 邮件模板 | Laravel Notification 邮件通道，模板用代码/Blade，不自建表 |
| ecs_email_list | 邮件订阅 | email_subscriptions |
| ecs_email_sendlist | 发送队列 | 框架队列（jobs 表），不自建表 |
| —（站内通知） | 订单/评论等站内触达 | notifications（Laravel database channel，取代原 user_notifications） |

## 十一、系统域（13）

| ECShop 表 | 说明 | 去向 |
|---|---|---|
| ecs_shop_config | 商店配置 | system_settings |
| ecs_region | 地区表 | system_regions |
| ecs_admin_user | 管理员 | admin 域已有（AdminUser） |
| ecs_role | 角色 | admin 域已有（AdminRole） |
| ecs_admin_action | 权限节点 | admin 域已有（AdminPermission） |
| ecs_admin_log | 后台操作日志 | system_operation_logs |
| ecs_admin_message | 管理员留言 | 建议废弃 |
| ecs_sessions | 会话 | 不迁移（换框架机制） |
| ecs_sessions_data | 会话数据 | 不迁移 |
| ecs_error_log | 错误日志 | 不迁移 |
| ecs_crons | 计划任务 | 换框架调度器（scheduler） |
| ecs_auto_manage | 自动上下线任务 | 换框架调度器 |
| ecs_plugins | 插件 | 不迁移 |

## 十二、评审要点

1. **ecs_comment 归属**：同时承载商品评论与文章评论，现归内容域并以 `subject_type` 区分；若商品评论量大且带晒图/追评需求，可考虑独立。
2. **注册扩展 → 表单域**：`ecs_reg_fields`/`ecs_reg_extend_info` 本质为自定义表单，直接映射 forms/form_fields/form_submissions，避免重复造轮子。
3. **region 归属**：已放系统域（system_regions），物流域仅保留配送方式相关表。
4. **可丢弃表**：sessions、sessions_data、error_log、stats、searchengine、adsense、admin_message、user_feed 等运行时/低价值数据建议排除，实际需迁移业务表约 65~70 张。
5. **已补充的能力**（本次迁移已落地）：商品属性体系（3 张）、价格策略（2 张）、营销促销基础（marketing_* 3 张）、钱包（2 张 + users 钱包字段）、物流（3 张）、消息通知（Laravel notifications + 邮件订阅）、导航（1 张）、会员等级（1 张 + users.rank_id）、支付域（payments/payment_channels 2 张）。
6. **支付域独立**：支付单与业务解耦，`payments.biz_type/biz_id` 同时服务订单支付与余额充值（充值回调后走 UserAccountService::credit 入账），原 order_payments 已并入支付域。
7. **通知不过度设计**：站内通知用 Laravel notifications（database channel），邮件走 Notification 邮件通道，发送队列用框架 jobs；已移除自建的 notify_templates/notify_jobs/user_notifications。
8. **贺卡/包装**：ECShop 付费附件概念陈旧，建议废弃或降级为字典项，不建表。
9. **并发库存**：products/product_skus 增加 `locked_stock`，可售=stock-locked_stock；下单锁定用条件更新（`where stock-locked_stock>=n`）防超卖，支付成功核减、取消/超时释放；product_stock_logs 保留事后追溯。
10. **支付幂等三层**：回调按 `unique(channel,notify_id)` 去重拦截第三方重发；payments.payment_no 唯一 + 状态机条件更新（`where status=0`）防重复置成功；订单/充值入账同样以状态字段条件更新；`payment_callbacks` 全量留痕供对账与重放排查。

## 十三、按需取舍清单（2026-10-20 已拍板）

> 2026-10-20 复审：结合最新 schema（微信私域/客服/秒杀/分销/统计已落地）逐项拍板；"已完成"为已有对应表。

| ECShop 表 | 场景 | 建议 | 状态 |
|---|---|---|---|
| ecs_goods_activity / ecs_auction_log / ecs_snatch_log | 拍卖/团购/夺宝 | 不做：限时抢购已由 marketing_flash_sales 覆盖；未来做团购可扩展 marketing_promotions.type | 已决策：不需要（2026-10-20） |
| ecs_wholesale | 批发方案 | 不做：product_volume_prices 阶梯价已覆盖按量定价；B2B 混批与单商家 B2C 定位不符 | 已决策：不需要（2026-10-20） |
| ecs_affiliate_log | 推荐分成 | 已升级为两级分销体系（marketing_distribution_* 分销员/绑定/佣金），超出原单层推荐 | 已完成（2026-10-19） |
| ecs_exchange_goods | 积分兑换商品 | 暂缓：users.points + order_promotions.points 已支持积分+现金/纯积分抵扣；上线时给 products 加兑换标记即可，无需提前建表 | 已决策：暂缓（2026-10-20） |
| ecs_booking_goods | 缺货登记 | 已建 product_stock_reminders（一用户一SKU 一条复用，upsert 重置）；到货后走触达降级链路提醒 | 已完成（2026-10-20） |
| ecs_virtual_card | 虚拟卡密发货 | 经营卡密/券码类商品时再建，订单需同步支持免物流发货 | 待定：业务信号触发 |
| ecs_suppliers | 供货商 | 不做：自营单商家无多供应商诉求；代发业务出现时再建（需含供货价/库存归属） | 已决策：不需要（2026-10-20） |
| ecs_goods_cat / ecs_link_goods / ecs_group_goods / ecs_package_goods | 扩展分类/关联商品/配件/礼包 | 暂缓：运营归组已由 content_topic_items 专题承载；相关推荐可用同分类/同品牌算法替代；组合销售上线时再加 | 已决策：暂缓（2026-10-20） |
| ecs_goods_article | 商品-文章关联 | 已建 content_article.product_ids(JSON)，种草文章挂商品购买入口 | 已完成（2026-10-20） |
| ecs_keywords | 搜索热词统计 | 已建 site_search_keywords | 已完成（2026-10-19） |
| ecs_vote / ecs_vote_option / ecs_vote_log | 投票 | 不建独立表：低频互动，forms 表单域可实现；投票运营成型再独立 | 已决策：不需要独立表（2026-10-20） |

### 非 ECShop 暂缓项

| 事项 | 场景 | 初步建议 | 状态 |
|---|---|---|---|
| 实名认证表 | 大额/虚拟商品合规 | user_realname_verifications，业务信号出现后建 | 暂缓 |
| 在线客服会话 | 客服系统 | 已推翻"接第三方"结论：自建 chat 域 6 表（会话/消息/客服状态/话术/机器人/FAQ） | 已完成：自建（2026-10-19） |
| 开放平台 app_keys | 第三方 API 接入 | 已由 system_apps 应用注册中心覆盖（code/appid/secret/config，多端身份与密钥） | 已完成：system_apps（2026-10-19） |
| 数据权限部门表 | 组织/门店数据隔离 | 管理后台复杂度提升后再做 | 暂缓 |
| 多语言翻译表 | 国际化（article/product translations） | 出海需求出现后建 | 暂缓 |
| redirects URL 重定向表 | ECShop 老 URL 301 到新 URL | 保 SEO 流量用；经评审确认无重定向需求 | 已决策：不采用（2026-10-19） |

## 参考

- [ecshop数据库表结构（v2.7.3，共88表） - 博客园](https://www.cnblogs.com/ecshopdevelop/p/6479259.html)
- [ecshop数据字典（适用v2.7.3） - 看云](https://www.kancloud.cn/xiak/patent/965466)
