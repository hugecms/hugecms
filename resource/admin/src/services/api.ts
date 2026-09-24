import request from '../utils/request'

// -------------------------------------------------------------
// 认证服务相关接口
// -------------------------------------------------------------
export interface LoginParams {
  username?: string
  email?: string
  password?: string
}

export interface UserInfo {
  id: number
  username: string
  nickname: string
  email: string
  avatar: string
  roles: string[]
  permissions: string[]
}

export const authApi = {
  login: (data: LoginParams) => request.post<{ token: string; user: UserInfo }>('/login', data),
  logout: () => request.post('/logout'),
  getInfo: () => request.get<UserInfo>('/info'),
}

// -------------------------------------------------------------
// 内容管理相关接口
// -------------------------------------------------------------
export interface ContentItem {
  id: number
  title: string
  slug: string
  model_id: number
  status: 'draft' | 'published' | 'archived'
  audit_status: 'pending' | 'approved' | 'rejected'
  visibility: 'public' | 'password' | 'private'
  views: number
  comment_count: number
  is_top: number
  published_at: string
  created_at: string
}

export const contentApi = {
  getList: (params?: any) => request.get<{ list: ContentItem[]; total: number }>('/contents', { params }),
  getDetail: (id: number) => request.get<ContentItem>(`/contents/${id}`),
  create: (data: any) => request.post('/contents', data),
  update: (id: number, data: any) => request.put(`/contents/${id}`, data),
  delete: (id: number) => request.delete(`/contents/${id}`),
}

// -------------------------------------------------------------
// 模型与分类标签接口
// -------------------------------------------------------------
export const modelApi = {
  getList: () => request.get('/content-models'),
}

export const taxonomyApi = {
  getTerms: (params?: any) => request.get('/terms', { params }),
}

// -------------------------------------------------------------
// 回收站与系统概览
// -------------------------------------------------------------
export const recycleBinApi = {
  getList: (params?: any) => request.get('/recycle-bins', { params }),
  restore: (id: number) => request.post(`/recycle-bins/${id}/restore`),
  clean: (id: number) => request.delete(`/recycle-bins/${id}`),
}
