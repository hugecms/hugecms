export interface IWechatQrcodeCreateRequest {
  appid: string, // 二维码所属应用AppID,公众号带参码或小程序码
  sceneStr: string, // 场景值,同一应用下唯一
  name: string, // 渠道码名称,如:分销员张三专属码
  type: string, // 类型:distributor=分销邀请,product=商品推广,channel=渠道投放
  targetId: number, // 绑定目标ID,分销码=分销员用户ID,商品码=商品ID
  scanCount: number, // 扫码次数
  registerCount: number, // 扫码注册转化数
  sort: number, // 排序
  status: number, // 状态:0=停用,1=启用
}

export interface IWechatQrcodeDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IWechatQrcodeQueryRequest {
  id?: number, // ID
  appid?: string, // 二维码所属应用AppID,公众号带参码或小程序码
  sceneStr?: string, // 场景值,同一应用下唯一
  type?: string, // 类型:distributor=分销邀请,product=商品推广,channel=渠道投放
  targetId?: number, // 绑定目标ID,分销码=分销员用户ID,商品码=商品ID
}

export interface IWechatQrcodeUpdateRequest {
  id: number, // ID
  appid: string, // 二维码所属应用AppID,公众号带参码或小程序码
  sceneStr: string, // 场景值,同一应用下唯一
  name: string, // 渠道码名称,如:分销员张三专属码
  type: string, // 类型:distributor=分销邀请,product=商品推广,channel=渠道投放
  targetId: number, // 绑定目标ID,分销码=分销员用户ID,商品码=商品ID
  scanCount: number, // 扫码次数
  registerCount: number, // 扫码注册转化数
  sort: number, // 排序
  status: number, // 状态:0=停用,1=启用
}

export interface IWechatQrcodeDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IWechatQrcodeQueryResponse {
  currentPage: number, // 当前页码
  data: IWechatQrcodeResponse[], // 数据列表
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

export interface IWechatQrcodeResponse {
  id: number, // ID
  appid: string, // 二维码所属应用AppID,公众号带参码或小程序码
  sceneStr: string, // 场景值,同一应用下唯一
  name: string, // 渠道码名称,如:分销员张三专属码
  type: string, // 类型:distributor=分销邀请,product=商品推广,channel=渠道投放
  targetId: number, // 绑定目标ID,分销码=分销员用户ID,商品码=商品ID
  scanCount: number, // 扫码次数
  registerCount: number, // 扫码注册转化数
  sort: number, // 排序
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

