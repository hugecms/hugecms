import { post, get, put } from '@/utils/request'
import type { IMarketingGroupBuyItemQueryRequest, IMarketingGroupBuyItemQueryResponse, IMarketingGroupBuyItemCreateRequest, IMarketingGroupBuyItemResponse, IMarketingGroupBuyItemUpdateRequest, IMarketingGroupBuyItemDestroyRequest, IMarketingGroupBuyItemDestroyResponse } from '@/types/marketingGroupBuyItem'

/** [拼团场次商品模块] 查询拼团场次商品列表接口 */
export function marketingGroupBuyItemSearchApi(page: number, pageSize: number, data: IMarketingGroupBuyItemQueryRequest): Promise<IMarketingGroupBuyItemQueryResponse> {
  return post<IMarketingGroupBuyItemQueryResponse>('admin/marketingGroupBuyItem/search', data, { params: { page, pageSize } })
}

/** [拼团场次商品模块] 新增拼团场次商品接口 */
export function marketingGroupBuyItemStoreApi(data: IMarketingGroupBuyItemCreateRequest): Promise<IMarketingGroupBuyItemResponse> {
  return post<IMarketingGroupBuyItemResponse>('admin/marketingGroupBuyItem/store', data)
}

/** [拼团场次商品模块] 获取拼团场次商品详情接口 */
export function marketingGroupBuyItemShowApi(id: number): Promise<IMarketingGroupBuyItemResponse> {
  return get<IMarketingGroupBuyItemResponse>('admin/marketingGroupBuyItem/show', { params: { id } })
}

/** [拼团场次商品模块] 更新拼团场次商品接口 */
export function marketingGroupBuyItemUpdateApi(id: number, data: IMarketingGroupBuyItemUpdateRequest): Promise<IMarketingGroupBuyItemResponse> {
  return put<IMarketingGroupBuyItemResponse>('admin/marketingGroupBuyItem/update', data, { params: { id } })
}

/** [拼团场次商品模块] 删除拼团场次商品接口 */
export function marketingGroupBuyItemDestroyApi(data: IMarketingGroupBuyItemDestroyRequest): Promise<IMarketingGroupBuyItemDestroyResponse> {
  return post<IMarketingGroupBuyItemDestroyResponse>('admin/marketingGroupBuyItem/destroy', data)
}
