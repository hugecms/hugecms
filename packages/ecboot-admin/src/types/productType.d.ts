export interface IProductTypeCreateRequest {
  name: string, // 类型名称,如:手机
  status: number, // 状态:0=禁用,1=启用
}

export interface IProductTypeDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IProductTypeQueryRequest {
  id?: number, // ID
}

export interface IProductTypeUpdateRequest {
  id: number, // ID
  name: string, // 类型名称,如:手机
  status: number, // 状态:0=禁用,1=启用
}

export interface IProductTypeDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IProductTypeQueryResponse {
  currentPage: number, // 当前页码
  data: IProductTypeResponse[], // 数据列表
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

export interface IProductTypeResponse {
  id: number, // ID
  name: string, // 类型名称,如:手机
  status: number, // 状态:0=禁用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

