export interface IMarketingGroupBuyTeamCreateRequest {
  activityId: number, // 拼团活动ID
  leaderUserId: number, // 团长用户ID
  status: number, // 状态:1=拼团中,2=已成团,3=已解散(超时/取消)
  memberCount: number, // 当前成员数
  expireTime: string, // 成团截止时间,超时扫描依据
  successTime: string, // 成团时间
}

export interface IMarketingGroupBuyTeamDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingGroupBuyTeamQueryRequest {
  id?: number, // ID
  activityId?: number, // 拼团活动ID
  leaderUserId?: number, // 团长用户ID
  status?: number, // 状态:1=拼团中,2=已成团,3=已解散(超时/取消)
  expireTime?: string, // 成团截止时间,超时扫描依据
}

export interface IMarketingGroupBuyTeamUpdateRequest {
  id: number, // ID
  activityId: number, // 拼团活动ID
  leaderUserId: number, // 团长用户ID
  status: number, // 状态:1=拼团中,2=已成团,3=已解散(超时/取消)
  memberCount: number, // 当前成员数
  expireTime: string, // 成团截止时间,超时扫描依据
  successTime: string, // 成团时间
}

export interface IMarketingGroupBuyTeamDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingGroupBuyTeamQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingGroupBuyTeamResponse[], // 数据列表
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

export interface IMarketingGroupBuyTeamResponse {
  id: number, // ID
  activityId: number, // 拼团活动ID
  leaderUserId: number, // 团长用户ID
  status: number, // 状态:1=拼团中,2=已成团,3=已解散(超时/取消)
  memberCount: number, // 当前成员数
  expireTime: string, // 成团截止时间,超时扫描依据
  successTime: string, // 成团时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

