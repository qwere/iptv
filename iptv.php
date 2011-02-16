#!/usr/bin/php
<?php
require_once "config/config.php";
require_once "include/functions.php";
require_once "Console/Getopt.php";

$progname = basename($argv[0]);
PEAR::setErrorHandling(PEAR_ERROR_DIE, "$progname %s\n");
$options = Console_Getopt::getopt($argv, 'rch', array('reload', 'check', 'help'));
error_reporting(E_ALL ^ E_NOTICE);
if($argv[1]==NULL){usage();}
foreach ($options[0] as $opt) {
    
    switch($opt[0]){
    
    case 'r': case '--reload':
    $reload = 1;
    break;
    case 'c' : case '--check' :
    $checkip = 1;
    break;
    case 'h' : case '--help' :
    usage();
    exit(1);

    }
}

if($checkip){
if(!check_ip()=='NULL'){
echo "Your WAN IP changed to: ".check_ip()."\n";
echo "Reloading stream list...\n"; reload();
} else echo "No changes.\n";

}elseif($reload){ echo "Reloading stream list...\n"; reload();}



?>