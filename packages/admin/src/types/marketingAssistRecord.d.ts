export interface IMarketingAssistRecordCreateRequest {
  activityId: number, // 助力活动ID
  userId: number, // 发起人用户ID
  helperCount: number, // 已助力人数,达required_count触发发奖
  status: number, // 状态:1=进行中,2=已完成发奖,3=已过期
  finishTime: string, // 完成时间
}

export interface IMarketingAssistRecordDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingAssistRecordQueryRequest {
  id?: number, // ID
  activityId?: number, // 助力活动ID
  userId?: number, // 发起人用户ID
}

export interface IMarketingAssistRecordUpdateRequest {
  id: number, // ID
  activityId: number, // 助力活动ID
  userId: number, // 发起人用户ID
  helperCount: number, // 已助力人数,达required_count触发发奖
  status: number, // 状态:1=进行中,2=已完成发奖,3=已过期
  finishTime: string, // 完成时间
}

export interface IMarketingAssistRecordDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingAssistRecordQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingAssistRecordResponse[], // 数据列表
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

export interface IMarketingAssistRecordResponse {
  id: number, // ID
  activityId: number, // 助力活动ID
  userId: number, // 发起人用户ID
  helperCount: number, // 已助力人数,达required_count触发发奖
  status: number, // 状态:1=进行中,2=已完成发奖,3=已过期
  finishTime: string, // 完成时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

