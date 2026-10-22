# 遗留决策点

> 2026-10-20 产品设计评审结论：schema 无阻断性缺口，以下均为应用层规则或运营前提，不阻塞开发。
> 拍板后请更新对应条目状态，并同步本文件。

## 决策点清单

### 1. site_daily_statistics 的 pv/uv 数据源

- **现状**：库内无访问日志明细表，`site_daily_statistics` 的 pv/uv 字段没有数据生产方。
- **建议（2026-10-21 拆分拍板）**：
  - **交易指标**（order_count/order_amount/pay_*/refund_*/new_users）由项目自有表（orders/payments/order_refunds/users）按日聚合，**立即实现**定时任务，不依赖外部统计，口径变更可重算；
  - **pv/uv** 保留列，V1 接第三方统计（小程序官方数据分析 API / 百度统计 H5），V1.1 后再评估自建轻量访问日志表。
- **状态**：交易指标自算已拍板；pv/uv 第三方接入待 V1.1（不阻塞报表上线）。

### 2. 粉丝↔会员自动归一依赖 UnionID

- **现状**：`wechat_fans.unionid ↔ user_socials.union_id` 是粉丝与注册会员自动关联的唯一桥梁。
- **风险**：未绑定微信开放平台时无 unionid，公众号粉丝与小程序/APP 会员无法自动归一，只能靠手机号手动关联。
- **建议**：上线前确认已开通开放平台并完成多应用绑定，这是私域矩阵的前提条件。
- **状态**：运营前提确认项。

### 3. 余额抵扣的表达方式

- **现状**：`order_promotions.type` 注释枚举为 coupon/points/promotion，未含余额。
- **选项**：A. 扩展 type=balance，余额抵扣与券/积分统一进优惠拆分与退款返还；B. 余额支付走独立 payments（biz_type=order，渠道=balance）。
- **建议**：A——复用既有摊销与返还逻辑，改动仅注释级；B 会让"一笔订单多张支付单"复杂化。
- **状态**：建议 A，待拍板。

### 4. 秒杀价与优惠券叠加规则

- **现状**：价格优先级已定（秒杀 > 阶梯 > 会员价 > 等级折扣），但"秒杀价能否再叠加优惠券/会员折扣"未约定；schema 不约束，order_promotions 可记录任意叠加结果。
- **建议**：秒杀价不再叠加任何优惠（业界惯例，控毛利），规则落 `system_settings`（如 `marketing.flash_sale_no_overlap=true`）。
- **状态**：建议秒杀不叠加，待拍板。

### 5. 秒杀支付超时回补窗口

- **现状**：`sold_stock` 的取消回补依赖"超时未支付自动关单"，窗口期未定；schema 无需改动。
- **建议**：15 分钟延迟队列关单 + 回补（秒杀场景可更短），同时释放 locked_stock。
- **状态**：待配置项确认，开发时落 system_settings。

### 6. 虚拟商品（卡密/券码）业务

- **现状**：ECShop virtual_card 映射的唯一业务信号触发项，未建表。
- **触发条件**：决定经营卡密/券码类商品时——需建 virtual_cards 发码表（购买发放/激活核销），且订单流程需支持免物流发货；纯积分兑换场景已被 users.points + order_promotions.points 覆盖，无需提前建表。
- **状态**：待业务信号。

### 7. P1 资金闭环方案（已拍板）

- **拍板内容**（2026-10-21）：①资金账户独立 `user_accounts`（balance 可负 + frozen），users.balance 列移除，流水双快照；②提现走 `user_withdraw_orders` 状态机（user_fund_applications 已删除：充值在线走 payments 回调、人工走 adjust，无需申请单）；③佣金冲销链（保护期 + 负额冲销记录）本期补齐；④佣金规则引擎 `marketing_distribution_commission_rules`（商品覆盖>分类默认>全局兜底）；⑤风控最小集 `system_risk_rules/records`。
- **状态**：已实施并测试通过（P1）。

### 8. 微信商家转账（提现自动打款）集成时机

- **现状**：`user_withdraw_orders` 状态机已就绪，打款动作由 `App\Plugins\Pay\PayService::withdraw()` 占位（返回空渠道单号，人工线下打款、后台标记回填凭证号）。
- **触发条件**：微信支付商户号资质办理完成、开通商家转账权限后，扩展 `channel=wechat` 并在插件内接入 V3 转账 API（easywechat/yansongda 已安装）。
- **状态**：待资质（不阻塞，人工打款可运营）。

## 维护约定

- 任一决策点拍板后：更新本文件状态 → 涉及 schema 的走独立 alter 迁移（生产库禁止 migrate:fresh）→ 涉及配置的落 system_settings 初始数据。
- 本文件清空（全部拍板）后可归档合并进 database-design.md。
