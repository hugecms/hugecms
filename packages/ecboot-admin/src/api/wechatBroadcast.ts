import { post, get, put } from '@/utils/request'
import type { IWechatBroadcastQueryRequest, IWechatBroadcastQueryResponse, IWechatBroadcastCreateRequest, IWechatBroadcastResponse, IWechatBroadcastUpdateRequest, IWechatBroadcastDestroyRequest, IWechatBroadcastDestroyResponse } from '@/types/wechatBroadcast'

/** [公众号群发记录模块] 查询公众号群发记录列表接口 */
export function wechatBroadcastSearchApi(page: number, pageSize: number, data: IWechatBroadcastQueryRequest): Promise<IWechatBroadcastQueryResponse> {
  return post<IWechatBroadcastQueryResponse>('admin/wechatBroadcast/search', data, { params: { page, pageSize } })
}

/** [公众号群发记录模块] 新增公众号群发记录接口 */
export function wechatBroadcastStoreApi(data: IWechatBroadcastCreateRequest): Promise<IWechatBroadcastResponse> {
  return post<IWechatBroadcastResponse>('admin/wechatBroadcast/store', data)
}

/** [公众号群发记录模块] 获取公众号群发记录详情接口 */
export function wechatBroadcastShowApi(id: number): Promise<IWechatBroadcastResponse> {
  return get<IWechatBroadcastResponse>('admin/wechatBroadcast/show', { params: { id } })
}

/** [公众号群发记录模块] 更新公众号群发记录接口 */
export function wechatBroadcastUpdateApi(id: number, data: IWechatBroadcastUpdateRequest): Promise<IWechatBroadcastResponse> {
  return put<IWechatBroadcastResponse>('admin/wechatBroadcast/update', data, { params: { id } })
}

/** [公众号群发记录模块] 删除公众号群发记录接口 */
export function wechatBroadcastDestroyApi(data: IWechatBroadcastDestroyRequest): Promise<IWechatBroadcastDestroyResponse> {
  return post<IWechatBroadcastDestroyResponse>('admin/wechatBroadcast/destroy', data)
}
