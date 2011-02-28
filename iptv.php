#!/usr/bin/php -q
<?php
require_once "config/config.php";
require_once "include/functions.php";
require_once "include/revision_nr.php";
error_reporting(E_ALL ^ E_NOTICE);
$directory=dirname(__FILE__);

reload();
kill_pids();
beep(1);
echo "\033[1;44m\n********************\nIPTV r".REVISION_NR."\nWAN IP: ".get_ip()."\n********************\033[0m\n";
exec(dirname(__FILE__)."/stream_list.sh\n");

for(;;) {

    
    if(check_ip()){

        echo "\033[1;44m\nYour WAN IP is changed to: ".get_ip()."\n";
        echo "Reloading stream list...\n"; reload();
        kill_pids();
        beep(1);
        exec(dirname(__FILE__)."/stream_list.sh");
	echo "Restarted.\033[0m\n";
	} elseif(!ports_check()) {
        kill_pids();
        echo "\033[1;41m\nReloading stream list...\n"; reload();
        beep(2);
        echo "VLC streams has crashed and has been restarted.\nNOTE: You might be applied to the wrong stream. \033[0m\n";
        exec(dirname(__FILE__)."/stream_list.sh");
	}
sleep(15);

}

?>