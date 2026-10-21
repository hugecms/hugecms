export interface IMarketingBargainRecordCreateRequest {
  bargainNo: string, // 砍价单号,全局唯一
  itemId: number, // 砍价场次商品ID
  userId: number, // 发起人用户ID
  currentPrice: string, // 当前价,逐刀递减且&gt;=floor_price由条件更新保证
  cutCount: number, // 已砍刀数
  status: number, // 状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消
  expireTime: string, // 砍价截止时间,超时扫描依据
  successTime: string, // 到底价时间
  orderNo: string, // 成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束
}

export interface IMarketingBargainRecordDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingBargainRecordQueryRequest {
  id?: number, // ID
  bargainNo?: string, // 砍价单号,全局唯一
  userId?: number, // 发起人用户ID
  status?: number, // 状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消
  expireTime?: string, // 砍价截止时间,超时扫描依据
  orderNo?: string, // 成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束
}

export interface IMarketingBargainRecordUpdateRequest {
  id: number, // ID
  bargainNo: string, // 砍价单号,全局唯一
  itemId: number, // 砍价场次商品ID
  userId: number, // 发起人用户ID
  currentPrice: string, // 当前价,逐刀递减且&gt;=floor_price由条件更新保证
  cutCount: number, // 已砍刀数
  status: number, // 状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消
  expireTime: string, // 砍价截止时间,超时扫描依据
  successTime: string, // 到底价时间
  orderNo: string, // 成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束
}

export interface IMarketingBargainRecordDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingBargainRecordQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingBargainRecordResponse[], // 数据列表
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

export interface IMarketingBargainRecordResponse {
  id: number, // ID
  bargainNo: string, // 砍价单号,全局唯一
  itemId: number, // 砍价场次商品ID
  userId: number, // 发起人用户ID
  currentPrice: string, // 当前价,逐刀递减且&gt;=floor_price由条件更新保证
  cutCount: number, // 已砍刀数
  status: number, // 状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消
  expireTime: string, // 砍价截止时间,超时扫描依据
  successTime: string, // 到底价时间
  orderNo: string, // 成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

