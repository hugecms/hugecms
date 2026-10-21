import { post, get, put } from '@/utils/request'
import type { IContentArticleQueryRequest, IContentArticleQueryResponse, IContentArticleCreateRequest, IContentArticleResponse, IContentArticleUpdateRequest, IContentArticleDestroyRequest, IContentArticleDestroyResponse } from '@/types/contentArticle'

/** [文章模块] 查询文章列表接口 */
export function contentArticleSearchApi(page: number, pageSize: number, data: IContentArticleQueryRequest): Promise<IContentArticleQueryResponse> {
  return post<IContentArticleQueryResponse>('admin/contentArticle/search', data, { params: { page, pageSize } })
}

/** [文章模块] 新增文章接口 */
export function contentArticleStoreApi(data: IContentArticleCreateRequest): Promise<IContentArticleResponse> {
  return post<IContentArticleResponse>('admin/contentArticle/store', data)
}

/** [文章模块] 获取文章详情接口 */
export function contentArticleShowApi(id: number): Promise<IContentArticleResponse> {
  return get<IContentArticleResponse>('admin/contentArticle/show', { params: { id } })
}

/** [文章模块] 更新文章接口 */
export function contentArticleUpdateApi(id: number, data: IContentArticleUpdateRequest): Promise<IContentArticleResponse> {
  return put<IContentArticleResponse>('admin/contentArticle/update', data, { params: { id } })
}

/** [文章模块] 删除文章接口 */
export function contentArticleDestroyApi(data: IContentArticleDestroyRequest): Promise<IContentArticleDestroyResponse> {
  return post<IContentArticleDestroyResponse>('admin/contentArticle/destroy', data)
}
