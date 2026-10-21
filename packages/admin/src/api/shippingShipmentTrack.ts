import { post, get, put } from '@/utils/request'
import type { IShippingShipmentTrackQueryRequest, IShippingShipmentTrackQueryResponse, IShippingShipmentTrackCreateRequest, IShippingShipmentTrackResponse, IShippingShipmentTrackUpdateRequest, IShippingShipmentTrackDestroyRequest, IShippingShipmentTrackDestroyResponse } from '@/types/shippingShipmentTrack'

/** [物流轨迹模块] 查询物流轨迹列表接口 */
export function shippingShipmentTrackSearchApi(page: number, pageSize: number, data: IShippingShipmentTrackQueryRequest): Promise<IShippingShipmentTrackQueryResponse> {
  return post<IShippingShipmentTrackQueryResponse>('admin/shippingShipmentTrack/search', data, { params: { page, pageSize } })
}

/** [物流轨迹模块] 新增物流轨迹接口 */
export function shippingShipmentTrackStoreApi(data: IShippingShipmentTrackCreateRequest): Promise<IShippingShipmentTrackResponse> {
  return post<IShippingShipmentTrackResponse>('admin/shippingShipmentTrack/store', data)
}

/** [物流轨迹模块] 获取物流轨迹详情接口 */
export function shippingShipmentTrackShowApi(id: number): Promise<IShippingShipmentTrackResponse> {
  return get<IShippingShipmentTrackResponse>('admin/shippingShipmentTrack/show', { params: { id } })
}

/** [物流轨迹模块] 更新物流轨迹接口 */
export function shippingShipmentTrackUpdateApi(id: number, data: IShippingShipmentTrackUpdateRequest): Promise<IShippingShipmentTrackResponse> {
  return put<IShippingShipmentTrackResponse>('admin/shippingShipmentTrack/update', data, { params: { id } })
}

/** [物流轨迹模块] 删除物流轨迹接口 */
export function shippingShipmentTrackDestroyApi(data: IShippingShipmentTrackDestroyRequest): Promise<IShippingShipmentTrackDestroyResponse> {
  return post<IShippingShipmentTrackDestroyResponse>('admin/shippingShipmentTrack/destroy', data)
}
