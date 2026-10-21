<?php

declare(strict_types=1);

namespace App\Plugins\Pay;

/**
 * 支付插件服务：打款/收单的统一入口
 * 微信商家转账待商户号资质办理后接入（easywechat/yansongda 已安装），
 * 本期提现走人工线下打款，withdraw 返回空渠道单号由后台人工回填
 */
class PayService
{
    /**
     * 发起提现打款，返回渠道打款单号（空=线下人工打款，由后台标记时回填真实凭证号）
     *
     * @param  array{withdraw_no: string, amount: float, account_type: string, account_name: string, account_no: string}  $order
     */
    public function withdraw(array $order): string
    {
        // TODO: 商户号资质办理后接入微信商家转账（V3 transfer/batches），按 user_withdraw_orders.channel 分派
        return '';
    }
}
