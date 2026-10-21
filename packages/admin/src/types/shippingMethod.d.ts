export interface IShippingMethodCreateRequest {
  name: string, // 配送方式名称,如:顺丰标快
  code: string, // 承运商/运费模板标识
  description: string, // 配送说明
  status: number, // 状态:0=停用,1=启用
  sort: number, // 排序
}

export interface IShippingMethodDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IShippingMethodQueryRequest {
  id?: number, // ID
}

export interface IShippingMethodUpdateRequest {
  id: number, // ID
  name: string, // 配送方式名称,如:顺丰标快
  code: string, // 承运商/运费模板标识
  description: string, // 配送说明
  status: number, // 状态:0=停用,1=启用
  sort: number, // 排序
}

export interface IShippingMethodDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IShippingMethodQueryResponse {
  currentPage: number, // 当前页码
  data: IShippingMethodResponse[], // 数据列表
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

export interface IShippingMethodResponse {
  id: number, // ID
  name: string, // 配送方式名称,如:顺丰标快
  code: string, // 承运商/运费模板标识
  description: string, // 配送说明
  status: number, // 状态:0=停用,1=启用
  sort: number, // 排序
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

