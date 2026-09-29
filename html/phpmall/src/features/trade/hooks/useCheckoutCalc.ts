import { useMemo } from 'react'
import { useCartStore } from '../../../stores/cartStore'

export function useCheckoutCalc(freightFee = 0) {
  const {
    getSelectedItems,
    getSelectedCount,
    getTotalPrice,
    getDiscountAmount,
    getFinalPayAmount,
  } = useCartStore()

  const selectedItems = getSelectedItems()
  const selectedCount = getSelectedCount()
  const rawTotalPrice = getTotalPrice()
  const discountAmount = getDiscountAmount()
  const basePayAmount = getFinalPayAmount()
  const finalPayAmount = useMemo(() => {
    return Math.max(0, basePayAmount + freightFee)
  }, [basePayAmount, freightFee])

  return {
    selectedItems,
    selectedCount,
    rawTotalPrice,
    discountAmount,
    freightFee,
    finalPayAmount,
  }
}

export default useCheckoutCalc
