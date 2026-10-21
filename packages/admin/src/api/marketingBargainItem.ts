import { post, get, put } from '@/utils/request'
import type { IMarketingBargainItemQueryRequest, IMarketingBargainItemQueryResponse, IMarketingBargainItemCreateRequest, IMarketingBargainItemResponse, IMarketingBargainItemUpdateRequest, IMarketingBargainItemDestroyRequest, IMarketingBargainItemDestroyResponse } from '@/types/marketingBargainItem'

/** [砍价场次商品模块] 查询砍价场次商品列表接口 */
export function marketingBargainItemSearchApi(page: number, pageSize: number, data: IMarketingBargainItemQueryRequest): Promise<IMarketingBargainItemQueryResponse> {
  return post<IMarketingBargainItemQueryResponse>('admin/marketingBargainItem/search', data, { params: { page, pageSize } })
}

/** [砍价场次商品模块] 新增砍价场次商品接口 */
export function marketingBargainItemStoreApi(data: IMarketingBargainItemCreateRequest): Promise<IMarketingBargainItemResponse> {
  return post<IMarketingBargainItemResponse>('admin/marketingBargainItem/store', data)
}

/** [砍价场次商品模块] 获取砍价场次商品详情接口 */
export function marketingBargainItemShowApi(id: number): Promise<IMarketingBargainItemResponse> {
  return get<IMarketingBargainItemResponse>('admin/marketingBargainItem/show', { params: { id } })
}

/** [砍价场次商品模块] 更新砍价场次商品接口 */
export function marketingBargainItemUpdateApi(id: number, data: IMarketingBargainItemUpdateRequest): Promise<IMarketingBargainItemResponse> {
  return put<IMarketingBargainItemResponse>('admin/marketingBargainItem/update', data, { params: { id } })
}

/** [砍价场次商品模块] 删除砍价场次商品接口 */
export function marketingBargainItemDestroyApi(data: IMarketingBargainItemDestroyRequest): Promise<IMarketingBargainItemDestroyResponse> {
  return post<IMarketingBargainItemDestroyResponse>('admin/marketingBargainItem/destroy', data)
}
