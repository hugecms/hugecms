export interface IOrderShipmentCreateRequest {
  orderId: number, // 订单ID
  expressCompany: string, // 快递公司
  expressNo: string, // 运单号
  remark: string, // 发货备注
  createTime: string, // 创建时间
}

export interface IOrderShipmentDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderShipmentQueryRequest {
  id?: number, // ID
  orderId?: number, // 订单ID
  expressNo?: string, // 运单号
}

export interface IOrderShipmentUpdateRequest {
  id: number, // ID
  orderId: number, // 订单ID
  expressCompany: string, // 快递公司
  expressNo: string, // 运单号
  remark: string, // 发货备注
  createTime: string, // 创建时间
}

export interface IOrderShipmentDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderShipmentQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderShipmentResponse[], // 数据列表
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

export interface IOrderShipmentResponse {
  id: number, // ID
  orderId: number, // 订单ID
  expressCompany: string, // 快递公司
  expressNo: string, // 运单号
  remark: string, // 发货备注
  createTime: string, // 创建时间
}

