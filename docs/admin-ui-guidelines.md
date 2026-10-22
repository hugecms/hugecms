# 管理端 UI/UX 规范（packages/admin）

> 本文件是管理后台页面设计的**唯一规范来源**。新增/改造任何页面（人类或 AI）前必须先读本文件。
> 参考模型：Ant Design ProComponents `ProTable` 的「查询区 → 工具栏 → 表格 → 分页」结构，落地组件为 Element Plus（Vue 3）。
> 色彩 token 的实现在 `packages/admin/src/assets/styles.css`；通用能力在 `src/composables/useCrudTable.ts`、`src/components/RefreshButton.vue`、`src/utils/export.ts`。

---

## 1. 设计原则

1. **分区即职责**：筛选、操作、数据、翻页各有明确区域，不交叉。筛选条件不放进工具栏，批量操作不放进筛选区。
2. **视觉权重四档**（按操作后果分配，不按功能重要性分配）：

   | 档位 | 用途 | 组件形态 |
   |---|---|---|
   | 主操作 | 新增、保存、审核通过、提交 | `el-button type="primary"` |
   | 次操作 | 编辑、查询、重置、导出 | `el-button`（默认） |
   | 辅助操作 | 刷新、全屏、列设置、复制 | icon + tooltip（`text` / 无边框） |
   | 危险操作 | 删除、批量删除、拒绝、作废 | `danger` + 二次确认 |

3. **查询与分页联动**：查询/重置/筛选条件变化一律回到第 1 页；页面大小变化回到第 1 页。
4. **错误提示只弹一次**：`request.ts` 拦截器统一 `ElMessage.error`，页面 `catch` 中**不重复弹错**，只做状态复位。
5. **一致优先于局部最优**：同类页面使用同一结构、同一按钮顺序、同一状态色，不为单页发明新样式。

---

## 2. 页面结构（四区）

```
┌──────────────────────────────────────────────────────────┐
│ 筛选区 Search（el-form inline / 栅格）                     │
│ [条件1] [条件2] [条件3]            [查询] [重置] [展开]     │
├──────────────────────────────────────────────────────────┤
│ 功能区 Toolbar（有序按钮组）                                │
│ [新增] [批量删除] [导出]                     [刷新 icon→右]  │
├──────────────────────────────────────────────────────────┤
│ 内容区 Table（flex:1，撑满剩余高度）                        │
│ □  ID  名称  状态  时间  操作(编辑/删除, fixed=right)       │
├──────────────────────────────────────────────────────────┤
│ 分页区 Pagination（右对齐）                                │
│              共 N 条  [10条/页]  < 1 2 3 >  前往 [ ] 页   │
└──────────────────────────────────────────────────────────┘
```

- 外层容器统一 `el-card shadow="never"`；卡片内不再套卡片。
- 四区之间用 12px 间距，不画硬分割线；筛选区与工具栏之间可用 12px 留白分隔。
- 表格主体 `flex: 1`，空数据时用 `el-empty` 占位，避免页面塌陷。
- 页面级标题不放内容区（Header 已有面包屑）；如需说明性副标题，放筛选区上方 14px 字号。

---

## 3. 筛选区（Search）

**布局**
- 使用 `el-form inline`；字段多于 3 个时改用栅格（`el-row/el-col`，桌面 3 列，`xs` 1 列）。
- 每个筛选项 `label` 与控件间距一致；输入框宽度 180–220px，日期区间 240–260px。
- 控件顺序：文本 → 枚举 → 日期 → 数值范围；枚举优先用 `el-select`，不要用输入框让用户手输。

**按钮**
- `查询` 为 `primary`，`重置` 为默认；两者紧邻，放在筛选区末尾。
- 查询回第 1 页；重置清空全部条件并回第 1 页。
- 回车键等价于查询（`@keyup.enter="handleSearch"`）。

**折叠**
- 超过 2 行（移动端 1 行）时提供「展开/收起」，默认只展示第一行。
- 折叠状态不影响已生效条件（条件仍在 searchForm 中）。

**反例**：把「新增」放进筛选区；筛选项使用无 label 输入框；查询后停留在原页码。

---

## 4. 功能区（Toolbar）

**左侧（业务动作，按频率与风险排序）**
```
[新增] [批量删除(需选中)] [导出] [其他批量动作]
```
- 批量按钮在未选中时 `disabled`，并显示选中数（如「批量删除(3)」）。
- 导出属于次操作；当前页导出用 `exportCsv`，全量导出需后端接口（待接入）。

**右侧（辅助工具，低权重）**
```
[刷新 icon] [列设置（后续）] [密度（后续）]
```
- 刷新统一使用 `RefreshButton`（icon + tooltip），靠右（`margin-left: auto`）。
- 辅助工具不使用文字按钮。

**反例**：工具栏中间混入筛选条件；刷新用 `<el-button>刷新</el-button>` 与主按钮同权重。

---

## 5. 内容区（Table）

**列约定**
- 第一列为多选（需要批量操作时），宽度 48。
- ID 列宽 80；名称/标题列 `min-width` 自适应；状态列 90–110；时间列 180。
- 金额列右对齐、两位小数；数量列右对齐。
- 操作列固定右侧（`fixed="right"`），宽度按按钮数 120–180；操作按钮使用 `link` 样式。
- 长文本列使用 `show-overflow-tooltip` 或 `min-width` + 省略。

**状态展示**
| 语义 | el-tag type |
|---|---|
| 启用 / 成功 / 已完成 / 已支付 | `success` |
| 停用 / 已关闭 / 无 | `info` |
| 待审核 / 待处理 / 进行中 | `warning` |
| 失败 / 拒绝 / 已取消 / 退款 | `danger` |
| 中性标签（类型、渠道） | `primary`（默认） |

**金额与促销**
- 金额格式：`¥1,234.56`，价格/促销金额使用 `var(--app-price-color)`（#FA541C）。
- 促销标签使用 `var(--app-promotion-color)`，不与状态标签混用。

**空态 / 加载**
- 加载：表格 `v-loading="loading"`，不额外放 loading 文案。
- 空态：`<el-empty description="暂无数据" :image-size="80" />`（表格内自动）。

---

## 6. 分页区（Pagination）

- 位置：内容区右下；与表格间距 12px。
- 配置固定为：`layout="total, sizes, prev, pager, next, jumper"`，`page-sizes="[10, 20, 50]"`。
- 查询/重置/页面大小变化 → 第 1 页；页码变化 → 请求对应页。
- 删除后：当前页为空且页码 > 1 时回退一页（`useCrudTable` 处理）。

---

## 7. 编辑形态：对话框 vs 抽屉

| 场景 | 形态 |
|---|---|
| 字段 ≤ 8 个、单实体编辑 | `el-dialog`（宽 420–560px），表单 `label-width` 80–100px |
| 字段 > 8 个、含子表/富文本/多步骤 | `el-drawer`（宽 480–720px） |
| 详情查看（订单、会员、商品） | `el-drawer`（宽 600–900px），内部用 `el-descriptions` + 标签页 |
| 表单字段 > 8 或跨页复用 | 抽 `components/` 下的 FormDialog，否则内联在页面中 |

- 对话框/抽屉内提交按钮在底部右侧：`取消`（默认） + `确定`（primary，`:loading`）。
- 关闭后重置表单；编辑时预填；提交成功后 `ElMessage.success` + 刷新列表/关闭。

---

## 8. 视觉规范（Token）

全部使用 `src/assets/styles.css` 中的变量，不硬编码色值：

| 用途 | Token |
|---|---|
| 主色 / 链接 / 选中 | `--el-color-primary`（#1677FF） |
| 成功 / 警告 / 危险 / 信息 | `--el-color-success/warning/danger/info` |
| 内容区背景 | `--app-content-bg`（#F5F7FA） |
| 边框 | `--app-border`（#EBEDF0） |
| 价格 / 促销强调 | `--app-price-color`（#FA541C）/ `--app-promotion-color`（#FF6B00） |
| 一级/二级导航 | `--app-primary-nav-*` / `--app-secondary-nav-*` |

- 字号：页面标题 18–20；区块标题 14–15 加粗；正文 13–14；辅助说明 12。
- 间距：统一 8 的倍数（8/12/16/24）；卡片内边距 16–20。
- 圆角：4px（按钮、输入框、标签），卡片 8px。
- 阴影：卡片默认 `shadow="never"`；悬浮卡片可用 `shadow="hover"`。

---

## 9. 代码落地约定

**目录**
```
views/<一级组>/<二级页>/index.vue      // 与路由 path 一一对应
components/PageContainer.vue           // 四区骨架（search/toolbar/default/pagination 插槽）
components/ErrorState.vue              // 列表错误态 + 重试
components/RefreshButton.vue           // 弱化刷新（icon + tooltip）
composables/useCrudTable.ts            // 标准列表页数据逻辑（含 error/retry）
composables/useFormGuard.ts            // 表单离开保护（路由/刷新）
utils/export.ts                        // CSV 导出
```

**标准 CRUD 页骨架**
```vue
<script setup lang="ts">
import { useCrudTable } from '@/composables/useCrudTable'
import RefreshButton from '@/components/RefreshButton.vue'

const { loading, list, total, page, pageSize, searchForm, selectedIds,
        handleSearch, handleReset, handleSizeChange, handleCurrentChange,
        handleSelectionChange, handleDelete } = useCrudTable({
  searchApi, destroyApi, createSearchForm: () => ({ name: '' }),
})
</script>

<template>
  <PageContainer>
    <template #search>
      <el-form inline @submit.prevent>
      <!-- 筛选条件 -->
      <el-form-item>
        <el-button type="primary" @click="handleSearch">查询</el-button>
        <el-button @click="handleReset">重置</el-button>
      </el-form-item>
      </el-form>
    </template>

    <template #toolbar>
      <el-button type="primary" @click="openCreate">新增</el-button>
      <el-button type="danger" plain :disabled="selectedIds.length === 0" @click="handleDelete(selectedIds)">
        批量删除
      </el-button>
      <RefreshButton class="toolbar__refresh" @click="refresh" />
    </template>

    <ErrorState v-if="error" @retry="retry" />
    <el-table v-else v-loading="loading" :data="list" @selection-change="handleSelectionChange">
      <!-- 列 -->
    </el-table>

    <template #pagination>
      <el-pagination layout="total, sizes, prev, pager, next, jumper"
        :total="total" :current-page="page" :page-size="pageSize" :page-sizes="[10, 20, 50]"
        @current-change="handleCurrentChange" @size-change="handleSizeChange" />
    </template>
  </PageContainer>
</template>
```

**交互约定**
- 删除：`ElMessageBox.confirm` 二次确认；批量删除显示选中数。
- 提交：按钮 `:loading`；成功 `ElMessage.success`；失败不重复弹错。
- 刷新：页面内用 `RefreshButton`（右对齐）；Header 的刷新仅用于整页重建。
- 列表失败：`useCrudTable` 暴露 `error`，页面用 `<ErrorState @retry="retry" />` 替换表格，提供重试入口。
- 表单保护：编辑页调用 `useFormGuard(isDirty)`（对话框打开且表单与初始快照不同即视为脏），路由切换/关闭标签/刷新前提示。
- 布局：最小工作宽度 1280px；<1440px 自动收起二级导航。
- 导出：`exportCsv(filename, columns, rows)`；列 `key` 与行数据字段一致。

---

## 10. 响应式与无障碍

- 1280px 以下：筛选区自动换行；表格允许横向滚动；操作列固定右侧。
- icon-only 按钮必须有 `tooltip` 与 `aria-label`。
- 颜色不作为唯一信息载体：状态同时用文字（如 `el-tag` 文本），不仅靠颜色。
- 快捷键：全局搜索 `⌘K / Ctrl+K`；对话框 Esc 关闭（Element Plus 默认）。

---

## 11. 新页面验收清单

- [ ] 四区齐全，顺序为 筛选 → 工具栏 → 表格 → 分页
- [ ] 主/次/辅助/危险按钮四档权重正确（刷新为 icon）
- [ ] 查询/重置/页面大小变化回第 1 页
- [ ] 删除有二次确认；批量按钮未选中时禁用
- [ ] 错误提示只有拦截器一次，页面无重复 `ElMessage.error`
- [ ] 状态标签颜色符合第 5 节映射
- [ ] 金额右对齐、价格使用强调色
- [ ] 空态为 `el-empty`，加载为 `v-loading`
- [ ] 使用 `PageContainer` 四区插槽、`useCrudTable`（含 `error/retry`）与 `RefreshButton`
- [ ] 列表失败展示 `ErrorState` 并可重试
- [ ] 编辑表单接入 `useFormGuard`（有修改时离开提示）
- [ ] 目录为 `views/<组>/<页>/index.vue`
- [ ] `vue-tsc`、`vitest`、`vite build` 通过
