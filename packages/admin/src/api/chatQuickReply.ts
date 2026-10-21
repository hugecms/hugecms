import { post, get, put } from '@/utils/request'
import type { IChatQuickReplyQueryRequest, IChatQuickReplyQueryResponse, IChatQuickReplyCreateRequest, IChatQuickReplyResponse, IChatQuickReplyUpdateRequest, IChatQuickReplyDestroyRequest, IChatQuickReplyDestroyResponse } from '@/types/chatQuickReply'

/** [客服话术库模块] 查询客服话术库列表接口 */
export function chatQuickReplySearchApi(page: number, pageSize: number, data: IChatQuickReplyQueryRequest): Promise<IChatQuickReplyQueryResponse> {
  return post<IChatQuickReplyQueryResponse>('admin/chatQuickReply/search', data, { params: { page, pageSize } })
}

/** [客服话术库模块] 新增客服话术库接口 */
export function chatQuickReplyStoreApi(data: IChatQuickReplyCreateRequest): Promise<IChatQuickReplyResponse> {
  return post<IChatQuickReplyResponse>('admin/chatQuickReply/store', data)
}

/** [客服话术库模块] 获取客服话术库详情接口 */
export function chatQuickReplyShowApi(id: number): Promise<IChatQuickReplyResponse> {
  return get<IChatQuickReplyResponse>('admin/chatQuickReply/show', { params: { id } })
}

/** [客服话术库模块] 更新客服话术库接口 */
export function chatQuickReplyUpdateApi(id: number, data: IChatQuickReplyUpdateRequest): Promise<IChatQuickReplyResponse> {
  return put<IChatQuickReplyResponse>('admin/chatQuickReply/update', data, { params: { id } })
}

/** [客服话术库模块] 删除客服话术库接口 */
export function chatQuickReplyDestroyApi(data: IChatQuickReplyDestroyRequest): Promise<IChatQuickReplyDestroyResponse> {
  return post<IChatQuickReplyDestroyResponse>('admin/chatQuickReply/destroy', data)
}
