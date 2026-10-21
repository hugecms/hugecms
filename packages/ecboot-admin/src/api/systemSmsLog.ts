import { post, get, put } from '@/utils/request'
import type { ISystemSmsLogQueryRequest, ISystemSmsLogQueryResponse, ISystemSmsLogCreateRequest, ISystemSmsLogResponse, ISystemSmsLogUpdateRequest, ISystemSmsLogDestroyRequest, ISystemSmsLogDestroyResponse } from '@/types/systemSmsLog'

/** [短信发送流水模块] 查询短信发送流水列表接口 */
export function systemSmsLogSearchApi(page: number, pageSize: number, data: ISystemSmsLogQueryRequest): Promise<ISystemSmsLogQueryResponse> {
  return post<ISystemSmsLogQueryResponse>('admin/systemSmsLog/search', data, { params: { page, pageSize } })
}

/** [短信发送流水模块] 新增短信发送流水接口 */
export function systemSmsLogStoreApi(data: ISystemSmsLogCreateRequest): Promise<ISystemSmsLogResponse> {
  return post<ISystemSmsLogResponse>('admin/systemSmsLog/store', data)
}

/** [短信发送流水模块] 获取短信发送流水详情接口 */
export function systemSmsLogShowApi(id: number): Promise<ISystemSmsLogResponse> {
  return get<ISystemSmsLogResponse>('admin/systemSmsLog/show', { params: { id } })
}

/** [短信发送流水模块] 更新短信发送流水接口 */
export function systemSmsLogUpdateApi(id: number, data: ISystemSmsLogUpdateRequest): Promise<ISystemSmsLogResponse> {
  return put<ISystemSmsLogResponse>('admin/systemSmsLog/update', data, { params: { id } })
}

/** [短信发送流水模块] 删除短信发送流水接口 */
export function systemSmsLogDestroyApi(data: ISystemSmsLogDestroyRequest): Promise<ISystemSmsLogDestroyResponse> {
  return post<ISystemSmsLogDestroyResponse>('admin/systemSmsLog/destroy', data)
}
