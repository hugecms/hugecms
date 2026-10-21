export interface IProductCreateRequest {
  categoryId: number, // 分类ID
  brandId: number, // 品牌ID
  typeId: number, // 商品类型ID,关联属性体系
  name: string, // 商品名称
  sn: string, // 商品编码
  subtitle: string, // 副标题
  cover: string, // 主图
  images: string, // 图册(JSON数组)
  content: string, // 商品详情
  price: string, // 售价,多规格时为最低规格价
  marketPrice: string, // 市场价
  stock: number, // 库存,单规格商品有效
  lockedStock: number, // 锁定库存(下单未支付),可售=stock-locked_stock
  sales: number, // 销量
  status: number, // 状态:0=下架,1=上架
  sort: number, // 排序
}

export interface IProductDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IProductQueryRequest {
  id?: number, // ID
  categoryId?: number, // 分类ID
  brandId?: number, // 品牌ID
  sn?: string, // 商品编码
  status?: number, // 状态:0=下架,1=上架
}

export interface IProductUpdateRequest {
  id: number, // ID
  categoryId: number, // 分类ID
  brandId: number, // 品牌ID
  typeId: number, // 商品类型ID,关联属性体系
  name: string, // 商品名称
  sn: string, // 商品编码
  subtitle: string, // 副标题
  cover: string, // 主图
  images: string, // 图册(JSON数组)
  content: string, // 商品详情
  price: string, // 售价,多规格时为最低规格价
  marketPrice: string, // 市场价
  stock: number, // 库存,单规格商品有效
  lockedStock: number, // 锁定库存(下单未支付),可售=stock-locked_stock
  sales: number, // 销量
  status: number, // 状态:0=下架,1=上架
  sort: number, // 排序
}

export interface IProductDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IProductQueryResponse {
  currentPage: number, // 当前页码
  data: IProductResponse[], // 数据列表
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

export interface IProductResponse {
  id: number, // ID
  categoryId: number, // 分类ID
  brandId: number, // 品牌ID
  typeId: number, // 商品类型ID,关联属性体系
  name: string, // 商品名称
  sn: string, // 商品编码
  subtitle: string, // 副标题
  cover: string, // 主图
  images: string, // 图册(JSON数组)
  content: string, // 商品详情
  price: string, // 售价,多规格时为最低规格价
  marketPrice: string, // 市场价
  stock: number, // 库存,单规格商品有效
  lockedStock: number, // 锁定库存(下单未支付),可售=stock-locked_stock
  sales: number, // 销量
  status: number, // 状态:0=下架,1=上架
  sort: number, // 排序
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

