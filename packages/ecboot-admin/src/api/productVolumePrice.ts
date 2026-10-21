import { post, get, put } from '@/utils/request'
import type { IProductVolumePriceQueryRequest, IProductVolumePriceQueryResponse, IProductVolumePriceCreateRequest, IProductVolumePriceResponse, IProductVolumePriceUpdateRequest, IProductVolumePriceDestroyRequest, IProductVolumePriceDestroyResponse } from '@/types/productVolumePrice'

/** [商品阶梯价模块] 查询商品阶梯价列表接口 */
export function productVolumePriceSearchApi(page: number, pageSize: number, data: IProductVolumePriceQueryRequest): Promise<IProductVolumePriceQueryResponse> {
  return post<IProductVolumePriceQueryResponse>('admin/productVolumePrice/search', data, { params: { page, pageSize } })
}

/** [商品阶梯价模块] 新增商品阶梯价接口 */
export function productVolumePriceStoreApi(data: IProductVolumePriceCreateRequest): Promise<IProductVolumePriceResponse> {
  return post<IProductVolumePriceResponse>('admin/productVolumePrice/store', data)
}

/** [商品阶梯价模块] 获取商品阶梯价详情接口 */
export function productVolumePriceShowApi(id: number): Promise<IProductVolumePriceResponse> {
  return get<IProductVolumePriceResponse>('admin/productVolumePrice/show', { params: { id } })
}

/** [商品阶梯价模块] 更新商品阶梯价接口 */
export function productVolumePriceUpdateApi(id: number, data: IProductVolumePriceUpdateRequest): Promise<IProductVolumePriceResponse> {
  return put<IProductVolumePriceResponse>('admin/productVolumePrice/update', data, { params: { id } })
}

/** [商品阶梯价模块] 删除商品阶梯价接口 */
export function productVolumePriceDestroyApi(data: IProductVolumePriceDestroyRequest): Promise<IProductVolumePriceDestroyResponse> {
  return post<IProductVolumePriceDestroyResponse>('admin/productVolumePrice/destroy', data)
}
