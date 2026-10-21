import { post, get, put } from '@/utils/request'
import type { IProductReviewQueryRequest, IProductReviewQueryResponse, IProductReviewCreateRequest, IProductReviewResponse, IProductReviewUpdateRequest, IProductReviewDestroyRequest, IProductReviewDestroyResponse } from '@/types/productReview'

/** [商品评价模块] 查询商品评价列表接口 */
export function productReviewSearchApi(page: number, pageSize: number, data: IProductReviewQueryRequest): Promise<IProductReviewQueryResponse> {
  return post<IProductReviewQueryResponse>('admin/productReview/search', data, { params: { page, pageSize } })
}

/** [商品评价模块] 新增商品评价接口 */
export function productReviewStoreApi(data: IProductReviewCreateRequest): Promise<IProductReviewResponse> {
  return post<IProductReviewResponse>('admin/productReview/store', data)
}

/** [商品评价模块] 获取商品评价详情接口 */
export function productReviewShowApi(id: number): Promise<IProductReviewResponse> {
  return get<IProductReviewResponse>('admin/productReview/show', { params: { id } })
}

/** [商品评价模块] 更新商品评价接口 */
export function productReviewUpdateApi(id: number, data: IProductReviewUpdateRequest): Promise<IProductReviewResponse> {
  return put<IProductReviewResponse>('admin/productReview/update', data, { params: { id } })
}

/** [商品评价模块] 删除商品评价接口 */
export function productReviewDestroyApi(data: IProductReviewDestroyRequest): Promise<IProductReviewDestroyResponse> {
  return post<IProductReviewDestroyResponse>('admin/productReview/destroy', data)
}
