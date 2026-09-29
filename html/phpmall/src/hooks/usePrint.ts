import { useRef, useCallback } from 'react'
import { useReactToPrint } from 'react-to-print'

export interface UsePrintOptions {
  documentTitle?: string
  onBeforePrint?: () => void | Promise<void>
  onAfterPrint?: () => void
  onPrintError?: (errorLocation: string, error: Error) => void
}

/**
 * 电商面单 / 履约单据打印 Hook
 */
export function usePrint<T extends HTMLElement = HTMLDivElement>(options: UsePrintOptions = {}) {
  const printRef = useRef<T>(null)

  const handlePrint = useReactToPrint({
    contentRef: printRef,
    documentTitle: options.documentTitle || '电子面单打印',
    onBeforePrint: options.onBeforePrint,
    onAfterPrint: options.onAfterPrint,
  })

  const triggerDirectPrint = useCallback(() => {
    if (handlePrint) {
      handlePrint()
    } else {
      window.print()
    }
  }, [handlePrint])

  return {
    printRef,
    handlePrint: triggerDirectPrint,
  }
}

export default usePrint
