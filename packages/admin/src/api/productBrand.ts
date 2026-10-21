import { post, get, put } from '@/utils/request'
import type { IProductBrandQueryRequest, IProductBrandQueryResponse, IProductBrandCreateRequest, IProductBrandResponse, IProductBrandUpdateRequest, IProductBrandDestroyRequest, IProductBrandDestroyResponse } from '@/types/productBrand'

/** [品牌模块] 查询品牌列表接口 */
export function productBrandSearchApi(page: number, pageSize: number, data: IProductBrandQueryRequest): Promise<IProductBrandQueryResponse> {
  return post<IProductBrandQueryResponse>('admin/productBrand/search', data, { params: { page, pageSize } })
}

/** [品牌模块] 新增品牌接口 */
export function productBrandStoreApi(data: IProductBrandCreateRequest): Promise<IProductBrandResponse> {
  return post<IProductBrandResponse>('admin/productBrand/store', data)
}

/** [品牌模块] 获取品牌详情接口 */
export function productBrandShowApi(id: number): Promise<IProductBrandResponse> {
  return get<IProductBrandResponse>('admin/productBrand/show', { params: { id } })
}

/** [品牌模块] 更新品牌接口 */
export function productBrandUpdateApi(id: number, data: IProductBrandUpdateRequest): Promise<IProductBrandResponse> {
  return put<IProductBrandResponse>('admin/productBrand/update', data, { params: { id } })
}

/** [品牌模块] 删除品牌接口 */
export function productBrandDestroyApi(data: IProductBrandDestroyRequest): Promise<IProductBrandDestroyResponse> {
  return post<IProductBrandDestroyResponse>('admin/productBrand/destroy', data)
}
