<?php
/**
 * 基于laravel 的sign 验证
 */

namespace kongServer;
use Exception;

class Application extends Server{

    public function __construct($linkTag = '&')
    {
        $config = $this->getConfig();
        $config['link_tag'] = $linkTag;
        parent::__construct($config);
    }
    /**
     * 检查
     */
    public function check($sign='', array $getParams=[], array $postParams=[])
    {
        if (empty($getParams))
        {
            $getParams = $_GET;
        }
        if (empty($postParams))
        {
            $postParams = $_POST;
        }
        if (empty($sign))
        {
            $sign = $getParams['sign']??'';
        }
        if (empty($sign))
        {
            throw new Exception('签名错误！', 409);
        }
        return $this->checkSign($sign, $getParams, $postParams);

    }
    /**
     * 从http header 中获取网关用户信息
     */
    private function getConfig()
    {
        $config = [
            'api_key' => '',
            'api_secret' => '',
            'client_name' => '',
        ];
        foreach($_SERVER as $key=>$value)
        {
            switch ($key)
            {
                case 'HTTP_X_CONSUMER_USERNAME':
                    $config['client_name'] = $value;
                    break;
                case 'HTTP_X_CONSUMER_ID':
                    $config['api_secret'] = $value;
                    break;
                case 'HTTP_APIKEY':
                    $config['api_key'] = $value;
                    break;
                default:
                    break;
            }
        }
        if (empty($config['api_key']) || empty($config['api_secret']) || empty($config['client_name']))
        {
            throw new Exception('签名错误，缺少参数', 409);
        }
        return $config;
    }


}