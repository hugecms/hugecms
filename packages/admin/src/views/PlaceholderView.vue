<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'

defineOptions({ name: 'PlaceholderView' })

const route = useRoute()
const router = useRouter()
</script>

<template>
  <div class="placeholder">
    <div class="placeholder-card">
      <h2>「{{ route.meta.title ?? '页面' }}」建设中</h2>
      <p>该功能页面正在按批次交付，敬请期待。</p>
      <el-button type="primary" @click="router.replace('/dashboard')">返回仪表盘</el-button>
    </div>
  </div>
</template>

<style scoped>
.placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 360px;
}

.placeholder-card {
  padding: 48px 64px;
  text-align: center;
  background: #fff;
  border-radius: 8px;
}

.placeholder-card h2 {
  margin: 0 0 12px;
  font-size: 18px;
  color: #303133;
}

.placeholder-card p {
  margin: 0 0 24px;
  font-size: 14px;
  color: #909399;
}
</style>
