import { post, get, put } from '@/utils/request'
import type { IContentCommentQueryRequest, IContentCommentQueryResponse, IContentCommentCreateRequest, IContentCommentResponse, IContentCommentUpdateRequest, IContentCommentDestroyRequest, IContentCommentDestroyResponse } from '@/types/contentComment'

/** [评论模块] 查询评论列表接口 */
export function contentCommentSearchApi(page: number, pageSize: number, data: IContentCommentQueryRequest): Promise<IContentCommentQueryResponse> {
  return post<IContentCommentQueryResponse>('admin/contentComment/search', data, { params: { page, pageSize } })
}

/** [评论模块] 新增评论接口 */
export function contentCommentStoreApi(data: IContentCommentCreateRequest): Promise<IContentCommentResponse> {
  return post<IContentCommentResponse>('admin/contentComment/store', data)
}

/** [评论模块] 获取评论详情接口 */
export function contentCommentShowApi(id: number): Promise<IContentCommentResponse> {
  return get<IContentCommentResponse>('admin/contentComment/show', { params: { id } })
}

/** [评论模块] 更新评论接口 */
export function contentCommentUpdateApi(id: number, data: IContentCommentUpdateRequest): Promise<IContentCommentResponse> {
  return put<IContentCommentResponse>('admin/contentComment/update', data, { params: { id } })
}

/** [评论模块] 删除评论接口 */
export function contentCommentDestroyApi(data: IContentCommentDestroyRequest): Promise<IContentCommentDestroyResponse> {
  return post<IContentCommentDestroyResponse>('admin/contentComment/destroy', data)
}
