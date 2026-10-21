export interface ISystemRiskRecordCreateRequest {
  userId: number, // 触发用户ID
  ruleId: number, // 命中规则ID,关联system_risk_rules
  objectType: number, // 对象类型:1=订单,2=优惠券,3=提现,4=售后,5=裂变活动(助力/帮砍)
  objectNo: string, // 对象单号,如提现单号/订单号
  action: number, // 处置快照:1=拦截,2=标记放行
  appealStatus: number, // 申诉状态:0=无,1=申诉中,2=申诉通过,3=申诉驳回
  appealNote: string, // 申诉理由
  handleNote: string, // 申诉处理说明
  handledAt: string, // 申诉处理时间
  remark: string, // 命中详情,如规则参数与实际值
}

export interface ISystemRiskRecordDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemRiskRecordQueryRequest {
  id?: number, // ID
  userId?: number, // 触发用户ID
  ruleId?: number, // 命中规则ID,关联system_risk_rules
  objectType?: number, // 对象类型:1=订单,2=优惠券,3=提现,4=售后,5=裂变活动(助力/帮砍)
  objectNo?: string, // 对象单号,如提现单号/订单号
}

export interface ISystemRiskRecordUpdateRequest {
  id: number, // ID
  userId: number, // 触发用户ID
  ruleId: number, // 命中规则ID,关联system_risk_rules
  objectType: number, // 对象类型:1=订单,2=优惠券,3=提现,4=售后,5=裂变活动(助力/帮砍)
  objectNo: string, // 对象单号,如提现单号/订单号
  action: number, // 处置快照:1=拦截,2=标记放行
  appealStatus: number, // 申诉状态:0=无,1=申诉中,2=申诉通过,3=申诉驳回
  appealNote: string, // 申诉理由
  handleNote: string, // 申诉处理说明
  handledAt: string, // 申诉处理时间
  remark: string, // 命中详情,如规则参数与实际值
}

export interface ISystemRiskRecordDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemRiskRecordQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemRiskRecordResponse[], // 数据列表
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

export interface ISystemRiskRecordResponse {
  id: number, // ID
  userId: number, // 触发用户ID
  ruleId: number, // 命中规则ID,关联system_risk_rules
  objectType: number, // 对象类型:1=订单,2=优惠券,3=提现,4=售后,5=裂变活动(助力/帮砍)
  objectNo: string, // 对象单号,如提现单号/订单号
  action: number, // 处置快照:1=拦截,2=标记放行
  appealStatus: number, // 申诉状态:0=无,1=申诉中,2=申诉通过,3=申诉驳回
  appealNote: string, // 申诉理由
  handleNote: string, // 申诉处理说明
  handledAt: string, // 申诉处理时间
  remark: string, // 命中详情,如规则参数与实际值
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

