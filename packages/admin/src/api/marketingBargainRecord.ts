import { post, get, put } from '@/utils/request'
import type { IMarketingBargainRecordQueryRequest, IMarketingBargainRecordQueryResponse, IMarketingBargainRecordCreateRequest, IMarketingBargainRecordResponse, IMarketingBargainRecordUpdateRequest, IMarketingBargainRecordDestroyRequest, IMarketingBargainRecordDestroyResponse } from '@/types/marketingBargainRecord'

/** [砍价单模块] 查询砍价单列表接口 */
export function marketingBargainRecordSearchApi(page: number, pageSize: number, data: IMarketingBargainRecordQueryRequest): Promise<IMarketingBargainRecordQueryResponse> {
  return post<IMarketingBargainRecordQueryResponse>('admin/marketingBargainRecord/search', data, { params: { page, pageSize } })
}

/** [砍价单模块] 新增砍价单接口 */
export function marketingBargainRecordStoreApi(data: IMarketingBargainRecordCreateRequest): Promise<IMarketingBargainRecordResponse> {
  return post<IMarketingBargainRecordResponse>('admin/marketingBargainRecord/store', data)
}

/** [砍价单模块] 获取砍价单详情接口 */
export function marketingBargainRecordShowApi(id: number): Promise<IMarketingBargainRecordResponse> {
  return get<IMarketingBargainRecordResponse>('admin/marketingBargainRecord/show', { params: { id } })
}

/** [砍价单模块] 更新砍价单接口 */
export function marketingBargainRecordUpdateApi(id: number, data: IMarketingBargainRecordUpdateRequest): Promise<IMarketingBargainRecordResponse> {
  return put<IMarketingBargainRecordResponse>('admin/marketingBargainRecord/update', data, { params: { id } })
}

/** [砍价单模块] 删除砍价单接口 */
export function marketingBargainRecordDestroyApi(data: IMarketingBargainRecordDestroyRequest): Promise<IMarketingBargainRecordDestroyResponse> {
  return post<IMarketingBargainRecordDestroyResponse>('admin/marketingBargainRecord/destroy', data)
}
