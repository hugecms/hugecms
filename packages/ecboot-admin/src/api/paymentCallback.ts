import { post, get, put } from '@/utils/request'
import type { IPaymentCallbackQueryRequest, IPaymentCallbackQueryResponse, IPaymentCallbackCreateRequest, IPaymentCallbackResponse, IPaymentCallbackUpdateRequest, IPaymentCallbackDestroyRequest, IPaymentCallbackDestroyResponse } from '@/types/paymentCallback'

/** [支付回调流水模块] 查询支付回调流水列表接口 */
export function paymentCallbackSearchApi(page: number, pageSize: number, data: IPaymentCallbackQueryRequest): Promise<IPaymentCallbackQueryResponse> {
  return post<IPaymentCallbackQueryResponse>('admin/paymentCallback/search', data, { params: { page, pageSize } })
}

/** [支付回调流水模块] 新增支付回调流水接口 */
export function paymentCallbackStoreApi(data: IPaymentCallbackCreateRequest): Promise<IPaymentCallbackResponse> {
  return post<IPaymentCallbackResponse>('admin/paymentCallback/store', data)
}

/** [支付回调流水模块] 获取支付回调流水详情接口 */
export function paymentCallbackShowApi(id: number): Promise<IPaymentCallbackResponse> {
  return get<IPaymentCallbackResponse>('admin/paymentCallback/show', { params: { id } })
}

/** [支付回调流水模块] 更新支付回调流水接口 */
export function paymentCallbackUpdateApi(id: number, data: IPaymentCallbackUpdateRequest): Promise<IPaymentCallbackResponse> {
  return put<IPaymentCallbackResponse>('admin/paymentCallback/update', data, { params: { id } })
}

/** [支付回调流水模块] 删除支付回调流水接口 */
export function paymentCallbackDestroyApi(data: IPaymentCallbackDestroyRequest): Promise<IPaymentCallbackDestroyResponse> {
  return post<IPaymentCallbackDestroyResponse>('admin/paymentCallback/destroy', data)
}
