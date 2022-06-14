<?php
// +----------------------------------------------------------------------
// | QQ : 462211958
// +----------------------------------------------------------------------
// | Date: 2022/6/14
// +----------------------------------------------------------------------
// | Time: 10:52 AM
// +----------------------------------------------------------------------
// | Author: henry <hailing.lin@outlook.com>
// +----------------------------------------------------------------------
namespace Zkeduo\Tools\AliPay\Request;


require_once __DIR__.'/../../vendor/alipay-sdk/aop/request/AlipayOpenFeeAdjustApplyRequest.php';

/**
 * alipay.open.fee.adjust.apply(特殊费率申请)
 */
class AliPayOpenFeeAdjustApply{
    protected $AOP = null;
    public function __construct($aop){
        $this->AOP = $aop;
    }

    public function handle(array $data = []): array
    {
        $result = [
            'msg' => '',
            'code' => 0,
            'status' => false,
            'data' => ''
        ];
        if (empty($this->AOP)){
            $result['msg'] = 'aopClient初始化错误';
            return $result;
        }

        $request = new \AlipayOpenFeeAdjustApplyRequest();
        if (empty($data) || empty($data['account'])){
            $result['msg'] = 'account-服务商代申请特殊费率的商家支付宝账户';
            $result['status'] = false;
            return $result;
        }
        $request->setAccount($data['account']);
        if (empty($data) || empty($data['product_code'])){
            $result['msg'] = 'product_code-服务商代商家申请调整费率的产品码';
            $result['status'] = false;
            return $result;
        }
        $request->setProductCode($data['product_code']);

        if (empty($data) || empty($data['application_fee'])){
            $result['msg'] = 'application_fee-服务商代商家申请特殊费率的费率值（%），如0.38表示0.38%';
            $result['status'] = false;
            return $result;
        }
        $request->setApplicationFee($data['application_fee']);

        // 一下是可选参数
        // 1. 特殊许可证书
        if (isset($data['cert_type']) && !empty($data['cert_type'])){
            $request->setCertType($data['cert_type']);
        }
        // 2.许可证书证件号
        if (isset($data['cert_no']) && !empty($data['cert_no'])){
            $request->setCertNo($data['cert_no']);
        }
        // 3.许可证书对应证件的图片
        if (isset($data['cert_pic']) && !empty($data['cert_pic'])){
            $request->setCertPic("@".$data['cert_pic']);
        }
        // 4.店铺门头照图片
        if (isset($data['shop_sign_pic']) && !empty($data['shop_sign_pic'])){
            $request->setShopSignPic("@".$data['shop_sign_pic']);
        }
        // 4.店铺内景图片
        if (isset($data['shop_scene_pic']) && !empty($data['shop_scene_pic'])){
            $request->setShopScenePic("@".$data['shop_scene_pic']);
        }
        // 5.其他支付方式费率证明或者业务补充说明
        if (isset($data['attachment']) && !empty($data['attachment'])){
            $request->setAttachment("@".$data['attachment']);
        }

        // 6.省份
        if (isset($data['province_code']) && !empty($data['province_code'])){
            $request->setProductCode($data['province_code']);
        }

        // 7.市
        if (isset($data['city_code']) && !empty($data['city_code'])){
            $request->setCityCode($data['city_code']);
        }

        // 8.区/县
        if (isset($data['district_code']) && !empty($data['district_code'])){
            $request->setDistrictCode($data['district_code']);
        }

        // 9.详细地址
        if (isset($data['detail_address']) && !empty($data['detail_address'])){
            $request->setDetailAddress($data['detail_address']);
        }
        if (isset($data['app_auth_token']) && !empty($data['app_auth_token'])){
            $response = $this->AOP->execute($request, null, $data['app_auth_token']);
        }else{
            $response = $this->AOP->execute($request);
        }
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