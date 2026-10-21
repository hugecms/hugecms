import { post, get, put } from '@/utils/request'
import type { IPaymentChannelQueryRequest, IPaymentChannelQueryResponse, IPaymentChannelCreateRequest, IPaymentChannelResponse, IPaymentChannelUpdateRequest, IPaymentChannelDestroyRequest, IPaymentChannelDestroyResponse } from '@/types/paymentChannel'

/** [支付渠道配置模块] 查询支付渠道配置列表接口 */
export function paymentChannelSearchApi(page: number, pageSize: number, data: IPaymentChannelQueryRequest): Promise<IPaymentChannelQueryResponse> {
  return post<IPaymentChannelQueryResponse>('admin/paymentChannel/search', data, { params: { page, pageSize } })
}

/** [支付渠道配置模块] 新增支付渠道配置接口 */
export function paymentChannelStoreApi(data: IPaymentChannelCreateRequest): Promise<IPaymentChannelResponse> {
  return post<IPaymentChannelResponse>('admin/paymentChannel/store', data)
}

/** [支付渠道配置模块] 获取支付渠道配置详情接口 */
export function paymentChannelShowApi(id: number): Promise<IPaymentChannelResponse> {
  return get<IPaymentChannelResponse>('admin/paymentChannel/show', { params: { id } })
}

/** [支付渠道配置模块] 更新支付渠道配置接口 */
export function paymentChannelUpdateApi(id: number, data: IPaymentChannelUpdateRequest): Promise<IPaymentChannelResponse> {
  return put<IPaymentChannelResponse>('admin/paymentChannel/update', data, { params: { id } })
}

/** [支付渠道配置模块] 删除支付渠道配置接口 */
export function paymentChannelDestroyApi(data: IPaymentChannelDestroyRequest): Promise<IPaymentChannelDestroyResponse> {
  return post<IPaymentChannelDestroyResponse>('admin/paymentChannel/destroy', data)
}
