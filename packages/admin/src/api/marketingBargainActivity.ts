import { post, get, put } from '@/utils/request'
import type { IMarketingBargainActivityQueryRequest, IMarketingBargainActivityQueryResponse, IMarketingBargainActivityCreateRequest, IMarketingBargainActivityResponse, IMarketingBargainActivityUpdateRequest, IMarketingBargainActivityDestroyRequest, IMarketingBargainActivityDestroyResponse } from '@/types/marketingBargainActivity'

/** [砍价活�模块] 查询砍价活�列表接口 */
export function marketingBargainActivitySearchApi(page: number, pageSize: number, data: IMarketingBargainActivityQueryRequest): Promise<IMarketingBargainActivityQueryResponse> {
  return post<IMarketingBargainActivityQueryResponse>('admin/marketingBargainActivity/search', data, { params: { page, pageSize } })
}

/** [砍价活�模块] 新增砍价活�接口 */
export function marketingBargainActivityStoreApi(data: IMarketingBargainActivityCreateRequest): Promise<IMarketingBargainActivityResponse> {
  return post<IMarketingBargainActivityResponse>('admin/marketingBargainActivity/store', data)
}

/** [砍价活�模块] 获取砍价活�详情接口 */
export function marketingBargainActivityShowApi(id: number): Promise<IMarketingBargainActivityResponse> {
  return get<IMarketingBargainActivityResponse>('admin/marketingBargainActivity/show', { params: { id } })
}

/** [砍价活�模块] 更新砍价活�接口 */
export function marketingBargainActivityUpdateApi(id: number, data: IMarketingBargainActivityUpdateRequest): Promise<IMarketingBargainActivityResponse> {
  return put<IMarketingBargainActivityResponse>('admin/marketingBargainActivity/update', data, { params: { id } })
}

/** [砍价活�模块] 删除砍价活�接口 */
export function marketingBargainActivityDestroyApi(data: IMarketingBargainActivityDestroyRequest): Promise<IMarketingBargainActivityDestroyResponse> {
  return post<IMarketingBargainActivityDestroyResponse>('admin/marketingBargainActivity/destroy', data)
}
