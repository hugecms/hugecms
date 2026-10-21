export interface IFormCreateRequest {
  name: string, // 表单名称,如:活动报名
  description: string, // 表单描述
  successMessage: string, // 提交成功提示语
  startTime: string, // 可提交开始时间,空=不限
  endTime: string, // 可提交结束时间,空=不限
  needLogin: number, // 需要登录:0=否,1=是
  maxSubmits: number, // 每人限提次数,0=不限
  status: number, // 状态:0=停用,1=启用
}

export interface IFormDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IFormQueryRequest {
  id?: number, // ID
}

export interface IFormUpdateRequest {
  id: number, // ID
  name: string, // 表单名称,如:活动报名
  description: string, // 表单描述
  successMessage: string, // 提交成功提示语
  startTime: string, // 可提交开始时间,空=不限
  endTime: string, // 可提交结束时间,空=不限
  needLogin: number, // 需要登录:0=否,1=是
  maxSubmits: number, // 每人限提次数,0=不限
  status: number, // 状态:0=停用,1=启用
}

export interface IFormDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IFormQueryResponse {
  currentPage: number, // 当前页码
  data: IFormResponse[], // 数据列表
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

export interface IFormResponse {
  id: number, // ID
  name: string, // 表单名称,如:活动报名
  description: string, // 表单描述
  successMessage: string, // 提交成功提示语
  startTime: string, // 可提交开始时间,空=不限
  endTime: string, // 可提交结束时间,空=不限
  needLogin: number, // 需要登录:0=否,1=是
  maxSubmits: number, // 每人限提次数,0=不限
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

