import { useState, useCallback } from 'react'

export function useCategoryNav() {
  const [activeCategoryId, setActiveCategoryId] = useState<string | number | null>(null)
  const [isMenuOpen, setIsMenuOpen] = useState(false)

  const handleMouseEnter = useCallback((id: string | number) => {
    setActiveCategoryId(id)
    setIsMenuOpen(true)
  }, [])

  const handleMouseLeave = useCallback(() => {
    setActiveCategoryId(null)
    setIsMenuOpen(false)
  }, [])

  return {
    activeCategoryId,
    isMenuOpen,
    handleMouseEnter,
    handleMouseLeave,
    setActiveCategoryId,
  }
}

export default useCategoryNav
