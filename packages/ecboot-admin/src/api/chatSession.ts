import { post, get, put } from '@/utils/request'
import type { IChatSessionQueryRequest, IChatSessionQueryResponse, IChatSessionCreateRequest, IChatSessionResponse, IChatSessionUpdateRequest, IChatSessionDestroyRequest, IChatSessionDestroyResponse } from '@/types/chatSession'

/** [客服会话模块] 查询客服会话列表接口 */
export function chatSessionSearchApi(page: number, pageSize: number, data: IChatSessionQueryRequest): Promise<IChatSessionQueryResponse> {
  return post<IChatSessionQueryResponse>('admin/chatSession/search', data, { params: { page, pageSize } })
}

/** [客服会话模块] 新增客服会话接口 */
export function chatSessionStoreApi(data: IChatSessionCreateRequest): Promise<IChatSessionResponse> {
  return post<IChatSessionResponse>('admin/chatSession/store', data)
}

/** [客服会话模块] 获取客服会话详情接口 */
export function chatSessionShowApi(id: number): Promise<IChatSessionResponse> {
  return get<IChatSessionResponse>('admin/chatSession/show', { params: { id } })
}

/** [客服会话模块] 更新客服会话接口 */
export function chatSessionUpdateApi(id: number, data: IChatSessionUpdateRequest): Promise<IChatSessionResponse> {
  return put<IChatSessionResponse>('admin/chatSession/update', data, { params: { id } })
}

/** [客服会话模块] 删除客服会话接口 */
export function chatSessionDestroyApi(data: IChatSessionDestroyRequest): Promise<IChatSessionDestroyResponse> {
  return post<IChatSessionDestroyResponse>('admin/chatSession/destroy', data)
}
