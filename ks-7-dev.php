<?php

if (!empty($_SERVER['REMOTE_ADDR'])) {
  $ipdata		= json_decode(file_get_contents('http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']));
  $tz		= $ipdata->timezone;
} else {
  $tz		= 'GMT';
}

echo "#

timezone $tz
";

readfile('../os/ks-7-dev.cfg');

?>


