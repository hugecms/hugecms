import { post, get, put } from '@/utils/request'
import type { ISystemRiskRecordQueryRequest, ISystemRiskRecordQueryResponse, ISystemRiskRecordCreateRequest, ISystemRiskRecordResponse, ISystemRiskRecordUpdateRequest, ISystemRiskRecordDestroyRequest, ISystemRiskRecordDestroyResponse } from '@/types/systemRiskRecord'

/** [风控事件模块] 查询风控事件列表接口 */
export function systemRiskRecordSearchApi(page: number, pageSize: number, data: ISystemRiskRecordQueryRequest): Promise<ISystemRiskRecordQueryResponse> {
  return post<ISystemRiskRecordQueryResponse>('admin/systemRiskRecord/search', data, { params: { page, pageSize } })
}

/** [风控事件模块] 新增风控事件接口 */
export function systemRiskRecordStoreApi(data: ISystemRiskRecordCreateRequest): Promise<ISystemRiskRecordResponse> {
  return post<ISystemRiskRecordResponse>('admin/systemRiskRecord/store', data)
}

/** [风控事件模块] 获取风控事件详情接口 */
export function systemRiskRecordShowApi(id: number): Promise<ISystemRiskRecordResponse> {
  return get<ISystemRiskRecordResponse>('admin/systemRiskRecord/show', { params: { id } })
}

/** [风控事件模块] 更新风控事件接口 */
export function systemRiskRecordUpdateApi(id: number, data: ISystemRiskRecordUpdateRequest): Promise<ISystemRiskRecordResponse> {
  return put<ISystemRiskRecordResponse>('admin/systemRiskRecord/update', data, { params: { id } })
}

/** [风控事件模块] 删除风控事件接口 */
export function systemRiskRecordDestroyApi(data: ISystemRiskRecordDestroyRequest): Promise<ISystemRiskRecordDestroyResponse> {
  return post<ISystemRiskRecordDestroyResponse>('admin/systemRiskRecord/destroy', data)
}
