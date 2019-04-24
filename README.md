browser-detector
===================
### 主要作用
* 根据UA判断浏览器类型和版本，
* 根据UA判断操作系统和版本号
* 根据UA判断设备

### 检测支持
#### 可检测浏览器
 * QQ浏览器
 * UC浏览器
 * 百度浏览器
 * 360浏览器
 * 猎豹浏览器
 * Vivaldi
 * Opera
 * Opera Mini
 * WebTV
 * Internet Explorer
 * Pocket Internet Explorer
 * Microsoft Edge
 * Konqueror
 * iCab
 * OmniWeb
 * Firebird
 * Firefox
 * Iceweasel
 * Shiretoko
 * Mozilla
 * Amaya
 * Lynx
 * Safari
 * Chrome
 * Navigator
 * GoogleBot
 * Yahoo! Slurp
 * W3C Validator
 * BlackBerry
 * IceCat
 * Nokia S60 OSS Browser
 * Nokia Browser
 * MSN Browser
 * MSN Bot
 * Netscape Navigator
 * Galeon
 * NetPositive
 * Phoenix
 * SeaMonkey
 * Yandex Browser
 * Comodo Dragon
 
#### 可检测操作系统
  * Windows
  * Windows Phone
  * OS X
  * iOS
  * Android
  * Chrome OS
  * Linux
  * SymbOS
  * Nokia
  * BlackBerry
  * FreeBSD
  * OpenBSD
  * NetBSD
  * OpenSolaris
  * SunOS
  * OS2
  * BeOS
  
#### 可检测设备
 * iPad
 * iPhone
 * Windows Phone
 * OPPO手机（OPPO）
 * 红米手机（Redmi）
 * 小米手机（XiaoMi）
 * 乐视手机（Letv）
 * Vivo手机（Vivo）
 * 三星手机（Samsung）
 * 华为手机（HuaWei）
 * 联想手机（Lenovo）
 * HTC
 * 魅族手机（Meizu）
 * 中兴手机（ZTE）
 * 一加手机（ONEPLUS）

### 安装
    
    composer require mokuyu/browser-detector

### 使用

  use ank\BrowserDetector\Browser;
  use ank\BrowserDetector\Device;
  use ank\BrowserDetector\Os;
  $userAgent = 'Mozilla/5.0 (Linux; U; Android 8.0.0;zh-cn; DUK-AL20 Build/HUAWEIDUK-AL20) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/57.0.2987.132 MQQBrowser/8.1 Mobile Safari/537.36';
  $browser   = new Browser($userAgent);
  echo $browser->getName() . "\n";
  echo $browser->getVersion() . "\n";
  if ($browser->getName() === Browser::IE && $browser->getVersion() < 11) {
      echo 'Please upgrade your browser.';
  }

  $os = new Os($userAgent);
  echo $os->getName() . "\n";
  echo $os->getVersion() . "\n";
  if ($os->getName() === Os::IOS) {
      echo 'You are using an iOS device.';
  }

  $device = new Device($userAgent);
  echo $device->getName() . "\n";
  if ($device->getName() === Device::IPAD) {
      echo 'You are using an iPad.';
  }


### 说明
本项目源码clone https://github.com/apanly/browser-detector 因为检测的一些信息会经常变化故自己clone一份维护自己修改使用

### Lecense
PHP Browser is licensed under [The MIT License (MIT)](LICENSE).


### 参考资料
* [sinergi/php-browser-detector](https://github.com/sinergi/php-browser-detector)
* [用户代理检测和浏览器Ua详细分析](http://www.cnblogs.com/hykun/p/Ua.html)


