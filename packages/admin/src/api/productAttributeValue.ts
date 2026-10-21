import { post, get, put } from '@/utils/request'
import type { IProductAttributeValueQueryRequest, IProductAttributeValueQueryResponse, IProductAttributeValueCreateRequest, IProductAttributeValueResponse, IProductAttributeValueUpdateRequest, IProductAttributeValueDestroyRequest, IProductAttributeValueDestroyResponse } from '@/types/productAttributeValue'

/** [商品属性值模块] 查询商品属性值列表接口 */
export function productAttributeValueSearchApi(page: number, pageSize: number, data: IProductAttributeValueQueryRequest): Promise<IProductAttributeValueQueryResponse> {
  return post<IProductAttributeValueQueryResponse>('admin/productAttributeValue/search', data, { params: { page, pageSize } })
}

/** [商品属性值模块] 新增商品属性值接口 */
export function productAttributeValueStoreApi(data: IProductAttributeValueCreateRequest): Promise<IProductAttributeValueResponse> {
  return post<IProductAttributeValueResponse>('admin/productAttributeValue/store', data)
}

/** [商品属性值模块] 获取商品属性值详情接口 */
export function productAttributeValueShowApi(id: number): Promise<IProductAttributeValueResponse> {
  return get<IProductAttributeValueResponse>('admin/productAttributeValue/show', { params: { id } })
}

/** [商品属性值模块] 更新商品属性值接口 */
export function productAttributeValueUpdateApi(id: number, data: IProductAttributeValueUpdateRequest): Promise<IProductAttributeValueResponse> {
  return put<IProductAttributeValueResponse>('admin/productAttributeValue/update', data, { params: { id } })
}

/** [商品属性值模块] 删除商品属性值接口 */
export function productAttributeValueDestroyApi(data: IProductAttributeValueDestroyRequest): Promise<IProductAttributeValueDestroyResponse> {
  return post<IProductAttributeValueDestroyResponse>('admin/productAttributeValue/destroy', data)
}
