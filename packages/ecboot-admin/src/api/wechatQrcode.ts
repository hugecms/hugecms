import { post, get, put } from '@/utils/request'
import type { IWechatQrcodeQueryRequest, IWechatQrcodeQueryResponse, IWechatQrcodeCreateRequest, IWechatQrcodeResponse, IWechatQrcodeUpdateRequest, IWechatQrcodeDestroyRequest, IWechatQrcodeDestroyResponse } from '@/types/wechatQrcode'

/** [微信渠道二维码模块] 查询微信渠道二维码列表接口 */
export function wechatQrcodeSearchApi(page: number, pageSize: number, data: IWechatQrcodeQueryRequest): Promise<IWechatQrcodeQueryResponse> {
  return post<IWechatQrcodeQueryResponse>('admin/wechatQrcode/search', data, { params: { page, pageSize } })
}

/** [微信渠道二维码模块] 新增微信渠道二维码接口 */
export function wechatQrcodeStoreApi(data: IWechatQrcodeCreateRequest): Promise<IWechatQrcodeResponse> {
  return post<IWechatQrcodeResponse>('admin/wechatQrcode/store', data)
}

/** [微信渠道二维码模块] 获取微信渠道二维码详情接口 */
export function wechatQrcodeShowApi(id: number): Promise<IWechatQrcodeResponse> {
  return get<IWechatQrcodeResponse>('admin/wechatQrcode/show', { params: { id } })
}

/** [微信渠道二维码模块] 更新微信渠道二维码接口 */
export function wechatQrcodeUpdateApi(id: number, data: IWechatQrcodeUpdateRequest): Promise<IWechatQrcodeResponse> {
  return put<IWechatQrcodeResponse>('admin/wechatQrcode/update', data, { params: { id } })
}

/** [微信渠道二维码模块] 删除微信渠道二维码接口 */
export function wechatQrcodeDestroyApi(data: IWechatQrcodeDestroyRequest): Promise<IWechatQrcodeDestroyResponse> {
  return post<IWechatQrcodeDestroyResponse>('admin/wechatQrcode/destroy', data)
}
