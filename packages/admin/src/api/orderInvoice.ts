import { post, get, put } from '@/utils/request'
import type { IOrderInvoiceQueryRequest, IOrderInvoiceQueryResponse, IOrderInvoiceCreateRequest, IOrderInvoiceResponse, IOrderInvoiceUpdateRequest, IOrderInvoiceDestroyRequest, IOrderInvoiceDestroyResponse } from '@/types/orderInvoice'

/** [订单发票表(合规必需:用户开票申请与发票状态跟踪)模块] 查询订单发票表(合规必需:用户开票申请与发票状态跟踪)列表接口 */
export function orderInvoiceSearchApi(page: number, pageSize: number, data: IOrderInvoiceQueryRequest): Promise<IOrderInvoiceQueryResponse> {
  return post<IOrderInvoiceQueryResponse>('admin/orderInvoice/search', data, { params: { page, pageSize } })
}

/** [订单发票表(合规必需:用户开票申请与发票状态跟踪)模块] 新增订单发票表(合规必需:用户开票申请与发票状态跟踪)接口 */
export function orderInvoiceStoreApi(data: IOrderInvoiceCreateRequest): Promise<IOrderInvoiceResponse> {
  return post<IOrderInvoiceResponse>('admin/orderInvoice/store', data)
}

/** [订单发票表(合规必需:用户开票申请与发票状态跟踪)模块] 获取订单发票表(合规必需:用户开票申请与发票状态跟踪)详情接口 */
export function orderInvoiceShowApi(id: number): Promise<IOrderInvoiceResponse> {
  return get<IOrderInvoiceResponse>('admin/orderInvoice/show', { params: { id } })
}

/** [订单发票表(合规必需:用户开票申请与发票状态跟踪)模块] 更新订单发票表(合规必需:用户开票申请与发票状态跟踪)接口 */
export function orderInvoiceUpdateApi(id: number, data: IOrderInvoiceUpdateRequest): Promise<IOrderInvoiceResponse> {
  return put<IOrderInvoiceResponse>('admin/orderInvoice/update', data, { params: { id } })
}

/** [订单发票表(合规必需:用户开票申请与发票状态跟踪)模块] 删除订单发票表(合规必需:用户开票申请与发票状态跟踪)接口 */
export function orderInvoiceDestroyApi(data: IOrderInvoiceDestroyRequest): Promise<IOrderInvoiceDestroyResponse> {
  return post<IOrderInvoiceDestroyResponse>('admin/orderInvoice/destroy', data)
}
