export interface IUserAccountLogCreateRequest {
  userId: number, // 用户ID
  changeType: string, // 变动类型:recharge=充值,withdraw=提现,order_pay=订单消费,refund=退款返现,points_exchange=积分兑换,admin=人工调整
  changeAmount: string, // 资金变动,正数入负数出
  changePoints: number, // 积分变动,正数入负数出
  description: string, // 变动说明
  relatedId: number, // 关联单据ID
  createTime: string, // 创建时间
}

export interface IUserAccountLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserAccountLogQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  createTime?: string, // 创建时间
}

export interface IUserAccountLogUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  changeType: string, // 变动类型:recharge=充值,withdraw=提现,order_pay=订单消费,refund=退款返现,points_exchange=积分兑换,admin=人工调整
  changeAmount: string, // 资金变动,正数入负数出
  changePoints: number, // 积分变动,正数入负数出
  description: string, // 变动说明
  relatedId: number, // 关联单据ID
  createTime: string, // 创建时间
}

export interface IUserAccountLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserAccountLogQueryResponse {
  currentPage: number, // 当前页码
  data: IUserAccountLogResponse[], // 数据列表
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

export interface IUserAccountLogResponse {
  id: number, // ID
  userId: number, // 用户ID
  changeType: string, // 变动类型:recharge=充值,withdraw=提现,order_pay=订单消费,refund=退款返现,points_exchange=积分兑换,admin=人工调整
  changeAmount: string, // 资金变动,正数入负数出
  changePoints: number, // 积分变动,正数入负数出
  description: string, // 变动说明
  relatedId: number, // 关联单据ID
  createTime: string, // 创建时间
}

