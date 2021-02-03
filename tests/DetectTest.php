<?php


namespace ank\BrowserDetector\tests;

use ank\BrowserDetector\Browser;
use ank\BrowserDetector\Device;
use ank\BrowserDetector\Os;
use PHPUnit\Framework\TestCase;

class DetectTest extends TestCase
{

    public function testDetectBrowser()
    {

        //qq浏览器
        $userAgent = 'Mozilla/5.0 (Linux; U; Android 8.0.0;zh-cn; DUK-AL20 Build/HUAWEIDUK-AL20) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/57.0.2987.132 MQQBrowser/8.1 Mobile Safari/537.36';
        //遨游浏览器
        // $userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.79 Safari/537.36 Maxthon/5.2.6.1000';
        // $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.90 Safari/537.36 2345Explorer/9.7.0.18838';
        //小米浏览器
        // $userAgent = 'Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; MI 4LTE Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.128 Mobile Safari/537.36 XiaoMi/MiuiBrowser/10.7.3';
        //qq浏览器
        // $userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3641.400 QQBrowser/10.4.3284.400';
        //手机百度
        // $userAgent = 'Mozilla/5.0 (Linux; Android 5.1; OPPO A59s Build/LMY47I; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/48.0.2564.116 Mobile Safari/537.36 T7/7.9 baiduboxapp/9.0.0.10 (Baidu; P1 5.1)';
        //搜狗
        // $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0';
        //华为
        // $userAgent = 'Mozilla/5.0 (Linux; Android 9; HMA-AL00 Build/HUAWEIHMA-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.111 HuaweiBrowser/9.0.1.335 Mobile Safari/537.36';
        //
        // $userAgent = 'Mozilla/5.0 (Linux; Android 8.0.0; SAMSUNG SM-C9000 Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/8.2 Chrome/63.0.3239.111 Mobile Safari/537.36';

        // $userAgent = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko';
        // $userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36 QIHU 360EE';
        // $userAgent = 'Mozilla/5.0 (Linux; Android 8.1.0; vivo X21A Build/OPM1.171019.011; wv) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36 VivoBrowser/6.2.0.5';
        // $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.90 Safari/537.36 2345Explorer/9.7.0.18838';
        // $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534+ (KHTML, like Gecko) BingPreview/1.0b';
        // $userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)';
        $browser = new Browser($userAgent);
        $this->assertFalse($browser->isRobot());
        // echo $browser->getName() . "\\n";
        // echo $browser->getVersion() . "\\n";

        // $os = new Os($userAgent);
        // echo $os->getName() . "\\n";
        // echo $os->getVersion() . "\\n";
        // if ($os->getName() === Os::IOS) {
        //     echo 'You are using an iOS device.';
        // }
        //
        // $device = new Device($userAgent);
        // echo $device->getName() . "\\n";
        // if ($device->getName() === Device::IPAD) {
        //     echo 'You are using an iPad.';
        // }
    }

    public function testSpider()
    {
        $browser = new Browser('', '180.163.128.6');
        $this->assertTrue($browser->isRobot());
    }
}