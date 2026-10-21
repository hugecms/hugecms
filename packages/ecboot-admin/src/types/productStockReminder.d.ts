export interface IProductStockReminderCreateRequest {
  userId: number, // 登记用户ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格/不限规格
  status: number, // 状态:0=待提醒,1=已提醒,2=已取消
  notifiedAt: string, // 提醒时间
}

export interface IProductStockReminderDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IProductStockReminderQueryRequest {
  id?: number, // ID
  userId?: number, // 登记用户ID
  productId?: number, // 商品ID
  skuId?: number, // SKU ID,0=单规格/不限规格
  status?: number, // 状态:0=待提醒,1=已提醒,2=已取消
}

export interface IProductStockReminderUpdateRequest {
  id: number, // ID
  userId: number, // 登记用户ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格/不限规格
  status: number, // 状态:0=待提醒,1=已提醒,2=已取消
  notifiedAt: string, // 提醒时间
}

export interface IProductStockReminderDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IProductStockReminderQueryResponse {
  currentPage: number, // 当前页码
  data: IProductStockReminderResponse[], // 数据列表
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

export interface IProductStockReminderResponse {
  id: number, // ID
  userId: number, // 登记用户ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格/不限规格
  status: number, // 状态:0=待提醒,1=已提醒,2=已取消
  notifiedAt: string, // 提醒时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

