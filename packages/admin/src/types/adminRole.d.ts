export interface IAdminRoleCreateRequest {
  name: string, // 角色名称
  code: string, // 角色标识
  description: string, // 角色描述
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface IAdminRoleDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IAdminRoleQueryRequest {
  id?: number, // ID
  code?: string, // 角色标识
}

export interface IAdminRoleUpdateRequest {
  id: number, // ID
  name: string, // 角色名称
  code: string, // 角色标识
  description: string, // 角色描述
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface IAdminRoleDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IAdminRoleQueryResponse {
  currentPage: number, // 当前页码
  data: IAdminRoleResponse[], // 数据列表
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

export interface IAdminRoleResponse {
  id: number, // ID
  name: string, // 角色名称
  code: string, // 角色标识
  description: string, // 角色描述
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

