export interface IProductReviewCreateRequest {
  orderId: number, // 订单ID,已购验证依据
  orderItemId: number, // 订单明细ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格
  userId: number, // 评价用户ID
  rating: number, // 评分:1-5星
  content: string, // 评价内容
  images: string, // 晒图(JSON数组)
  appendContent: string, // 追评内容
  appendedAt: string, // 追评时间
  reply: string, // 商家回复
  repliedAt: string, // 商家回复时间
  isAnonymous: number, // 匿名:0=否,1=是
  status: number, // 状态:0=待审,1=通过,2=拒绝
}

export interface IProductReviewDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IProductReviewQueryRequest {
  id?: number, // ID
  orderItemId?: number, // 订单明细ID
  productId?: number, // 商品ID
  userId?: number, // 评价用户ID
  status?: number, // 状态:0=待审,1=通过,2=拒绝
}

export interface IProductReviewUpdateRequest {
  id: number, // ID
  orderId: number, // 订单ID,已购验证依据
  orderItemId: number, // 订单明细ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格
  userId: number, // 评价用户ID
  rating: number, // 评分:1-5星
  content: string, // 评价内容
  images: string, // 晒图(JSON数组)
  appendContent: string, // 追评内容
  appendedAt: string, // 追评时间
  reply: string, // 商家回复
  repliedAt: string, // 商家回复时间
  isAnonymous: number, // 匿名:0=否,1=是
  status: number, // 状态:0=待审,1=通过,2=拒绝
}

export interface IProductReviewDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IProductReviewQueryResponse {
  currentPage: number, // 当前页码
  data: IProductReviewResponse[], // 数据列表
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

export interface IProductReviewResponse {
  id: number, // ID
  orderId: number, // 订单ID,已购验证依据
  orderItemId: number, // 订单明细ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格
  userId: number, // 评价用户ID
  rating: number, // 评分:1-5星
  content: string, // 评价内容
  images: string, // 晒图(JSON数组)
  appendContent: string, // 追评内容
  appendedAt: string, // 追评时间
  reply: string, // 商家回复
  repliedAt: string, // 商家回复时间
  isAnonymous: number, // 匿名:0=否,1=是
  status: number, // 状态:0=待审,1=通过,2=拒绝
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

