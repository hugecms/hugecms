export interface IMarketingBargainHelperCreateRequest {
  recordId: number, // 砍价单ID
  helperUserId: number, // 帮砍人用户ID
  cutAmount: string, // 本刀砍掉金额(与record条件更新同事务)
  createTime: string, // 帮砍时间
}

export interface IMarketingBargainHelperDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingBargainHelperQueryRequest {
  id?: number, // ID
  recordId?: number, // 砍价单ID
  helperUserId?: number, // 帮砍人用户ID
}

export interface IMarketingBargainHelperUpdateRequest {
  id: number, // ID
  recordId: number, // 砍价单ID
  helperUserId: number, // 帮砍人用户ID
  cutAmount: string, // 本刀砍掉金额(与record条件更新同事务)
  createTime: string, // 帮砍时间
}

export interface IMarketingBargainHelperDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingBargainHelperQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingBargainHelperResponse[], // 数据列表
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

export interface IMarketingBargainHelperResponse {
  id: number, // ID
  recordId: number, // 砍价单ID
  helperUserId: number, // 帮砍人用户ID
  cutAmount: string, // 本刀砍掉金额(与record条件更新同事务)
  createTime: string, // 帮砍时间
}

