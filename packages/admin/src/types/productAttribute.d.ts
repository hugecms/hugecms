export interface IProductAttributeCreateRequest {
  typeId: number, // 所属商品类型ID
  name: string, // 属性名,如:颜色
  inputType: number, // 录入方式:1=手工输入,2=列表选择
  values: string, // 可选值列表(逗号分隔),列表选择时用
  isSpec: number, // 是否规格属性:0=否,1=是(参与SKU组合)
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface IProductAttributeDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IProductAttributeQueryRequest {
  id?: number, // ID
  typeId?: number, // 所属商品类型ID
}

export interface IProductAttributeUpdateRequest {
  id: number, // ID
  typeId: number, // 所属商品类型ID
  name: string, // 属性名,如:颜色
  inputType: number, // 录入方式:1=手工输入,2=列表选择
  values: string, // 可选值列表(逗号分隔),列表选择时用
  isSpec: number, // 是否规格属性:0=否,1=是(参与SKU组合)
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface IProductAttributeDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IProductAttributeQueryResponse {
  currentPage: number, // 当前页码
  data: IProductAttributeResponse[], // 数据列表
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

export interface IProductAttributeResponse {
  id: number, // ID
  typeId: number, // 所属商品类型ID
  name: string, // 属性名,如:颜色
  inputType: number, // 录入方式:1=手工输入,2=列表选择
  values: string, // 可选值列表(逗号分隔),列表选择时用
  isSpec: number, // 是否规格属性:0=否,1=是(参与SKU组合)
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

