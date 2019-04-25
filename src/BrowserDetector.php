<?php

namespace ank\BrowserDetector;

class BrowserDetector implements DetectorInterface
{
    const FUNC_PREFIX = 'checkBrowser';

    protected static $userAgentString;

    /**
     * @var Browser
     */
    protected static $browser;

    protected static $browsersList = array(
        // well-known, well-used
        // Special Notes:
        // (1) Opera must be checked before FireFox due to the odd
        //     user agents used in some older versions of Opera
        // (2) WebTV is strapped onto Internet Explorer so we must
        //     check for WebTV before IE
        // (3) Because of Internet Explorer 11 using
        //     "Mozilla/5.0 ([...] Trident/7.0; rv:11.0) like Gecko"
        //     as user agent, tests for IE must be run before any
        //     tests checking for "Mozilla"
        // (4) (deprecated) Galeon is based on Firefox and needs to be
        //     tested before Firefox is tested
        // (5) OmniWeb is based on Safari so OmniWeb check must occur
        //     before Safari
        // (6) Netscape 9+ is based on Firefox so Netscape checks
        //     before FireFox are necessary
        // (7) Microsoft Edge must be checked before Chrome and Safari
        // (7) Vivaldi must be checked before Chrome
        'WebTv'                  => 'WebTv',
        'InternetExplorer'       => 'IE',
        'Edge'                   => 'Edge',
        'Opera'                  => 'Opera',
        'Vivaldi'                => 'Vivaldi',
        'Dragon'                 => 'Dragon',
        'Galeon'                 => 'Galeon',
        'NetscapeNavigator9Plus' => '网景',
        'SeaMonkey'              => 'SeaMonkey',
        'Firefox'                => '火狐',
        'Yandex'                 => 'Yandex',
        'BAIDU'                  => '百度',
        'UC'                     => 'UC',
        'QQ'                     => 'QQ',
        'LIEBAO'                 => '猎豹',
        //遨游
        'Maxthon'                => '遨游',
        'SouGou'                 => '搜狗',
        '2345Explorer'           => '2345',
        'MiuiBrowser'            => '小米',
        'baiduboxapp'            => '百度APP',
        'HuaWei'                 => '华为',
        'Samsung'                => '三星',
        'Oppo'                   => 'Oppo',
        //360安全
        '360SE'                  => '360安全',
        //360急速
        '360EE'                  => '360急速',
        'Vivo'                   => 'Vivo',
        //chrome内核的国内浏览器要放在它之前,只要里面带有chrome都要放它前面
        'Chrome'                 => '谷歌',
        'OmniWeb'                => 'OmniWeb',
        // common mobile
        'Android'                => '安卓',
        'BlackBerry'             => '黑莓',
        'Nokia'                  => '诺基亚',
        'Gsa'                    => 'Gsa',
        // common bots
        'Robot'                  => '蜘蛛',
        // WebKit base check (post mobile and others)
        'Safari'                 => 'Safari',
        // everyone else
        'NetPositive'            => 'NetPositive',
        'Firebird'               => '火鸟',
        'Konqueror'              => 'Konqueror',
        'Icab'                   => 'Icab',
        'Phoenix'                => 'Phoenix',
        'Amaya'                  => 'Amaya',
        'Lynx'                   => 'Lynx',
        'Shiretoko'              => 'Shiretoko',
        'IceCat'                 => 'IceCat',
        'Iceweasel'              => 'Iceweasel',
        'Mozilla'                => 'Mozilla',
        /* Mozilla is such an open standard that you must check it last */
    );

    /**
     * Routine to determine the browser type.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    public static function detect(Browser $browser, UserAgent $userAgent = null)
    {
        self::$browser = $browser;
        if (is_null($userAgent)) {
            $userAgent = self::$browser->getUserAgent();
        }
        self::$userAgentString = $userAgent->getUserAgentString();

        self::$browser->setName(Browser::UNKNOWN);
        self::$browser->setVersion(Browser::VERSION_UNKNOWN);

        self::checkChromeFrame();
        self::checkFacebookWebView();

        foreach (self::$browsersList as $browserName => $title) {
            $funcName = self::FUNC_PREFIX . $browserName;

            if (self::$funcName($browserName, $title)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user is using Chrome Frame.
     *
     * @return bool
     */
    public static function checkChromeFrame()
    {
        if (strpos(self::$userAgentString, 'chromeframe') !== false) {
            self::$browser->setIsChromeFrame(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user is using Facebook.
     *
     * @return bool
     */
    public static function checkFacebookWebView()
    {
        if (strpos(self::$userAgentString, 'FBAV') !== false) {
            self::$browser->setIsFacebookWebView(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user is using a BlackBerry.
     *
     * @return bool
     */
    public static function checkBrowserBlackBerry($name, $title)
    {
        if (stripos(self::$userAgentString, 'blackberry') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'BlackBerry'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        } elseif (stripos(self::$userAgentString, 'BB10') !== false) {
            $aresult = explode('Version/10.', self::$userAgentString);
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion('10.' . $aversion[0]);
            }
            self::$browser->setName($title);
            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is a robot.
     *
     * @return bool
     */
    public static function checkBrowserRobot($name, $title)
    {
        if (stripos(self::$userAgentString, 'bot') !== false ||
            stripos(self::$userAgentString, 'spider') !== false ||
            stripos(self::$userAgentString, 'crawler') !== false ||
            stripos(self::$userAgentString, 'spider') !== false
        ) {
            self::$browser->setIsRobot(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Internet Explorer.
     *
     * @return bool
     */
    public static function checkBrowserInternetExplorer($name, $title)
    {
        // Test for v1 - v1.5 IE
        if (stripos(self::$userAgentString, 'microsoft internet explorer') !== false) {
            self::$browser->setName($title);
            self::$browser->setVersion('1.0');
            $aresult = stristr(self::$userAgentString, '/');
            if (preg_match('/308|425|426|474|0b1/i', $aresult)) {
                self::$browser->setVersion('1.5');
            }

            return true;
        } // Test for versions > 1.5 and < 11 and some cases of 11
        else {
            if (stripos(self::$userAgentString, 'msie') !== false && stripos(self::$userAgentString, 'opera') === false
            ) {
                // See if the browser is the odd MSN Explorer
                if (stripos(self::$userAgentString, 'msnb') !== false) {
                    $aresult = explode(' ', stristr(str_replace(';', '; ', self::$userAgentString), 'MSN'));
                    self::$browser->setName($title);
                    if (isset($aresult[1])) {
                        self::$browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                    }

                    return true;
                }
                $aresult = explode(' ', stristr(str_replace(';', '; ', self::$userAgentString), 'msie'));
                self::$browser->setName($title);
                if (isset($aresult[1])) {
                    self::$browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                }
                // See https://msdn.microsoft.com/en-us/library/ie/hh869301%28v=vs.85%29.aspx
                // Might be 11, anyway !
                if (stripos(self::$userAgentString, 'trident') !== false) {
                    preg_match('/rv:(\d+\.\d+)/', self::$userAgentString, $matches);
                    if (isset($matches[1])) {
                        self::$browser->setVersion($matches[1]);
                    }

                    // At this poing in the method, we know the MSIE and Trident
                    // strings are present in the $userAgentString. If we're in
                    // compatibility mode, we need to determine the true version.
                    // If the MSIE version is 7.0, we can look at the Trident
                    // version to *approximate* the true IE version. If we don't
                    // find a matching pair, ( e.g. MSIE 7.0 && Trident/7.0 )
                    // we're *not* in compatibility mode and the browser really
                    // is version 7.0.
                    if (stripos(self::$userAgentString, 'MSIE 7.0;')) {
                        if (stripos(self::$userAgentString, 'Trident/7.0;')) {
                            // IE11 in compatibility mode
                            self::$browser->setVersion('11.0');
                            self::$browser->setIsCompatibilityMode(true);
                        } elseif (stripos(self::$userAgentString, 'Trident/6.0;')) {
                            // IE10 in compatibility mode
                            self::$browser->setVersion('10.0');
                            self::$browser->setIsCompatibilityMode(true);
                        } elseif (stripos(self::$userAgentString, 'Trident/5.0;')) {
                            // IE9 in compatibility mode
                            self::$browser->setVersion('9.0');
                            self::$browser->setIsCompatibilityMode(true);
                        } elseif (stripos(self::$userAgentString, 'Trident/4.0;')) {
                            // IE8 in compatibility mode
                            self::$browser->setVersion('8.0');
                            self::$browser->setIsCompatibilityMode(true);
                        }
                    }
                }

                return true;
            } // Test for versions >= 11
            else {
                if (stripos(self::$userAgentString, 'trident') !== false) {
                    self::$browser->setName($title);

                    preg_match('/rv:(\d+\.\d+)/', self::$userAgentString, $matches);
                    if (isset($matches[1])) {
                        self::$browser->setVersion($matches[1]);

                        return true;
                    } else {
                        return false;
                    }
                } // Test for Pocket IE
                else {
                    if (stripos(self::$userAgentString, 'mspie') !== false ||
                        stripos(
                            self::$userAgentString,
                            'pocket'
                        ) !== false
                    ) {
                        $aresult = explode(' ', stristr(self::$userAgentString, 'mspie'));
                        self::$browser->setName($title);

                        if (stripos(self::$userAgentString, 'mspie') !== false) {
                            if (isset($aresult[1])) {
                                self::$browser->setVersion($aresult[1]);
                            }
                        } else {
                            $aversion = explode('/', self::$userAgentString);
                            if (isset($aversion[1])) {
                                self::$browser->setVersion($aversion[1]);
                            }
                        }

                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Determine if the browser is Opera.
     *
     * @return bool
     */
    public static function checkBrowserOpera($name, $title)
    {
        if (stripos(self::$userAgentString, 'opera mini') !== false) {
            $resultant = stristr(self::$userAgentString, 'opera mini');
            if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', $resultant);
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    self::$browser->setVersion($aversion[0]);
                }
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera mini'));
                if (isset($aversion[1])) {
                    self::$browser->setVersion($aversion[1]);
                }
            }
            self::$browser->setName($title);

            return true;
        } elseif (stripos(self::$userAgentString, 'OPiOS') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'OPiOS'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        } elseif (stripos(self::$userAgentString, 'opera') !== false) {
            $resultant = stristr(self::$userAgentString, 'opera');
            if (preg_match('/Version\/(1[0-2].*)$/', $resultant, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
            } elseif (preg_match('/\//', $resultant)) {
                $aresult = explode('/', str_replace('(', ' ', $resultant));
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    self::$browser->setVersion($aversion[0]);
                }
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera'));
                self::$browser->setVersion(isset($aversion[1]) ? $aversion[1] : '');
            }
            self::$browser->setName($title);

            return true;
        } elseif (stripos(self::$userAgentString, ' OPR/') !== false) {
            self::$browser->setName($title);
            if (preg_match('/OPR\/([\d\.]*)/', self::$userAgentString, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Chrome.
     *
     * @return bool
     */
    public static function checkBrowserChrome($name, $title)
    {
        if (stripos(self::$userAgentString, 'Chrome') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Chrome'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        } elseif (stripos(self::$userAgentString, 'CriOS') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'CriOS'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Vivaldi.
     *
     * @return bool
     */
    public static function checkBrowserVivaldi($name, $title)
    {
        if (stripos(self::$userAgentString, 'Vivaldi') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Vivaldi'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Microsoft Edge.
     *
     * @return bool
     */
    public static function checkBrowserEdge($name, $title)
    {
        if (stripos(self::$userAgentString, 'Edge') !== false) {
            $version = explode('Edge/', self::$userAgentString);
            if (isset($version[1])) {
                self::$browser->setVersion((float) $version[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Google Search Appliance.
     *
     * @return bool
     */
    public static function checkBrowserGsa($name, $title)
    {
        if (stripos(self::$userAgentString, 'GSA') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'GSA'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is WebTv.
     *
     * @return bool
     */
    public static function checkBrowserWebTv($name, $title)
    {
        if (stripos(self::$userAgentString, 'webtv') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'webtv'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is NetPositive.
     *
     * @return bool
     */
    public static function checkBrowserNetPositive($name, $title)
    {
        if (stripos(self::$userAgentString, 'NetPositive') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'NetPositive'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion(str_replace(array('(', ')', ';'), '', $aversion[0]));
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Galeon.
     *
     * @return bool
     */
    public static function checkBrowserGaleon($name, $title)
    {
        if (stripos(self::$userAgentString, 'galeon') !== false) {
            $aresult  = explode(' ', stristr(self::$userAgentString, 'galeon'));
            $aversion = explode('/', $aresult[0]);
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Konqueror.
     *
     * @return bool
     */
    public static function checkBrowserKonqueror($name, $title)
    {
        if (stripos(self::$userAgentString, 'Konqueror') !== false) {
            $aresult  = explode(' ', stristr(self::$userAgentString, 'Konqueror'));
            $aversion = explode('/', $aresult[0]);
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is iCab.
     *
     * @return bool
     */
    public static function checkBrowserIcab($name, $title)
    {
        if (stripos(self::$userAgentString, 'icab') !== false) {
            $aversion = explode(' ', stristr(str_replace('/', ' ', self::$userAgentString), 'icab'));
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is OmniWeb.
     *
     * @return bool
     */
    public static function checkBrowserOmniWeb($name, $title)
    {
        if (stripos(self::$userAgentString, 'omniweb') !== false) {
            $aresult  = explode('/', stristr(self::$userAgentString, 'omniweb'));
            $aversion = explode(' ', isset($aresult[1]) ? $aresult[1] : '');
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Phoenix.
     *
     * @return bool
     */
    public static function checkBrowserPhoenix($name, $title)
    {
        if (stripos(self::$userAgentString, 'Phoenix') !== false) {
            $aversion = explode('/', stristr(self::$userAgentString, 'Phoenix'));
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Firebird.
     *
     * @return bool
     */
    public static function checkBrowserFirebird($name, $title)
    {
        if (stripos(self::$userAgentString, 'Firebird') !== false) {
            $aversion = explode('/', stristr(self::$userAgentString, 'Firebird'));
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Netscape Navigator 9+.
     *
     * @return bool
     */
    public static function checkBrowserNetscapeNavigator9Plus($name, $title)
    {
        if (stripos(self::$userAgentString, 'Firefox') !== false &&
            preg_match('/Navigator\/([^ ]*)/i', self::$userAgentString, $matches)
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName($title);

            return true;
        } elseif (stripos(self::$userAgentString, 'Firefox') === false &&
            preg_match('/Netscape6?\/([^ ]*)/i', self::$userAgentString, $matches)
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Shiretoko.
     *
     * @return bool
     */
    public static function checkBrowserShiretoko($name, $title)
    {
        if (stripos(self::$userAgentString, 'Mozilla') !== false &&
            preg_match('/Shiretoko\/([^ ]*)/i', self::$userAgentString, $matches)
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Ice Cat.
     *
     * @return bool
     */
    public static function checkBrowserIceCat($name, $title)
    {
        if (stripos(self::$userAgentString, 'Mozilla') !== false &&
            preg_match('/IceCat\/([^ ]*)/i', self::$userAgentString, $matches)
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Nokia.
     *
     * @return bool
     */
    public static function checkBrowserNokia($name, $title)
    {
        if (preg_match("/Nokia([^\/]+)\/([^ SP]+)/i", self::$userAgentString, $matches)) {
            self::$browser->setVersion($matches[2]);
            if (stripos(self::$userAgentString, 'Series60') !== false ||
                strpos(self::$userAgentString, 'S60') !== false
            ) {
                self::$browser->setName($title);
            } else {
                self::$browser->setName($title);
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Firefox.
     *
     * @return bool
     */
    public static function checkBrowserFirefox($name, $title)
    {
        if (stripos(self::$userAgentString, 'safari') === false) {
            if (preg_match("/Firefox[\/ \(]([^ ;\)]+)/i", self::$userAgentString, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
                self::$browser->setName($title);

                return true;
            } elseif (preg_match('/Firefox$/i', self::$userAgentString, $matches)) {
                self::$browser->setVersion('');
                self::$browser->setName($title);

                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the browser is SeaMonkey.
     *
     * @return bool
     */
    public static function checkBrowserSeaMonkey($name, $title)
    {
        if (stripos(self::$userAgentString, 'safari') === false) {
            if (preg_match("/SeaMonkey[\/ \(]([^ ;\)]+)/i", self::$userAgentString, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
                self::$browser->setName($title);

                return true;
            } elseif (preg_match('/SeaMonkey$/i', self::$userAgentString, $matches)) {
                self::$browser->setVersion('');
                self::$browser->setName($title);

                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the browser is Iceweasel.
     *
     * @return bool
     */
    public static function checkBrowserIceweasel($name, $title)
    {
        if (stripos(self::$userAgentString, 'Iceweasel') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Iceweasel'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Mozilla.
     *
     * @return bool
     */
    public static function checkBrowserMozilla($name, $title)
    {
        if (stripos(self::$userAgentString, 'mozilla') !== false &&
            preg_match('/rv:[0-9].[0-9][a-b]?/i', self::$userAgentString) &&
            stripos(self::$userAgentString, 'netscape') === false
        ) {
            $aversion = explode(' ', stristr(self::$userAgentString, 'rv:'));
            preg_match('/rv:[0-9].[0-9][a-b]?/i', self::$userAgentString, $aversion);
            self::$browser->setVersion(str_replace('rv:', '', $aversion[0]));
            self::$browser->setName($title);

            return true;
        } elseif (stripos(self::$userAgentString, 'mozilla') !== false &&
            preg_match('/rv:[0-9]\.[0-9]/i', self::$userAgentString) &&
            stripos(self::$userAgentString, 'netscape') === false
        ) {
            $aversion = explode('', stristr(self::$userAgentString, 'rv:'));
            self::$browser->setVersion(str_replace('rv:', '', $aversion[0]));
            self::$browser->setName($title);

            return true;
        } elseif (stripos(self::$userAgentString, 'mozilla') !== false &&
            preg_match('/mozilla\/([^ ]*)/i', self::$userAgentString, $matches) &&
            stripos(self::$userAgentString, 'netscape') === false
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Lynx.
     *
     * @return bool
     */
    public static function checkBrowserLynx($name, $title)
    {
        if (stripos(self::$userAgentString, 'lynx') !== false) {
            $aresult  = explode('/', stristr(self::$userAgentString, 'Lynx'));
            $aversion = explode(' ', (isset($aresult[1]) ? $aresult[1] : ''));
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Amaya.
     *
     * @return bool
     */
    public static function checkBrowserAmaya($name, $title)
    {
        if (stripos(self::$userAgentString, 'amaya') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Amaya'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Safari.
     *
     * @return bool
     */
    public static function checkBrowserSafari($name, $title)
    {
        if (stripos(self::$userAgentString, 'Safari') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Version'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            } else {
                self::$browser->setVersion(Browser::VERSION_UNKNOWN);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Yandex.
     *
     * @return bool
     */
    public static function checkBrowserYandex($name, $title)
    {
        if (stripos(self::$userAgentString, 'YaBrowser') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'YaBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Comodo Dragon / Ice Dragon / Chromodo.
     *
     * @return bool
     */
    public static function checkBrowserDragon($name, $title)
    {
        if (stripos(self::$userAgentString, 'Dragon') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Dragon'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Android.
     *
     * @return bool
     */
    public static function checkBrowserAndroid($name, $title)
    {
        // Navigator
        if (stripos(self::$userAgentString, 'Android') !== false) {
            if (preg_match('/Version\/([\d\.]*)/i', self::$userAgentString, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
            } else {
                self::$browser->setVersion(Browser::VERSION_UNKNOWN);
            }
            self::$browser->setName($title);

            return true;
        }

        return false;
    }

    /* check uc browser*/
    public static function checkBrowserUC($name, $title)
    {
        if (stripos(self::$userAgentString, 'UBrowser') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'UBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);
            return true;
        }

        if (stripos(self::$userAgentString, 'UCBrowser') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'UCBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);
            return true;
        }

        return false;
    }

    /* check baidu browser*/
    public static function checkBrowserBAIDU($name, $title)
    {
        if (stripos(self::$userAgentString, 'BIDUBrowser') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'BIDUBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);
            return true;
        }

        return false;
    }

    /* check qq browser*/
    public static function checkBrowserQQ($name, $title)
    {
        if (stripos(self::$userAgentString, 'QQBrowser') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'QQBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName($title);
            return true;
        }

        return false;
    }

    /* check 猎豹 browser*/
    public static function checkBrowserLIEBAO($name, $title)
    {
        if (stripos(self::$userAgentString, 'LBBROWSER') !== false) {
            self::$browser->setName($title);
            return true;
        }

        return false;
    }
    public static function checkBrowserMaxthon($name, $title)
    {
        if (preg_match('/Maxthon\/(.+)/i', self::$userAgentString, $mat)) {
            self::$browser->setName($title);
            self::$browser->setVersion($mat[1]);
            return true;
        }

        return false;
    }
    public static function checkBrowser2345Explorer($name, $title)
    {
        if (preg_match('/(Mb)?2345Explorer\/([\.\d]+)/i', self::$userAgentString, $mat)) {
            self::$browser->setName($title);
            self::$browser->setVersion($mat[2]);
            return true;
        }

        return false;
    }
    public static function checkBrowserMiuiBrowser($name, $title)
    {
        if (preg_match('/MiuiBrowser\/([\.\d]+)/i', self::$userAgentString, $mat)) {
            self::$browser->setName($title);
            self::$browser->setVersion($mat[1]);
            return true;
        }

        return false;
    }
    public static function checkBrowserBaiduBoxApp($name, $title)
    {
        if (preg_match('/baiduboxapp\/([\.\d]+)/i', self::$userAgentString, $mat)) {
            self::$browser->setName($title);
            self::$browser->setVersion($mat[1]);
            return true;
        }

        return false;
    }
    public static function checkBrowserSouGou($name, $title)
    {
        if (preg_match('/\sSE\s(.*?)\sMetaSr/i', self::$userAgentString, $mat)) {
            self::$browser->setName($title);
            self::$browser->setVersion($mat[1]);
            return true;
        }

        return false;
    }
    public static function checkBrowserHuaWei($name, $title)
    {
        // Mozilla/5.0 (Linux; Android 5.1; zh-cn; HUAWEI CUN-AL00 Build/CUN-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36
        //Mozilla/5.0 (Linux; Android 9; COR-AL00 Build/HUAWEICOR-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36
        //Mozilla/5.0 (Linux; Android 7.0; BLN-AL40 Build/HONORBLN-AL40) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.0.0 Mobile Safari/537.36
        if (preg_match('/HuaweiBrowser\/([\.\d]+)/i', self::$userAgentString, $mat)) {
            self::$browser->setName($title);
            self::$browser->setVersion($mat[1]);
            return true;
        }

        if (stripos(self::$userAgentString, ' HUAWEI ') !== false
            || stripos(self::$userAgentString, 'Build/HUAWEI') !== false
            || stripos(self::$userAgentString, 'Build/HONOR') !== false
        ) {
            self::$browser->setName($title);
            self::$browser->setVersion('0');
            return true;
        }
        return false;
    }
    public static function checkBrowserSamsung($name, $title)
    {
        if (preg_match('/SamsungBrowser\/([\.\d]+)/i', self::$userAgentString, $mat)) {
            self::$browser->setName($title);
            self::$browser->setVersion($mat[1]);
            return true;
        }

        return false;
    }
    public static function checkBrowserVivo($name, $title)
    {
        if (preg_match('/VivoBrowser\/([\.\d]+)/i', self::$userAgentString, $mat)) {
            self::$browser->setName($title);
            self::$browser->setVersion($mat[1]);
            return true;
        }

        return false;
    }
    public static function checkBrowserOppo($name, $title)
    {
        if (preg_match('/OppoBrowser\/([\.\d]+)/i', self::$userAgentString, $mat)) {
            self::$browser->setName($title);
            self::$browser->setVersion($mat[1]);
            return true;
        }

        return false;
    }
    public static function checkBrowser360SE($name, $title)
    {
        if (stripos(self::$userAgentString, '360SE') !== false) {
            self::$browser->setName($title);
            if (preg_match('/Chrome\/([\.\d]+)/i', self::$userAgentString, $mat)) {
                self::$browser->setVersion($mat[1]);
            } else {
                self::$browser->setVersion('0');
            }
            return true;
        }
        return false;
    }
    public static function checkBrowser360EE($name, $title)
    {
        if (stripos(self::$userAgentString, '360EE') !== false) {
            self::$browser->setName($title);
            if (preg_match('/Chrome\/([\.\d]+)/i', self::$userAgentString, $mat)) {
                self::$browser->setVersion($mat[1]);
            } else {
                self::$browser->setVersion('0');
            }
            return true;
        }
        return false;
    }
}
