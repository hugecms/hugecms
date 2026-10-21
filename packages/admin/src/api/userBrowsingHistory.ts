import { post, get, put } from '@/utils/request'
import type { IUserBrowsingHistoryQueryRequest, IUserBrowsingHistoryQueryResponse, IUserBrowsingHistoryCreateRequest, IUserBrowsingHistoryResponse, IUserBrowsingHistoryUpdateRequest, IUserBrowsingHistoryDestroyRequest, IUserBrowsingHistoryDestroyResponse } from '@/types/userBrowsingHistory'

/** [用户浏览足迹模块] 查询用户浏览足迹列表接口 */
export function userBrowsingHistorySearchApi(page: number, pageSize: number, data: IUserBrowsingHistoryQueryRequest): Promise<IUserBrowsingHistoryQueryResponse> {
  return post<IUserBrowsingHistoryQueryResponse>('admin/userBrowsingHistory/search', data, { params: { page, pageSize } })
}

/** [用户浏览足迹模块] 新增用户浏览足迹接口 */
export function userBrowsingHistoryStoreApi(data: IUserBrowsingHistoryCreateRequest): Promise<IUserBrowsingHistoryResponse> {
  return post<IUserBrowsingHistoryResponse>('admin/userBrowsingHistory/store', data)
}

/** [用户浏览足迹模块] 获取用户浏览足迹详情接口 */
export function userBrowsingHistoryShowApi(id: number): Promise<IUserBrowsingHistoryResponse> {
  return get<IUserBrowsingHistoryResponse>('admin/userBrowsingHistory/show', { params: { id } })
}

/** [用户浏览足迹模块] 更新用户浏览足迹接口 */
export function userBrowsingHistoryUpdateApi(id: number, data: IUserBrowsingHistoryUpdateRequest): Promise<IUserBrowsingHistoryResponse> {
  return put<IUserBrowsingHistoryResponse>('admin/userBrowsingHistory/update', data, { params: { id } })
}

/** [用户浏览足迹模块] 删除用户浏览足迹接口 */
export function userBrowsingHistoryDestroyApi(data: IUserBrowsingHistoryDestroyRequest): Promise<IUserBrowsingHistoryDestroyResponse> {
  return post<IUserBrowsingHistoryDestroyResponse>('admin/userBrowsingHistory/destroy', data)
}
