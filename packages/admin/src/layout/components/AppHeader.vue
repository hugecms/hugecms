<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import {
  ArrowDown,
  Expand,
  Fold,
  FullScreen,
  Key,
  Refresh,
  UserFilled,
} from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'
import { useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'
import { useTabsStore } from '@/stores/tabs'
import { authChangePasswordApi } from '@/api/auth'
import GlobalSearch from '@/layout/components/GlobalSearch.vue'
import TodoBell from '@/layout/components/TodoBell.vue'

defineOptions({ name: 'AppHeader' })

const props = defineProps<{ collapsed?: boolean }>()
const emit = defineEmits<{ 'update:collapsed': [value: boolean]; refresh: [] }>()

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()
const tabsStore = useTabsStore()

/** 面包屑：Layout 之下的组 / 页 */
const breadcrumb = computed(() =>
  route.matched.filter((record) => record.name !== 'layout' && record.meta?.title).map((record) => record.meta.title),
)

const collapseIcon = computed(() => (props.collapsed ? Expand : Fold))

function toggleCollapsed(): void {
  emit('update:collapsed', !props.collapsed)
}

function handleRefresh(): void {
  emit('refresh')
}

function handleFullscreen(): void {
  if (document.fullscreenElement) {
    void document.exitFullscreen()
  } else {
    void document.documentElement.requestFullscreen()
  }
}

// 修改密码
const passwordVisible = ref(false)
const passwordSubmitting = ref(false)
const passwordForm = reactive({ old_password: '', new_password: '', new_password_confirmation: '' })

function openChangePassword(): void {
  Object.assign(passwordForm, { old_password: '', new_password: '', new_password_confirmation: '' })
  passwordVisible.value = true
}

async function submitChangePassword(): Promise<void> {
  if (!passwordForm.old_password || !passwordForm.new_password) {
    ElMessage.warning('请填写原密码与新密码')
    return
  }
  if (passwordForm.new_password !== passwordForm.new_password_confirmation) {
    ElMessage.warning('两次输入的新密码不一致')
    return
  }
  passwordSubmitting.value = true
  try {
    await authChangePasswordApi({ ...passwordForm })
    ElMessage.success('密码修改成功')
    passwordVisible.value = false
  } catch {
    // 拦截器已提示错误
  } finally {
    passwordSubmitting.value = false
  }
}

async function handleCommand(command: string): Promise<void> {
  if (command === 'password') {
    openChangePassword()
    return
  }
  if (command !== 'logout') {
    return
  }
  try {
    await userStore.logout()
  } finally {
    tabsStore.reset()
    ElMessage.success('已退出登录')
    router.replace('/login')
  }
}
</script>

<template>
  <div class="app-header">
    <div class="header-left">
      <el-icon class="header-action" @click="toggleCollapsed">
        <component :is="collapseIcon" />
      </el-icon>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item v-for="title in breadcrumb" :key="title">{{ title }}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>

    <div class="header-right">
      <GlobalSearch />
      <el-tooltip content="刷新当前页" placement="bottom">
        <el-icon class="header-action" @click="handleRefresh"><Refresh /></el-icon>
      </el-tooltip>
      <el-tooltip content="全屏" placement="bottom">
        <el-icon class="header-action" @click="handleFullscreen"><FullScreen /></el-icon>
      </el-tooltip>
      <TodoBell />

      <el-dropdown trigger="click" @command="handleCommand">
        <span class="header-user">
          <el-icon><UserFilled /></el-icon>
          <span>{{ userStore.nickname || '用户' }}</span>
          <el-tag v-if="userStore.isSuper" size="small" type="warning">超管</el-tag>
          <el-icon class="header-arrow"><ArrowDown /></el-icon>
        </span>
        <template #dropdown>
          <el-dropdown-menu>
            <el-dropdown-item command="password">
              <el-icon><Key /></el-icon>
              修改密码
            </el-dropdown-item>
            <el-dropdown-item command="logout" divided>退出登录</el-dropdown-item>
          </el-dropdown-menu>
        </template>
      </el-dropdown>
    </div>

    <el-dialog v-model="passwordVisible" title="修改密码" width="420px" destroy-on-close>
      <el-form label-width="90px">
        <el-form-item label="原密码" required>
          <el-input v-model="passwordForm.old_password" type="password" show-password />
        </el-form-item>
        <el-form-item label="新密码" required>
          <el-input v-model="passwordForm.new_password" type="password" show-password placeholder="至少6位" />
        </el-form-item>
        <el-form-item label="确认新密码" required>
          <el-input v-model="passwordForm.new_password_confirmation" type="password" show-password />
        </el-form-item>
      </el-form>
      <template #footer>
        <el-button @click="passwordVisible = false">取消</el-button>
        <el-button type="primary" :loading="passwordSubmitting" @click="submitChangePassword">确定</el-button>
      </template>
    </el-dialog>
  </div>
</template>

<style scoped>
.app-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
}

.header-left,
.header-right {
  display: flex;
  gap: 12px;
  align-items: center;
}

.header-action {
  font-size: 18px;
  color: #303133;
  cursor: pointer;
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
