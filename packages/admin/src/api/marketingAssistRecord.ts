import { post, get, put } from '@/utils/request'
import type { IMarketingAssistRecordQueryRequest, IMarketingAssistRecordQueryResponse, IMarketingAssistRecordCreateRequest, IMarketingAssistRecordResponse, IMarketingAssistRecordUpdateRequest, IMarketingAssistRecordDestroyRequest, IMarketingAssistRecordDestroyResponse } from '@/types/marketingAssistRecord'

/** [助力参与记录模块] 查询助力参与记录列表接口 */
export function marketingAssistRecordSearchApi(page: number, pageSize: number, data: IMarketingAssistRecordQueryRequest): Promise<IMarketingAssistRecordQueryResponse> {
  return post<IMarketingAssistRecordQueryResponse>('admin/marketingAssistRecord/search', data, { params: { page, pageSize } })
}

/** [助力参与记录模块] 新增助力参与记录接口 */
export function marketingAssistRecordStoreApi(data: IMarketingAssistRecordCreateRequest): Promise<IMarketingAssistRecordResponse> {
  return post<IMarketingAssistRecordResponse>('admin/marketingAssistRecord/store', data)
}

/** [助力参与记录模块] 获取助力参与记录详情接口 */
export function marketingAssistRecordShowApi(id: number): Promise<IMarketingAssistRecordResponse> {
  return get<IMarketingAssistRecordResponse>('admin/marketingAssistRecord/show', { params: { id } })
}

/** [助力参与记录模块] 更新助力参与记录接口 */
export function marketingAssistRecordUpdateApi(id: number, data: IMarketingAssistRecordUpdateRequest): Promise<IMarketingAssistRecordResponse> {
  return put<IMarketingAssistRecordResponse>('admin/marketingAssistRecord/update', data, { params: { id } })
}

/** [助力参与记录模块] 删除助力参与记录接口 */
export function marketingAssistRecordDestroyApi(data: IMarketingAssistRecordDestroyRequest): Promise<IMarketingAssistRecordDestroyResponse> {
  return post<IMarketingAssistRecordDestroyResponse>('admin/marketingAssistRecord/destroy', data)
}
