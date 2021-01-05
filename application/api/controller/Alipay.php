<?php
namespace app\api\controller;
class AliPay extends Common
{

    protected $appId = '2019080166007702';//支付宝AppId
    protected $rsaPrivateKey = 'MIIEpQIBAAKCAQEA5hRU3jGqu9ZTVXB2BIoNxNr9G6BxhZ+YoPVO5PYiCASLkRu/R3dwNKKFqvse18sn/paD+3YA4iuvGO0eGoqvFXbPYBkIYpxJ6j2DVGOgE+9EZc207GJztXxuzvb8HJ2X87KgJOnRmOwoviDAgZRdVPcmgtAamNdn4lzve9VUGa/lJinrOgc040g8uPDnaGsTJJ/7up4iUzNFFWeFLeSQ4JLLHnYFwR1zpS/7mqpZuhMmkXdUV0y3AhPzR22Fq6CsYt9DSKMgoAl86d2vdwHfk6ybPSBbgufv8GG4ZSLcz9Bd9vKLqsLFhlt9oPWT0UpphfY5bJpTrrRoBWgAQ47gWQIDAQABAoIBAQDQaVhbEThr///qo/1zrS49xHSD4vkSJnhWTP9TLZW0F5HpNu40qX9tXk6gi+rrZG5tEiCp1sGEHjf0501ek4N3ePDuRp6u3I2j7maZOclWZWVaplSdz6yql/Wz6kyDC0oKiSLBbT/cOwfI+dgHSMKmZV5wHlwJWQ4UIUUZsisFO/TJX7TZexwIoDySdzJesHzgRJq7Zqy38ZGK0tn0XqYSAFHkTZHyR6QcWlXbSJ6Ti+F+UCz8n9d+3CTzBb64MU+H/VWJ7gKNhmJlf6vxV9p9hnMcaKm2kbuaMskjahxlkvfahtsgny7ihHI/EwCOUywBYHDqd9dSTFTgh+o3/CX1AoGBAP1r8yEXnW46YC0jlDs4dgQEerR+6nsydnkhy63TRUPvgyrwQGhvQTrGRs3zL0IKUANjSwNHQSR9BPzq8NcyriOkgdgWd70fPndVa5aL/uIx6y2G0ehxPD/+jz1rxwrg86HFUO58eytUpP5beemF+ozO8CcySCnySUKBz94+DZGzAoGBAOhrlewhv9Aa2W/esH7zpV6bUfrHw4Ed1qATlk0F3Zk5OLJR1wtq+hVLVDNB7r/IqNc/mtkuc7bIgSPcFLIaitMMMsNxHSSfgWyMsjlF7uZDD5Pl8O6r7+g/Ejb8QoDZorCNhgIS36gFTsHNwVG0gNF/CzOa/skXo2MJzX+uTwfDAoGBAO+l4OI/abHuZDrrdWbXSssbzUnY5zafjueX7PuxWcQXwGufjNj1tLK3BAgIW31RMT8y66HrBRBTZZ8jMaPfmku8asmE3bCr1i+VSMuyEdOnryV7ZA8y3e4KdTx5Kl4AQoSNiq4sa3GbdvfOW6hY+Ymku/flFdcM9vh8t60L81r/AoGAHJLFbq9DfpkebDNrbWAUz4m2zWbrsR7y+OEPlE6JTMINJ1cKYfv33NBS/K45ZNiNcVD/f33HegwyXC89WBwNPx77yq1IUO1PIwI1Evd3BFXwP1WAhkcbCmlemuXuYxredmgftneNiYGFiiv6fUn8oETHIsQGWMCQNKfK3MyYKLMCgYEA5vcNQVsY1oQqfNcQLG9sSYtmS8jDGO1kUZIaS29la7glaLuE02VAWZTKffVgU7CmZvs+Ikny5Px/gU9kFCbJTrNBX4CDUp2haFrW0uDLzv1nqpuOGV7WvM6kTr5CYRN39klm9oUgPfnfq5H8Jnfrrnk8J5DgTiCMTjmjBuLrAuE=';//支付宝私钥
    protected $aliPayRsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhB2yPkulYu98xwqzsflY7jLmOjYnZQENWst+vY+YJSXLHD+zFRaMVyVsRmx440uCFl2QZhnXlEaC2+Cnv8JEvP7SAGmr0viJpS3ON5h20sT2lIfsxQF+Kwx0ryNnx69pDf5bnFpVkw2eSTT6C961Exiig+pORXOnqEhlTvsv0eOzuP/uc/Mi3lLm3IVTrDE1uxPpcEfnRy8P0CaeIaAB2pxILC10GuJwG2698NH6PKS77GmUHYl3kZkDCa9G77A8Kxv55tXCKi2UKXofvG/eRsyzJN2ed/Ljx3TUP6H5LAzekFjfkxa+hnSSGuq4r6haIEsKoljkh6p917HX8VnPqwIDAQAB';//支付宝公钥
    /*
     * 支付宝支付
     */
    public function aliPay($body,$subject,$product_code, $total_amount, $notify_url)
    {
        /**
         * 调用支付宝接口。
         */
        vendor('Alipay.aop.AopClient');
        vendor('Alipay.aop.request.AlipayTradeAppPayRequest');
        $aop = new \AopClient();
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = $this->appId;
        $aop->rsaPrivateKey = $this->rsaPrivateKey;
        $aop->alipayrsaPublicKey = $this->aliPayRsaPublicKey;
        $aop->apiVersion = '1.0';
        $aop->signType = "RSA2";
        $aop->charset = "UTF-8";
        $aop->format = "json";
        $request = new \AlipayTradeAppPayRequest();
        $arr['body'] = $body;
        $arr['subject'] = $subject;
        $arr['out_trade_no'] = $product_code;
        $arr['timeout_express']  = '1d';//失效时间为 1天
        $arr['total_amount'] = $total_amount;
        $arr['product_code'] = 'QUICK_MSECURITY_PAY';
        $json = json_encode($arr);
        $request->setNotifyUrl($notify_url);
        $request->setBizContent($json);
        $response = $aop->sdkExecute($request);
        return $response;
    }
}


?>