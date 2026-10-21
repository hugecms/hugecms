export interface IMarketingDistributorCreateRequest {
  userId: number, // 用户ID
  inviterId: number, // 上级分销员用户ID,0=无上级
  level: number, // 分销员等级:1=普通,2=高级
  totalCommission: string, // 累计佣金
  settledCommission: string, // 已结算佣金,可提现=total-settled
  status: number, // 状态:0=待审核,1=通过,2=拒绝,3=冻结
}

export interface IMarketingDistributorDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingDistributorQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  inviterId?: number, // 上级分销员用户ID,0=无上级
}

export interface IMarketingDistributorUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  inviterId: number, // 上级分销员用户ID,0=无上级
  level: number, // 分销员等级:1=普通,2=高级
  totalCommission: string, // 累计佣金
  settledCommission: string, // 已结算佣金,可提现=total-settled
  status: number, // 状态:0=待审核,1=通过,2=拒绝,3=冻结
}

export interface IMarketingDistributorDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingDistributorQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingDistributorResponse[], // 数据列表
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

export interface IMarketingDistributorResponse {
  id: number, // ID
  userId: number, // 用户ID
  inviterId: number, // 上级分销员用户ID,0=无上级
  level: number, // 分销员等级:1=普通,2=高级
  totalCommission: string, // 累计佣金
  settledCommission: string, // 已结算佣金,可提现=total-settled
  status: number, // 状态:0=待审核,1=通过,2=拒绝,3=冻结
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

