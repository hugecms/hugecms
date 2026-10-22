export interface IMarketingDistributionCommissionCreateRequest {
  distributorId: number, // 分销员用户ID
  userId: number, // 购买用户ID
  orderId: number, // 订单ID
  orderItemId: number, // 订单明细ID,空=整单计佣
  level: number, // 层级:1=一级,2=二级
  orderAmount: string, // 计佣基数(商品实付)
  rate: string, // 佣金比例(%),如5.00表示5%
  amount: string, // 佣金金额,冲销记录为负值
  status: number, // 状态:0=待结算,1=已结算(负额冲销记录亦为此态),2=已失效(保护期内退款),3=已冲销(结算后退款/原记录侧)
  settledAt: string, // 实际结算入账时间
  settleTime: string, // 应结算时间=订单完成时间+保护期,到期由结算流程入账
  reversalRecordId: number, // 冲销记录ID,0=未被冲销,原记录侧回指负额冲销记录
  reversalOfId: number, // 原佣金记录ID,NULL=本记录为原始计佣记录,冲销记录回指原记录
}

export interface IMarketingDistributionCommissionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingDistributionCommissionQueryRequest {
  id?: number, // ID
  distributorId?: number, // 分销员用户ID
  orderId?: number, // 订单ID
  status?: number, // 状态:0=待结算,1=已结算(负额冲销记录亦为此态),2=已失效(保护期内退款),3=已冲销(结算后退款/原记录侧)
  reversalOfId?: number, // 原佣金记录ID,NULL=本记录为原始计佣记录,冲销记录回指原记录
}

export interface IMarketingDistributionCommissionUpdateRequest {
  id: number, // ID
  distributorId: number, // 分销员用户ID
  userId: number, // 购买用户ID
  orderId: number, // 订单ID
  orderItemId: number, // 订单明细ID,空=整单计佣
  level: number, // 层级:1=一级,2=二级
  orderAmount: string, // 计佣基数(商品实付)
  rate: string, // 佣金比例(%),如5.00表示5%
  amount: string, // 佣金金额,冲销记录为负值
  status: number, // 状态:0=待结算,1=已结算(负额冲销记录亦为此态),2=已失效(保护期内退款),3=已冲销(结算后退款/原记录侧)
  settledAt: string, // 实际结算入账时间
  settleTime: string, // 应结算时间=订单完成时间+保护期,到期由结算流程入账
  reversalRecordId: number, // 冲销记录ID,0=未被冲销,原记录侧回指负额冲销记录
  reversalOfId: number, // 原佣金记录ID,NULL=本记录为原始计佣记录,冲销记录回指原记录
}

export interface IMarketingDistributionCommissionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingDistributionCommissionQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingDistributionCommissionResponse[], // 数据列表
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

export interface IMarketingDistributionCommissionResponse {
  id: number, // ID
  distributorId: number, // 分销员用户ID
  userId: number, // 购买用户ID
  orderId: number, // 订单ID
  orderItemId: number, // 订单明细ID,空=整单计佣
  level: number, // 层级:1=一级,2=二级
  orderAmount: string, // 计佣基数(商品实付)
  rate: string, // 佣金比例(%),如5.00表示5%
  amount: string, // 佣金金额,冲销记录为负值
  status: number, // 状态:0=待结算,1=已结算(负额冲销记录亦为此态),2=已失效(保护期内退款),3=已冲销(结算后退款/原记录侧)
  settledAt: string, // 实际结算入账时间
  settleTime: string, // 应结算时间=订单完成时间+保护期,到期由结算流程入账
  reversalRecordId: number, // 冲销记录ID,0=未被冲销,原记录侧回指负额冲销记录
  reversalOfId: number, // 原佣金记录ID,NULL=本记录为原始计佣记录,冲销记录回指原记录
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

