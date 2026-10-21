export interface IOrderRefundCreateRequest {
  refundNo: string, // 退款单号
  orderId: number, // 订单ID
  paymentId: number, // 关联支付单ID(payments 表)
  type: number, // 售后类型:1=仅退款,2=退货退款
  amount: string, // 退款金额
  reason: string, // 退款原因
  adminNote: string, // 商家审核备注/拒绝原因
  images: string, // 申请凭证图片(JSON数组)
  returnExpressCompany: string, // 退货物流公司,退货退款时填写
  returnExpressNo: string, // 退货物流单号
  status: number, // 状态:0=待审核,1=退款中,2=已退款,3=已拒绝
  refundedAt: string, // 退款完成时间
}

export interface IOrderRefundDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderRefundQueryRequest {
  id?: number, // ID
  refundNo?: string, // 退款单号
  orderId?: number, // 订单ID
}

export interface IOrderRefundUpdateRequest {
  id: number, // ID
  refundNo: string, // 退款单号
  orderId: number, // 订单ID
  paymentId: number, // 关联支付单ID(payments 表)
  type: number, // 售后类型:1=仅退款,2=退货退款
  amount: string, // 退款金额
  reason: string, // 退款原因
  adminNote: string, // 商家审核备注/拒绝原因
  images: string, // 申请凭证图片(JSON数组)
  returnExpressCompany: string, // 退货物流公司,退货退款时填写
  returnExpressNo: string, // 退货物流单号
  status: number, // 状态:0=待审核,1=退款中,2=已退款,3=已拒绝
  refundedAt: string, // 退款完成时间
}

export interface IOrderRefundDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderRefundQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderRefundResponse[], // 数据列表
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

export interface IOrderRefundResponse {
  id: number, // ID
  refundNo: string, // 退款单号
  orderId: number, // 订单ID
  paymentId: number, // 关联支付单ID(payments 表)
  type: number, // 售后类型:1=仅退款,2=退货退款
  amount: string, // 退款金额
  reason: string, // 退款原因
  adminNote: string, // 商家审核备注/拒绝原因
  images: string, // 申请凭证图片(JSON数组)
  returnExpressCompany: string, // 退货物流公司,退货退款时填写
  returnExpressNo: string, // 退货物流单号
  status: number, // 状态:0=待审核,1=退款中,2=已退款,3=已拒绝
  refundedAt: string, // 退款完成时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

