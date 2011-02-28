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

function get_ip(){
    preg_match('/(\d{1,3}\.){3}\d{1,3}/', file_get_contents('http://ip1.dynupdate.no-ip.com/'), $wan_ip);

return $wan_ip[0];
}

function check_ip(){
global $cache_file;


    if(file_exists($cache_file)) {


       $fp=fopen($cache_file, 'r');


       preg_match('/(\d{1,3}\.){3}\d{1,3}/', fgets($fp), $cached_ip);


       if($cached_ip[0]!=get_ip()){
       
       $fpw = fopen($cache_file, "w+");
       fputs($fpw, get_ip());
       fclose($fpw);

       return 1;
       }


        }  else {

        $fp = fopen($cache_file, "a+");

        fputs($fp, get_ip());

        return 1;
        }


   fclose($fp);


   return NULL;
}

function reload(){
global $stream, $www, $ip;

login();
$sessid=sessid();
$i=0;
$fp=fopen("stream_list.sh", "w+");
unlink("playlist.asx");
$playlist=fopen("playlist.asx", "w+");
fputs($fp, "#!/bin/sh\n");
fputs($playlist, '<ASX version = "3.0">');
foreach($stream as $str){
    fputs($fp, "cvlc 'http://iptv.bg/asx/btc/".$str['quality']."/$sessid/".$str['channel'].".asx' --sout '#standard{access=http,mux=asf,dst=$ip:80$i/".$str['channel']."}' --pidfile pid/80$i.pid -d\n");
    fputs($playlist, '<ENTRY><TITLE>'.$str['name'].' - '.$organisation.'</TITLE><REF HREF = "http://'.$ip.':80'.$i.'/'.$str['channel'].'" /></ENTRY>');
    $i++;
}
fputs($playlist, '</ASX>');
fclose($playlist);
fclose($fp);
chmod("stream_list.sh", 700);


}

function ports_check(){
    global $stream, $ip;
    $i=0;
    $r=0;
    $a=count($stream);
    while($i <= $a--){
    
    if(@fsockopen($ip, "80".$i , $errno, $errstr, 5)){
        $r++;
    }

    $i++;
    }
    if($i==$r){ return 1;  } else { return 0; }
    
};

function kill_pids(){
global $stream, $directory;
$i=0;
$a=count($stream);

    while ($i <= $a--) {
    if(file_exists("pid/80$i.pid")){

    $fp=fopen("pid/80$i.pid", "r");
    exec("kill -9 ".fread($fp, filesize("pid/80$i.pid")));
    fclose($fp);
    unlink("pid/80$i.pid");
    }

    $i++;
    }

}

function beep ($int_beeps = 1) {
for ($i = 0; $i < $int_beeps; $i++){ $beep.="\x07";
print $beep;}
}

        
?>
