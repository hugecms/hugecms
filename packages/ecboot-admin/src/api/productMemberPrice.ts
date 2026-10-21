import { post, get, put } from '@/utils/request'
import type { IProductMemberPriceQueryRequest, IProductMemberPriceQueryResponse, IProductMemberPriceCreateRequest, IProductMemberPriceResponse, IProductMemberPriceUpdateRequest, IProductMemberPriceDestroyRequest, IProductMemberPriceDestroyResponse } from '@/types/productMemberPrice'

/** [商品会员等级价模块] 查询商品会员等级价列表接口 */
export function productMemberPriceSearchApi(page: number, pageSize: number, data: IProductMemberPriceQueryRequest): Promise<IProductMemberPriceQueryResponse> {
  return post<IProductMemberPriceQueryResponse>('admin/productMemberPrice/search', data, { params: { page, pageSize } })
}

/** [商品会员等级价模块] 新增商品会员等级价接口 */
export function productMemberPriceStoreApi(data: IProductMemberPriceCreateRequest): Promise<IProductMemberPriceResponse> {
  return post<IProductMemberPriceResponse>('admin/productMemberPrice/store', data)
}

/** [商品会员等级价模块] 获取商品会员等级价详情接口 */
export function productMemberPriceShowApi(id: number): Promise<IProductMemberPriceResponse> {
  return get<IProductMemberPriceResponse>('admin/productMemberPrice/show', { params: { id } })
}

/** [商品会员等级价模块] 更新商品会员等级价接口 */
export function productMemberPriceUpdateApi(id: number, data: IProductMemberPriceUpdateRequest): Promise<IProductMemberPriceResponse> {
  return put<IProductMemberPriceResponse>('admin/productMemberPrice/update', data, { params: { id } })
}

/** [商品会员等级价模块] 删除商品会员等级价接口 */
export function productMemberPriceDestroyApi(data: IProductMemberPriceDestroyRequest): Promise<IProductMemberPriceDestroyResponse> {
  return post<IProductMemberPriceDestroyResponse>('admin/productMemberPrice/destroy', data)
}
