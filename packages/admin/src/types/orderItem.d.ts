export interface IOrderItemCreateRequest {
  orderId: number, // 订单ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,关联product_skus
  productName: string, // 商品名称快照
  skuSpecs: string, // 规格快照
  skuNo: string, // SKU编号快照
  skuName: string, // SKU名称快照
  skuImage: string, // SKU图片快照
  image: string, // 商品图快照
  price: string, // 成交单价
  quantity: number, // 数量
  totalAmount: string, // 小计
  createTime: string, // 创建时间
}

export interface IOrderItemDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderItemQueryRequest {
  id?: number, // ID
  orderId?: number, // 订单ID
  productId?: number, // 商品ID
}

export interface IOrderItemUpdateRequest {
  id: number, // ID
  orderId: number, // 订单ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,关联product_skus
  productName: string, // 商品名称快照
  skuSpecs: string, // 规格快照
  skuNo: string, // SKU编号快照
  skuName: string, // SKU名称快照
  skuImage: string, // SKU图片快照
  image: string, // 商品图快照
  price: string, // 成交单价
  quantity: number, // 数量
  totalAmount: string, // 小计
  createTime: string, // 创建时间
}

export interface IOrderItemDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderItemQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderItemResponse[], // 数据列表
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

export interface IOrderItemResponse {
  id: number, // ID
  orderId: number, // 订单ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,关联product_skus
  productName: string, // 商品名称快照
  skuSpecs: string, // 规格快照
  skuNo: string, // SKU编号快照
  skuName: string, // SKU名称快照
  skuImage: string, // SKU图片快照
  image: string, // 商品图快照
  price: string, // 成交单价
  quantity: number, // 数量
  totalAmount: string, // 小计
  createTime: string, // 创建时间
}

