export interface ISystemRegionCreateRequest {
  parentId: number, // 父级ID,0=省级
  name: string, // 区域名称
  level: number, // 层级:1=省,2=市,3=区县
  code: string, // 行政区划码
  sort: number, // 排序
  createTime: string, // 创建时间
}

export interface ISystemRegionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemRegionQueryRequest {
  id?: number, // ID
  parentId?: number, // 父级ID,0=省级
}

export interface ISystemRegionUpdateRequest {
  id: number, // ID
  parentId: number, // 父级ID,0=省级
  name: string, // 区域名称
  level: number, // 层级:1=省,2=市,3=区县
  code: string, // 行政区划码
  sort: number, // 排序
  createTime: string, // 创建时间
}

export interface ISystemRegionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemRegionQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemRegionResponse[], // 数据列表
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

export interface ISystemRegionResponse {
  id: number, // ID
  parentId: number, // 父级ID,0=省级
  name: string, // 区域名称
  level: number, // 层级:1=省,2=市,3=区县
  code: string, // 行政区划码
  sort: number, // 排序
  createTime: string, // 创建时间
}

