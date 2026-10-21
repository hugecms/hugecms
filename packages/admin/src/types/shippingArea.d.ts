export interface IShippingAreaCreateRequest {
  shippingId: number, // 配送方式ID
  name: string, // 区域名称,如:华东地区
  feeConfig: string, // 运价配置(JSON):首重/续重/首费/续费/固定运费等
  status: number, // 状态:0=停用,1=启用
}

export interface IShippingAreaDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IShippingAreaQueryRequest {
  id?: number, // ID
  shippingId?: number, // 配送方式ID
}

export interface IShippingAreaUpdateRequest {
  id: number, // ID
  shippingId: number, // 配送方式ID
  name: string, // 区域名称,如:华东地区
  feeConfig: string, // 运价配置(JSON):首重/续重/首费/续费/固定运费等
  status: number, // 状态:0=停用,1=启用
}

export interface IShippingAreaDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IShippingAreaQueryResponse {
  currentPage: number, // 当前页码
  data: IShippingAreaResponse[], // 数据列表
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

export interface IShippingAreaResponse {
  id: number, // ID
  shippingId: number, // 配送方式ID
  name: string, // 区域名称,如:华东地区
  feeConfig: string, // 运价配置(JSON):首重/续重/首费/续费/固定运费等
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

