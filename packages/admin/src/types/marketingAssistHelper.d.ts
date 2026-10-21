export interface IMarketingAssistHelperCreateRequest {
  recordId: number, // 助力参与记录ID
  helperUserId: number, // 助力人用户ID
  createTime: string, // 助力时间
}

export interface IMarketingAssistHelperDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingAssistHelperQueryRequest {
  id?: number, // ID
  recordId?: number, // 助力参与记录ID
  helperUserId?: number, // 助力人用户ID
}

export interface IMarketingAssistHelperUpdateRequest {
  id: number, // ID
  recordId: number, // 助力参与记录ID
  helperUserId: number, // 助力人用户ID
  createTime: string, // 助力时间
}

export interface IMarketingAssistHelperDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingAssistHelperQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingAssistHelperResponse[], // 数据列表
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

export interface IMarketingAssistHelperResponse {
  id: number, // ID
  recordId: number, // 助力参与记录ID
  helperUserId: number, // 助力人用户ID
  createTime: string, // 助力时间
}

