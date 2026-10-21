import { post, get, put } from '@/utils/request'
import type { IProductCategoryQueryRequest, IProductCategoryQueryResponse, IProductCategoryCreateRequest, IProductCategoryResponse, IProductCategoryUpdateRequest, IProductCategoryDestroyRequest, IProductCategoryDestroyResponse } from '@/types/productCategory'

/** [商品分类模块] 查询商品分类列表接口 */
export function productCategorySearchApi(page: number, pageSize: number, data: IProductCategoryQueryRequest): Promise<IProductCategoryQueryResponse> {
  return post<IProductCategoryQueryResponse>('admin/productCategory/search', data, { params: { page, pageSize } })
}

/** [商品分类模块] 新增商品分类接口 */
export function productCategoryStoreApi(data: IProductCategoryCreateRequest): Promise<IProductCategoryResponse> {
  return post<IProductCategoryResponse>('admin/productCategory/store', data)
}

/** [商品分类模块] 获取商品分类详情接口 */
export function productCategoryShowApi(id: number): Promise<IProductCategoryResponse> {
  return get<IProductCategoryResponse>('admin/productCategory/show', { params: { id } })
}

/** [商品分类模块] 更新商品分类接口 */
export function productCategoryUpdateApi(id: number, data: IProductCategoryUpdateRequest): Promise<IProductCategoryResponse> {
  return put<IProductCategoryResponse>('admin/productCategory/update', data, { params: { id } })
}

/** [商品分类模块] 删除商品分类接口 */
export function productCategoryDestroyApi(data: IProductCategoryDestroyRequest): Promise<IProductCategoryDestroyResponse> {
  return post<IProductCategoryDestroyResponse>('admin/productCategory/destroy', data)
}
