import { post, get, put } from '@/utils/request'
import type { IOrderPromotionQueryRequest, IOrderPromotionQueryResponse, IOrderPromotionCreateRequest, IOrderPromotionResponse, IOrderPromotionUpdateRequest, IOrderPromotionDestroyRequest, IOrderPromotionDestroyResponse } from '@/types/orderPromotion'

/** [订单优惠明细模块] 查询订单优惠明细列表接口 */
export function orderPromotionSearchApi(page: number, pageSize: number, data: IOrderPromotionQueryRequest): Promise<IOrderPromotionQueryResponse> {
  return post<IOrderPromotionQueryResponse>('admin/orderPromotion/search', data, { params: { page, pageSize } })
}

/** [订单优惠明细模块] 新增订单优惠明细接口 */
export function orderPromotionStoreApi(data: IOrderPromotionCreateRequest): Promise<IOrderPromotionResponse> {
  return post<IOrderPromotionResponse>('admin/orderPromotion/store', data)
}

/** [订单优惠明细模块] 获取订单优惠明细详情接口 */
export function orderPromotionShowApi(id: number): Promise<IOrderPromotionResponse> {
  return get<IOrderPromotionResponse>('admin/orderPromotion/show', { params: { id } })
}

/** [订单优惠明细模块] 更新订单优惠明细接口 */
export function orderPromotionUpdateApi(id: number, data: IOrderPromotionUpdateRequest): Promise<IOrderPromotionResponse> {
  return put<IOrderPromotionResponse>('admin/orderPromotion/update', data, { params: { id } })
}

/** [订单优惠明细模块] 删除订单优惠明细接口 */
export function orderPromotionDestroyApi(data: IOrderPromotionDestroyRequest): Promise<IOrderPromotionDestroyResponse> {
  return post<IOrderPromotionDestroyResponse>('admin/orderPromotion/destroy', data)
}
