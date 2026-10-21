import { post, get, put } from '@/utils/request'
import type { IProductTypeQueryRequest, IProductTypeQueryResponse, IProductTypeCreateRequest, IProductTypeResponse, IProductTypeUpdateRequest, IProductTypeDestroyRequest, IProductTypeDestroyResponse } from '@/types/productType'

/** [商品类型模块] 查询商品类型列表接口 */
export function productTypeSearchApi(page: number, pageSize: number, data: IProductTypeQueryRequest): Promise<IProductTypeQueryResponse> {
  return post<IProductTypeQueryResponse>('admin/productType/search', data, { params: { page, pageSize } })
}

/** [商品类型模块] 新增商品类型接口 */
export function productTypeStoreApi(data: IProductTypeCreateRequest): Promise<IProductTypeResponse> {
  return post<IProductTypeResponse>('admin/productType/store', data)
}

/** [商品类型模块] 获取商品类型详情接口 */
export function productTypeShowApi(id: number): Promise<IProductTypeResponse> {
  return get<IProductTypeResponse>('admin/productType/show', { params: { id } })
}

/** [商品类型模块] 更新商品类型接口 */
export function productTypeUpdateApi(id: number, data: IProductTypeUpdateRequest): Promise<IProductTypeResponse> {
  return put<IProductTypeResponse>('admin/productType/update', data, { params: { id } })
}

/** [商品类型模块] 删除商品类型接口 */
export function productTypeDestroyApi(data: IProductTypeDestroyRequest): Promise<IProductTypeDestroyResponse> {
  return post<IProductTypeDestroyResponse>('admin/productType/destroy', data)
}
