export interface IOrderInvoiceCreateRequest {
  orderId: number, // 订单ID
  type: number, // 类型:1=电子普通发票,2=纸质普通发票,3=增值税专用发票
  title: string, // 发票抬头
  taxNo: string, // 纳税人识别号,个人抬头为空
  amount: string, // 开票金额
  status: number, // 状态:0=待开票,1=已开票,2=已作废
  invoiceNo: string, // 发票号码
  invoicedAt: string, // 开票时间
  remark: string, // 备注
}

export interface IOrderInvoiceDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderInvoiceQueryRequest {
  id?: number, // ID
  orderId?: number, // 订单ID
}

export interface IOrderInvoiceUpdateRequest {
  id: number, // ID
  orderId: number, // 订单ID
  type: number, // 类型:1=电子普通发票,2=纸质普通发票,3=增值税专用发票
  title: string, // 发票抬头
  taxNo: string, // 纳税人识别号,个人抬头为空
  amount: string, // 开票金额
  status: number, // 状态:0=待开票,1=已开票,2=已作废
  invoiceNo: string, // 发票号码
  invoicedAt: string, // 开票时间
  remark: string, // 备注
}

export interface IOrderInvoiceDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderInvoiceQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderInvoiceResponse[], // 数据列表
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

export interface IOrderInvoiceResponse {
  id: number, // ID
  orderId: number, // 订单ID
  type: number, // 类型:1=电子普通发票,2=纸质普通发票,3=增值税专用发票
  title: string, // 发票抬头
  taxNo: string, // 纳税人识别号,个人抬头为空
  amount: string, // 开票金额
  status: number, // 状态:0=待开票,1=已开票,2=已作废
  invoiceNo: string, // 发票号码
  invoicedAt: string, // 开票时间
  remark: string, // 备注
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

