import { post, get, put } from '@/utils/request'
import type { IOrderCartQueryRequest, IOrderCartQueryResponse, IOrderCartCreateRequest, IOrderCartResponse, IOrderCartUpdateRequest, IOrderCartDestroyRequest, IOrderCartDestroyResponse } from '@/types/orderCart'

/** [购物车模块] 查询购物车列表接口 */
export function orderCartSearchApi(page: number, pageSize: number, data: IOrderCartQueryRequest): Promise<IOrderCartQueryResponse> {
  return post<IOrderCartQueryResponse>('admin/orderCart/search', data, { params: { page, pageSize } })
}

/** [购物车模块] 新增购物车接口 */
export function orderCartStoreApi(data: IOrderCartCreateRequest): Promise<IOrderCartResponse> {
  return post<IOrderCartResponse>('admin/orderCart/store', data)
}

/** [购物车模块] 获取购物车详情接口 */
export function orderCartShowApi(id: number): Promise<IOrderCartResponse> {
  return get<IOrderCartResponse>('admin/orderCart/show', { params: { id } })
}

/** [购物车模块] 更新购物车接口 */
export function orderCartUpdateApi(id: number, data: IOrderCartUpdateRequest): Promise<IOrderCartResponse> {
  return put<IOrderCartResponse>('admin/orderCart/update', data, { params: { id } })
}

/** [购物车模块] 删除购物车接口 */
export function orderCartDestroyApi(data: IOrderCartDestroyRequest): Promise<IOrderCartDestroyResponse> {
  return post<IOrderCartDestroyResponse>('admin/orderCart/destroy', data)
}
