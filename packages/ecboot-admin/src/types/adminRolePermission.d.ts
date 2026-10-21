export interface IAdminRolePermissionCreateRequest {
  roleId: number, // 角色ID
  permissionId: number, // 权限ID
  createTime: string, // 创建时间
}

export interface IAdminRolePermissionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IAdminRolePermissionQueryRequest {
  roleId?: number, // 角色ID
  permissionId?: number, // 权限ID
}

export interface IAdminRolePermissionUpdateRequest {
  roleId: number, // 角色ID
  permissionId: number, // 权限ID
  createTime: string, // 创建时间
}

export interface IAdminRolePermissionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IAdminRolePermissionQueryResponse {
  currentPage: number, // 当前页码
  data: IAdminRolePermissionResponse[], // 数据列表
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

export interface IAdminRolePermissionResponse {
  roleId: number, // 角色ID
  permissionId: number, // 权限ID
  createTime: string, // 创建时间
}

