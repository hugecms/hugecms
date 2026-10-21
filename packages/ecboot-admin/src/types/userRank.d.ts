export interface IUserRankCreateRequest {
  name: string, // 等级名称,如:普通会员
  minPoints: number, // 升级所需最低积分
  maxPoints: number, // 积分上限,0=不限(最高级)
  discount: number, // 会员折扣,100=原价,95=95折
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface IUserRankDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserRankQueryRequest {
  id?: number, // ID
}

export interface IUserRankUpdateRequest {
  id: number, // ID
  name: string, // 等级名称,如:普通会员
  minPoints: number, // 升级所需最低积分
  maxPoints: number, // 积分上限,0=不限(最高级)
  discount: number, // 会员折扣,100=原价,95=95折
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
}

export interface IUserRankDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserRankQueryResponse {
  currentPage: number, // 当前页码
  data: IUserRankResponse[], // 数据列表
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

export interface IUserRankResponse {
  id: number, // ID
  name: string, // 等级名称,如:普通会员
  minPoints: number, // 升级所需最低积分
  maxPoints: number, // 积分上限,0=不限(最高级)
  discount: number, // 会员折扣,100=原价,95=95折
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

