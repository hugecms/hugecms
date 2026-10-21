export interface IMarketingUserCouponCreateRequest {
  couponId: number, // 优惠券类型ID
  userId: number, // 持有用户ID,发放即创建归属
  orderId: number, // 核销订单ID
  status: number, // 状态:0=未使用,1=已使用,2=已过期
  usedAt: string, // 核销时间
  expireAt: string, // 过期时间,空=按券类型有效期
}

export interface IMarketingUserCouponDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingUserCouponQueryRequest {
  id?: number, // ID
  couponId?: number, // 优惠券类型ID
  userId?: number, // 持有用户ID,发放即创建归属
  status?: number, // 状态:0=未使用,1=已使用,2=已过期
}

export interface IMarketingUserCouponUpdateRequest {
  id: number, // ID
  couponId: number, // 优惠券类型ID
  userId: number, // 持有用户ID,发放即创建归属
  orderId: number, // 核销订单ID
  status: number, // 状态:0=未使用,1=已使用,2=已过期
  usedAt: string, // 核销时间
  expireAt: string, // 过期时间,空=按券类型有效期
}

export interface IMarketingUserCouponDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingUserCouponQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingUserCouponResponse[], // 数据列表
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

export interface IMarketingUserCouponResponse {
  id: number, // ID
  couponId: number, // 优惠券类型ID
  userId: number, // 持有用户ID,发放即创建归属
  orderId: number, // 核销订单ID
  status: number, // 状态:0=未使用,1=已使用,2=已过期
  usedAt: string, // 核销时间
  expireAt: string, // 过期时间,空=按券类型有效期
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

