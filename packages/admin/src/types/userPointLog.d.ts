export interface IUserPointLogCreateRequest {
  userId: number, // 用户ID
  bizType: number, // 业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送,8=活动奖励(助力等)
  changePoints: number, // 积分变动,正数入负数出
  pointsAfter: number, // 变动后积分余额快照
  bizNo: string, // 关联业务单号
  description: string, // 变动说明
  createTime: string, // 创建时间
}

export interface IUserPointLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserPointLogQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  createTime?: string, // 创建时间
}

export interface IUserPointLogUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  bizType: number, // 业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送,8=活动奖励(助力等)
  changePoints: number, // 积分变动,正数入负数出
  pointsAfter: number, // 变动后积分余额快照
  bizNo: string, // 关联业务单号
  description: string, // 变动说明
  createTime: string, // 创建时间
}

export interface IUserPointLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserPointLogQueryResponse {
  currentPage: number, // 当前页码
  data: IUserPointLogResponse[], // 数据列表
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

export interface IUserPointLogResponse {
  id: number, // ID
  userId: number, // 用户ID
  bizType: number, // 业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送,8=活动奖励(助力等)
  changePoints: number, // 积分变动,正数入负数出
  pointsAfter: number, // 变动后积分余额快照
  bizNo: string, // 关联业务单号
  description: string, // 变动说明
  createTime: string, // 创建时间
}

