import { post, get, put } from '@/utils/request'
import type { IProductStockReminderQueryRequest, IProductStockReminderQueryResponse, IProductStockReminderCreateRequest, IProductStockReminderResponse, IProductStockReminderUpdateRequest, IProductStockReminderDestroyRequest, IProductStockReminderDestroyResponse } from '@/types/productStockReminder'

/** [到货提醒模块] 查询到货提醒列表接口 */
export function productStockReminderSearchApi(page: number, pageSize: number, data: IProductStockReminderQueryRequest): Promise<IProductStockReminderQueryResponse> {
  return post<IProductStockReminderQueryResponse>('admin/productStockReminder/search', data, { params: { page, pageSize } })
}

/** [到货提醒模块] 新增到货提醒接口 */
export function productStockReminderStoreApi(data: IProductStockReminderCreateRequest): Promise<IProductStockReminderResponse> {
  return post<IProductStockReminderResponse>('admin/productStockReminder/store', data)
}

/** [到货提醒模块] 获取到货提醒详情接口 */
export function productStockReminderShowApi(id: number): Promise<IProductStockReminderResponse> {
  return get<IProductStockReminderResponse>('admin/productStockReminder/show', { params: { id } })
}

/** [到货提醒模块] 更新到货提醒接口 */
export function productStockReminderUpdateApi(id: number, data: IProductStockReminderUpdateRequest): Promise<IProductStockReminderResponse> {
  return put<IProductStockReminderResponse>('admin/productStockReminder/update', data, { params: { id } })
}

/** [到货提醒模块] 删除到货提醒接口 */
export function productStockReminderDestroyApi(data: IProductStockReminderDestroyRequest): Promise<IProductStockReminderDestroyResponse> {
  return post<IProductStockReminderDestroyResponse>('admin/productStockReminder/destroy', data)
}
