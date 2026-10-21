import { post, get, put } from '@/utils/request'
import type { IWechatSubscribeQueryRequest, IWechatSubscribeQueryResponse, IWechatSubscribeCreateRequest, IWechatSubscribeResponse, IWechatSubscribeUpdateRequest, IWechatSubscribeDestroyRequest, IWechatSubscribeDestroyResponse } from '@/types/wechatSubscribe'

/** [用户订阅授权模块] 查询用户订阅授权列表接口 */
export function wechatSubscribeSearchApi(page: number, pageSize: number, data: IWechatSubscribeQueryRequest): Promise<IWechatSubscribeQueryResponse> {
  return post<IWechatSubscribeQueryResponse>('admin/wechatSubscribe/search', data, { params: { page, pageSize } })
}

/** [用户订阅授权模块] 新增用户订阅授权接口 */
export function wechatSubscribeStoreApi(data: IWechatSubscribeCreateRequest): Promise<IWechatSubscribeResponse> {
  return post<IWechatSubscribeResponse>('admin/wechatSubscribe/store', data)
}

/** [用户订阅授权模块] 获取用户订阅授权详情接口 */
export function wechatSubscribeShowApi(id: number): Promise<IWechatSubscribeResponse> {
  return get<IWechatSubscribeResponse>('admin/wechatSubscribe/show', { params: { id } })
}

/** [用户订阅授权模块] 更新用户订阅授权接口 */
export function wechatSubscribeUpdateApi(id: number, data: IWechatSubscribeUpdateRequest): Promise<IWechatSubscribeResponse> {
  return put<IWechatSubscribeResponse>('admin/wechatSubscribe/update', data, { params: { id } })
}

/** [用户订阅授权模块] 删除用户订阅授权接口 */
export function wechatSubscribeDestroyApi(data: IWechatSubscribeDestroyRequest): Promise<IWechatSubscribeDestroyResponse> {
  return post<IWechatSubscribeDestroyResponse>('admin/wechatSubscribe/destroy', data)
}
