import { post, get, put } from '@/utils/request'
import type { IWechatFanQueryRequest, IWechatFanQueryResponse, IWechatFanCreateRequest, IWechatFanResponse, IWechatFanUpdateRequest, IWechatFanDestroyRequest, IWechatFanDestroyResponse } from '@/types/wechatFan'

/** [公众号粉丝模块] 查询公众号粉丝列表接口 */
export function wechatFanSearchApi(page: number, pageSize: number, data: IWechatFanQueryRequest): Promise<IWechatFanQueryResponse> {
  return post<IWechatFanQueryResponse>('admin/wechatFan/search', data, { params: { page, pageSize } })
}

/** [公众号粉丝模块] 新增公众号粉丝接口 */
export function wechatFanStoreApi(data: IWechatFanCreateRequest): Promise<IWechatFanResponse> {
  return post<IWechatFanResponse>('admin/wechatFan/store', data)
}

/** [公众号粉丝模块] 获取公众号粉丝详情接口 */
export function wechatFanShowApi(id: number): Promise<IWechatFanResponse> {
  return get<IWechatFanResponse>('admin/wechatFan/show', { params: { id } })
}

/** [公众号粉丝模块] 更新公众号粉丝接口 */
export function wechatFanUpdateApi(id: number, data: IWechatFanUpdateRequest): Promise<IWechatFanResponse> {
  return put<IWechatFanResponse>('admin/wechatFan/update', data, { params: { id } })
}

/** [公众号粉丝模块] 删除公众号粉丝接口 */
export function wechatFanDestroyApi(data: IWechatFanDestroyRequest): Promise<IWechatFanDestroyResponse> {
  return post<IWechatFanDestroyResponse>('admin/wechatFan/destroy', data)
}
