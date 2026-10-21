export interface IMarketingDistributionCommissionRuleCreateRequest {
  scopeType: number, // 范围类型:1=分类默认,2=商品覆盖
  scopeId: number, // 目标ID,scope_type=1指向分类,2指向商品
  level1Rate: string, // 一级佣金比例(%),如5.00表示5%
  level2Rate: string, // 二级佣金比例(%)
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingDistributionCommissionRuleDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingDistributionCommissionRuleQueryRequest {
  id?: number, // ID
  scopeType?: number, // 范围类型:1=分类默认,2=商品覆盖
  scopeId?: number, // 目标ID,scope_type=1指向分类,2指向商品
  status?: number, // 状态:0=停用,1=启用
}

export interface IMarketingDistributionCommissionRuleUpdateRequest {
  id: number, // ID
  scopeType: number, // 范围类型:1=分类默认,2=商品覆盖
  scopeId: number, // 目标ID,scope_type=1指向分类,2指向商品
  level1Rate: string, // 一级佣金比例(%),如5.00表示5%
  level2Rate: string, // 二级佣金比例(%)
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingDistributionCommissionRuleDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingDistributionCommissionRuleQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingDistributionCommissionRuleResponse[], // 数据列表
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

export interface IMarketingDistributionCommissionRuleResponse {
  id: number, // ID
  scopeType: number, // 范围类型:1=分类默认,2=商品覆盖
  scopeId: number, // 目标ID,scope_type=1指向分类,2指向商品
  level1Rate: string, // 一级佣金比例(%),如5.00表示5%
  level2Rate: string, // 二级佣金比例(%)
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

