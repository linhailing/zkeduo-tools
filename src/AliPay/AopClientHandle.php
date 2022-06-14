<?php
// +----------------------------------------------------------------------
// | QQ : 462211958
// +----------------------------------------------------------------------
// | Date: 2022/6/14
// +----------------------------------------------------------------------
// | Time: 10:43 AM
// +----------------------------------------------------------------------
// | Author: henry <hailing.lin@outlook.com>
// +----------------------------------------------------------------------

namespace Zkeduo\Tools\AliPay;

use Zkeduo\Tools\AliPay\Request\AliPayOpenAgentSignStatusQuery;
use Zkeduo\Tools\AliPay\Request\AliPayOpenFeeAdjustApply;

require_once __DIR__."/../vendor/alipay-sdk/aop/AopClient.php";
class AopClientHandle{
    protected $APPID;
    protected $GateWayUrl;
    protected $RsaPrivateKey; // 请填写开发者私钥去头去尾去回车，一行字符串
    protected $AliPayRsaPublicKey; // 请填写支付宝公钥，一行字符串
    protected $ApiVersion = '1.0';
    protected $SignType = 'RSA2';
    protected $PostCharset = 'UTF-8';
    protected $Format = 'json';
    protected $Aop = null;


    /**
     * 初始化参数
     */
    public function __construct($appid,$rsaPrivateKey,$aliPayRsaPublicKey,$gateWayUrl='https://openapi.alipay.com/gateway.do',$apiVersion='1.0'
        ,$signType='RSA2',$postCharset = 'UTF-8',$format = 'json'){
        $this->APPID = $appid;
        $this->GateWayUrl = $gateWayUrl;
        $this->RsaPrivateKey = $rsaPrivateKey;
        $this->AliPayRsaPublicKey = $aliPayRsaPublicKey;
        $this->ApiVersion = $apiVersion;
        $this->SignType = $signType;
        $this->PostCharset = $postCharset;
        $this->Format = $format;
        // 1、execute使用
        $aop = new \AopClient();
        $aop->gatewayUrl = $this->GateWayUrl;
        $aop->appId = $this->APPID;
        $aop->rsaPrivateKey = $this->RsaPrivateKey;
        $aop->alipayrsaPublicKey = $this->AliPayRsaPublicKey;
        $aop->apiVersion = $this->ApiVersion;
        $aop->signType = $this->SignType;
        $aop->postCharset = $this->PostCharset;
        $aop->format = $this->Format;
        $this->Aop = $aop;
    }

    public function handle($fun,array $data = []) :array
    {
        $result = [
            'msg' => '',
            'code' => 0,
            'status' => true,
            'data' => []
        ];
        if (empty($this->Aop)){
            $result['status'] = false;
            $result['msg'] = 'aopClient初始化错误';
            return $result;
        }
        switch ($fun){
            case 'alipay.open.agent.signstatus.query':
                $statusQuery = new AliPayOpenAgentSignStatusQuery($this->Aop);
                $result = $statusQuery->query($data);
                break;
            case 'alipay.open.fee.adjust.apply':
                $feeApply = new AliPayOpenFeeAdjustApply($this->Aop);
                $result = $feeApply->handle($data);
                break;
            default:
                $result['msg'] = '参数错误';
                $result['status'] = false;
        }
        return $result;
    }

}