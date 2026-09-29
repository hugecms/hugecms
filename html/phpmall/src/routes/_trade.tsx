import { createFileRoute } from '@tanstack/react-router'
import { TradeLayout } from '../layouts/TradeLayout'

export const Route = createFileRoute('/_trade')({
  component: TradeLayout,
})
