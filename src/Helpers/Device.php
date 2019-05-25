<?php


namespace Starpeace\Console\Helpers;


class Device
{
    public static function getOSSpecific()
    {
        $osArray = [
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
            '/CYGWIN_NT-5.1/i' => '',
            '/Darwin/i' => 'MAC',
            '/FreeBSD/i' => 'UNIX',
            '/HP-UX/i' => 'UNIX',
            '/IRIX64/i' => 'UNIX',
            '/Linux/i' => 'LINUX',
            '/NetBSD/i' => 'UNIX',
            '/OpenBSD/i' => 'UNIX',
            '/SunOS/i' => 'UNIX',
            '/Unix/i' => 'UNIX',
            '/WIN32|WINNT|Windows/i' => 'WINDOWS',
        ];

        return self::pregMatchAgent($osArray);
    }

    public static function getOS()
    {
        $osArray = [
            '/win|windows|WIN32|WINNT|Windows/i' => 'WINDOWS',
            '/macintosh|mac os x|mac_powerpc/i' => 'MAC',
            '/linux/i' => 'LINUX',
            '/ubuntu/i' => 'UBUNTU',
            '/iphone/i' => 'IPHONE',
            '/ipod/i' => 'IPOD',
            '/ipad/i' => 'IPAD',
            '/android/i' => 'ANDROID',
            '/blackberry/i' => 'BLACKBERRY',
            '/webos/i' => 'MOBILE',
            '/CYGWIN_NT-5.1/i' => 'WINMOBILE',
            '/Darwin/i' => 'MAC',
            '/FreeBSD|HP-UX|IRIX64|NetBSD|OpenBSD|SunOS|Unix/i' => 'UNIX',
        ];

        return self::pregMatchAgent($osArray);
    }

    public static function pregMatchAgent($deviceArray)
    {
        $data = cli() ? php_uname('s') : $_SERVER['HTTP_USER_AGENT'];

        foreach ($deviceArray as $regex => $value) {
            if (preg_match($regex, $data)) {
                $device = $value;
            }
        }

        return $device ?? 'UNKNOWN';
    }

    public static function getBrowser()
    {
        $browserArray = [
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        ];

        return self::pregMatchAgent($browserArray);
    }

    public static function defineOSVars()
    {
        define('APP_PATH', dirname(dirname(__DIR__)));

        switch (self::getOSSpecific()) {
            case 'LINUX':
                self::defineLinuxVars();
                break;
            case 'MAC':
                self::defineMacVars();
                break;
            default:
                self::defineWindowsVars();
                break;
        }
    }

    public static function defineWindowsVars()
    {
        define('BASE_WEB_PATH', path_join('D:', 'INETPUB', 'wwwroot'));
        define('BASE_DATA_PATH', path_join('D:', 'FIVE', 'Data'));
        define('BASE_LOGS_PATH', path_join(BASE_DATA_PATH, 'Logs'));
        define('BASE_SERVER_PATH', path_join('D:', 'FIVE', 'Servers'));
        define('BASE_COMMON_PATH', path_join('D:', 'FIVE', 'Common'));
        define('BASE_TESTING_PATH', path_join(APP_PATH, 'Testing'));
    }

    public static function defineLinuxVars()
    {
        define('BASE_LINUX_PATH', path_join('', 'var', 'lib', 'five'));
        define('BASE_WEB_PATH', path_join(BASE_LINUX_PATH, 'www'));
        define('BASE_DATA_PATH', path_join(BASE_LINUX_PATH,'data'));
        define('BASE_SERVER_PATH', path_join(BASE_LINUX_PATH, 'servers'));
        define('BASE_COMMON_PATH', path_join(BASE_LINUX_PATH, 'common'));
        define('BASE_TESTING_PATH', path_join(APP_PATH, 'Testing'));
    }

    public static function defineMacVars()
    {
        define('BASE_MAC_PATH', path_join('', 'var', 'lib', 'five'));
        define('BASE_WEB_PATH', path_join(BASE_MAC_PATH, 'www'));
        define('BASE_DATA_PATH', path_join(BASE_MAC_PATH,'data'));
        define('BASE_SERVER_PATH', path_join(BASE_MAC_PATH, 'servers'));
        define('BASE_COMMON_PATH', path_join(BASE_MAC_PATH, 'common'));
        define('BASE_TESTING_PATH', path_join(APP_PATH, 'Testing'));
    }

    public static function isCLI()
    {
        return (php_sapi_name() === 'cli');
    }
}