import { post, get, put } from '@/utils/request'
import type { ISystemLoginLogQueryRequest, ISystemLoginLogQueryResponse, ISystemLoginLogCreateRequest, ISystemLoginLogResponse, ISystemLoginLogUpdateRequest, ISystemLoginLogDestroyRequest, ISystemLoginLogDestroyResponse } from '@/types/systemLoginLog'

/** [登录日志模块] 查询登录日志列表接口 */
export function systemLoginLogSearchApi(page: number, pageSize: number, data: ISystemLoginLogQueryRequest): Promise<ISystemLoginLogQueryResponse> {
  return post<ISystemLoginLogQueryResponse>('admin/systemLoginLog/search', data, { params: { page, pageSize } })
}

/** [登录日志模块] 新增登录日志接口 */
export function systemLoginLogStoreApi(data: ISystemLoginLogCreateRequest): Promise<ISystemLoginLogResponse> {
  return post<ISystemLoginLogResponse>('admin/systemLoginLog/store', data)
}

/** [登录日志模块] 获取登录日志详情接口 */
export function systemLoginLogShowApi(id: number): Promise<ISystemLoginLogResponse> {
  return get<ISystemLoginLogResponse>('admin/systemLoginLog/show', { params: { id } })
}

/** [登录日志模块] 更新登录日志接口 */
export function systemLoginLogUpdateApi(id: number, data: ISystemLoginLogUpdateRequest): Promise<ISystemLoginLogResponse> {
  return put<ISystemLoginLogResponse>('admin/systemLoginLog/update', data, { params: { id } })
}

/** [登录日志模块] 删除登录日志接口 */
export function systemLoginLogDestroyApi(data: ISystemLoginLogDestroyRequest): Promise<ISystemLoginLogDestroyResponse> {
  return post<ISystemLoginLogDestroyResponse>('admin/systemLoginLog/destroy', data)
}
