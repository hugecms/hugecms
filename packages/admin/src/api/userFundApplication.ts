import { post, get, put } from '@/utils/request'
import type { IUserFundApplicationQueryRequest, IUserFundApplicationQueryResponse, IUserFundApplicationCreateRequest, IUserFundApplicationResponse, IUserFundApplicationUpdateRequest, IUserFundApplicationDestroyRequest, IUserFundApplicationDestroyResponse } from '@/types/userFundApplication'

/** [用户资金申请模块] 查询用户资金申请列表接口 */
export function userFundApplicationSearchApi(page: number, pageSize: number, data: IUserFundApplicationQueryRequest): Promise<IUserFundApplicationQueryResponse> {
  return post<IUserFundApplicationQueryResponse>('admin/userFundApplication/search', data, { params: { page, pageSize } })
}

/** [用户资金申请模块] 新增用户资金申请接口 */
export function userFundApplicationStoreApi(data: IUserFundApplicationCreateRequest): Promise<IUserFundApplicationResponse> {
  return post<IUserFundApplicationResponse>('admin/userFundApplication/store', data)
}

/** [用户资金申请模块] 获取用户资金申请详情接口 */
export function userFundApplicationShowApi(id: number): Promise<IUserFundApplicationResponse> {
  return get<IUserFundApplicationResponse>('admin/userFundApplication/show', { params: { id } })
}

/** [用户资金申请模块] 更新用户资金申请接口 */
export function userFundApplicationUpdateApi(id: number, data: IUserFundApplicationUpdateRequest): Promise<IUserFundApplicationResponse> {
  return put<IUserFundApplicationResponse>('admin/userFundApplication/update', data, { params: { id } })
}

/** [用户资金申请模块] 删除用户资金申请接口 */
export function userFundApplicationDestroyApi(data: IUserFundApplicationDestroyRequest): Promise<IUserFundApplicationDestroyResponse> {
  return post<IUserFundApplicationDestroyResponse>('admin/userFundApplication/destroy', data)
}
