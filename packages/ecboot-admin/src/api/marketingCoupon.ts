import { post, get, put } from '@/utils/request'
import type { IMarketingCouponQueryRequest, IMarketingCouponQueryResponse, IMarketingCouponCreateRequest, IMarketingCouponResponse, IMarketingCouponUpdateRequest, IMarketingCouponDestroyRequest, IMarketingCouponDestroyResponse } from '@/types/marketingCoupon'

/** [优惠券类型模块] 查询优惠券类型列表接口 */
export function marketingCouponSearchApi(page: number, pageSize: number, data: IMarketingCouponQueryRequest): Promise<IMarketingCouponQueryResponse> {
  return post<IMarketingCouponQueryResponse>('admin/marketingCoupon/search', data, { params: { page, pageSize } })
}

/** [优惠券类型模块] 新增优惠券类型接口 */
export function marketingCouponStoreApi(data: IMarketingCouponCreateRequest): Promise<IMarketingCouponResponse> {
  return post<IMarketingCouponResponse>('admin/marketingCoupon/store', data)
}

/** [优惠券类型模块] 获取优惠券类型详情接口 */
export function marketingCouponShowApi(id: number): Promise<IMarketingCouponResponse> {
  return get<IMarketingCouponResponse>('admin/marketingCoupon/show', { params: { id } })
}

/** [优惠券类型模块] 更新优惠券类型接口 */
export function marketingCouponUpdateApi(id: number, data: IMarketingCouponUpdateRequest): Promise<IMarketingCouponResponse> {
  return put<IMarketingCouponResponse>('admin/marketingCoupon/update', data, { params: { id } })
}

/** [优惠券类型模块] 删除优惠券类型接口 */
export function marketingCouponDestroyApi(data: IMarketingCouponDestroyRequest): Promise<IMarketingCouponDestroyResponse> {
  return post<IMarketingCouponDestroyResponse>('admin/marketingCoupon/destroy', data)
}
