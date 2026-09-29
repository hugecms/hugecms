/**
 * 全局统一通用 API 响应与分页数据结构
 */

export interface ApiResponse<T = any> {
  code: number
  message: string
  data: T
  success: boolean
  timestamp?: number
}

export interface PageResult<T = any> {
  list: T[]
  total: number
  page: number
  pageSize: number
  hasMore?: boolean
}

export interface BaseEntity {
  id: string | number
  createdAt?: string
  updatedAt?: string
}

export type SortOrder = 'asc' | 'desc' | 'default'

export interface SelectOption<T = string | number> {
  label: string
  value: T
  disabled?: boolean
}
