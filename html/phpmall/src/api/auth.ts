import { request } from './client'
import type { AuthUser } from '../stores/authStore'

export interface LoginParams {
  username: string
  password?: string
  verifyCode?: string
  type?: 'account' | 'mobile'
}

export interface RegisterParams {
  phone: string
  verifyCode: string
  password?: string
}

export interface LoginResult {
  token: string
  user: AuthUser
}

/**
 * 用户登录
 */
export async function loginApi(params: LoginParams): Promise<LoginResult> {
  return request<LoginResult>({
    url: '/auth/login',
    method: 'POST',
    data: params,
  })
}

/**
 * 用户注册
 */
export async function registerApi(params: RegisterParams): Promise<LoginResult> {
  return request<LoginResult>({
    url: '/auth/register',
    method: 'POST',
    data: params,
  })
}

/**
 * 退出登录
 */
export async function logoutApi(): Promise<void> {
  return request<void>({
    url: '/auth/logout',
    method: 'POST',
  })
}

/**
 * 获取当前用户信息
 */
export async function getUserProfileApi(): Promise<AuthUser> {
  return request<AuthUser>({
    url: '/auth/profile',
    method: 'GET',
  })
}
