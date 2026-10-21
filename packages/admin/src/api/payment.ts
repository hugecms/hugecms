import { post, get, put } from '@/utils/request'
import type { IPaymentQueryRequest, IPaymentQueryResponse, IPaymentCreateRequest, IPaymentResponse, IPaymentUpdateRequest, IPaymentDestroyRequest, IPaymentDestroyResponse } from '@/types/payment'

/** [支付单模块] 查询支付单列表接口 */
export function paymentSearchApi(page: number, pageSize: number, data: IPaymentQueryRequest): Promise<IPaymentQueryResponse> {
  return post<IPaymentQueryResponse>('admin/payment/search', data, { params: { page, pageSize } })
}

/** [支付单模块] 新增支付单接口 */
export function paymentStoreApi(data: IPaymentCreateRequest): Promise<IPaymentResponse> {
  return post<IPaymentResponse>('admin/payment/store', data)
}

/** [支付单模块] 获取支付单详情接口 */
export function paymentShowApi(id: number): Promise<IPaymentResponse> {
  return get<IPaymentResponse>('admin/payment/show', { params: { id } })
}

/** [支付单模块] 更新支付单接口 */
export function paymentUpdateApi(id: number, data: IPaymentUpdateRequest): Promise<IPaymentResponse> {
  return put<IPaymentResponse>('admin/payment/update', data, { params: { id } })
}

/** [支付单模块] 删除支付单接口 */
export function paymentDestroyApi(data: IPaymentDestroyRequest): Promise<IPaymentDestroyResponse> {
  return post<IPaymentDestroyResponse>('admin/payment/destroy', data)
}
