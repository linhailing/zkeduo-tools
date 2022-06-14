<?php
// +----------------------------------------------------------------------
// | QQ : 462211958
// +----------------------------------------------------------------------
// | Date: 2022/6/14
// +----------------------------------------------------------------------
// | Time: 10:50 AM
// +----------------------------------------------------------------------
// | Author: henry <hailing.lin@outlook.com>
// +----------------------------------------------------------------------
namespace Zkeduo\Tools\AliPay\Request;

require_once __DIR__.'/../../vendor/alipay-sdk/aop/request/AlipayOpenAgentSignstatusQueryRequest.php';

/**
 * 查询商户某个产品的签约状态
 */
class AliPayOpenAgentSignStatusQuery{

    protected $AOP = null;
    public function __construct($aop){
        $this->AOP = $aop;
    }

    /**
     * @param array $data
     * @return array
     */
    public function query(array $data = []) :array
    {
        $result = [
            'msg' => '',
            'code' => 0,
            'status' => true,
            'data' => []
        ];
        if (empty($this->AOP)){
            $result['status'] = false;
            $result['msg'] = 'aopClient初始化错误';
            return $result;
        }
        $request = new \AlipayOpenAgentSignstatusQueryRequest();
        // isv要查询签约状态的商户账号，可以是支付宝账号pid（2088开头），也可以是商户的登录账号（邮箱或手机号）
        if (!isset($data['pid']) || empty($data['pid'])){
            $result['msg'] = 'pid支付宝账号或商户的登录账号';
            $result['status'] = false;
        }

        if (!isset($data['pid']) || empty($data['pid'])){
            $result['msg'] = '{pid}支付宝账号或商户的登录账号必填';
            $result['status'] = false;
            return $result;
        }
        if (!isset($data['product_codes']) || empty($data['product_codes'])){
            $result['msg'] = 'product_codes产品码必填';
            $result['status'] = false;
            return $result;
        }

        $bizContent = [];
        $bizContent['pid'] = $data['pid'];
        $bizContent['product_codes'] = $data['product_codes'];
        $request->setBizContent(json_encode($bizContent, 320));
        $response = $this->AOP->execute($request);
        $responseBody = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $response->$responseBody->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            $result['status'] = true;
            $result['code'] = $resultCode;
            $result['msg'] = isset($response->$responseBody->sub_msg) ? $response->$responseBody->sub_msg : $response->$responseBody->msg;
            $result['data'] = (array)$response->$responseBody;
        } else {
            $result['status'] = false;
            $result['msg'] = isset($response->$responseBody->sub_msg) ? $response->$responseBody->sub_msg : $response->$responseBody->msg;
            $result['data'] = (array)$response->$responseBody;
        }
        return $result;
    }
}