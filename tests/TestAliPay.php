<?php
// +----------------------------------------------------------------------
// | QQ : 462211958
// +----------------------------------------------------------------------
// | Date: 2022/6/14
// +----------------------------------------------------------------------
// | Time: 10:55 AM
// +----------------------------------------------------------------------
// | Author: henry <hailing.lin@outlook.com>
// +----------------------------------------------------------------------


class TestAliPay extends \PHPUnit\Framework\TestCase {
    private $appid = '';
    private $aliPayRsaPublicKey = '';
    private $rsaPrivateKey = '';

    public function testAdjustApply(){
        $aop = new \Zkeduo\Tools\AliPay\AopClientHandle($this->appid, $this->rsaPrivateKey, $this->aliPayRsaPublicKey);
        $data = [];
        $result = $aop->handle('alipay.open.agent.signstatus.query', $data);
        print_r($result);
    }
}