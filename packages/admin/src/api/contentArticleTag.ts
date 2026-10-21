import { post, get, put } from '@/utils/request'
import type { IContentArticleTagQueryRequest, IContentArticleTagQueryResponse, IContentArticleTagCreateRequest, IContentArticleTagResponse, IContentArticleTagUpdateRequest, IContentArticleTagDestroyRequest, IContentArticleTagDestroyResponse } from '@/types/contentArticleTag'

/** [文章标签关联模块] 查询文章标签关联列表接口 */
export function contentArticleTagSearchApi(page: number, pageSize: number, data: IContentArticleTagQueryRequest): Promise<IContentArticleTagQueryResponse> {
  return post<IContentArticleTagQueryResponse>('admin/contentArticleTag/search', data, { params: { page, pageSize } })
}

/** [文章标签关联模块] 新增文章标签关联接口 */
export function contentArticleTagStoreApi(data: IContentArticleTagCreateRequest): Promise<IContentArticleTagResponse> {
  return post<IContentArticleTagResponse>('admin/contentArticleTag/store', data)
}

/** [文章标签关联模块] 获取文章标签关联详情接口 */
export function contentArticleTagShowApi(id: number): Promise<IContentArticleTagResponse> {
  return get<IContentArticleTagResponse>('admin/contentArticleTag/show', { params: { id } })
}

/** [文章标签关联模块] 更新文章标签关联接口 */
export function contentArticleTagUpdateApi(id: number, data: IContentArticleTagUpdateRequest): Promise<IContentArticleTagResponse> {
  return put<IContentArticleTagResponse>('admin/contentArticleTag/update', data, { params: { id } })
}

/** [文章标签关联模块] 删除文章标签关联接口 */
export function contentArticleTagDestroyApi(data: IContentArticleTagDestroyRequest): Promise<IContentArticleTagDestroyResponse> {
  return post<IContentArticleTagDestroyResponse>('admin/contentArticleTag/destroy', data)
}
