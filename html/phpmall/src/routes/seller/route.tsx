import { createFileRoute } from '@tanstack/react-router'
import { SellerLayout } from '../../layouts/SellerLayout'

export const Route = createFileRoute('/seller')({
  component: SellerLayout,
})
