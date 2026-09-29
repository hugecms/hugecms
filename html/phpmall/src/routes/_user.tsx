import { createFileRoute } from '@tanstack/react-router'
import { UserLayout } from '../layouts/UserLayout'

export const Route = createFileRoute('/_user')({
  component: UserLayout,
})
