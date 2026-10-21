import { post, get, put } from '@/utils/request'
import type { ISystemOperationLogQueryRequest, ISystemOperationLogQueryResponse, ISystemOperationLogCreateRequest, ISystemOperationLogResponse, ISystemOperationLogUpdateRequest, ISystemOperationLogDestroyRequest, ISystemOperationLogDestroyResponse } from '@/types/systemOperationLog'

/** [操作日志模块] 查询操作日志列表接口 */
export function systemOperationLogSearchApi(page: number, pageSize: number, data: ISystemOperationLogQueryRequest): Promise<ISystemOperationLogQueryResponse> {
  return post<ISystemOperationLogQueryResponse>('admin/systemOperationLog/search', data, { params: { page, pageSize } })
}

/** [操作日志模块] 新增操作日志接口 */
export function systemOperationLogStoreApi(data: ISystemOperationLogCreateRequest): Promise<ISystemOperationLogResponse> {
  return post<ISystemOperationLogResponse>('admin/systemOperationLog/store', data)
}

/** [操作日志模块] 获取操作日志详情接口 */
export function systemOperationLogShowApi(id: number): Promise<ISystemOperationLogResponse> {
  return get<ISystemOperationLogResponse>('admin/systemOperationLog/show', { params: { id } })
}

/** [操作日志模块] 更新操作日志接口 */
export function systemOperationLogUpdateApi(id: number, data: ISystemOperationLogUpdateRequest): Promise<ISystemOperationLogResponse> {
  return put<ISystemOperationLogResponse>('admin/systemOperationLog/update', data, { params: { id } })
}

/** [操作日志模块] 删除操作日志接口 */
export function systemOperationLogDestroyApi(data: ISystemOperationLogDestroyRequest): Promise<ISystemOperationLogDestroyResponse> {
  return post<ISystemOperationLogDestroyResponse>('admin/systemOperationLog/destroy', data)
}
