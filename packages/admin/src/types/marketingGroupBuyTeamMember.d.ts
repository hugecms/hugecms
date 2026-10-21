export interface IMarketingGroupBuyTeamMemberCreateRequest {
  teamId: number, // 团ID
  userId: number, // 成员用户ID
  orderNo: string, // 成员订单号,关联orders.order_no
  joinTime: string, // 参团时间
  createTime: string, // 创建时间
}

export interface IMarketingGroupBuyTeamMemberDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingGroupBuyTeamMemberQueryRequest {
  id?: number, // ID
  teamId?: number, // 团ID
  userId?: number, // 成员用户ID
  orderNo?: string, // 成员订单号,关联orders.order_no
}

export interface IMarketingGroupBuyTeamMemberUpdateRequest {
  id: number, // ID
  teamId: number, // 团ID
  userId: number, // 成员用户ID
  orderNo: string, // 成员订单号,关联orders.order_no
  joinTime: string, // 参团时间
  createTime: string, // 创建时间
}

export interface IMarketingGroupBuyTeamMemberDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingGroupBuyTeamMemberQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingGroupBuyTeamMemberResponse[], // 数据列表
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

export interface IMarketingGroupBuyTeamMemberResponse {
  id: number, // ID
  teamId: number, // 团ID
  userId: number, // 成员用户ID
  orderNo: string, // 成员订单号,关联orders.order_no
  joinTime: string, // 参团时间
  createTime: string, // 创建时间
}

