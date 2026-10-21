export interface IMarketingFlashSaleLogCreateRequest {
  flashSaleItemId: number, // 秒杀商品ID,关联marketing_flash_sale_items
  orderId: number, // 关联订单ID,扣减与回补均关联订单
  changeQuantity: number, // 变动数量,正数扣减,负数回补
  beforeSoldStock: number, // 变动前已售量
  afterSoldStock: number, // 变动后已售量
  createTime: string, // 创建时间
}

export interface IMarketingFlashSaleLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingFlashSaleLogQueryRequest {
  id?: number, // ID
  flashSaleItemId?: number, // 秒杀商品ID,关联marketing_flash_sale_items
  orderId?: number, // 关联订单ID,扣减与回补均关联订单
}

export interface IMarketingFlashSaleLogUpdateRequest {
  id: number, // ID
  flashSaleItemId: number, // 秒杀商品ID,关联marketing_flash_sale_items
  orderId: number, // 关联订单ID,扣减与回补均关联订单
  changeQuantity: number, // 变动数量,正数扣减,负数回补
  beforeSoldStock: number, // 变动前已售量
  afterSoldStock: number, // 变动后已售量
  createTime: string, // 创建时间
}

export interface IMarketingFlashSaleLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingFlashSaleLogQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingFlashSaleLogResponse[], // 数据列表
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

export interface IMarketingFlashSaleLogResponse {
  id: number, // ID
  flashSaleItemId: number, // 秒杀商品ID,关联marketing_flash_sale_items
  orderId: number, // 关联订单ID,扣减与回补均关联订单
  changeQuantity: number, // 变动数量,正数扣减,负数回补
  beforeSoldStock: number, // 变动前已售量
  afterSoldStock: number, // 变动后已售量
  createTime: string, // 创建时间
}

