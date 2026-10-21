import { post, get, put } from '@/utils/request'
import type { IWechatTemplateQueryRequest, IWechatTemplateQueryResponse, IWechatTemplateCreateRequest, IWechatTemplateResponse, IWechatTemplateUpdateRequest, IWechatTemplateDestroyRequest, IWechatTemplateDestroyResponse } from '@/types/wechatTemplate'

/** [微信消息模板模块] 查询微信消息模板列表接口 */
export function wechatTemplateSearchApi(page: number, pageSize: number, data: IWechatTemplateQueryRequest): Promise<IWechatTemplateQueryResponse> {
  return post<IWechatTemplateQueryResponse>('admin/wechatTemplate/search', data, { params: { page, pageSize } })
}

/** [微信消息模板模块] 新增微信消息模板接口 */
export function wechatTemplateStoreApi(data: IWechatTemplateCreateRequest): Promise<IWechatTemplateResponse> {
  return post<IWechatTemplateResponse>('admin/wechatTemplate/store', data)
}

/** [微信消息模板模块] 获取微信消息模板详情接口 */
export function wechatTemplateShowApi(id: number): Promise<IWechatTemplateResponse> {
  return get<IWechatTemplateResponse>('admin/wechatTemplate/show', { params: { id } })
}

/** [微信消息模板模块] 更新微信消息模板接口 */
export function wechatTemplateUpdateApi(id: number, data: IWechatTemplateUpdateRequest): Promise<IWechatTemplateResponse> {
  return put<IWechatTemplateResponse>('admin/wechatTemplate/update', data, { params: { id } })
}

/** [微信消息模板模块] 删除微信消息模板接口 */
export function wechatTemplateDestroyApi(data: IWechatTemplateDestroyRequest): Promise<IWechatTemplateDestroyResponse> {
  return post<IWechatTemplateDestroyResponse>('admin/wechatTemplate/destroy', data)
}
