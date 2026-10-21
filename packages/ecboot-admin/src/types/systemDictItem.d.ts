export interface ISystemDictItemCreateRequest {
  dictId: number, // 字典类型ID
  value: string, // 字典值
  label: string, // 字典标签
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface ISystemDictItemDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemDictItemQueryRequest {
  id?: number, // ID
  dictId?: number, // 字典类型ID
}

export interface ISystemDictItemUpdateRequest {
  id: number, // ID
  dictId: number, // 字典类型ID
  value: string, // 字典值
  label: string, // 字典标签
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface ISystemDictItemDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemDictItemQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemDictItemResponse[], // 数据列表
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

export interface ISystemDictItemResponse {
  id: number, // ID
  dictId: number, // 字典类型ID
  value: string, // 字典值
  label: string, // 字典标签
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

