export interface ISystemAppCreateRequest {
  code: string, // 应用标识:web,h5,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios,app_android
  name: string, // 应用名称
  type: string, // 应用类型:web,h5,wechat,app
  appid: string, // 微信类应用AppID,非微信应用为空
  secret: string, // 应用密钥,加密存储
  config: string, // 扩展配置(JSON):token,aes_key,推送参数,API域名,商户号绑定等
  sort: number, // 排序
  status: number, // 状态:0=停用,1=启用,停用即一键下线该端
}

export interface ISystemAppDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemAppQueryRequest {
  id?: number, // ID
  code?: string, // 应用标识:web,h5,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios,app_android
  appid?: string, // 微信类应用AppID,非微信应用为空
}

export interface ISystemAppUpdateRequest {
  id: number, // ID
  code: string, // 应用标识:web,h5,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios,app_android
  name: string, // 应用名称
  type: string, // 应用类型:web,h5,wechat,app
  appid: string, // 微信类应用AppID,非微信应用为空
  secret: string, // 应用密钥,加密存储
  config: string, // 扩展配置(JSON):token,aes_key,推送参数,API域名,商户号绑定等
  sort: number, // 排序
  status: number, // 状态:0=停用,1=启用,停用即一键下线该端
}

export interface ISystemAppDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemAppQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemAppResponse[], // 数据列表
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

export interface ISystemAppResponse {
  id: number, // ID
  code: string, // 应用标识:web,h5,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios,app_android
  name: string, // 应用名称
  type: string, // 应用类型:web,h5,wechat,app
  appid: string, // 微信类应用AppID,非微信应用为空
  secret: string, // 应用密钥,加密存储
  config: string, // 扩展配置(JSON):token,aes_key,推送参数,API域名,商户号绑定等
  sort: number, // 排序
  status: number, // 状态:0=停用,1=启用,停用即一键下线该端
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

