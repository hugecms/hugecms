export interface IUserFavoriteCreateRequest {
  userId: number, // 用户ID
  targetType: string, // 目标类型:article,product
  targetId: number, // 目标ID
  createTime: string, // 创建时间
}

export interface IUserFavoriteDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserFavoriteQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  targetType?: string, // 目标类型:article,product
  targetId?: number, // 目标ID
}

export interface IUserFavoriteUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  targetType: string, // 目标类型:article,product
  targetId: number, // 目标ID
  createTime: string, // 创建时间
}

export interface IUserFavoriteDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserFavoriteQueryResponse {
  currentPage: number, // 当前页码
  data: IUserFavoriteResponse[], // 数据列表
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

export interface IUserFavoriteResponse {
  id: number, // ID
  userId: number, // 用户ID
  targetType: string, // 目标类型:article,product
  targetId: number, // 目标ID
  createTime: string, // 创建时间
}

