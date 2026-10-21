import { post, get, put } from '@/utils/request'
import type { IContentArticleContentQueryRequest, IContentArticleContentQueryResponse, IContentArticleContentCreateRequest, IContentArticleContentResponse, IContentArticleContentUpdateRequest, IContentArticleContentDestroyRequest, IContentArticleContentDestroyResponse } from '@/types/contentArticleContent'

/** [文章正文模块] 查询文章正文列表接口 */
export function contentArticleContentSearchApi(page: number, pageSize: number, data: IContentArticleContentQueryRequest): Promise<IContentArticleContentQueryResponse> {
  return post<IContentArticleContentQueryResponse>('admin/contentArticleContent/search', data, { params: { page, pageSize } })
}

/** [文章正文模块] 新增文章正文接口 */
export function contentArticleContentStoreApi(data: IContentArticleContentCreateRequest): Promise<IContentArticleContentResponse> {
  return post<IContentArticleContentResponse>('admin/contentArticleContent/store', data)
}

/** [文章正文模块] 获取文章正文详情接口 */
export function contentArticleContentShowApi(id: number): Promise<IContentArticleContentResponse> {
  return get<IContentArticleContentResponse>('admin/contentArticleContent/show', { params: { id } })
}

/** [文章正文模块] 更新文章正文接口 */
export function contentArticleContentUpdateApi(id: number, data: IContentArticleContentUpdateRequest): Promise<IContentArticleContentResponse> {
  return put<IContentArticleContentResponse>('admin/contentArticleContent/update', data, { params: { id } })
}

/** [文章正文模块] 删除文章正文接口 */
export function contentArticleContentDestroyApi(data: IContentArticleContentDestroyRequest): Promise<IContentArticleContentDestroyResponse> {
  return post<IContentArticleContentDestroyResponse>('admin/contentArticleContent/destroy', data)
}
