<script setup lang="ts">
import { Lock, User } from '@element-plus/icons-vue'
import { ElMessage, type FormInstance, type FormRules } from 'element-plus'
import { onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'

defineOptions({ name: 'LoginView' })

const REMEMBER_KEY = 'ecboot-admin-username'

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()
const formRef = ref<FormInstance>()
const loading = ref(false)

const loginForm = reactive({
  username: '',
  password: '',
  remember: true,
})

const loginRules: FormRules<typeof loginForm> = {
  username: [
    { required: true, message: '请输入用户名', trigger: 'blur' },
    { min: 3, max: 30, message: '用户名长度为 3-30 个字符', trigger: 'blur' },
  ],
  password: [
    { required: true, message: '请输入密码', trigger: 'blur' },
    { min: 6, max: 32, message: '密码长度为 6-32 位', trigger: 'blur' },
  ],
}

onMounted(() => {
  const saved = localStorage.getItem(REMEMBER_KEY)
  if (saved) {
    loginForm.username = saved
  }
})

async function handleLogin(): Promise<void> {
  const form = formRef.value
  if (!form) {
    return
  }
  try {
    await form.validate()
  } catch {
    return
  }

  loading.value = true
  try {
    await userStore.login({
      username: loginForm.username,
      password: loginForm.password,
    })

    if (loginForm.remember) {
      localStorage.setItem(REMEMBER_KEY, loginForm.username)
    } else {
      localStorage.removeItem(REMEMBER_KEY)
    }

    ElMessage.success('登录成功')
    const redirect = route.query.redirect
    await router.replace(typeof redirect === 'string' && redirect !== '' ? redirect : '/')
  } catch {
    // 错误提示已由 request 拦截器统一弹出
    return
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-page">
    <div class="login-card">
      <div class="login-brand">
        <div class="brand-logo">EC</div>
        <h1 class="brand-title">ECBoot</h1>
        <p class="brand-subtitle">管理后台</p>
      </div>

      <el-form
        ref="formRef"
        :model="loginForm"
        :rules="loginRules"
        size="large"
        @submit.prevent="handleLogin"
      >
        <el-form-item prop="username">
          <el-input
            v-model.trim="loginForm.username"
            name="username"
            placeholder="用户名"
            :prefix-icon="User"
            autocomplete="username"
          />
        </el-form-item>

        <el-form-item prop="password">
          <el-input
            v-model.trim="loginForm.password"
            name="password"
            type="password"
            placeholder="密码"
            :prefix-icon="Lock"
            show-password
            autocomplete="current-password"
          />
        </el-form-item>

        <div class="login-extra">
          <el-checkbox v-model="loginForm.remember">记住用户名</el-checkbox>
        </div>

        <el-button class="login-submit" type="primary" native-type="submit" :loading="loading">
          登 录
        </el-button>
      </el-form>

      <p class="login-footer">ECBoot · B2C 自营电商管理系统</p>
    </div>
  </div>
</template>

<style scoped>
.login-page {
  position: relative; /* 装饰伪元素 ::before/::after 的定位基准；缺失时它们会以视口为包含块，撑出滚动条 */
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #1d2b64 0%, #2b5876 55%, #3a7bd5 100%);
  overflow: hidden;
}

.login-page::before,
.login-page::after {
  content: '';
  position: absolute;
  border-radius: 50%;
  background: rgb(255 255 255 / 6%);
  pointer-events: none;
}

.login-page::before {
  width: 480px;
  height: 480px;
  top: -160px;
  left: -120px;
}

.login-page::after {
  width: 360px;
  height: 360px;
  right: -100px;
  bottom: -140px;
}

.login-card {
  position: relative;
  z-index: 1;
  width: 400px;
  max-width: calc(100vw - 32px);
  padding: 40px 36px 24px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 20px 50px rgb(0 0 0 / 25%);
}

.login-brand {
  margin-bottom: 28px;
  text-align: center;
}

.brand-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 56px;
  height: 56px;
  margin: 0 auto 12px;
  font-size: 22px;
  font-weight: 700;
  color: #fff;
  background: linear-gradient(135deg, #409eff, #3a7bd5);
  border-radius: 14px;
}

.brand-title {
  margin: 0;
  font-size: 24px;
  font-weight: 600;
  color: #303133;
}

.brand-subtitle {
  margin: 6px 0 0;
  font-size: 14px;
  color: #909399;
}

.login-extra {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.login-submit {
  width: 100%;
  letter-spacing: 4px;
}

.login-footer {
  margin: 24px 0 0;
  font-size: 12px;
  color: #c0c4cc;
  text-align: center;
}
</style>
