<?php
$releases	= array(7, 6); 
$resut		= '';
/*
$release	= $_GET["release"] ?: '6';
$repo		= $_GET["repo"] ?: 'os';
$arch		= $_GET["arch"] ?: 'x86_64';
*/
if (!empty($_SERVER['REMOTE_ADDR'])) {
  $ipdata		= json_decode(file_get_contents('http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']));
  $tz		= $ipdata->timezone;
} else {
  $tz		= 'GMT';
}

if (empty($ipdata->country) || empty($ipdata->city)) {
  $address = 'Unknown Location';
} else {
  $address = $ipdata->country . ", " . $ipdata->city;
}

foreach ( $releases as $r ) {

  $mirrorlist 	= file_get_contents("http://mirrorlist.centos.org/?release=$r&repo=os&arch=x86_64");
  $mirrors 	= explode("\n", $mirrorlist);
  $mirrors      = array_filter($mirrors);

  if ( count($mirrors) > 2 ) {
    do {
       $mirror 	= $mirrors[array_rand($mirrors)];
    } while ( ! filter_var($mirror, FILTER_VALIDATE_URL) );
  } else {
       $mirror	= "http://mirrors.kernel.org/centos/$r/os/x86_64/";
  }
  $resut .= "set url$r $mirror\n";
}

echo "#!ipxe

$resut
set url $mirror
set tz  $tz
set geo $address
";

readfile('script.body');

?>

