import { post, get, put } from '@/utils/request'
import type { ISiteBannerQueryRequest, ISiteBannerQueryResponse, ISiteBannerCreateRequest, ISiteBannerResponse, ISiteBannerUpdateRequest, ISiteBannerDestroyRequest, ISiteBannerDestroyResponse } from '@/types/siteBanner'

/** [轮播图/广告模块] 查询轮播图/广告列表接口 */
export function siteBannerSearchApi(page: number, pageSize: number, data: ISiteBannerQueryRequest): Promise<ISiteBannerQueryResponse> {
  return post<ISiteBannerQueryResponse>('admin/siteBanner/search', data, { params: { page, pageSize } })
}

/** [轮播图/广告模块] 新增轮播图/广告接口 */
export function siteBannerStoreApi(data: ISiteBannerCreateRequest): Promise<ISiteBannerResponse> {
  return post<ISiteBannerResponse>('admin/siteBanner/store', data)
}

/** [轮播图/广告模块] 获取轮播图/广告详情接口 */
export function siteBannerShowApi(id: number): Promise<ISiteBannerResponse> {
  return get<ISiteBannerResponse>('admin/siteBanner/show', { params: { id } })
}

/** [轮播图/广告模块] 更新轮播图/广告接口 */
export function siteBannerUpdateApi(id: number, data: ISiteBannerUpdateRequest): Promise<ISiteBannerResponse> {
  return put<ISiteBannerResponse>('admin/siteBanner/update', data, { params: { id } })
}

/** [轮播图/广告模块] 删除轮播图/广告接口 */
export function siteBannerDestroyApi(data: ISiteBannerDestroyRequest): Promise<ISiteBannerDestroyResponse> {
  return post<ISiteBannerDestroyResponse>('admin/siteBanner/destroy', data)
}
