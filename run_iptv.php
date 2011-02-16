#!/usr/bin/php
<?php
require_once 'include/functions.php';
require_once 'config/config.php';
error_reporting(E_ALL ^ E_NOTICE);


while (1 < 2) {

    if(!check_ip()=='NULL'){
	sleep(14);
	reload();
	exec('/etc/rc.d/rc.iptv restart');
	echo "Restarted!";
	} elseif(!system("ps ax | awk '($5 ~ /vlc/) { print $1 }'")) { exec('/etc/rc.d/rc.iptv restart');}
sleep(30);
}

?>
