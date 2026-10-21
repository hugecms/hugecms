import { post, get, put } from '@/utils/request'
import type { ISystemIpBlacklistQueryRequest, ISystemIpBlacklistQueryResponse, ISystemIpBlacklistCreateRequest, ISystemIpBlacklistResponse, ISystemIpBlacklistUpdateRequest, ISystemIpBlacklistDestroyRequest, ISystemIpBlacklistDestroyResponse } from '@/types/systemIpBlacklist'

/** [IP黑名单模块] 查询IP黑名单列表接口 */
export function systemIpBlacklistSearchApi(page: number, pageSize: number, data: ISystemIpBlacklistQueryRequest): Promise<ISystemIpBlacklistQueryResponse> {
  return post<ISystemIpBlacklistQueryResponse>('admin/systemIpBlacklist/search', data, { params: { page, pageSize } })
}

/** [IP黑名单模块] 新增IP黑名单接口 */
export function systemIpBlacklistStoreApi(data: ISystemIpBlacklistCreateRequest): Promise<ISystemIpBlacklistResponse> {
  return post<ISystemIpBlacklistResponse>('admin/systemIpBlacklist/store', data)
}

/** [IP黑名单模块] 获取IP黑名单详情接口 */
export function systemIpBlacklistShowApi(id: number): Promise<ISystemIpBlacklistResponse> {
  return get<ISystemIpBlacklistResponse>('admin/systemIpBlacklist/show', { params: { id } })
}

/** [IP黑名单模块] 更新IP黑名单接口 */
export function systemIpBlacklistUpdateApi(id: number, data: ISystemIpBlacklistUpdateRequest): Promise<ISystemIpBlacklistResponse> {
  return put<ISystemIpBlacklistResponse>('admin/systemIpBlacklist/update', data, { params: { id } })
}

/** [IP黑名单模块] 删除IP黑名单接口 */
export function systemIpBlacklistDestroyApi(data: ISystemIpBlacklistDestroyRequest): Promise<ISystemIpBlacklistDestroyResponse> {
  return post<ISystemIpBlacklistDestroyResponse>('admin/systemIpBlacklist/destroy', data)
}
