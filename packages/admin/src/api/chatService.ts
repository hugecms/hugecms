import { post, get, put } from '@/utils/request'
import type { IChatServiceQueryRequest, IChatServiceQueryResponse, IChatServiceCreateRequest, IChatServiceResponse, IChatServiceUpdateRequest, IChatServiceDestroyRequest, IChatServiceDestroyResponse } from '@/types/chatService'

/** [客服工作状态模块] 查询客服工作状态列表接口 */
export function chatServiceSearchApi(page: number, pageSize: number, data: IChatServiceQueryRequest): Promise<IChatServiceQueryResponse> {
  return post<IChatServiceQueryResponse>('admin/chatService/search', data, { params: { page, pageSize } })
}

/** [客服工作状态模块] 新增客服工作状态接口 */
export function chatServiceStoreApi(data: IChatServiceCreateRequest): Promise<IChatServiceResponse> {
  return post<IChatServiceResponse>('admin/chatService/store', data)
}

/** [客服工作状态模块] 获取客服工作状态详情接口 */
export function chatServiceShowApi(id: number): Promise<IChatServiceResponse> {
  return get<IChatServiceResponse>('admin/chatService/show', { params: { id } })
}

/** [客服工作状态模块] 更新客服工作状态接口 */
export function chatServiceUpdateApi(id: number, data: IChatServiceUpdateRequest): Promise<IChatServiceResponse> {
  return put<IChatServiceResponse>('admin/chatService/update', data, { params: { id } })
}

/** [客服工作状态模块] 删除客服工作状态接口 */
export function chatServiceDestroyApi(data: IChatServiceDestroyRequest): Promise<IChatServiceDestroyResponse> {
  return post<IChatServiceDestroyResponse>('admin/chatService/destroy', data)
}
