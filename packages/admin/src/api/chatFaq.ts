import { post, get, put } from '@/utils/request'
import type { IChatFaqQueryRequest, IChatFaqQueryResponse, IChatFaqCreateRequest, IChatFaqResponse, IChatFaqUpdateRequest, IChatFaqDestroyRequest, IChatFaqDestroyResponse } from '@/types/chatFaq'

/** [常见问题模块] 查询常见问题列表接口 */
export function chatFaqSearchApi(page: number, pageSize: number, data: IChatFaqQueryRequest): Promise<IChatFaqQueryResponse> {
  return post<IChatFaqQueryResponse>('admin/chatFaq/search', data, { params: { page, pageSize } })
}

/** [常见问题模块] 新增常见问题接口 */
export function chatFaqStoreApi(data: IChatFaqCreateRequest): Promise<IChatFaqResponse> {
  return post<IChatFaqResponse>('admin/chatFaq/store', data)
}

/** [常见问题模块] 获取常见问题详情接口 */
export function chatFaqShowApi(id: number): Promise<IChatFaqResponse> {
  return get<IChatFaqResponse>('admin/chatFaq/show', { params: { id } })
}

/** [常见问题模块] 更新常见问题接口 */
export function chatFaqUpdateApi(id: number, data: IChatFaqUpdateRequest): Promise<IChatFaqResponse> {
  return put<IChatFaqResponse>('admin/chatFaq/update', data, { params: { id } })
}

/** [常见问题模块] 删除常见问题接口 */
export function chatFaqDestroyApi(data: IChatFaqDestroyRequest): Promise<IChatFaqDestroyResponse> {
  return post<IChatFaqDestroyResponse>('admin/chatFaq/destroy', data)
}
