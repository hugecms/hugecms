export interface IProductStockLogCreateRequest {
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格商品
  changeType: string, // 变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整
  changeQuantity: number, // 变动数量,正数入库负数出库
  beforeStock: number, // 变动前库存
  afterStock: number, // 变动后库存
  relatedId: number, // 关联单据ID(订单/退款单)
  userType: string, // 操作账户类型:admin,user,system
  userId: number, // 操作人ID
  remark: string, // 备注
  createTime: string, // 创建时间
}

export interface IProductStockLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IProductStockLogQueryRequest {
  id?: number, // ID
  productId?: number, // 商品ID
  skuId?: number, // SKU ID,0=单规格商品
  relatedId?: number, // 关联单据ID(订单/退款单)
}

export interface IProductStockLogUpdateRequest {
  id: number, // ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格商品
  changeType: string, // 变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整
  changeQuantity: number, // 变动数量,正数入库负数出库
  beforeStock: number, // 变动前库存
  afterStock: number, // 变动后库存
  relatedId: number, // 关联单据ID(订单/退款单)
  userType: string, // 操作账户类型:admin,user,system
  userId: number, // 操作人ID
  remark: string, // 备注
  createTime: string, // 创建时间
}

export interface IProductStockLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IProductStockLogQueryResponse {
  currentPage: number, // 当前页码
  data: IProductStockLogResponse[], // 数据列表
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

export interface IProductStockLogResponse {
  id: number, // ID
  productId: number, // 商品ID
  skuId: number, // SKU ID,0=单规格商品
  changeType: string, // 变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整
  changeQuantity: number, // 变动数量,正数入库负数出库
  beforeStock: number, // 变动前库存
  afterStock: number, // 变动后库存
  relatedId: number, // 关联单据ID(订单/退款单)
  userType: string, // 操作账户类型:admin,user,system
  userId: number, // 操作人ID
  remark: string, // 备注
  createTime: string, // 创建时间
}

