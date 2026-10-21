import { post, get, put } from '@/utils/request'
import type { IProductAttributeQueryRequest, IProductAttributeQueryResponse, IProductAttributeCreateRequest, IProductAttributeResponse, IProductAttributeUpdateRequest, IProductAttributeDestroyRequest, IProductAttributeDestroyResponse } from '@/types/productAttribute'

/** [商品属性定义模块] 查询商品属性定义列表接口 */
export function productAttributeSearchApi(page: number, pageSize: number, data: IProductAttributeQueryRequest): Promise<IProductAttributeQueryResponse> {
  return post<IProductAttributeQueryResponse>('admin/productAttribute/search', data, { params: { page, pageSize } })
}

/** [商品属性定义模块] 新增商品属性定义接口 */
export function productAttributeStoreApi(data: IProductAttributeCreateRequest): Promise<IProductAttributeResponse> {
  return post<IProductAttributeResponse>('admin/productAttribute/store', data)
}

/** [商品属性定义模块] 获取商品属性定义详情接口 */
export function productAttributeShowApi(id: number): Promise<IProductAttributeResponse> {
  return get<IProductAttributeResponse>('admin/productAttribute/show', { params: { id } })
}

/** [商品属性定义模块] 更新商品属性定义接口 */
export function productAttributeUpdateApi(id: number, data: IProductAttributeUpdateRequest): Promise<IProductAttributeResponse> {
  return put<IProductAttributeResponse>('admin/productAttribute/update', data, { params: { id } })
}

/** [商品属性定义模块] 删除商品属性定义接口 */
export function productAttributeDestroyApi(data: IProductAttributeDestroyRequest): Promise<IProductAttributeDestroyResponse> {
  return post<IProductAttributeDestroyResponse>('admin/productAttribute/destroy', data)
}
