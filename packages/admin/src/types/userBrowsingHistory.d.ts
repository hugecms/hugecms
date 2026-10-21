export interface IUserBrowsingHistoryCreateRequest {
  userId: number, // 用户ID
  targetType: string, // 目标类型:article,product
  targetId: number, // 目标ID
  createTime: string, // 最近浏览时间,重复浏览更新
}

export interface IUserBrowsingHistoryDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserBrowsingHistoryQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  targetType?: string, // 目标类型:article,product
  targetId?: number, // 目标ID
  createTime?: string, // 最近浏览时间,重复浏览更新
}

export interface IUserBrowsingHistoryUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  targetType: string, // 目标类型:article,product
  targetId: number, // 目标ID
  createTime: string, // 最近浏览时间,重复浏览更新
}

export interface IUserBrowsingHistoryDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserBrowsingHistoryQueryResponse {
  currentPage: number, // 当前页码
  data: IUserBrowsingHistoryResponse[], // 数据列表
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

export interface IUserBrowsingHistoryResponse {
  id: number, // ID
  userId: number, // 用户ID
  targetType: string, // 目标类型:article,product
  targetId: number, // 目标ID
  createTime: string, // 最近浏览时间,重复浏览更新
}

