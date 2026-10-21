export interface IMarketingAssistActivityCreateRequest {
  name: string, // 活动名称
  rewardType: number, // 奖励类型:1=优惠券,2=积分
  rewardRef: number, // 奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config
  requiredCount: number, // 所需助力人数
  perLimit: number, // 每人可发起次数
  startTime: string, // 开始时间
  endTime: string, // 结束时间
  config: string, // 扩展参数(积分数/阶梯奖励等)
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingAssistActivityDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingAssistActivityQueryRequest {
  id?: number, // ID
}

export interface IMarketingAssistActivityUpdateRequest {
  id: number, // ID
  name: string, // 活动名称
  rewardType: number, // 奖励类型:1=优惠券,2=积分
  rewardRef: number, // 奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config
  requiredCount: number, // 所需助力人数
  perLimit: number, // 每人可发起次数
  startTime: string, // 开始时间
  endTime: string, // 结束时间
  config: string, // 扩展参数(积分数/阶梯奖励等)
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingAssistActivityDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingAssistActivityQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingAssistActivityResponse[], // 数据列表
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

export interface IMarketingAssistActivityResponse {
  id: number, // ID
  name: string, // 活动名称
  rewardType: number, // 奖励类型:1=优惠券,2=积分
  rewardRef: number, // 奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config
  requiredCount: number, // 所需助力人数
  perLimit: number, // 每人可发起次数
  startTime: string, // 开始时间
  endTime: string, // 结束时间
  config: string, // 扩展参数(积分数/阶梯奖励等)
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

