import { post, get, put } from '@/utils/request'
import type { IProductQueryRequest, IProductQueryResponse, IProductCreateRequest, IProductResponse, IProductUpdateRequest, IProductDestroyRequest, IProductDestroyResponse } from '@/types/product'

/** [商品模块] 查询商品列表接口 */
export function productSearchApi(page: number, pageSize: number, data: IProductQueryRequest): Promise<IProductQueryResponse> {
  return post<IProductQueryResponse>('admin/product/search', data, { params: { page, pageSize } })
}

/** [商品模块] 新增商品接口 */
export function productStoreApi(data: IProductCreateRequest): Promise<IProductResponse> {
  return post<IProductResponse>('admin/product/store', data)
}

/** [商品模块] 获取商品详情接口 */
export function productShowApi(id: number): Promise<IProductResponse> {
  return get<IProductResponse>('admin/product/show', { params: { id } })
}

/** [商品模块] 更新商品接口 */
export function productUpdateApi(id: number, data: IProductUpdateRequest): Promise<IProductResponse> {
  return put<IProductResponse>('admin/product/update', data, { params: { id } })
}

/** [商品模块] 删除商品接口 */
export function productDestroyApi(data: IProductDestroyRequest): Promise<IProductDestroyResponse> {
  return post<IProductDestroyResponse>('admin/product/destroy', data)
}
