export interface IFormFieldCreateRequest {
  formId: number, // 表单ID
  name: string, // 字段名称,如:联系电话
  fieldName: string, // 字段标识,英文,提交数据的键
  type: string, // 类型:text=文本,textarea=多行文本,select=下拉单选,radio=单选,checkbox=多选,number=数字,date=日期,image=图片,file=文件
  options: string, // 选项(JSON),select/radio/checkbox用
  defaultValue: string, // 默认值
  isRequired: number, // 必填:0=否,1=是
  pattern: string, // 正则验证规则
  sort: number, // 排序
  status: number, // 状态:0=隐藏,1=显示
}

export interface IFormFieldDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IFormFieldQueryRequest {
  id?: number, // ID
  formId?: number, // 表单ID
  fieldName?: string, // 字段标识,英文,提交数据的键
}

export interface IFormFieldUpdateRequest {
  id: number, // ID
  formId: number, // 表单ID
  name: string, // 字段名称,如:联系电话
  fieldName: string, // 字段标识,英文,提交数据的键
  type: string, // 类型:text=文本,textarea=多行文本,select=下拉单选,radio=单选,checkbox=多选,number=数字,date=日期,image=图片,file=文件
  options: string, // 选项(JSON),select/radio/checkbox用
  defaultValue: string, // 默认值
  isRequired: number, // 必填:0=否,1=是
  pattern: string, // 正则验证规则
  sort: number, // 排序
  status: number, // 状态:0=隐藏,1=显示
}

export interface IFormFieldDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IFormFieldQueryResponse {
  currentPage: number, // 当前页码
  data: IFormFieldResponse[], // 数据列表
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

export interface IFormFieldResponse {
  id: number, // ID
  formId: number, // 表单ID
  name: string, // 字段名称,如:联系电话
  fieldName: string, // 字段标识,英文,提交数据的键
  type: string, // 类型:text=文本,textarea=多行文本,select=下拉单选,radio=单选,checkbox=多选,number=数字,date=日期,image=图片,file=文件
  options: string, // 选项(JSON),select/radio/checkbox用
  defaultValue: string, // 默认值
  isRequired: number, // 必填:0=否,1=是
  pattern: string, // 正则验证规则
  sort: number, // 排序
  status: number, // 状态:0=隐藏,1=显示
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

