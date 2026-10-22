export interface ISiteAdCreateRequest {
  positionCode: string, // 广告位标识,关联site_ad_positions.code,如:home_top
  title: string, // 标题
  image: string, // 图片地址
  link: string, // 跳转链接
  description: string, // 描述
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  startTime: string, // 投放开始时间
  endTime: string, // 投放结束时间
}

export interface ISiteAdDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISiteAdQueryRequest {
  id?: number, // ID
  positionCode?: string, // 广告位标识,关联site_ad_positions.code,如:home_top
}

export interface ISiteAdUpdateRequest {
  id: number, // ID
  positionCode: string, // 广告位标识,关联site_ad_positions.code,如:home_top
  title: string, // 标题
  image: string, // 图片地址
  link: string, // 跳转链接
  description: string, // 描述
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  startTime: string, // 投放开始时间
  endTime: string, // 投放结束时间
}

export interface ISiteAdDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISiteAdQueryResponse {
  currentPage: number, // 当前页码
  data: ISiteAdResponse[], // 数据列表
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

export interface ISiteAdResponse {
  id: number, // ID
  positionCode: string, // 广告位标识,关联site_ad_positions.code,如:home_top
  title: string, // 标题
  image: string, // 图片地址
  link: string, // 跳转链接
  description: string, // 描述
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  startTime: string, // 投放开始时间
  endTime: string, // 投放结束时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

