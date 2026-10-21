import { post, get, put } from '@/utils/request'
import type { IUserFavoriteQueryRequest, IUserFavoriteQueryResponse, IUserFavoriteCreateRequest, IUserFavoriteResponse, IUserFavoriteUpdateRequest, IUserFavoriteDestroyRequest, IUserFavoriteDestroyResponse } from '@/types/userFavorite'

/** [用户收藏模块] 查询用户收藏列表接口 */
export function userFavoriteSearchApi(page: number, pageSize: number, data: IUserFavoriteQueryRequest): Promise<IUserFavoriteQueryResponse> {
  return post<IUserFavoriteQueryResponse>('admin/userFavorite/search', data, { params: { page, pageSize } })
}

/** [用户收藏模块] 新增用户收藏接口 */
export function userFavoriteStoreApi(data: IUserFavoriteCreateRequest): Promise<IUserFavoriteResponse> {
  return post<IUserFavoriteResponse>('admin/userFavorite/store', data)
}

/** [用户收藏模块] 获取用户收藏详情接口 */
export function userFavoriteShowApi(id: number): Promise<IUserFavoriteResponse> {
  return get<IUserFavoriteResponse>('admin/userFavorite/show', { params: { id } })
}

/** [用户收藏模块] 更新用户收藏接口 */
export function userFavoriteUpdateApi(id: number, data: IUserFavoriteUpdateRequest): Promise<IUserFavoriteResponse> {
  return put<IUserFavoriteResponse>('admin/userFavorite/update', data, { params: { id } })
}

/** [用户收藏模块] 删除用户收藏接口 */
export function userFavoriteDestroyApi(data: IUserFavoriteDestroyRequest): Promise<IUserFavoriteDestroyResponse> {
  return post<IUserFavoriteDestroyResponse>('admin/userFavorite/destroy', data)
}
