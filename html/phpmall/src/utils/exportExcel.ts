import * as XLSX from 'xlsx'
import saveAs from 'file-saver'

export interface ExcelExportColumn<T = any> {
  header: string
  key: keyof T | string
  formatter?: (val: any, row: T) => any
}

/**
 * 通用 Excel 导出封装工具
 * @param data 要导出的数组数据
 * @param columns 列配置映射
 * @param fileName 导出的文件名 (默认包含时间戳)
 * @param sheetName 工作表名
 */
export function exportExcel<T = any>(
  data: T[],
  columns: ExcelExportColumn<T>[],
  fileName = '导出数据',
  sheetName = 'Sheet1'
): void {
  const exportRows = data.map((row) => {
    const formattedRow: Record<string, any> = {}
    columns.forEach((col) => {
      const rawVal = (row as any)[col.key]
      formattedRow[col.header] = col.formatter ? col.formatter(rawVal, row) : rawVal ?? ''
    })
    return formattedRow
  })

  const worksheet = XLSX.utils.json_to_sheet(exportRows)
  const workbook = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(workbook, worksheet, sheetName)

  const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' })
  const blob = new Blob([excelBuffer], {
    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8',
  })

  const timeSuffix = new Date().toISOString().slice(0, 10)
  saveAs(blob, `${fileName}_${timeSuffix}.xlsx`)
}

/**
 * 针对电商订单的快捷导出封装
 */
export function exportOrdersExcel(orders: any[], fileName = '电商订单列表'): void {
  const columns: ExcelExportColumn[] = [
    { header: '订单编号', key: 'sn' },
    { header: '下单时间', key: 'orderTime' },
    { header: '商品名称', key: 'goodsTitle' },
    { header: '商品规格', key: 'goodsSku' },
    { header: '实付金额', key: 'payAmount', formatter: (val) => `¥${val}` },
    { header: '收件人姓名', key: 'receiverName' },
    { header: '联系电话', key: 'receiverPhone' },
    { header: '收件地址', key: 'receiverAddress' },
    { header: '物流承运商', key: 'expressCompany' },
    { header: '快递单号', key: 'trackingNo' },
    {
      header: '履约状态',
      key: 'status',
      formatter: (val) => (val === 'shipped' ? '已发货' : '待发货'),
    },
  ]

  exportExcel(orders, columns, fileName, '订单履约台账')
}
