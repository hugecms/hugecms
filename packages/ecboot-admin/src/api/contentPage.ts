import { post, get, put } from '@/utils/request'
import type { IContentPageQueryRequest, IContentPageQueryResponse, IContentPageCreateRequest, IContentPageResponse, IContentPageUpdateRequest, IContentPageDestroyRequest, IContentPageDestroyResponse } from '@/types/contentPage'

/** [单页模块] 查询单页列表接口 */
export function contentPageSearchApi(page: number, pageSize: number, data: IContentPageQueryRequest): Promise<IContentPageQueryResponse> {
  return post<IContentPageQueryResponse>('admin/contentPage/search', data, { params: { page, pageSize } })
}

/** [单页模块] 新增单页接口 */
export function contentPageStoreApi(data: IContentPageCreateRequest): Promise<IContentPageResponse> {
  return post<IContentPageResponse>('admin/contentPage/store', data)
}

/** [单页模块] 获取单页详情接口 */
export function contentPageShowApi(id: number): Promise<IContentPageResponse> {
  return get<IContentPageResponse>('admin/contentPage/show', { params: { id } })
}

/** [单页模块] 更新单页接口 */
export function contentPageUpdateApi(id: number, data: IContentPageUpdateRequest): Promise<IContentPageResponse> {
  return put<IContentPageResponse>('admin/contentPage/update', data, { params: { id } })
}

/** [单页模块] 删除单页接口 */
export function contentPageDestroyApi(data: IContentPageDestroyRequest): Promise<IContentPageDestroyResponse> {
  return post<IContentPageDestroyResponse>('admin/contentPage/destroy', data)
}
