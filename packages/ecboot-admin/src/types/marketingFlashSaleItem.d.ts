export interface IMarketingFlashSaleItemCreateRequest {
  saleId: number, // 秒杀活动ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格
  originalPrice: string, // 原价快照
  price: string, // 秒杀价
  totalStock: number, // 秒杀总量
  soldStock: number, // 已售量,可购=total_stock-sold_stock
  perLimit: number, // 每人限购
  sort: number, // 排序
  status: number, // 状态:0=下架,1=上架
}

export interface IMarketingFlashSaleItemDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingFlashSaleItemQueryRequest {
  id?: number, // ID
  saleId?: number, // 秒杀活动ID
  productId?: number, // 商品ID
  skuId?: number, // SKU ID,0=单规格
  status?: number, // 状态:0=下架,1=上架
}

export interface IMarketingFlashSaleItemUpdateRequest {
  id: number, // ID
  saleId: number, // 秒杀活动ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格
  originalPrice: string, // 原价快照
  price: string, // 秒杀价
  totalStock: number, // 秒杀总量
  soldStock: number, // 已售量,可购=total_stock-sold_stock
  perLimit: number, // 每人限购
  sort: number, // 排序
  status: number, // 状态:0=下架,1=上架
}

export interface IMarketingFlashSaleItemDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingFlashSaleItemQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingFlashSaleItemResponse[], // 数据列表
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

export interface IMarketingFlashSaleItemResponse {
  id: number, // ID
  saleId: number, // 秒杀活动ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格
  originalPrice: string, // 原价快照
  price: string, // 秒杀价
  totalStock: number, // 秒杀总量
  soldStock: number, // 已售量,可购=total_stock-sold_stock
  perLimit: number, // 每人限购
  sort: number, // 排序
  status: number, // 状态:0=下架,1=上架
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

