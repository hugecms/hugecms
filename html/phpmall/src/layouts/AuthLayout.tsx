import React from 'react'
import { Link, Outlet } from '@tanstack/react-router'

interface AuthLayoutProps {
  pageTitle?: string
  children?: React.ReactNode
}

export const AuthLayout: React.FC<AuthLayoutProps> = ({
  pageTitle = '欢迎登录',
  children,
}) => {
  return (
    <div className="auth-layout min-h-screen flex flex-col bg-white">
      {/* 头部简约Logo区 */}
      <div className="w header-login-nav" style={{ padding: '24px 0', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: '20px' }}>
          <Link to="/" style={{ display: 'flex', alignItems: 'center', textDecoration: 'none' }}>
            <div style={{
              width: '120px',
              height: '42px',
              background: '#e1251b',
              borderRadius: '4px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: '#fff',
              fontSize: '24px',
              fontWeight: 900,
              letterSpacing: '2px',
            }}>
              JD
            </div>
          </Link>
          <span style={{ fontSize: '24px', color: '#333', fontWeight: 500 }}>{pageTitle}</span>
        </div>
        <Link to="/" style={{ color: '#999', fontSize: '12px' }}>返回商城首页 ›</Link>
      </div>

      {/* 主体表单区 */}
      <main className="flex-1 w-full">
        {children || <Outlet />}
      </main>

      {/* 底部简约版权 */}
      <footer style={{ padding: '30px 0', borderTop: '1px solid #eee', textAlign: 'center', color: '#999', fontSize: '12px' }}>
        <p style={{ marginBottom: '8px' }}>
          <a href="javascript:;" style={{ color: '#666', margin: '0 8px' }}>关于我们</a>|
          <a href="javascript:;" style={{ color: '#666', margin: '0 8px' }}>联系我们</a>|
          <a href="javascript:;" style={{ color: '#666', margin: '0 8px' }}>人才招聘</a>|
          <a href="javascript:;" style={{ color: '#666', margin: '0 8px' }}>商家入驻</a>|
          <a href="javascript:;" style={{ color: '#666', margin: '0 8px' }}>广告服务</a>|
          <a href="javascript:;" style={{ color: '#666', margin: '0 8px' }}>手机京东</a>|
          <a href="javascript:;" style={{ color: '#666', margin: '0 8px' }}>友情链接</a>|
          <a href="javascript:;" style={{ color: '#666', margin: '0 8px' }}>销售联盟</a>
        </p>
        <p>Copyright © 2004 - 2026 京东JD.com 版权所有</p>
      </footer>
    </div>
  )
}

export default AuthLayout
