import { useAuthStore } from '../stores/authStore'

export const useAuth = () => {
  const { user, token, isAuthenticated, login, logout, checkAuth } = useAuthStore()

  return {
    user,
    token,
    isAuthenticated,
    isSeller: user?.role === 'seller',
    isAdmin: user?.role === 'admin',
    login,
    logout,
    checkAuth,
  }
}

export default useAuth
