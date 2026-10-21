import { post, get, put } from '@/utils/request'
import type { IUserWithdrawOrderQueryRequest, IUserWithdrawOrderQueryResponse, IUserWithdrawOrderCreateRequest, IUserWithdrawOrderResponse, IUserWithdrawOrderUpdateRequest, IUserWithdrawOrderDestroyRequest, IUserWithdrawOrderDestroyResponse } from '@/types/userWithdrawOrder'

/** [用户提现单模块] 查询用户提现单列表接口 */
export function userWithdrawOrderSearchApi(page: number, pageSize: number, data: IUserWithdrawOrderQueryRequest): Promise<IUserWithdrawOrderQueryResponse> {
  return post<IUserWithdrawOrderQueryResponse>('admin/userWithdrawOrder/search', data, { params: { page, pageSize } })
}

/** [用户提现单模块] 新增用户提现单接口 */
export function userWithdrawOrderStoreApi(data: IUserWithdrawOrderCreateRequest): Promise<IUserWithdrawOrderResponse> {
  return post<IUserWithdrawOrderResponse>('admin/userWithdrawOrder/store', data)
}

/** [用户提现单模块] 获取用户提现单详情接口 */
export function userWithdrawOrderShowApi(id: number): Promise<IUserWithdrawOrderResponse> {
  return get<IUserWithdrawOrderResponse>('admin/userWithdrawOrder/show', { params: { id } })
}

/** [用户提现单模块] 更新用户提现单接口 */
export function userWithdrawOrderUpdateApi(id: number, data: IUserWithdrawOrderUpdateRequest): Promise<IUserWithdrawOrderResponse> {
  return put<IUserWithdrawOrderResponse>('admin/userWithdrawOrder/update', data, { params: { id } })
}

/** [用户提现单模块] 删除用户提现单接口 */
export function userWithdrawOrderDestroyApi(data: IUserWithdrawOrderDestroyRequest): Promise<IUserWithdrawOrderDestroyResponse> {
  return post<IUserWithdrawOrderDestroyResponse>('admin/userWithdrawOrder/destroy', data)
}
