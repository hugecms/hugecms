import { post, get, put } from '@/utils/request'
import type { IUserFollowerQueryRequest, IUserFollowerQueryResponse, IUserFollowerCreateRequest, IUserFollowerResponse, IUserFollowerUpdateRequest, IUserFollowerDestroyRequest, IUserFollowerDestroyResponse } from '@/types/userFollower'

/** [关注关系模块] 查询关注关系列表接口 */
export function userFollowerSearchApi(page: number, pageSize: number, data: IUserFollowerQueryRequest): Promise<IUserFollowerQueryResponse> {
  return post<IUserFollowerQueryResponse>('admin/userFollower/search', data, { params: { page, pageSize } })
}

/** [关注关系模块] 新增关注关系接口 */
export function userFollowerStoreApi(data: IUserFollowerCreateRequest): Promise<IUserFollowerResponse> {
  return post<IUserFollowerResponse>('admin/userFollower/store', data)
}

/** [关注关系模块] 获取关注关系详情接口 */
export function userFollowerShowApi(id: number): Promise<IUserFollowerResponse> {
  return get<IUserFollowerResponse>('admin/userFollower/show', { params: { id } })
}

/** [关注关系模块] 更新关注关系接口 */
export function userFollowerUpdateApi(id: number, data: IUserFollowerUpdateRequest): Promise<IUserFollowerResponse> {
  return put<IUserFollowerResponse>('admin/userFollower/update', data, { params: { id } })
}

/** [关注关系模块] 删除关注关系接口 */
export function userFollowerDestroyApi(data: IUserFollowerDestroyRequest): Promise<IUserFollowerDestroyResponse> {
  return post<IUserFollowerDestroyResponse>('admin/userFollower/destroy', data)
}
