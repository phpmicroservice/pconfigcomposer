<?php

namespace regreg;

use Phalcon\Events\Event;
use pms\Output;

/**
 * 注册服务
 * Class Register
 * @property \pms\bear\ClientCoroutine $register_client
 * @package pms
 */
class Register
{

    private $addr;# 地址
    private $type;# 验证方式
    private $consumer;# 消费者名字
    private $key;# 验证key
    private $path;


    /**
     * 配置初始化
     */
    public function __construct(string $addr, $consumer = 'public', $type = 'kong', $key = '', $path = '')
    {
        $this->addr = $addr;
        $this->type = $type;
        $this->consumer = $consumer;
        $this->key = $key;
        if (!$path) {
            $this->path = ROOT_DIR . '/config/config';
        } else {
            $this->path = $path;
        }
    }

    public function get()
    {
        $url=$this->addr.'/out/index';
        
    }

    /**
     * 传入数组进行HTTP POST请求
     */
    private function curlPost($url, $post_data = array(), $timeout = 5, $header = "", $data_type = "")
    {
        $header = empty($header) ? '' : $header;
        //支持json数据数据提交
        if ($data_type == 'json') {
            $post_string = json_encode($post_data);
        } elseif ($data_type == 'array') {
            $post_string = $post_data;
        } elseif (is_array($post_data)) {
            $post_string = http_build_query($post_data, '', '&');
        }

        $ch = curl_init();    // 启动一个CURL会话
        curl_setopt($ch, CURLOPT_URL, $url);     // 要访问的地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // 对认证证书来源的检查   // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        //curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($ch, CURLOPT_POST, true); // 发送一个常规的Post请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);     // Post提交的数据包
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);     // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        //curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     // 获取的信息以文件流的形式返回
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //模拟的header头
        $result = curl_exec($ch);

        // 打印请求的header信息
        //$a = curl_getinfo($ch);
        //var_dump($a);

        curl_close($ch);
        return $result;
    }


}