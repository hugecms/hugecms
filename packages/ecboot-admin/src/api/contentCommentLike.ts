import { post, get, put } from '@/utils/request'
import type { IContentCommentLikeQueryRequest, IContentCommentLikeQueryResponse, IContentCommentLikeCreateRequest, IContentCommentLikeResponse, IContentCommentLikeUpdateRequest, IContentCommentLikeDestroyRequest, IContentCommentLikeDestroyResponse } from '@/types/contentCommentLike'

/** [评论点赞模块] 查询评论点赞列表接口 */
export function contentCommentLikeSearchApi(page: number, pageSize: number, data: IContentCommentLikeQueryRequest): Promise<IContentCommentLikeQueryResponse> {
  return post<IContentCommentLikeQueryResponse>('admin/contentCommentLike/search', data, { params: { page, pageSize } })
}

/** [评论点赞模块] 新增评论点赞接口 */
export function contentCommentLikeStoreApi(data: IContentCommentLikeCreateRequest): Promise<IContentCommentLikeResponse> {
  return post<IContentCommentLikeResponse>('admin/contentCommentLike/store', data)
}

/** [评论点赞模块] 获取评论点赞详情接口 */
export function contentCommentLikeShowApi(id: number): Promise<IContentCommentLikeResponse> {
  return get<IContentCommentLikeResponse>('admin/contentCommentLike/show', { params: { id } })
}

/** [评论点赞模块] 更新评论点赞接口 */
export function contentCommentLikeUpdateApi(id: number, data: IContentCommentLikeUpdateRequest): Promise<IContentCommentLikeResponse> {
  return put<IContentCommentLikeResponse>('admin/contentCommentLike/update', data, { params: { id } })
}

/** [评论点赞模块] 删除评论点赞接口 */
export function contentCommentLikeDestroyApi(data: IContentCommentLikeDestroyRequest): Promise<IContentCommentLikeDestroyResponse> {
  return post<IContentCommentLikeDestroyResponse>('admin/contentCommentLike/destroy', data)
}
