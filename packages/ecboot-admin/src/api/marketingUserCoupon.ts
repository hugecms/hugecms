import { post, get, put } from '@/utils/request'
import type { IMarketingUserCouponQueryRequest, IMarketingUserCouponQueryResponse, IMarketingUserCouponCreateRequest, IMarketingUserCouponResponse, IMarketingUserCouponUpdateRequest, IMarketingUserCouponDestroyRequest, IMarketingUserCouponDestroyResponse } from '@/types/marketingUserCoupon'

/** [用户优惠券模块] 查询用户优惠券列表接口 */
export function marketingUserCouponSearchApi(page: number, pageSize: number, data: IMarketingUserCouponQueryRequest): Promise<IMarketingUserCouponQueryResponse> {
  return post<IMarketingUserCouponQueryResponse>('admin/marketingUserCoupon/search', data, { params: { page, pageSize } })
}

/** [用户优惠券模块] 新增用户优惠券接口 */
export function marketingUserCouponStoreApi(data: IMarketingUserCouponCreateRequest): Promise<IMarketingUserCouponResponse> {
  return post<IMarketingUserCouponResponse>('admin/marketingUserCoupon/store', data)
}

/** [用户优惠券模块] 获取用户优惠券详情接口 */
export function marketingUserCouponShowApi(id: number): Promise<IMarketingUserCouponResponse> {
  return get<IMarketingUserCouponResponse>('admin/marketingUserCoupon/show', { params: { id } })
}

/** [用户优惠券模块] 更新用户优惠券接口 */
export function marketingUserCouponUpdateApi(id: number, data: IMarketingUserCouponUpdateRequest): Promise<IMarketingUserCouponResponse> {
  return put<IMarketingUserCouponResponse>('admin/marketingUserCoupon/update', data, { params: { id } })
}

/** [用户优惠券模块] 删除用户优惠券接口 */
export function marketingUserCouponDestroyApi(data: IMarketingUserCouponDestroyRequest): Promise<IMarketingUserCouponDestroyResponse> {
  return post<IMarketingUserCouponDestroyResponse>('admin/marketingUserCoupon/destroy', data)
}
