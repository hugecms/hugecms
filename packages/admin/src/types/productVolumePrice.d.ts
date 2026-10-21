export interface IProductVolumePriceCreateRequest {
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=商品统一价
  minQuantity: number, // 起购数量
  price: string, // 单价
}

export interface IProductVolumePriceDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IProductVolumePriceQueryRequest {
  id?: number, // ID
  productId?: number, // 商品ID
  skuId?: number, // SKU ID,0=商品统一价
  minQuantity?: number, // 起购数量
}

export interface IProductVolumePriceUpdateRequest {
  id: number, // ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=商品统一价
  minQuantity: number, // 起购数量
  price: string, // 单价
}

export interface IProductVolumePriceDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IProductVolumePriceQueryResponse {
  currentPage: number, // 当前页码
  data: IProductVolumePriceResponse[], // 数据列表
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

export interface IProductVolumePriceResponse {
  id: number, // ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=商品统一价
  minQuantity: number, // 起购数量
  price: string, // 单价
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

