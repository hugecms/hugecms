import { post, get, put } from '@/utils/request'
import type { IEmailSubscriptionQueryRequest, IEmailSubscriptionQueryResponse, IEmailSubscriptionCreateRequest, IEmailSubscriptionResponse, IEmailSubscriptionUpdateRequest, IEmailSubscriptionDestroyRequest, IEmailSubscriptionDestroyResponse } from '@/types/emailSubscription'

/** [邮件订阅模块] 查询邮件订阅列表接口 */
export function emailSubscriptionSearchApi(page: number, pageSize: number, data: IEmailSubscriptionQueryRequest): Promise<IEmailSubscriptionQueryResponse> {
  return post<IEmailSubscriptionQueryResponse>('admin/emailSubscription/search', data, { params: { page, pageSize } })
}

/** [邮件订阅模块] 新增邮件订阅接口 */
export function emailSubscriptionStoreApi(data: IEmailSubscriptionCreateRequest): Promise<IEmailSubscriptionResponse> {
  return post<IEmailSubscriptionResponse>('admin/emailSubscription/store', data)
}

/** [邮件订阅模块] 获取邮件订阅详情接口 */
export function emailSubscriptionShowApi(id: number): Promise<IEmailSubscriptionResponse> {
  return get<IEmailSubscriptionResponse>('admin/emailSubscription/show', { params: { id } })
}

/** [邮件订阅模块] 更新邮件订阅接口 */
export function emailSubscriptionUpdateApi(id: number, data: IEmailSubscriptionUpdateRequest): Promise<IEmailSubscriptionResponse> {
  return put<IEmailSubscriptionResponse>('admin/emailSubscription/update', data, { params: { id } })
}

/** [邮件订阅模块] 删除邮件订阅接口 */
export function emailSubscriptionDestroyApi(data: IEmailSubscriptionDestroyRequest): Promise<IEmailSubscriptionDestroyResponse> {
  return post<IEmailSubscriptionDestroyResponse>('admin/emailSubscription/destroy', data)
}
