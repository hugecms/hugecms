<script setup lang="ts">
import { useUserStore } from '@/stores/user'

defineOptions({ name: 'DashboardView' })

const userStore = useUserStore()
</script>

<template>
  <div class="dashboard">
    <el-card>
      <template #header>仪表盘</template>
      <p>欢迎回来，{{ userStore.nickname || '管理员' }}！</p>
      <p class="dashboard-tip">ECBoot 管理后台基础框架已就绪，后续功能模块将在各自目录下扩展。</p>
    </el-card>
  </div>
</template>

<style scoped>
.dashboard-tip {
  margin-top: 8px;
  font-size: 13px;
  color: #909399;
}
</style>
