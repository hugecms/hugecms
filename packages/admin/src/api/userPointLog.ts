import { post, get, put } from '@/utils/request'
import type { IUserPointLogQueryRequest, IUserPointLogQueryResponse, IUserPointLogCreateRequest, IUserPointLogResponse, IUserPointLogUpdateRequest, IUserPointLogDestroyRequest, IUserPointLogDestroyResponse } from '@/types/userPointLog'

/** [用户积分流水模块] 查询用户积分流水列表接口 */
export function userPointLogSearchApi(page: number, pageSize: number, data: IUserPointLogQueryRequest): Promise<IUserPointLogQueryResponse> {
  return post<IUserPointLogQueryResponse>('admin/userPointLog/search', data, { params: { page, pageSize } })
}

/** [用户积分流水模块] 新增用户积分流水接口 */
export function userPointLogStoreApi(data: IUserPointLogCreateRequest): Promise<IUserPointLogResponse> {
  return post<IUserPointLogResponse>('admin/userPointLog/store', data)
}

/** [用户积分流水模块] 获取用户积分流水详情接口 */
export function userPointLogShowApi(id: number): Promise<IUserPointLogResponse> {
  return get<IUserPointLogResponse>('admin/userPointLog/show', { params: { id } })
}

/** [用户积分流水模块] 更新用户积分流水接口 */
export function userPointLogUpdateApi(id: number, data: IUserPointLogUpdateRequest): Promise<IUserPointLogResponse> {
  return put<IUserPointLogResponse>('admin/userPointLog/update', data, { params: { id } })
}

/** [用户积分流水模块] 删除用户积分流水接口 */
export function userPointLogDestroyApi(data: IUserPointLogDestroyRequest): Promise<IUserPointLogDestroyResponse> {
  return post<IUserPointLogDestroyResponse>('admin/userPointLog/destroy', data)
}
