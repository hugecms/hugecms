import { post, get, put } from '@/utils/request'
import type { IMarketingShareRecordQueryRequest, IMarketingShareRecordQueryResponse, IMarketingShareRecordCreateRequest, IMarketingShareRecordResponse, IMarketingShareRecordUpdateRequest, IMarketingShareRecordDestroyRequest, IMarketingShareRecordDestroyResponse } from '@/types/marketingShareRecord'

/** [分享行为记录模块] 查询分享行为记录列表接口 */
export function marketingShareRecordSearchApi(page: number, pageSize: number, data: IMarketingShareRecordQueryRequest): Promise<IMarketingShareRecordQueryResponse> {
  return post<IMarketingShareRecordQueryResponse>('admin/marketingShareRecord/search', data, { params: { page, pageSize } })
}

/** [分享行为记录模块] 新增分享行为记录接口 */
export function marketingShareRecordStoreApi(data: IMarketingShareRecordCreateRequest): Promise<IMarketingShareRecordResponse> {
  return post<IMarketingShareRecordResponse>('admin/marketingShareRecord/store', data)
}

/** [分享行为记录模块] 获取分享行为记录详情接口 */
export function marketingShareRecordShowApi(id: number): Promise<IMarketingShareRecordResponse> {
  return get<IMarketingShareRecordResponse>('admin/marketingShareRecord/show', { params: { id } })
}

/** [分享行为记录模块] 更新分享行为记录接口 */
export function marketingShareRecordUpdateApi(id: number, data: IMarketingShareRecordUpdateRequest): Promise<IMarketingShareRecordResponse> {
  return put<IMarketingShareRecordResponse>('admin/marketingShareRecord/update', data, { params: { id } })
}

/** [分享行为记录模块] 删除分享行为记录接口 */
export function marketingShareRecordDestroyApi(data: IMarketingShareRecordDestroyRequest): Promise<IMarketingShareRecordDestroyResponse> {
  return post<IMarketingShareRecordDestroyResponse>('admin/marketingShareRecord/destroy', data)
}
