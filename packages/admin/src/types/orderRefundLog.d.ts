export interface IOrderRefundLogCreateRequest {
  refundId: number, // 退款单ID
  operatorType: string, // 操作账户类型:admin=后台,user=前台,system=系统
  operatorId: number, // 操作人ID
  operator: string, // 操作人名称快照
  content: string, // 协商内容/操作说明
  images: string, // 凭证图片(JSON数组)
  createTime: string, // 创建时间
}

export interface IOrderRefundLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderRefundLogQueryRequest {
  id?: number, // ID
  refundId?: number, // 退款单ID
}

export interface IOrderRefundLogUpdateRequest {
  id: number, // ID
  refundId: number, // 退款单ID
  operatorType: string, // 操作账户类型:admin=后台,user=前台,system=系统
  operatorId: number, // 操作人ID
  operator: string, // 操作人名称快照
  content: string, // 协商内容/操作说明
  images: string, // 凭证图片(JSON数组)
  createTime: string, // 创建时间
}

export interface IOrderRefundLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderRefundLogQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderRefundLogResponse[], // 数据列表
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

export interface IOrderRefundLogResponse {
  id: number, // ID
  refundId: number, // 退款单ID
  operatorType: string, // 操作账户类型:admin=后台,user=前台,system=系统
  operatorId: number, // 操作人ID
  operator: string, // 操作人名称快照
  content: string, // 协商内容/操作说明
  images: string, // 凭证图片(JSON数组)
  createTime: string, // 创建时间
}

