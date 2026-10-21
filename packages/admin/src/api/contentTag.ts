import { post, get, put } from '@/utils/request'
import type { IContentTagQueryRequest, IContentTagQueryResponse, IContentTagCreateRequest, IContentTagResponse, IContentTagUpdateRequest, IContentTagDestroyRequest, IContentTagDestroyResponse } from '@/types/contentTag'

/** [标签模块] 查询标签列表接口 */
export function contentTagSearchApi(page: number, pageSize: number, data: IContentTagQueryRequest): Promise<IContentTagQueryResponse> {
  return post<IContentTagQueryResponse>('admin/contentTag/search', data, { params: { page, pageSize } })
}

/** [标签模块] 新增标签接口 */
export function contentTagStoreApi(data: IContentTagCreateRequest): Promise<IContentTagResponse> {
  return post<IContentTagResponse>('admin/contentTag/store', data)
}

/** [标签模块] 获取标签详情接口 */
export function contentTagShowApi(id: number): Promise<IContentTagResponse> {
  return get<IContentTagResponse>('admin/contentTag/show', { params: { id } })
}

/** [标签模块] 更新标签接口 */
export function contentTagUpdateApi(id: number, data: IContentTagUpdateRequest): Promise<IContentTagResponse> {
  return put<IContentTagResponse>('admin/contentTag/update', data, { params: { id } })
}

/** [标签模块] 删除标签接口 */
export function contentTagDestroyApi(data: IContentTagDestroyRequest): Promise<IContentTagDestroyResponse> {
  return post<IContentTagDestroyResponse>('admin/contentTag/destroy', data)
}
