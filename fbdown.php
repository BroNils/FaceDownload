<?php 
// Dibuat oleh GoogleX
//
//  __|                 |    \ \  /     
// (_ |  _ \  _ \  _` | |  -_)>  <    
//\___|\___/\___/\__, |_|\___|_/\_\   
//               ____/                   
//
// Copyright 2017
//                                                    [-CREDIT-]
//
// Credit:
//- GoogleX

$uri = $_REQUEST['url'];
$saveddir = "output";
$names = "";

if(!file_exists($saveddir)){
	mkdir($saveddir);
}

function ambilKata($param, $kata1, $kata2){
    if(strpos($param, $kata1) === FALSE) return FALSE;
    if(strpos($param, $kata2) === FALSE) return FALSE;
    $start = strpos($param, $kata1) + strlen($kata1);
    $end = strpos($param, $kata2, $start);
    $return = substr($param, $start, $end - $start);
    return $return;
}

function getsource($url,$post=null) {
	    global $names;
		$ch = curl_init($url);
		if($post != null) {
	 	 	curl_setopt($ch, CURLOPT_POST, true);
		  	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
		  	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		  	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		  	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		  	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		  	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		   	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$execc = curl_exec($ch);
		  	curl_close($ch);
			return $execc;
}

if(!$uri){echo "T_T";exit();}elseif(preg_match("/m.facebook.com/", $uri)){
	$xhttp = getsource($uri);
	if(preg_match("/videoID/", $xhttp)){
		$xhttp = str_replace("&quot;",'"',$xhttp);
		$xhttp = str_replace("&amp;","&",$xhttp);
		$vidID = ambilKata($xhttp, '"videoID":"','"');
		if(!$vidID){
			$vidID = ambilKata($xhttp, 'videoID:',',');
		}
		$uriDL = ambilKata($xhttp, '"type":"video","src":"', '"');
		$uriDL = str_replace("\\","",$uriDL);
		
		if(file_put_contents($saveddir."/".$vidID.".mp4", fopen($uriDL, 'r'))){
			echo "\nDone -> <a href='./".$saveddir."/".$vidID.".mp4'>".$vidID."</a>";
		}else{
			echo "Failed saving video !";
		}
	}else{
		echo "No video were found...";exit();
	}
}elseif(preg_match("/facebook.com/", $uri) && preg_match("/videos/",$uri)){
	$xhttp = getsource($uri);
	if(preg_match("/videoID/", $xhttp)){
		$vidID = ambilKata($xhttp, '"videoID":"','"');
		if(!$vidID){
			$vidID = ambilKata($xhttp, 'videoID:',',');
		}
		$uriDL = ambilKata($xhttp, 'sd_src_no_ratelimit:"', '",aspect_ratio:1');
		if(file_put_contents($saveddir."/".$vidID.".mp4", fopen($uriDL, 'r'))){
			echo "\nDone -> <a href='./".$saveddir."/".$vidID.".mp4'>".$vidID."</a>";
		}else{
			echo "Failed saving video !";
		}
	}else{
		echo "No video were found...";exit();
	}
}else{echo ";;;";exit();}
?>
