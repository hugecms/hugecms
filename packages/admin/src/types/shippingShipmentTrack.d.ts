export interface IShippingShipmentTrackCreateRequest {
  shipmentId: number, // 发货单ID
  acceptTime: string, // 轨迹发生时间
  location: string, // 所在城市/网点
  description: string, // 轨迹描述,如:已签收
  createTime: string, // 创建时间
}

export interface IShippingShipmentTrackDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IShippingShipmentTrackQueryRequest {
  id?: number, // ID
  shipmentId?: number, // 发货单ID
  acceptTime?: string, // 轨迹发生时间
}

export interface IShippingShipmentTrackUpdateRequest {
  id: number, // ID
  shipmentId: number, // 发货单ID
  acceptTime: string, // 轨迹发生时间
  location: string, // 所在城市/网点
  description: string, // 轨迹描述,如:已签收
  createTime: string, // 创建时间
}

export interface IShippingShipmentTrackDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IShippingShipmentTrackQueryResponse {
  currentPage: number, // 当前页码
  data: IShippingShipmentTrackResponse[], // 数据列表
  firstPageUrl: string, // 首页URL
  from: number, // 当前页面上的开始位置
  lastPage: number, // 最后页码
  lastPageUrl: string, // 最后页URL
  links: IPaginateLinkVo[], // 分页链接的数组
  nextPageUrl: string, // 下一页URL
  path: string, // 分页URL
  perPage: number, // 每页显示的记录数量
  prevPageUrl: string, // 上一页URL
  to: number, // 当前页面上的最后位置
  total: number, // 数据总数
}

export interface IShippingShipmentTrackResponse {
  id: number, // ID
  shipmentId: number, // 发货单ID
  acceptTime: string, // 轨迹发生时间
  location: string, // 所在城市/网点
  description: string, // 轨迹描述,如:已签收
  createTime: string, // 创建时间
}

