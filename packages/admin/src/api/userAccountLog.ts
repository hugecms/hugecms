import { post, get, put } from '@/utils/request'
import type { IUserAccountLogQueryRequest, IUserAccountLogQueryResponse, IUserAccountLogCreateRequest, IUserAccountLogResponse, IUserAccountLogUpdateRequest, IUserAccountLogDestroyRequest, IUserAccountLogDestroyResponse } from '@/types/userAccountLog'

/** [用户资金/积分流水模块] 查询用户资金/积分流水列表接口 */
export function userAccountLogSearchApi(page: number, pageSize: number, data: IUserAccountLogQueryRequest): Promise<IUserAccountLogQueryResponse> {
  return post<IUserAccountLogQueryResponse>('admin/userAccountLog/search', data, { params: { page, pageSize } })
}

/** [用户资金/积分流水模块] 新增用户资金/积分流水接口 */
export function userAccountLogStoreApi(data: IUserAccountLogCreateRequest): Promise<IUserAccountLogResponse> {
  return post<IUserAccountLogResponse>('admin/userAccountLog/store', data)
}

/** [用户资金/积分流水模块] 获取用户资金/积分流水详情接口 */
export function userAccountLogShowApi(id: number): Promise<IUserAccountLogResponse> {
  return get<IUserAccountLogResponse>('admin/userAccountLog/show', { params: { id } })
}

/** [用户资金/积分流水模块] 更新用户资金/积分流水接口 */
export function userAccountLogUpdateApi(id: number, data: IUserAccountLogUpdateRequest): Promise<IUserAccountLogResponse> {
  return put<IUserAccountLogResponse>('admin/userAccountLog/update', data, { params: { id } })
}

/** [用户资金/积分流水模块] 删除用户资金/积分流水接口 */
export function userAccountLogDestroyApi(data: IUserAccountLogDestroyRequest): Promise<IUserAccountLogDestroyResponse> {
  return post<IUserAccountLogDestroyResponse>('admin/userAccountLog/destroy', data)
}
