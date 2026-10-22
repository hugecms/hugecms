<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { use } from 'echarts/core'
import { CanvasRenderer } from 'echarts/renderers'
import { LineChart } from 'echarts/charts'
import { GridComponent, LegendComponent, TooltipComponent } from 'echarts/components'
import VChart from 'vue-echarts'
import { dashboardTodoApi } from '@/api/dashboard'
import { siteDailyStatisticSearchApi } from '@/api/siteDailyStatistic'
import type { IDashboardTodoResponse } from '@/types/dashboard'
import type { ISiteDailyStatisticResponse } from '@/types/siteDailyStatistic'

use([CanvasRenderer, LineChart, GridComponent, TooltipComponent, LegendComponent])

defineOptions({ name: 'DashboardView' })

const router = useRouter()
const loading = ref(true)
const todo = ref<IDashboardTodoResponse>({
  pendingShipments: 0,
  pendingRefunds: 0,
  pendingWithdrawals: 0,
  lowStock: 0,
  pendingRiskAppeals: 0,
})
const stats = ref<ISiteDailyStatisticResponse[]>([])

const cards = computed(() => [
  { label: '待发货订单', value: todo.value.pendingShipments, path: '/order/list', type: 'primary' },
  { label: '待审核退款', value: todo.value.pendingRefunds, path: '/order/refund', type: 'warning' },
  { label: '待审核提现', value: todo.value.pendingWithdrawals, path: '/finance/withdraw', type: 'danger' },
  { label: '库存预警', value: todo.value.lowStock, path: '/product/inventory', type: 'warning' },
  { label: '待处理风控', value: todo.value.pendingRiskAppeals, path: '/system/risk-event', type: 'info' },
])

const chartOption = computed(() => ({
  tooltip: { trigger: 'axis' },
  legend: { data: ['下单金额', '支付金额'] },
  grid: { left: 48, right: 24, top: 48, bottom: 32 },
  xAxis: { type: 'category', data: stats.value.map((item) => item.statDate) },
  yAxis: { type: 'value' },
  series: [
    {
      name: '下单金额',
      type: 'line',
      smooth: true,
      data: stats.value.map((item) => Number(item.orderAmount)),
    },
    {
      name: '支付金额',
      type: 'line',
      smooth: true,
      data: stats.value.map((item) => Number(item.payAmount)),
    },
  ],
}))

function go(path: string): void {
  void router.push(path)
}

onMounted(async () => {
  try {
    const [todoResult, statsResult] = await Promise.all([
      dashboardTodoApi(),
      siteDailyStatisticSearchApi(1, 14, {}),
    ])
    todo.value = todoResult
    // 接口默认倒序，图表按日期升序
    stats.value = [...statsResult.data].reverse()
  } catch {
    // 拦截器已提示错误；页面展示空态
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div v-loading="loading" class="dashboard">
    <div class="todo-cards">
      <el-card
        v-for="card in cards"
        :key="card.label"
        class="todo-card"
        shadow="hover"
        @click="go(card.path)"
      >
        <div class="todo-card__label">{{ card.label }}</div>
        <div class="todo-card__value" :class="`is-${card.type}`">{{ card.value }}</div>
      </el-card>
    </div>

    <el-card shadow="never" class="chart-card">
      <template #header>
        <div class="chart-card__header">
          <span>近 14 日交易趋势</span>
          <el-button link type="primary" @click="go('/statistics/daily')">查看每日统计</el-button>
        </div>
      </template>
      <VChart v-if="stats.length > 0" class="chart" :option="chartOption" autoresize />
      <el-empty v-else description="暂无统计数据（每日统计任务接入后展示）" :image-size="80" />
    </el-card>
  </div>
</template>

<style scoped>
.dashboard {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.todo-cards {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 16px;
}

.todo-card {
  cursor: pointer;
}

.todo-card__label {
  font-size: 13px;
  color: #909399;
}

.todo-card__value {
  margin-top: 8px;
  font-size: 28px;
  font-weight: 600;
  color: #303133;
}

.todo-card__value.is-danger {
  color: #f56c6c;
}

.todo-card__value.is-warning {
  color: #e6a23c;
}

.todo-card__value.is-primary {
  color: #409eff;
}

.todo-card__value.is-info {
  color: #909399;
}

.chart-card__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.chart {
  width: 100%;
  height: 320px;
}
</style>
