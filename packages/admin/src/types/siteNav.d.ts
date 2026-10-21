export interface ISiteNavCreateRequest {
  parentId: number, // 父级ID,0=顶级
  name: string, // 导航名称
  url: string, // 链接地址
  position: string, // 展示位置:header=头部,footer=底部
  sort: number, // 排序
  status: number, // 状态:0=隐藏,1=显示
}

export interface ISiteNavDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISiteNavQueryRequest {
  id?: number, // ID
  position?: string, // 展示位置:header=头部,footer=底部
}

export interface ISiteNavUpdateRequest {
  id: number, // ID
  parentId: number, // 父级ID,0=顶级
  name: string, // 导航名称
  url: string, // 链接地址
  position: string, // 展示位置:header=头部,footer=底部
  sort: number, // 排序
  status: number, // 状态:0=隐藏,1=显示
}

export interface ISiteNavDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISiteNavQueryResponse {
  currentPage: number, // 当前页码
  data: ISiteNavResponse[], // 数据列表
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

export interface ISiteNavResponse {
  id: number, // ID
  parentId: number, // 父级ID,0=顶级
  name: string, // 导航名称
  url: string, // 链接地址
  position: string, // 展示位置:header=头部,footer=底部
  sort: number, // 排序
  status: number, // 状态:0=隐藏,1=显示
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

