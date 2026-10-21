import { post, get, put } from '@/utils/request'
import type { IContentTopicItemQueryRequest, IContentTopicItemQueryResponse, IContentTopicItemCreateRequest, IContentTopicItemResponse, IContentTopicItemUpdateRequest, IContentTopicItemDestroyRequest, IContentTopicItemDestroyResponse } from '@/types/contentTopicItem'

/** [专题内容关联模块] 查询专题内容关联列表接口 */
export function contentTopicItemSearchApi(page: number, pageSize: number, data: IContentTopicItemQueryRequest): Promise<IContentTopicItemQueryResponse> {
  return post<IContentTopicItemQueryResponse>('admin/contentTopicItem/search', data, { params: { page, pageSize } })
}

/** [专题内容关联模块] 新增专题内容关联接口 */
export function contentTopicItemStoreApi(data: IContentTopicItemCreateRequest): Promise<IContentTopicItemResponse> {
  return post<IContentTopicItemResponse>('admin/contentTopicItem/store', data)
}

/** [专题内容关联模块] 获取专题内容关联详情接口 */
export function contentTopicItemShowApi(id: number): Promise<IContentTopicItemResponse> {
  return get<IContentTopicItemResponse>('admin/contentTopicItem/show', { params: { id } })
}

/** [专题内容关联模块] 更新专题内容关联接口 */
export function contentTopicItemUpdateApi(id: number, data: IContentTopicItemUpdateRequest): Promise<IContentTopicItemResponse> {
  return put<IContentTopicItemResponse>('admin/contentTopicItem/update', data, { params: { id } })
}

/** [专题内容关联模块] 删除专题内容关联接口 */
export function contentTopicItemDestroyApi(data: IContentTopicItemDestroyRequest): Promise<IContentTopicItemDestroyResponse> {
  return post<IContentTopicItemDestroyResponse>('admin/contentTopicItem/destroy', data)
}
