import { post, get, put } from '@/utils/request'
import type { ISiteSearchKeywordQueryRequest, ISiteSearchKeywordQueryResponse, ISiteSearchKeywordCreateRequest, ISiteSearchKeywordResponse, ISiteSearchKeywordUpdateRequest, ISiteSearchKeywordDestroyRequest, ISiteSearchKeywordDestroyResponse } from '@/types/siteSearchKeyword'

/** [搜索热词模块] 查询搜索热词列表接口 */
export function siteSearchKeywordSearchApi(page: number, pageSize: number, data: ISiteSearchKeywordQueryRequest): Promise<ISiteSearchKeywordQueryResponse> {
  return post<ISiteSearchKeywordQueryResponse>('admin/siteSearchKeyword/search', data, { params: { page, pageSize } })
}

/** [搜索热词模块] 新增搜索热词接口 */
export function siteSearchKeywordStoreApi(data: ISiteSearchKeywordCreateRequest): Promise<ISiteSearchKeywordResponse> {
  return post<ISiteSearchKeywordResponse>('admin/siteSearchKeyword/store', data)
}

/** [搜索热词模块] 获取搜索热词详情接口 */
export function siteSearchKeywordShowApi(id: number): Promise<ISiteSearchKeywordResponse> {
  return get<ISiteSearchKeywordResponse>('admin/siteSearchKeyword/show', { params: { id } })
}

/** [搜索热词模块] 更新搜索热词接口 */
export function siteSearchKeywordUpdateApi(id: number, data: ISiteSearchKeywordUpdateRequest): Promise<ISiteSearchKeywordResponse> {
  return put<ISiteSearchKeywordResponse>('admin/siteSearchKeyword/update', data, { params: { id } })
}

/** [搜索热词模块] 删除搜索热词接口 */
export function siteSearchKeywordDestroyApi(data: ISiteSearchKeywordDestroyRequest): Promise<ISiteSearchKeywordDestroyResponse> {
  return post<ISiteSearchKeywordDestroyResponse>('admin/siteSearchKeyword/destroy', data)
}
