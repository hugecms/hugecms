import { post, get, put } from '@/utils/request'
import type { IUserAddressQueryRequest, IUserAddressQueryResponse, IUserAddressCreateRequest, IUserAddressResponse, IUserAddressUpdateRequest, IUserAddressDestroyRequest, IUserAddressDestroyResponse } from '@/types/userAddress'

/** [用户收货地址模块] 查询用户收货地址列表接口 */
export function userAddressSearchApi(page: number, pageSize: number, data: IUserAddressQueryRequest): Promise<IUserAddressQueryResponse> {
  return post<IUserAddressQueryResponse>('admin/userAddress/search', data, { params: { page, pageSize } })
}

/** [用户收货地址模块] 新增用户收货地址接口 */
export function userAddressStoreApi(data: IUserAddressCreateRequest): Promise<IUserAddressResponse> {
  return post<IUserAddressResponse>('admin/userAddress/store', data)
}

/** [用户收货地址模块] 获取用户收货地址详情接口 */
export function userAddressShowApi(id: number): Promise<IUserAddressResponse> {
  return get<IUserAddressResponse>('admin/userAddress/show', { params: { id } })
}

/** [用户收货地址模块] 更新用户收货地址接口 */
export function userAddressUpdateApi(id: number, data: IUserAddressUpdateRequest): Promise<IUserAddressResponse> {
  return put<IUserAddressResponse>('admin/userAddress/update', data, { params: { id } })
}

/** [用户收货地址模块] 删除用户收货地址接口 */
export function userAddressDestroyApi(data: IUserAddressDestroyRequest): Promise<IUserAddressDestroyResponse> {
  return post<IUserAddressDestroyResponse>('admin/userAddress/destroy', data)
}
