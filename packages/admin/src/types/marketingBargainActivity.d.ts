export interface IMarketingBargainActivityCreateRequest {
  name: string, // 活动名称
  spuId: number, // 商品SPU ID
  startTime: string, // 开始时间
  endTime: string, // 结束时间
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingBargainActivityDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingBargainActivityQueryRequest {
  id?: number, // ID
  spuId?: number, // 商品SPU ID
  status?: number, // 状态:0=停用,1=启用
}

export interface IMarketingBargainActivityUpdateRequest {
  id: number, // ID
  name: string, // 活动名称
  spuId: number, // 商品SPU ID
  startTime: string, // 开始时间
  endTime: string, // 结束时间
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingBargainActivityDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingBargainActivityQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingBargainActivityResponse[], // 数据列表
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

export interface IMarketingBargainActivityResponse {
  id: number, // ID
  name: string, // 活动名称
  spuId: number, // 商品SPU ID
  startTime: string, // 开始时间
  endTime: string, // 结束时间
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

