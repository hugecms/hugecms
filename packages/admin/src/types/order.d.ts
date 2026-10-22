export interface IOrderCreateRequest {
  orderNo: string, // 订单号
  requestToken: string, // 下单请求幂等令牌,客户端生成,唯一约束防重复下单
  orderChannel: string, // 下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用
  attributedUserId: number, // 归因用户ID:分享者/分销员,佣金与ROI归因依据
  attributionType: string, // 归因类型:share=分享,distributor=分销,channel=渠道投放
  userId: number, // 下单用户ID
  totalAmount: string, // 商品总额
  freightAmount: string, // 运费
  discountAmount: string, // 优惠金额
  payAmount: string, // 实付金额
  status: number, // 状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)
  refundStatus: number, // 售后状态:0=无,1=售后中,2=部分退款,3=整单退款;由order_refunds汇总回写
  receiverName: string, // 收货人
  receiverPhone: string, // 收货电话
  receiverRegion: string, // 省市区(合并展示用)
  receiverProvince: string, // 省
  receiverCity: string, // 市
  receiverDistrict: string, // 区县
  receiverProvinceCode: string, // 省行政区划码,物流面单
  receiverCityCode: string, // 市行政区划码
  receiverDistrictCode: string, // 区县行政区划码
  receiverAddress: string, // 详细地址
  buyerRemark: string, // 买家备注
  sellerRemark: string, // 商家备注,客服/发货协作
  payTime: string, // 支付时间
  deliverTime: string, // 发货时间
  finishTime: string, // 完成时间
  cancelTime: string, // 取消时间
  cancelReason: string, // 取消原因
  cancelType: number, // 取消类型:1=用户取消,2=超时未支付,3=商家/系统取消
}

export interface IOrderDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IOrderQueryRequest {
  id?: number, // ID
  orderNo?: string, // 订单号
  requestToken?: string, // 下单请求幂等令牌,客户端生成,唯一约束防重复下单
  orderChannel?: string, // 下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用
  attributedUserId?: number, // 归因用户ID:分享者/分销员,佣金与ROI归因依据
  userId?: number, // 下单用户ID
  status?: number, // 状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)
  createdAt?: string, // 创建时间
}

export interface IOrderUpdateRequest {
  id: number, // ID
  orderNo: string, // 订单号
  requestToken: string, // 下单请求幂等令牌,客户端生成,唯一约束防重复下单
  orderChannel: string, // 下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用
  attributedUserId: number, // 归因用户ID:分享者/分销员,佣金与ROI归因依据
  attributionType: string, // 归因类型:share=分享,distributor=分销,channel=渠道投放
  userId: number, // 下单用户ID
  totalAmount: string, // 商品总额
  freightAmount: string, // 运费
  discountAmount: string, // 优惠金额
  payAmount: string, // 实付金额
  status: number, // 状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)
  refundStatus: number, // 售后状态:0=无,1=售后中,2=部分退款,3=整单退款;由order_refunds汇总回写
  receiverName: string, // 收货人
  receiverPhone: string, // 收货电话
  receiverRegion: string, // 省市区(合并展示用)
  receiverProvince: string, // 省
  receiverCity: string, // 市
  receiverDistrict: string, // 区县
  receiverProvinceCode: string, // 省行政区划码,物流面单
  receiverCityCode: string, // 市行政区划码
  receiverDistrictCode: string, // 区县行政区划码
  receiverAddress: string, // 详细地址
  buyerRemark: string, // 买家备注
  sellerRemark: string, // 商家备注,客服/发货协作
  payTime: string, // 支付时间
  deliverTime: string, // 发货时间
  finishTime: string, // 完成时间
  cancelTime: string, // 取消时间
  cancelReason: string, // 取消原因
  cancelType: number, // 取消类型:1=用户取消,2=超时未支付,3=商家/系统取消
}

export interface IOrderDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IOrderQueryResponse {
  currentPage: number, // 当前页码
  data: IOrderResponse[], // 数据列表
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

export interface IOrderResponse {
  id: number, // ID
  orderNo: string, // 订单号
  requestToken: string, // 下单请求幂等令牌,客户端生成,唯一约束防重复下单
  orderChannel: string, // 下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用
  attributedUserId: number, // 归因用户ID:分享者/分销员,佣金与ROI归因依据
  attributionType: string, // 归因类型:share=分享,distributor=分销,channel=渠道投放
  userId: number, // 下单用户ID
  totalAmount: string, // 商品总额
  freightAmount: string, // 运费
  discountAmount: string, // 优惠金额
  payAmount: string, // 实付金额
  status: number, // 状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)
  refundStatus: number, // 售后状态:0=无,1=售后中,2=部分退款,3=整单退款;由order_refunds汇总回写
  receiverName: string, // 收货人
  receiverPhone: string, // 收货电话
  receiverRegion: string, // 省市区(合并展示用)
  receiverProvince: string, // 省
  receiverCity: string, // 市
  receiverDistrict: string, // 区县
  receiverProvinceCode: string, // 省行政区划码,物流面单
  receiverCityCode: string, // 市行政区划码
  receiverDistrictCode: string, // 区县行政区划码
  receiverAddress: string, // 详细地址
  buyerRemark: string, // 买家备注
  sellerRemark: string, // 商家备注,客服/发货协作
  payTime: string, // 支付时间
  deliverTime: string, // 发货时间
  finishTime: string, // 完成时间
  cancelTime: string, // 取消时间
  cancelReason: string, // 取消原因
  cancelType: number, // 取消类型:1=用户取消,2=超时未支付,3=商家/系统取消
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

