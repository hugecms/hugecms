import { post, get, put } from '@/utils/request'
import type { INotificationQueryRequest, INotificationQueryResponse, INotificationCreateRequest, INotificationResponse, INotificationUpdateRequest, INotificationDestroyRequest, INotificationDestroyResponse } from '@/types/notification'

/** [消息通知模块] 查询消息通知列表接口 */
export function notificationSearchApi(page: number, pageSize: number, data: INotificationQueryRequest): Promise<INotificationQueryResponse> {
  return post<INotificationQueryResponse>('admin/notification/search', data, { params: { page, pageSize } })
}

/** [消息通知模块] 新增消息通知接口 */
export function notificationStoreApi(data: INotificationCreateRequest): Promise<INotificationResponse> {
  return post<INotificationResponse>('admin/notification/store', data)
}

/** [消息通知模块] 获取消息通知详情接口 */
export function notificationShowApi(id: number): Promise<INotificationResponse> {
  return get<INotificationResponse>('admin/notification/show', { params: { id } })
}

/** [消息通知模块] 更新消息通知接口 */
export function notificationUpdateApi(id: number, data: INotificationUpdateRequest): Promise<INotificationResponse> {
  return put<INotificationResponse>('admin/notification/update', data, { params: { id } })
}

/** [消息通知模块] 删除消息通知接口 */
export function notificationDestroyApi(data: INotificationDestroyRequest): Promise<INotificationDestroyResponse> {
  return post<INotificationDestroyResponse>('admin/notification/destroy', data)
}
