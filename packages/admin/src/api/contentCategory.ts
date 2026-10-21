import { post, get, put } from '@/utils/request'
import type { IContentCategoryQueryRequest, IContentCategoryQueryResponse, IContentCategoryCreateRequest, IContentCategoryResponse, IContentCategoryUpdateRequest, IContentCategoryDestroyRequest, IContentCategoryDestroyResponse } from '@/types/contentCategory'

/** [栏目模块] 查询栏目列表接口 */
export function contentCategorySearchApi(page: number, pageSize: number, data: IContentCategoryQueryRequest): Promise<IContentCategoryQueryResponse> {
  return post<IContentCategoryQueryResponse>('admin/contentCategory/search', data, { params: { page, pageSize } })
}

/** [栏目模块] 新增栏目接口 */
export function contentCategoryStoreApi(data: IContentCategoryCreateRequest): Promise<IContentCategoryResponse> {
  return post<IContentCategoryResponse>('admin/contentCategory/store', data)
}

/** [栏目模块] 获取栏目详情接口 */
export function contentCategoryShowApi(id: number): Promise<IContentCategoryResponse> {
  return get<IContentCategoryResponse>('admin/contentCategory/show', { params: { id } })
}

/** [栏目模块] 更新栏目接口 */
export function contentCategoryUpdateApi(id: number, data: IContentCategoryUpdateRequest): Promise<IContentCategoryResponse> {
  return put<IContentCategoryResponse>('admin/contentCategory/update', data, { params: { id } })
}

/** [栏目模块] 删除栏目接口 */
export function contentCategoryDestroyApi(data: IContentCategoryDestroyRequest): Promise<IContentCategoryDestroyResponse> {
  return post<IContentCategoryDestroyResponse>('admin/contentCategory/destroy', data)
}
