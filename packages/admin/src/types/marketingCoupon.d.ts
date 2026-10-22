export interface IMarketingCouponCreateRequest {
  name: string, // 券名称
  type: string, // 类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券
  minAmount: string, // 使用门槛,0=无门槛
  reduceAmount: string, // 满减金额,满减券必填
  discountRate: string, // 折扣率,如8.5表示85折,折扣券必填
  receiveType: string, // 发放方式:manual=后台发放,order=下单赠送,register=注册赠送
  total: number, // 发放总量,0=不限;领券事务条件更新 received_count &lt; total 防超发
  receivedCount: number, // 已领取数量,领券事务内条件更新 received_count &lt; total 防超发
  perLimit: number, // 每人限领
  validType: number, // 有效期方式:1=固定区间(start_time/end_time),2=领取后N天
  validDays: number, // 领取后有效天数,valid_type=2 时必填
  startTime: string, // 有效期开始,空=领取即生效
  endTime: string, // 有效期结束
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingCouponDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingCouponQueryRequest {
  id?: number, // ID
}

export interface IMarketingCouponUpdateRequest {
  id: number, // ID
  name: string, // 券名称
  type: string, // 类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券
  minAmount: string, // 使用门槛,0=无门槛
  reduceAmount: string, // 满减金额,满减券必填
  discountRate: string, // 折扣率,如8.5表示85折,折扣券必填
  receiveType: string, // 发放方式:manual=后台发放,order=下单赠送,register=注册赠送
  total: number, // 发放总量,0=不限;领券事务条件更新 received_count &lt; total 防超发
  receivedCount: number, // 已领取数量,领券事务内条件更新 received_count &lt; total 防超发
  perLimit: number, // 每人限领
  validType: number, // 有效期方式:1=固定区间(start_time/end_time),2=领取后N天
  validDays: number, // 领取后有效天数,valid_type=2 时必填
  startTime: string, // 有效期开始,空=领取即生效
  endTime: string, // 有效期结束
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingCouponDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingCouponQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingCouponResponse[], // 数据列表
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

export interface IMarketingCouponResponse {
  id: number, // ID
  name: string, // 券名称
  type: string, // 类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券
  minAmount: string, // 使用门槛,0=无门槛
  reduceAmount: string, // 满减金额,满减券必填
  discountRate: string, // 折扣率,如8.5表示85折,折扣券必填
  receiveType: string, // 发放方式:manual=后台发放,order=下单赠送,register=注册赠送
  total: number, // 发放总量,0=不限;领券事务条件更新 received_count &lt; total 防超发
  receivedCount: number, // 已领取数量,领券事务内条件更新 received_count &lt; total 防超发
  perLimit: number, // 每人限领
  validType: number, // 有效期方式:1=固定区间(start_time/end_time),2=领取后N天
  validDays: number, // 领取后有效天数,valid_type=2 时必填
  startTime: string, // 有效期开始,空=领取即生效
  endTime: string, // 有效期结束
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

