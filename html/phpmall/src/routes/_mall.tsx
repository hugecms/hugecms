import { createFileRoute } from '@tanstack/react-router'
import { MallLayout } from '../layouts/MallLayout'

export const Route = createFileRoute('/_mall')({
  component: MallLayout,
})
