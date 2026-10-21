export interface IAdminPermissionCreateRequest {
  parentId: number, // 父级ID,0=顶级
  name: string, // 权限名称
  code: string, // 权限标识,如 user:list
  type: number, // 类型:1=菜单,2=按钮
  path: string, // 路由地址
  icon: string, // 菜单图标
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface IAdminPermissionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IAdminPermissionQueryRequest {
  id?: number, // ID
  parentId?: number, // 父级ID,0=顶级
  code?: string, // 权限标识,如 user:list
}

export interface IAdminPermissionUpdateRequest {
  id: number, // ID
  parentId: number, // 父级ID,0=顶级
  name: string, // 权限名称
  code: string, // 权限标识,如 user:list
  type: number, // 类型:1=菜单,2=按钮
  path: string, // 路由地址
  icon: string, // 菜单图标
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface IAdminPermissionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IAdminPermissionQueryResponse {
  currentPage: number, // 当前页码
  data: IAdminPermissionResponse[], // 数据列表
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

export interface IAdminPermissionResponse {
  id: number, // ID
  parentId: number, // 父级ID,0=顶级
  name: string, // 权限名称
  code: string, // 权限标识,如 user:list
  type: number, // 类型:1=菜单,2=按钮
  path: string, // 路由地址
  icon: string, // 菜单图标
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

