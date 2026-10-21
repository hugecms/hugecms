export interface IOrderCartCreateRequest {
  userId: number, // 用户ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格
  productName: string, // 商品名称快照
  image: string, // 商品图快照
  price: string, // 加购时价格快照
  quantity: number, // 数量
  checked: number, // 结算勾选:0=否,1=是
}

export interface IOrderCartDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderCartQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  productId?: number, // 商品ID
  skuId?: number, // SKU ID,0=单规格
}

export interface IOrderCartUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格
  productName: string, // 商品名称快照
  image: string, // 商品图快照
  price: string, // 加购时价格快照
  quantity: number, // 数量
  checked: number, // 结算勾选:0=否,1=是
}

export interface IOrderCartDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderCartQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderCartResponse[], // 数据列表
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

export interface IOrderCartResponse {
  id: number, // ID
  userId: number, // 用户ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格
  productName: string, // 商品名称快照
  image: string, // 商品图快照
  price: string, // 加购时价格快照
  quantity: number, // 数量
  checked: number, // 结算勾选:0=否,1=是
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

