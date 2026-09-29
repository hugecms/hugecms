import { create } from 'zustand'
import { persist } from 'zustand/middleware'

export type UserRole = 'buyer' | 'seller' | 'admin'

export interface AuthUser {
  id: string | number
  username: string
  nickname?: string
  phone?: string
  avatar?: string
  role: UserRole
  creditScore?: number
  plusMember?: boolean
}

interface AuthState {
  user: AuthUser | null
  token: string | null
  isAuthenticated: boolean
  login: (user: AuthUser, token: string) => void
  logout: () => void
  setUserInfo: (user: Partial<AuthUser>) => void
  checkAuth: () => boolean
}

export const useAuthStore = create<AuthState>()(
  persist(
    (set, get) => ({
      user: {
        id: '1001',
        username: 'jd_vip_buyer',
        nickname: '张三',
        phone: '138****8888',
        avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&q=80',
        role: 'buyer',
        creditScore: 102.5,
        plusMember: true,
      },
      token: 'demo_jwt_token_phpmall_buyer',
      isAuthenticated: true,

      login: (user, token) => {
        set({ user, token, isAuthenticated: true })
      },

      logout: () => {
        set({ user: null, token: null, isAuthenticated: false })
      },

      setUserInfo: (updatedFields) => {
        const currentUser = get().user
        if (currentUser) {
          set({ user: { ...currentUser, ...updatedFields } })
        }
      },

      checkAuth: () => {
        return !!get().token
      },
    }),
    {
      name: 'phpmall-auth-storage',
    }
  )
)
