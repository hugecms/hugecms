export interface ISystemRiskRuleCreateRequest {
  name: string, // 规则名称
  ruleType: number, // 规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级
  conditionExpr: string, // 条件参数(JSON),如黑名单{&quot;user_ids&quot;:[1,2]}、高频{&quot;max_count&quot;:3,&quot;window_minutes&quot;:60}
  action: number, // 处置动作:1=拦截,2=标记放行
  status: number, // 状态:0=停用,1=启用
}

export interface ISystemRiskRuleDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemRiskRuleQueryRequest {
  id?: number, // ID
  ruleType?: number, // 规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级
  status?: number, // 状态:0=停用,1=启用
}

export interface ISystemRiskRuleUpdateRequest {
  id: number, // ID
  name: string, // 规则名称
  ruleType: number, // 规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级
  conditionExpr: string, // 条件参数(JSON),如黑名单{&quot;user_ids&quot;:[1,2]}、高频{&quot;max_count&quot;:3,&quot;window_minutes&quot;:60}
  action: number, // 处置动作:1=拦截,2=标记放行
  status: number, // 状态:0=停用,1=启用
}

export interface ISystemRiskRuleDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemRiskRuleQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemRiskRuleResponse[], // 数据列表
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

export interface ISystemRiskRuleResponse {
  id: number, // ID
  name: string, // 规则名称
  ruleType: number, // 规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级
  conditionExpr: string, // 条件参数(JSON),如黑名单{&quot;user_ids&quot;:[1,2]}、高频{&quot;max_count&quot;:3,&quot;window_minutes&quot;:60}
  action: number, // 处置动作:1=拦截,2=标记放行
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

