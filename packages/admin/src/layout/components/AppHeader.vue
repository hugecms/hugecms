<script setup lang="ts">
import { ArrowDown, UserFilled } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'
import { useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'

defineOptions({ name: 'AppHeader' })

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()

async function handleCommand(command: string) {
  if (command !== 'logout') {
    return
  }
  try {
    await userStore.logout()
  } finally {
    ElMessage.success('已退出登录')
    router.replace('/login')
  }
}
</script>

<template>
  <div class="app-header">
    <div class="header-title">{{ route.meta.title ?? '' }}</div>

    <el-dropdown trigger="click" @command="handleCommand">
      <span class="header-user">
        <el-icon><UserFilled /></el-icon>
        <span>{{ userStore.nickname || '用户' }}</span>
        <el-icon class="header-arrow"><ArrowDown /></el-icon>
      </span>
      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item command="logout">退出登录</el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
  </div>
</template>

<style scoped>
.app-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
}

.header-title {
  font-size: 15px;
  font-weight: 500;
  color: #303133;
}

.header-user {
  display: flex;
  gap: 6px;
  align-items: center;
  font-size: 14px;
  color: #303133;
  cursor: pointer;
}

.header-arrow {
  font-size: 12px;
  color: #909399;
}
</style>
