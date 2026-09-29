import React from 'react'
import { Link } from '@tanstack/react-router'

export const MallFooter: React.FC = () => {
  return (
    <footer className="footer">
      {/* 京东“多快好省”4大承诺标志 */}
      <div className="footer-service">
        <div className="w service-box">
          <div className="service-item">
            <div className="s-icon duo">多</div>
            <div className="s-text">
              <h4>品类齐全</h4>
              <p>轻松购物 一应俱全</p>
            </div>
          </div>
          <div className="service-item">
            <div className="s-icon kuai">快</div>
            <div className="s-text">
              <h4>多仓直发</h4>
              <p>急速配送 准时必达</p>
            </div>
          </div>
          <div className="service-item">
            <div className="s-icon hao">好</div>
            <div className="s-text">
              <h4>正品行货</h4>
              <p>精致服务 品质保障</p>
            </div>
          </div>
          <div className="service-item">
            <div className="s-icon sheng">省</div>
            <div className="s-text">
              <h4>天天低价</h4>
              <p>畅选无忧 畅享优惠</p>
            </div>
          </div>
        </div>
      </div>

      {/* 购物指引与帮助中心多列导航 */}
      <div className="footer-help w">
        <div className="help-col">
          <h5>购物指南</h5>
          <ul>
            <li><a href="javascript:;">购物流程</a></li>
            <li><a href="javascript:;">会员介绍</a></li>
            <li><a href="javascript:;">生活旅行</a></li>
            <li><a href="javascript:;">常见问题</a></li>
            <li><a href="javascript:;">大家电</a></li>
            <li><Link to="/chat">联系客服</Link></li>
          </ul>
        </div>
        <div className="help-col">
          <h5>配送方式</h5>
          <ul>
            <li><a href="javascript:;">上门自提</a></li>
            <li><a href="javascript:;">211限时达</a></li>
            <li><a href="javascript:;">配送服务查询</a></li>
            <li><a href="javascript:;">配送费收取标准</a></li>
            <li><a href="javascript:;">海外配送</a></li>
          </ul>
        </div>
        <div className="help-col">
          <h5>支付方式</h5>
          <ul>
            <li><a href="javascript:;">货到付款</a></li>
            <li><a href="javascript:;">在线支付</a></li>
            <li><a href="javascript:;">分期付款</a></li>
            <li><a href="javascript:;">公司转账</a></li>
          </ul>
        </div>
        <div className="help-col">
          <h5>售后服务</h5>
          <ul>
            <li><a href="javascript:;">售后政策</a></li>
            <li><a href="javascript:;">价格保护</a></li>
            <li><a href="javascript:;">退款说明</a></li>
            <li><Link to="/user/order">返修/退换货</Link></li>
            <li><a href="javascript:;">取消订单</a></li>
          </ul>
        </div>
        <div className="help-col">
          <h5>特色服务</h5>
          <ul>
            <li><a href="javascript:;">夺宝岛</a></li>
            <li><a href="javascript:;">DIY装机</a></li>
            <li><a href="javascript:;">延保服务</a></li>
            <li><a href="javascript:;">京东E卡</a></li>
            <li><a href="javascript:;">京东通信</a></li>
          </ul>
        </div>
        <div className="help-col cover-area">
          <h5>京东自营覆盖区县</h5>
          <p>京东已向全国2661个区县提供自营配送服务，支持货到付款、POS机刷卡和售后上门服务。</p>
          <a href="javascript:;" className="more-link">查看详情 ›</a>
        </div>
      </div>

      {/* 资质与版权信息 */}
      <div className="footer-copyright w">
        <p className="links">
          <a href="javascript:;">关于我们</a><span className="split">|</span>
          <a href="javascript:;">联系我们</a><span className="split">|</span>
          <Link to="/chat">联系客服</Link><span className="split">|</span>
          <Link to="/merchant/settle">合作招商</Link><span className="split">|</span>
          <Link to="/seller">商家帮助/工作台</Link><span className="split">|</span>
          <a href="javascript:;">营销中心</a><span className="split">|</span>
          <a href="javascript:;">手机京东</a><span className="split">|</span>
          <a href="javascript:;">友情链接</a><span className="split">|</span>
          <a href="javascript:;">销售联盟</a><span className="split">|</span>
          <a href="javascript:;">京东社区</a><span className="split">|</span>
          <a href="javascript:;">风险监测</a><span className="split">|</span>
          <a href="javascript:;">隐私政策</a>
        </p>
        <p className="cert-info">
          <span>京公网安备 11000002000088号</span>
          <span>京ICP备11041704号</span>
          <span>ICP经营许可证：京B2-20190132</span>
          <span>新出发京零 字第大120007号</span>
        </p>
        <p className="copy">
          Copyright © 2004 - 2026 京东JD.com 版权所有 | 消费者维权热线：4006065500
        </p>
      </div>
    </footer>
  )
}
