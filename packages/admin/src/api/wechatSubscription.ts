import { post, get, put } from '@/utils/request'
import type { IWechatSubscriptionQueryRequest, IWechatSubscriptionQueryResponse, IWechatSubscriptionCreateRequest, IWechatSubscriptionResponse, IWechatSubscriptionUpdateRequest, IWechatSubscriptionDestroyRequest, IWechatSubscriptionDestroyResponse } from '@/types/wechatSubscription'

/** [用户订阅授权模块] 查询用户订阅授权列表接口 */
export function wechatSubscriptionSearchApi(page: number, pageSize: number, data: IWechatSubscriptionQueryRequest): Promise<IWechatSubscriptionQueryResponse> {
  return post<IWechatSubscriptionQueryResponse>('admin/wechatSubscription/search', data, { params: { page, pageSize } })
}

/** [用户订阅授权模块] 新增用户订阅授权接口 */
export function wechatSubscriptionStoreApi(data: IWechatSubscriptionCreateRequest): Promise<IWechatSubscriptionResponse> {
  return post<IWechatSubscriptionResponse>('admin/wechatSubscription/store', data)
}

/** [用户订阅授权模块] 获取用户订阅授权详情接口 */
export function wechatSubscriptionShowApi(id: number): Promise<IWechatSubscriptionResponse> {
  return get<IWechatSubscriptionResponse>('admin/wechatSubscription/show', { params: { id } })
}

/** [用户订阅授权模块] 更新用户订阅授权接口 */
export function wechatSubscriptionUpdateApi(id: number, data: IWechatSubscriptionUpdateRequest): Promise<IWechatSubscriptionResponse> {
  return put<IWechatSubscriptionResponse>('admin/wechatSubscription/update', data, { params: { id } })
}

/** [用户订阅授权模块] 删除用户订阅授权接口 */
export function wechatSubscriptionDestroyApi(data: IWechatSubscriptionDestroyRequest): Promise<IWechatSubscriptionDestroyResponse> {
  return post<IWechatSubscriptionDestroyResponse>('admin/wechatSubscription/destroy', data)
}
