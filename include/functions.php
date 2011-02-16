<?php
function login() {
global $email, $password;
$url = "http://iptv.bg/watch";
$ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.4) Gecko/2008102920 Firefox/3.0.4" );
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
          'submit' => "Вход",
	  'login' => $email,
          'password' => $password,
      )));
      curl_exec($ch);
	  curl_close ($ch);

}

function sessid(){
$key = explode("SSID", fread(fopen('cookie.txt', 'r'), filesize('cookie.txt')));
unlink('cookie.txt');
return preg_replace('/[^a-z0-9]/', '', $key[1]);
}

function check_ip(){
global $cache_file;

    preg_match('/(\d{1,3}\.){3}\d{1,3}/', file_get_contents('http://checkip.dyndns.com'), $wan_ip);

    if(file_exists($cache_file)) {
        $fp = fopen($cache_file, "w+");

       preg_match('/(\d{1,3}\.){3}\d{1,3}/', fgets($fp), $cached_ip);

       if($cached_ip[0]!=$wan_ip[0]){

       fputs($fp, $wan_ip[0]);
       
       return $wan_ip[0];
       }


        }  else {

        $fp = fopen($cache_file, "a+");

        fputs($fp, $wan_ip[0]);
        
        return $wan_ip[0];
        }


   fclose($fp);

   return NULL;
}

function usage(){
    global $progname;
    $stderr = fopen("php://stderr", "w");
    
    fwrite($stderr, "Usage: $progname [option]
 Options:
       -r, --reload       reload channel list
       -c, --check        check ip
       -h, --help         this help message
");
    fclose($stderr);
}

function reload(){
global $stream;

login();
$sessid=sessid();
$i=0;

$fp=fopen('/usr/share/iptv/stream_list.sh', "w+");
$fpp=fopen('/usr/share/iptv/example/stream_list.sh', 'r');
fputs($fp, fread($fpp, filesize('/usr/share/iptv/example/stream_list.sh')));
fclose($fpp);
foreach($stream as $str)
{
    fputs($fp, "cvlc 'http://iptv.bg/asx/btc/".$str['quality']."/$sessid/".$str['name'].".asx' --sout '#standard{access=http,mux=asf,dst=$ip:80$i/".$str['name'].".asx}' &\n");
    $i++;
}
fclose($fp);
}

?>
