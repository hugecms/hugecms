import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import 'pinia-plugin-persistedstate'
import type { UserInfo } from '@/types/api'

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

    return { token, userInfo, isLoggedIn, nickname, reset }
  },
  { persist: true },
)
