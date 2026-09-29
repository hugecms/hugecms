# 电商全端商城 (phpmall) 架构规范与执行基准

> 本文件为项目最高开发准则与不可逾越的红线。任何开发任务必须严格按照以下规划与目录规范推进，严禁将千行代码堆叠在单一路由文件中！

---

## 1. 核心目录与职责规范

```text
phpmall/src/
├── api/                          # API 请求模块 (Axios + TanStack Query 接口层)
│   ├── client.ts                 # Axios 统一封装 (拦截器、Token鉴权、通用错误处理)
│   ├── auth.ts                   # 登录/注册/注销
│   ├── goods.ts                  # 商品列表/详情/SKU
│   ├── order.ts                  # 下单/支付/履约
│   └── seller.ts                 # 商家端专属接口
│
├── components/                   # 全局通用展示组件 (纯 UI，无业务耦合)
│   ├── common/                   # 通用原子组件 (AntD 扩展组件、Custom Button、Tag)
│   ├── feedback/                 # 统一弹窗、轻提示包装、确认对话框
│   └── icons/                    # 自定义 SVG 图标或 AntD 图标集合
│
├── features/                     # 业务领域模块 (按业务域内聚：UI + Hook + Store)
│   ├── seller/                   # 商家后台专属模块
│   │   ├── components/           # SiderMenu, MetricsGrid, DispatchTable, AddGoodsModal
│   │   ├── hooks/                # useGoodsForm, useOrderDispatch
│   │   └── types.ts              # 商家端专属类型定义
│   ├── mall/                     # 商城前台模块
│   │   ├── components/           # HomeHero, HomeSeckill, HomeFloor, HomeFeed, GoodsCard
│   │   ├── hooks/                # useCategoryNav, useSeckillTimer
│   │   └── types.ts              # 商城通用展示类型
│   ├── trade/                    # 购物车/结算/收银台模块
│   │   ├── components/           # CartList, AddressSelector, PayChannelList
│   │   └── hooks/                # useCheckoutCalc
│   └── user/                     # 买家用户中心模块
│       └── components/           # UserSidebar, OrderTable, AddressModal
│
├── hooks/                        # 全局通用自定义 Hooks
│   ├── useAuth.ts                # 当前用户/商家认证上下文
│   ├── useDebounce.ts            # 防抖/节流
│   └── usePrint.ts               # 面单打印 Hook
│
├── layouts/                      # 基础布局模版 (不同子系统的外壳框架，内置 <Outlet />)
│   ├── MallLayout.tsx            # 前台商城布局 (32px顶通 + 经典红Header搜索 + 主频道条 + 4大徽标Footer)
│   ├── SellerLayout.tsx          # 商家端工作台布局 (深色 Header + 256px AntD 侧边栏)
│   ├── UserLayout.tsx            # 用户中心两栏布局 (左侧菜单 + 右侧内容)
│   └── AuthLayout.tsx            # 登录注册独立纯净居中布局
│
├── stores/                       # 全局持久化状态 (Zustand)
│   ├── authStore.ts              # 登录态、Token、角色信息 (持久化到 LocalStorage)
│   ├── cartStore.ts              # 购物车本地预选、计数器
│   └── sellerStore.ts            # 商家端工作台配置、折叠状态
│
├── types/                        # 全局通用 TypeScript 类型定义
│   ├── common.ts                 # 统一 API 响应结构 (ApiResponse<T>, PageResult<T>)
│   ├── goods.ts                  # 商品、SKU、SPU、分类模型
│   └── order.ts                  # 订单状态、发货流转、支付模型
│
├── utils/                        # 纯纯工具函数库
│   ├── cn.ts                     # clsx + tailwind-merge 类名合成
│   ├── format.ts                 # 金额格式化 (¥)、时间日期格式化 (Day.js)
│   └── exportExcel.ts            # XLSX 订单导出封装
│
├── routes/                       # 核心路由树 (由 TanStack Router 自动扫描生成)
│   ├── __root.tsx                # 全局根路由 (注入 Antd ConfigProvider、QueryClientProvider)
│   ├── _mall.tsx                 # 前台商城布局路由 (嵌套 MallLayout)
│   ├── _mall/
│   │   ├── index.tsx             # / (商城首页)
│   │   ├── list.tsx              # /list (商品列表搜索)
│   │   ├── item.$id.tsx          # /item/1001 (商品详情动态路由)
│   │   ├── seckill.tsx           # /seckill (秒杀专场)
│   │   ├── coupon.tsx            # /coupon (领券中心)
│   │   ├── plus.tsx              # /plus (PLUS会员)
│   │   ├── shop.tsx              # /shop (店铺展厅)
│   │   └── b2b.tsx               # /b2b (企业采购)
│   ├── _trade.tsx                # 交易流布局路由
│   ├── _trade/
│   │   ├── cart.tsx              # /cart (购物车)
│   │   ├── checkout.tsx          # /checkout (订单确认)
│   │   └── pay.tsx               # /pay (收银台)
│   ├── _auth.tsx                 # 认证流布局路由 (嵌套 AuthLayout)
│   ├── _auth/
│   │   ├── login.tsx             # /login
│   │   └── register.tsx          # /register
│   ├── _user.tsx                 # 用户中心布局路由 (嵌套 UserLayout)
│   ├── _user/
│   │   ├── user.tsx              # /user (用户总览)
│   │   └── user/
│   │       └── order.tsx         # /user/order (订单跟踪)
│   ├── seller/                   # 商家工作台分组 (嵌套 SellerLayout)
│   │   ├── index.tsx             # /seller (总览大屏仪表盘)
│   │   ├── goods.tsx             # /seller/goods
│   │   └── orders.tsx            # /seller/orders
│   ├── admin/                    # 平台管理端
│   │   └── index.tsx             # /admin
│   └── chat.tsx                  # /chat (客服中心)
│
└── styles/                       # 原版 CSS 资源与样式
    ├── antdTheme.ts              # Ant Design 主题色定制 Token
    └── style.css, list.css, etc. # 1:1 对齐原版的专用样式表
```

---

## 2. 严禁事项与开发红线

1. **严禁巨石单文件**：任何单一组件/路由文件不得超过 400 行。超过者必须将功能区块拆入 `features/<domain>/components/`。
2. **严禁在页面中重复粘贴 Header/Footer**：前台所有普通页面一律必须继承 `_mall.tsx` / `MallLayout.tsx`，严禁在页面内部单独手写 32px 顶通和页脚！
3. **严禁脱离原版 CSS 自行发挥**：视觉效果必须与 `archive/` 静态原版 1:1 严格对齐，必须使用对应的专用样式表和 HTML 类名。
4. **状态与副作用规范**：
   - 购物车必须走 `stores/cartStore.ts`。
   - 商家端配置必须走 `stores/sellerStore.ts`。
   - 登录态必须走 `stores/authStore.ts`。

---

## 3. 整改任务执行路线图 (立刻落地)

- [ ] **Task 1: 基础骨架 Layout 落地**
  - 创建 `src/layouts/MallLayout.tsx`（内聚 32px 顶通、Header 搜索与迷你购物车、频道导航条、经典 4 徽标页脚）。
  - 创建 `src/layouts/AuthLayout.tsx`（登录与注册统一简洁通栏框架）。
  - 创建 `src/layouts/UserLayout.tsx`（买家中心左侧 180px 经典菜单 + 右侧内容容器）。
  - 创建 `src/layouts/TradeLayout.tsx`（购物车/结算/收银台专用的简洁头尾框架）。

- [ ] **Task 2: 前台首页 `index.tsx` (1160行) 拆解重构**
  - 提取 `features/mall/components/HomeHero.tsx`（14 类目 MegaMenu + 轮播 + 用户资讯服务三栏）。
  - 提取 `features/mall/components/HomeSeckill.tsx`（秒杀看台、动态倒计时钟、横向滚动轮播）。
  - 提取 `features/mall/components/HomeFeaturedMatrix.tsx`（每日特价、热卖榜、新品首发、锯齿券）。
  - 提取 `features/mall/components/HomeFloor.tsx`（3C数码家电 8 宫格楼层展板）。
  - 提取 `features/mall/components/HomeFeed.tsx`（为你推荐瀑布流好物矩阵）。
  - 提取 `features/mall/components/HomeLiftNav.tsx`（右侧电梯导航与回到顶部）。
  - `routes/_mall/index.tsx` 仅作为纯净聚合容器，代码控制在 100 行以内。

- [ ] **Task 3: 路由树结构规范归位**
  - 将所有商城公共页面迁移并归入 `_mall/`（如 `list`, `item.$id`, `seckill`, `coupon`, `plus`, `shop`, `b2b`）。
  - 将认证页面迁移并归入 `_auth/`（`login`, `register`）。
  - 将交易页面迁移并归入 `_trade/`（`cart`, `checkout`, `pay`）。
  - 将用户中心迁移并归入 `_user/`。
  - 清理 `routes/` 根目录下的平铺孤岛文件。

- [ ] **Task 4: 全局状态、通用组件与 Hooks 补齐**
  - 补齐 `components/common/`、`components/feedback/`。
  - 补齐 `hooks/useAuth.ts`、`hooks/useDebounce.ts`。

- [ ] **Task 5: 全量构建与无头浏览器 1:1 双屏验证**
  - 运行 `npm run build` 确保 TypeScript 类型与路由树无任何错误。
  - 截取所有页面与 `archive/` 进行肉眼与 DOM 双重验证，确保零偏差。

---

## 4. 样式工程最高准则：统一使用 CSS Modules (样式紧跟 TSX)

> 解决内页样式错乱与相互覆盖的根本方案：强制模块化隔离，杜绝全局 CSS 污染！

1. **样式文件组织（紧跟 TSX 同级同名）**：
   - 内页及所有业务组件的样式一律采用 CSS Modules，命名规则为 `[Name].module.css`。
   - 样式文件必须与对应 `.tsx` 存放在同一目录下，例如：
     - `src/routes/cart.tsx` ➔ `src/routes/cart.module.css`
     - `src/routes/checkout.tsx` ➔ `src/routes/checkout.module.css`
     - `src/routes/pay.tsx` ➔ `src/routes/pay.module.css`
     - `src/routes/login.tsx` ➔ `src/routes/login.module.css`
     - `src/features/mall/components/HomeHero.tsx` ➔ `src/features/mall/components/HomeHero.module.css`
2. **严禁全局平铺旧式 CSS 污染**：
   - 严禁在内页中使用 `import '../styles/cart.css'` 这种无作用域的平铺导入！
   - 全局样式仅限基准重置、全局变量（`:root` 色值）和 1200px 版心（`.w`）。组件与页面一律使用 `styles.xxx` 命名空间。
3. **UI 交互与动画准则（干脆、无多余过渡）**：
   - 严禁无脑设置 `transition: all`！
   - 默认**禁用过渡**的属性：`border-color`、`border-width`、`box-shadow`、`width`、`height`、`margin`、`padding`、`top/left/right/bottom`。
   - **允许保留过渡**的属性（仅限有明确意图的微交互）：`background-color`（hover 按钮/卡片）、`color`（文字悬浮）、`opacity`（淡入淡出）、`transform`（轻微位移缩放）。
   - 过渡属性必须精确声明，如 `transition: background-color 0.15s ease, color 0.15s ease;`。
