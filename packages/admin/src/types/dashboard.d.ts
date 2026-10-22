export interface IDashboardTodoResponse {
  pendingShipments: number, // 待发货订单数
  pendingRefunds: number, // 待审核退款单数
  pendingWithdrawals: number, // 待审核提现单数
  lowStock: number, // 库存预警 SKU 数
  pendingRiskAppeals: number, // 申诉中风控事件数
}
