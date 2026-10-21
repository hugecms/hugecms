export interface IProductSkuCreateRequest {
  productId: number, // 商品ID
  specs: string, // 规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}
  price: string, // 售价
  stock: number, // 库存
  lockedStock: number, // 锁定库存(下单未支付),可售=stock-locked_stock
  status: number, // 状态:0=禁用,1=启用
}

export interface IProductSkuDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IProductSkuQueryRequest {
  id?: number, // ID
  productId?: number, // 商品ID
}

export interface IProductSkuUpdateRequest {
  id: number, // ID
  productId: number, // 商品ID
  specs: string, // 规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}
  price: string, // 售价
  stock: number, // 库存
  lockedStock: number, // 锁定库存(下单未支付),可售=stock-locked_stock
  status: number, // 状态:0=禁用,1=启用
}

export interface IProductSkuDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IProductSkuQueryResponse {
  currentPage: number, // 当前页码
  data: IProductSkuResponse[], // 数据列表
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

export interface IProductSkuResponse {
  id: number, // ID
  productId: number, // 商品ID
  specs: string, // 规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}
  price: string, // 售价
  stock: number, // 库存
  lockedStock: number, // 锁定库存(下单未支付),可售=stock-locked_stock
  status: number, // 状态:0=禁用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

