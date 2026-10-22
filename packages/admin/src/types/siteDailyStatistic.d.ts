export interface ISiteDailyStatisticCreateRequest {
  statDate: string, // 统计日期
  pv: number, // 页面浏览量
  uv: number, // 独立访客数
  newUsers: number, // 新增注册用户数
  orderCount: number, // 下单笔数
  orderAmount: string, // 下单金额
  payCount: number, // 支付笔数
  payAmount: string, // 支付金额
  refundCount: number, // 退款笔数
  refundAmount: string, // 退款金额
}

export interface ISiteDailyStatisticDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISiteDailyStatisticQueryRequest {
  id?: number, // ID
  statDate?: string, // 统计日期
}

export interface ISiteDailyStatisticUpdateRequest {
  id: number, // ID
  statDate: string, // 统计日期
  pv: number, // 页面浏览量
  uv: number, // 独立访客数
  newUsers: number, // 新增注册用户数
  orderCount: number, // 下单笔数
  orderAmount: string, // 下单金额
  payCount: number, // 支付笔数
  payAmount: string, // 支付金额
  refundCount: number, // 退款笔数
  refundAmount: string, // 退款金额
}

export interface ISiteDailyStatisticDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISiteDailyStatisticQueryResponse {
  currentPage: number, // 当前页码
  data: ISiteDailyStatisticResponse[], // 数据列表
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

export interface ISiteDailyStatisticResponse {
  id: number, // ID
  statDate: string, // 统计日期
  pv: number, // 页面浏览量
  uv: number, // 独立访客数
  newUsers: number, // 新增注册用户数
  orderCount: number, // 下单笔数
  orderAmount: string, // 下单金额
  payCount: number, // 支付笔数
  payAmount: string, // 支付金额
  refundCount: number, // 退款笔数
  refundAmount: string, // 退款金额
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

