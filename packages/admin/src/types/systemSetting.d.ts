export interface ISystemSettingCreateRequest {
  group: string, // 配置分组:basic=基础,seo,upload
  key: string, // 配置键,如:site_name
  title: string, // 配置名称,用于后台表单展示
  value: string, // 配置值
  type: string, // 值类型:text,number,select,radio
}

export interface ISystemSettingDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemSettingQueryRequest {
  id?: number, // ID
  group?: string, // 配置分组:basic=基础,seo,upload
  key?: string, // 配置键,如:site_name
}

export interface ISystemSettingUpdateRequest {
  id: number, // ID
  group: string, // 配置分组:basic=基础,seo,upload
  key: string, // 配置键,如:site_name
  title: string, // 配置名称,用于后台表单展示
  value: string, // 配置值
  type: string, // 值类型:text,number,select,radio
}

export interface ISystemSettingDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemSettingQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemSettingResponse[], // 数据列表
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

export interface ISystemSettingResponse {
  id: number, // ID
  group: string, // 配置分组:basic=基础,seo,upload
  key: string, // 配置键,如:site_name
  title: string, // 配置名称,用于后台表单展示
  value: string, // 配置值
  type: string, // 值类型:text,number,select,radio
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

