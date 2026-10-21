import { post, get, put } from '@/utils/request'
import type { IUserSocialQueryRequest, IUserSocialQueryResponse, IUserSocialCreateRequest, IUserSocialResponse, IUserSocialUpdateRequest, IUserSocialDestroyRequest, IUserSocialDestroyResponse } from '@/types/userSocial'

/** [社交登录绑定模块] 查询社交登录绑定列表接口 */
export function userSocialSearchApi(page: number, pageSize: number, data: IUserSocialQueryRequest): Promise<IUserSocialQueryResponse> {
  return post<IUserSocialQueryResponse>('admin/userSocial/search', data, { params: { page, pageSize } })
}

/** [社交登录绑定模块] 新增社交登录绑定接口 */
export function userSocialStoreApi(data: IUserSocialCreateRequest): Promise<IUserSocialResponse> {
  return post<IUserSocialResponse>('admin/userSocial/store', data)
}

/** [社交登录绑定模块] 获取社交登录绑定详情接口 */
export function userSocialShowApi(id: number): Promise<IUserSocialResponse> {
  return get<IUserSocialResponse>('admin/userSocial/show', { params: { id } })
}

/** [社交登录绑定模块] 更新社交登录绑定接口 */
export function userSocialUpdateApi(id: number, data: IUserSocialUpdateRequest): Promise<IUserSocialResponse> {
  return put<IUserSocialResponse>('admin/userSocial/update', data, { params: { id } })
}

/** [社交登录绑定模块] 删除社交登录绑定接口 */
export function userSocialDestroyApi(data: IUserSocialDestroyRequest): Promise<IUserSocialDestroyResponse> {
  return post<IUserSocialDestroyResponse>('admin/userSocial/destroy', data)
}
