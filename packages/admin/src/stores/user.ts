import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import 'pinia-plugin-persistedstate'
import { authLoginApi, authLogoutApi, authMeApi } from '@/api/auth'
import type { LoginParams, UserInfo } from '@/types/api'

/** 登录用户状态：token 与用户信息持久化到 localStorage */
export const useUserStore = defineStore(
  'user',
  () => {
    const token = ref('')
    const userInfo = ref<UserInfo | null>(null)

    const isLoggedIn = computed(() => token.value !== '')
    const nickname = computed(() => userInfo.value?.username || '')
    const isSuper = computed(() => userInfo.value?.isSuper === 1)

    /** 清空登录态（401、登出时调用） */
    function reset() {
      token.value = ''
      userInfo.value = null
    }

    /** 登录：写入 token 并尽力拉取用户信息（拉取失败不阻塞登录） */
    async function login(params: LoginParams) {
      const { token: accessToken } = await authLoginApi({ user_name: params.username, password: params.password })
      token.value = accessToken
      try {
        const me = await authMeApi()
        userInfo.value = { id: me.id, username: me.username, email: me.email, isSuper: me.isSuper }
      } catch {
        userInfo.value = null
      }
    }

    /** 登出：调用登出接口，无论成败都清空本地登录态 */
    async function logout() {
      try {
        await authLogoutApi()
      } finally {
        reset()
      }
    }

    return { token, userInfo, isLoggedIn, nickname, isSuper, login, logout, reset }
  },
  { persist: true },
)
