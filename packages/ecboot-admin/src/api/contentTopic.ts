import { post, get, put } from '@/utils/request'
import type { IContentTopicQueryRequest, IContentTopicQueryResponse, IContentTopicCreateRequest, IContentTopicResponse, IContentTopicUpdateRequest, IContentTopicDestroyRequest, IContentTopicDestroyResponse } from '@/types/contentTopic'

/** [专题模块] 查询专题列表接口 */
export function contentTopicSearchApi(page: number, pageSize: number, data: IContentTopicQueryRequest): Promise<IContentTopicQueryResponse> {
  return post<IContentTopicQueryResponse>('admin/contentTopic/search', data, { params: { page, pageSize } })
}

/** [专题模块] 新增专题接口 */
export function contentTopicStoreApi(data: IContentTopicCreateRequest): Promise<IContentTopicResponse> {
  return post<IContentTopicResponse>('admin/contentTopic/store', data)
}

/** [专题模块] 获取专题详情接口 */
export function contentTopicShowApi(id: number): Promise<IContentTopicResponse> {
  return get<IContentTopicResponse>('admin/contentTopic/show', { params: { id } })
}

/** [专题模块] 更新专题接口 */
export function contentTopicUpdateApi(id: number, data: IContentTopicUpdateRequest): Promise<IContentTopicResponse> {
  return put<IContentTopicResponse>('admin/contentTopic/update', data, { params: { id } })
}

/** [专题模块] 删除专题接口 */
export function contentTopicDestroyApi(data: IContentTopicDestroyRequest): Promise<IContentTopicDestroyResponse> {
  return post<IContentTopicDestroyResponse>('admin/contentTopic/destroy', data)
}
