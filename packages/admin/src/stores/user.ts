import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import 'pinia-plugin-persistedstate'
import { getProfileApi, loginApi, logoutApi } from '@/api/auth'
import type { LoginParams, UserInfo } from '@/types/api'

/** 登录用户状态：token 与用户信息持久化到 localStorage */
export const useUserStore = defineStore(
  'user',
  () => {
    const token = ref('')
    const userInfo = ref<UserInfo | null>(null)

    const isLoggedIn = computed(() => token.value !== '')
    const nickname = computed(() => userInfo.value?.nickname || userInfo.value?.username || '')

    /** 清空登录态（401、登出时调用） */
    function reset() {
      token.value = ''
      userInfo.value = null
    }

    /** 登录：写入 token 并尽力拉取用户信息（拉取失败不阻塞登录） */
    async function login(params: LoginParams) {
      const { access_token } = await loginApi(params)
      token.value = access_token
      try {
        userInfo.value = await getProfileApi()
      } catch {
        userInfo.value = null
      }
    }

    /** 登出：调用登出接口，无论成败都清空本地登录态 */
    async function logout() {
      try {
        await logoutApi()
      } finally {
        reset()
      }
    }

    return { token, userInfo, isLoggedIn, nickname, login, logout, reset }
  },
  { persist: true },
)
