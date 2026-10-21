import { post, get, put } from '@/utils/request'
import type { IChatMessageQueryRequest, IChatMessageQueryResponse, IChatMessageCreateRequest, IChatMessageResponse, IChatMessageUpdateRequest, IChatMessageDestroyRequest, IChatMessageDestroyResponse } from '@/types/chatMessage'

/** [客服消息模块] 查询客服消息列表接口 */
export function chatMessageSearchApi(page: number, pageSize: number, data: IChatMessageQueryRequest): Promise<IChatMessageQueryResponse> {
  return post<IChatMessageQueryResponse>('admin/chatMessage/search', data, { params: { page, pageSize } })
}

/** [客服消息模块] 新增客服消息接口 */
export function chatMessageStoreApi(data: IChatMessageCreateRequest): Promise<IChatMessageResponse> {
  return post<IChatMessageResponse>('admin/chatMessage/store', data)
}

/** [客服消息模块] 获取客服消息详情接口 */
export function chatMessageShowApi(id: number): Promise<IChatMessageResponse> {
  return get<IChatMessageResponse>('admin/chatMessage/show', { params: { id } })
}

/** [客服消息模块] 更新客服消息接口 */
export function chatMessageUpdateApi(id: number, data: IChatMessageUpdateRequest): Promise<IChatMessageResponse> {
  return put<IChatMessageResponse>('admin/chatMessage/update', data, { params: { id } })
}

/** [客服消息模块] 删除客服消息接口 */
export function chatMessageDestroyApi(data: IChatMessageDestroyRequest): Promise<IChatMessageDestroyResponse> {
  return post<IChatMessageDestroyResponse>('admin/chatMessage/destroy', data)
}
