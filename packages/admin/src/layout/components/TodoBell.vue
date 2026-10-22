<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { Bell } from '@element-plus/icons-vue'
import { useRouter } from 'vue-router'
import { dashboardTodoApi } from '@/api/dashboard'
import type { IDashboardTodoResponse } from '@/types/dashboard'

defineOptions({ name: 'TodoBell' })

const router = useRouter()
const counts = ref<IDashboardTodoResponse>({
  pendingShipments: 0,
  pendingRefunds: 0,
  pendingWithdrawals: 0,
  lowStock: 0,
  pendingRiskAppeals: 0,
})

const total = computed(() => Object.values(counts.value).reduce((sum, value) => sum + value, 0))

const items = computed(() => [
  { label: '待发货订单', value: counts.value.pendingShipments, path: '/order/list' },
  { label: '待审核退款', value: counts.value.pendingRefunds, path: '/order/refund' },
  { label: '待审核提现', value: counts.value.pendingWithdrawals, path: '/finance/withdraw' },
  { label: '库存预警', value: counts.value.lowStock, path: '/product/inventory' },
  { label: '待处理风控', value: counts.value.pendingRiskAppeals, path: '/system/risk-event' },
])

async function load(): Promise<void> {
  try {
    counts.value = await dashboardTodoApi()
  } catch {
    // 拦截器已提示错误
  }
}

function go(path: string): void {
  void router.push(path)
}

onMounted(load)
</script>

<template>
  <el-popover placement="bottom-end" :width="260" trigger="click" @show="load">
    <template #reference>
      <el-badge :value="total" :hidden="total === 0" :max="99">
        <el-button text class="todo-bell">
          <el-icon :size="18"><Bell /></el-icon>
        </el-button>
      </el-badge>
    </template>

    <div class="todo-list">
      <div v-for="item in items" :key="item.label" class="todo-item" @click="go(item.path)">
        <span>{{ item.label }}</span>
        <el-tag :type="item.value > 0 ? 'danger' : 'info'" size="small">{{ item.value }}</el-tag>
      </div>
    </div>
  </el-popover>
</template>

<style scoped>
.todo-bell {
  padding: 4px;
}

.todo-list {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.todo-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 6px 8px;
  font-size: 14px;
  color: #303133;
  cursor: pointer;
  border-radius: 4px;
}

.todo-item:hover {
  background: #f5f7fa;
}
</style>
