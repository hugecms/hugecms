import { post, get, put } from '@/utils/request'
import type { IWechatMessageQueryRequest, IWechatMessageQueryResponse, IWechatMessageCreateRequest, IWechatMessageResponse, IWechatMessageUpdateRequest, IWechatMessageDestroyRequest, IWechatMessageDestroyResponse } from '@/types/wechatMessage'

/** [微信消息推送记录模块] 查询微信消息推送记录列表接口 */
export function wechatMessageSearchApi(page: number, pageSize: number, data: IWechatMessageQueryRequest): Promise<IWechatMessageQueryResponse> {
  return post<IWechatMessageQueryResponse>('admin/wechatMessage/search', data, { params: { page, pageSize } })
}

/** [微信消息推送记录模块] 新增微信消息推送记录接口 */
export function wechatMessageStoreApi(data: IWechatMessageCreateRequest): Promise<IWechatMessageResponse> {
  return post<IWechatMessageResponse>('admin/wechatMessage/store', data)
}

/** [微信消息推送记录模块] 获取微信消息推送记录详情接口 */
export function wechatMessageShowApi(id: number): Promise<IWechatMessageResponse> {
  return get<IWechatMessageResponse>('admin/wechatMessage/show', { params: { id } })
}

/** [微信消息推送记录模块] 更新微信消息推送记录接口 */
export function wechatMessageUpdateApi(id: number, data: IWechatMessageUpdateRequest): Promise<IWechatMessageResponse> {
  return put<IWechatMessageResponse>('admin/wechatMessage/update', data, { params: { id } })
}

/** [微信消息推送记录模块] 删除微信消息推送记录接口 */
export function wechatMessageDestroyApi(data: IWechatMessageDestroyRequest): Promise<IWechatMessageDestroyResponse> {
  return post<IWechatMessageDestroyResponse>('admin/wechatMessage/destroy', data)
}
