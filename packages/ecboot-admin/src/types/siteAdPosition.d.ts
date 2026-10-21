export interface ISiteAdPositionCreateRequest {
  code: string, // 广告位标识,如:home_top
  name: string, // 广告位名称
  description: string, // 说明,如:首页顶部,建议尺寸750x300
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface ISiteAdPositionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISiteAdPositionQueryRequest {
  id?: number, // ID
  code?: string, // 广告位标识,如:home_top
}

export interface ISiteAdPositionUpdateRequest {
  id: number, // ID
  code: string, // 广告位标识,如:home_top
  name: string, // 广告位名称
  description: string, // 说明,如:首页顶部,建议尺寸750x300
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface ISiteAdPositionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISiteAdPositionQueryResponse {
  currentPage: number, // 当前页码
  data: ISiteAdPositionResponse[], // 数据列表
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

export interface ISiteAdPositionResponse {
  id: number, // ID
  code: string, // 广告位标识,如:home_top
  name: string, // 广告位名称
  description: string, // 说明,如:首页顶部,建议尺寸750x300
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

