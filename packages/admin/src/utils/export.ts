export interface ExportColumn<TRow> {
  key: keyof TRow & string
  label: string
}

/** CSV 单元格转义：包裹双引号并转义内部引号 */
function escapeCell(value: unknown): string {
  const text = value === null || value === undefined ? '' : String(value)
  return `"${text.replace(/"/g, '""')}"`
}

/**
 * 前端导出 CSV（带 BOM，Excel 中文不乱码）。
 * 适用于当前页/已加载数据；全量导出需后端接口，后续在页面接入。
 */
export function exportCsv<TRow extends object>(
  filename: string,
  columns: ExportColumn<TRow>[],
  rows: TRow[],
): void {
  const header = columns.map((col) => escapeCell(col.label)).join(',')
  const body = rows
    .map((row) => columns.map((col) => escapeCell((row as Record<string, unknown>)[col.key])).join(','))
    .join('\n')
  const blob = new Blob([`﻿${header}\n${body}`], { type: 'text/csv;charset=utf-8' })

  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = filename.endsWith('.csv') ? filename : `${filename}.csv`
  link.click()
  URL.revokeObjectURL(url)
}
