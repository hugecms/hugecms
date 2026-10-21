import { post, get, put } from '@/utils/request'
import type { IProductSkuQueryRequest, IProductSkuQueryResponse, IProductSkuCreateRequest, IProductSkuResponse, IProductSkuUpdateRequest, IProductSkuDestroyRequest, IProductSkuDestroyResponse } from '@/types/productSku'

/** [商品SKU模块] 查询商品SKU列表接口 */
export function productSkuSearchApi(page: number, pageSize: number, data: IProductSkuQueryRequest): Promise<IProductSkuQueryResponse> {
  return post<IProductSkuQueryResponse>('admin/productSku/search', data, { params: { page, pageSize } })
}

/** [商品SKU模块] 新增商品SKU接口 */
export function productSkuStoreApi(data: IProductSkuCreateRequest): Promise<IProductSkuResponse> {
  return post<IProductSkuResponse>('admin/productSku/store', data)
}

/** [商品SKU模块] 获取商品SKU详情接口 */
export function productSkuShowApi(id: number): Promise<IProductSkuResponse> {
  return get<IProductSkuResponse>('admin/productSku/show', { params: { id } })
}

/** [商品SKU模块] 更新商品SKU接口 */
export function productSkuUpdateApi(id: number, data: IProductSkuUpdateRequest): Promise<IProductSkuResponse> {
  return put<IProductSkuResponse>('admin/productSku/update', data, { params: { id } })
}

/** [商品SKU模块] 删除商品SKU接口 */
export function productSkuDestroyApi(data: IProductSkuDestroyRequest): Promise<IProductSkuDestroyResponse> {
  return post<IProductSkuDestroyResponse>('admin/productSku/destroy', data)
}
