import { post, get, put } from '@/utils/request'
import type { IUserRankQueryRequest, IUserRankQueryResponse, IUserRankCreateRequest, IUserRankResponse, IUserRankUpdateRequest, IUserRankDestroyRequest, IUserRankDestroyResponse } from '@/types/userRank'

/** [会员等级模块] 查询会员等级列表接口 */
export function userRankSearchApi(page: number, pageSize: number, data: IUserRankQueryRequest): Promise<IUserRankQueryResponse> {
  return post<IUserRankQueryResponse>('admin/userRank/search', data, { params: { page, pageSize } })
}

/** [会员等级模块] 新增会员等级接口 */
export function userRankStoreApi(data: IUserRankCreateRequest): Promise<IUserRankResponse> {
  return post<IUserRankResponse>('admin/userRank/store', data)
}

/** [会员等级模块] 获取会员等级详情接口 */
export function userRankShowApi(id: number): Promise<IUserRankResponse> {
  return get<IUserRankResponse>('admin/userRank/show', { params: { id } })
}

/** [会员等级模块] 更新会员等级接口 */
export function userRankUpdateApi(id: number, data: IUserRankUpdateRequest): Promise<IUserRankResponse> {
  return put<IUserRankResponse>('admin/userRank/update', data, { params: { id } })
}

/** [会员等级模块] 删除会员等级接口 */
export function userRankDestroyApi(data: IUserRankDestroyRequest): Promise<IUserRankDestroyResponse> {
  return post<IUserRankDestroyResponse>('admin/userRank/destroy', data)
}
