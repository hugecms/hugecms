import { get } from '@/utils/request'
/** [登录] 登录 */
export function loginApi(): Promise<any> {
  return get<any>('user/login')
}
