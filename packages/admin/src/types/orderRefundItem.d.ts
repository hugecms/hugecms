export interface IOrderRefundItemCreateRequest {
  refundId: number, // 退款单ID
  orderItemId: number, // 订单明细ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,关联product_skus
  quantity: number, // 退货数量
  amount: string, // 退款金额
  createTime: string, // 创建时间
}

export interface IOrderRefundItemDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderRefundItemQueryRequest {
  id?: number, // ID
  refundId?: number, // 退款单ID
  orderItemId?: number, // 订单明细ID
}

export interface IOrderRefundItemUpdateRequest {
  id: number, // ID
  refundId: number, // 退款单ID
  orderItemId: number, // 订单明细ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,关联product_skus
  quantity: number, // 退货数量
  amount: string, // 退款金额
  createTime: string, // 创建时间
}

export interface IOrderRefundItemDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderRefundItemQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderRefundItemResponse[], // 数据列表
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

export interface IOrderRefundItemResponse {
  id: number, // ID
  refundId: number, // 退款单ID
  orderItemId: number, // 订单明细ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,关联product_skus
  quantity: number, // 退货数量
  amount: string, // 退款金额
  createTime: string, // 创建时间
}

