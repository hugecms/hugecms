export interface IOrderPromotionCreateRequest {
  orderId: number, // 订单ID
  type: string, // 优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动
  relatedId: number, // 关联ID:coupon=用户券ID,promotion=活动ID,points=空
  name: string, // 优惠名称快照,如:满100减20
  orderItemId: number, // 订单明细ID,空=全单优惠
  amount: string, // 抵扣金额
  points: number, // 消耗积分,积分抵扣时记录,退款按比例返还
}

export interface IOrderPromotionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderPromotionQueryRequest {
  id?: number, // ID
  orderId?: number, // 订单ID
  type?: string, // 优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动
  relatedId?: number, // 关联ID:coupon=用户券ID,promotion=活动ID,points=空
}

export interface IOrderPromotionUpdateRequest {
  id: number, // ID
  orderId: number, // 订单ID
  type: string, // 优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动
  relatedId: number, // 关联ID:coupon=用户券ID,promotion=活动ID,points=空
  name: string, // 优惠名称快照,如:满100减20
  orderItemId: number, // 订单明细ID,空=全单优惠
  amount: string, // 抵扣金额
  points: number, // 消耗积分,积分抵扣时记录,退款按比例返还
}

export interface IOrderPromotionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderPromotionQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderPromotionResponse[], // 数据列表
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

export interface IOrderPromotionResponse {
  id: number, // ID
  orderId: number, // 订单ID
  type: string, // 优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动
  relatedId: number, // 关联ID:coupon=用户券ID,promotion=活动ID,points=空
  name: string, // 优惠名称快照,如:满100减20
  orderItemId: number, // 订单明细ID,空=全单优惠
  amount: string, // 抵扣金额
  points: number, // 消耗积分,积分抵扣时记录,退款按比例返还
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

