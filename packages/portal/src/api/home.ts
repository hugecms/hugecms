import { get } from '@/utils/request'
/** [首页] 首页 */
export function homeApi(): Promise<any> {
  return get<any>('portal/home')
}
