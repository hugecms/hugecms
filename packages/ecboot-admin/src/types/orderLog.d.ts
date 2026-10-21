export interface IOrderLogCreateRequest {
  orderId: number, // 订单ID
  fromStatus: number, // 变更前状态
  toStatus: number, // 变更后状态
  operatorType: string, // 操作账户类型:admin,user,system
  operatorId: number, // 操作人ID
  operator: string, // 操作人名称快照
  note: string, // 操作备注
  createTime: string, // 创建时间
}

export interface IOrderLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderLogQueryRequest {
  id?: number, // ID
  orderId?: number, // 订单ID
}

export interface IOrderLogUpdateRequest {
  id: number, // ID
  orderId: number, // 订单ID
  fromStatus: number, // 变更前状态
  toStatus: number, // 变更后状态
  operatorType: string, // 操作账户类型:admin,user,system
  operatorId: number, // 操作人ID
  operator: string, // 操作人名称快照
  note: string, // 操作备注
  createTime: string, // 创建时间
}

export interface IOrderLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderLogQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderLogResponse[], // 数据列表
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

export interface IOrderLogResponse {
  id: number, // ID
  orderId: number, // 订单ID
  fromStatus: number, // 变更前状态
  toStatus: number, // 变更后状态
  operatorType: string, // 操作账户类型:admin,user,system
  operatorId: number, // 操作人ID
  operator: string, // 操作人名称快照
  note: string, // 操作备注
  createTime: string, // 创建时间
}

