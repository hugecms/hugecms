export interface ISystemSmsLogCreateRequest {
  phone: string, // 手机号
  template: string, // 模板标识,如:verify_code
  content: string, // 短信内容
  params: string, // 模板变量(JSON)
  gateway: string, // 发送网关:aliyun=阿里云,tencent=腾讯云,qiniu=七牛
  status: number, // 状态:0=发送中,1=成功,2=失败
  messageId: string, // 网关消息ID
  error: string, // 失败原因
  costTime: number, // 耗时(毫秒)
  ip: string, // 触发来源IP
  createTime: string, // 创建时间
}

export interface ISystemSmsLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemSmsLogQueryRequest {
  id?: number, // ID
  phone?: string, // 手机号
  status?: number, // 状态:0=发送中,1=成功,2=失败
  createTime?: string, // 创建时间
}

export interface ISystemSmsLogUpdateRequest {
  id: number, // ID
  phone: string, // 手机号
  template: string, // 模板标识,如:verify_code
  content: string, // 短信内容
  params: string, // 模板变量(JSON)
  gateway: string, // 发送网关:aliyun=阿里云,tencent=腾讯云,qiniu=七牛
  status: number, // 状态:0=发送中,1=成功,2=失败
  messageId: string, // 网关消息ID
  error: string, // 失败原因
  costTime: number, // 耗时(毫秒)
  ip: string, // 触发来源IP
  createTime: string, // 创建时间
}

export interface ISystemSmsLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemSmsLogQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemSmsLogResponse[], // 数据列表
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

export interface ISystemSmsLogResponse {
  id: number, // ID
  phone: string, // 手机号
  template: string, // 模板标识,如:verify_code
  content: string, // 短信内容
  params: string, // 模板变量(JSON)
  gateway: string, // 发送网关:aliyun=阿里云,tencent=腾讯云,qiniu=七牛
  status: number, // 状态:0=发送中,1=成功,2=失败
  messageId: string, // 网关消息ID
  error: string, // 失败原因
  costTime: number, // 耗时(毫秒)
  ip: string, // 触发来源IP
  createTime: string, // 创建时间
}

