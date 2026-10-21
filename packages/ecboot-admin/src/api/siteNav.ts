import { post, get, put } from '@/utils/request'
import type { ISiteNavQueryRequest, ISiteNavQueryResponse, ISiteNavCreateRequest, ISiteNavResponse, ISiteNavUpdateRequest, ISiteNavDestroyRequest, ISiteNavDestroyResponse } from '@/types/siteNav'

/** [导航模块] 查询导航列表接口 */
export function siteNavSearchApi(page: number, pageSize: number, data: ISiteNavQueryRequest): Promise<ISiteNavQueryResponse> {
  return post<ISiteNavQueryResponse>('admin/siteNav/search', data, { params: { page, pageSize } })
}

/** [导航模块] 新增导航接口 */
export function siteNavStoreApi(data: ISiteNavCreateRequest): Promise<ISiteNavResponse> {
  return post<ISiteNavResponse>('admin/siteNav/store', data)
}

/** [导航模块] 获取导航详情接口 */
export function siteNavShowApi(id: number): Promise<ISiteNavResponse> {
  return get<ISiteNavResponse>('admin/siteNav/show', { params: { id } })
}

/** [导航模块] 更新导航接口 */
export function siteNavUpdateApi(id: number, data: ISiteNavUpdateRequest): Promise<ISiteNavResponse> {
  return put<ISiteNavResponse>('admin/siteNav/update', data, { params: { id } })
}

/** [导航模块] 删除导航接口 */
export function siteNavDestroyApi(data: ISiteNavDestroyRequest): Promise<ISiteNavDestroyResponse> {
  return post<ISiteNavDestroyResponse>('admin/siteNav/destroy', data)
}
