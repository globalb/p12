<?php

function getUrl($url , $postData = false, $invisible = false , $image = false){
    $html = false;
    if(function_exists("file_get_contents") && ($image === true || ($invisible === false && stripos($url , 'ocalprice.com') < 1 && stripos($url , 'liexpress') < 1 && stripos($url , 'inthebox') < 1 && stripos($url , "tmall.") < 1 && stripos($url , "taobao.") < 1 && stripos($url , "taobaocdn.") < 1  && stripos($url , "newark.") < 1 && stripos($url , "alibaba.") < 1 && stripos($url , "1188.") < 1 && stripos($url , "1688.") < 1 && $postData == false) ) ){
        $html = @file_get_contents($url);    
    }
    if(!$html){
        ///print_r($postData);
        if($invisible === true){
            $apiKEY = file_get_contents(realpath(dirname(__FILE__) . '/../../../ApiKey.txt'));
            if(strlen($apiKEY) > 5){
                $html = getApiUrl($url , $apiKEY);
            }else{
                $html = _curl($url, $postData, $invisible);
            }
        }else{
            $html = _curl($url, $postData, $invisible);
        }
    }
    return $html;
}

function _curl($url , $postData = false, $invisible = false){
        $user_agent = getUserAgents();
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$host = getHost($url);
		//echo $host;exit;
		
		if(strpos($url , "tmall.") > 1 || strpos($url , "taobao.") > 1 || strpos($url , "taobaocdn.") > 1 || strpos($url , "newark.") > 1){
		     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		     curl_setopt($ch, CURLOPT_COOKIEFILE, APPPATH . 'cache/' . md5($host) . '.txt');
		     curl_setopt($ch, CURLOPT_COOKIEJAR,  APPPATH . 'cache/' . md5($host) . '.txt');
		     $user_agent = getUserAgents_taobao();
		}
		
		// user agents
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent[array_rand($user_agent)]);
		unset($user_agent);
		
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if($postData){
			curl_setopt($ch , CURLOPT_POST, true);
			curl_setopt($ch , CURLOPT_POSTFIELDS, $postData);
		}
		if($invisible === true){
		    curl_setopt($ch, CURLOPT_PROXY, getProxY() );
		}
		if(strpos($url , 'ttps:') > 0){
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		// for Request Payload (CURL POST BUT NOT WITH ARRAY, BY JSON)
		if($postData && !is_array($postData) && strlen($postData) > 2){
		  curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ));
		}
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_ENCODING , ""); 
		$html = _curl_redirect_exec($ch , $postData);
		//$html = curl_exec($ch);
		//echo '<pre>' . print_r(curl_getinfo($ch) , 1) . '</pre>';exit;
		//echo $html;exit;
		curl_close($ch);
		return $html;
}


function _curl_redirect_exec($ch, $postData, $redirects = 0, $curlopt_returntransfer = true, $curlopt_maxredirs = 30, $curlopt_header = false) {
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if($postData){
		curl_setopt($ch , CURLOPT_POST, true);
		curl_setopt($ch , CURLOPT_POSTFIELDS, $postData);
	}
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $exceeded_max_redirects = $curlopt_maxredirs > $redirects;
    $exist_more_redirects = false;
    if ($http_code == 301 || $http_code == 302) {
        if ($exceeded_max_redirects) {
            list($header) = explode("\r\n\r\n", $data, 2);
            $matches = array();
            preg_match('/(Location:|URI:)(.*?)\n/', $header, $matches);
            $url = trim(array_pop($matches));
            $url_parsed = parse_url($url);
            if (isset($url_parsed)) {
                curl_setopt($ch, CURLOPT_URL, $url);
                $redirects++;
                return _curl_redirect_exec($ch, $postData, $redirects, $curlopt_returntransfer, $curlopt_maxredirs, $curlopt_header);
            }
        }else{
            $exist_more_redirects = true;
        }
    }
    if ($data !== false) {
        if (!$curlopt_header)
            list(,$data) = explode("\r\n\r\n", $data, 2);
        if ($exist_more_redirects) return false;
        if ($curlopt_returntransfer) {
            return $data;
        }else{
            echo $data;
            if (curl_errno($ch) === 0) return true;
            else return false;
        }
    }else{
        return false;
    }
}



function getProxY(){
    $out = false;
    if(strlen(file_get_contents(realpath(dirname(__FILE__) . '/../../../proxyMyOwn.txt'))) > 5){
        $lines = file(realpath(dirname(__FILE__) . '/../../../proxyMyOwn.txt'), FILE_IGNORE_NEW_LINES);
    }else{
        checkProxy();
        $lines = file(dirname(__FILE__) . '/proxy.txt', FILE_IGNORE_NEW_LINES);
    }
    $res = false;
    $attempts = 0;
    while(!$res){
        $tryproxy = $lines[array_rand($lines)];
        $tryproxyData = explode(":" , $tryproxy);
        $tryproxyHost = $tryproxyData[0];
        $tryproxyPort = $tryproxyData[1];
        if(isset($tryproxyData[1])){
            $tryproxyPort = $tryproxyData[1];
        }
        if(GLOBAL_DEBUG_SEMAFOR > 0){
                echo 'try' . $tryproxy . '<br />';
        }
        if(@fsockopen ($tryproxyHost , $tryproxyPort , $errno, $errstr, 5)){
            $res = true;
            $out = $tryproxy;
        }
        $attempts++;
        if($attempts > 10){
            break;
        }
    }
    //echo $out;exit;
    return $out;
}


function getApiUrl($url , $key){
    $ch = curl_init('http://multiscraper.com/api/geturl');
    curl_setopt($ch, CURLOPT_URL, 'http://multiscraper.com/api/geturl');
    curl_setopt($ch , CURLOPT_POST, true);
    curl_setopt($ch , CURLOPT_POSTFIELDS, array('url' => $url , 'key' => $key));
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING , "");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $html = curl_exec($ch);
    //echo $html;exit;
    curl_close($ch);
    return $html;
}

function checkProxy(){
    $last_time = (int) trim(file_get_contents(dirname(__FILE__) . '/proxy_last_update_time.txt'));
    $now = now();
    //echo $last_time . $now;
    if((int) ($now - $last_time) > 20){
        // update PROXY LIST
        //$result = file_get_contents('http://multiscraper.com/api/getProxy/mspro');
        $result = file_get_contents('http://multiscraper.com/public/files/proxy.txt');
        file_put_contents(dirname(__FILE__) . '/proxy.txt', $result);
        // update last time
        file_put_contents(dirname(__FILE__) . '/proxy_last_update_time.txt', trim($now));
    }
    return;
}


function getUserAgents(){
    $user_agent = array();
    $user_agent[] = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.125 Safari/537.36';
    return $user_agent;
}

function getUserAgents_taobao(){
    $user_agent = array();
    $user_agent[] = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.125 Safari/537.36';
    $user_agent[] = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.205 Safari/534.16';
    $user_agent[] = 'Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US; rv:1.8.1.6) Gecko/2007072300 Iceweasel/2.0.0.6 (Debian-2.0.0.6-0etch1+lenny1)';
    $user_agent[] = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)';
    $user_agent[] = 'Mozilla/5.0 (X11; U; Linux i686; cs-CZ; rv:1.7.12) Gecko/20050929';
    $user_agent[] = 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51';
    $user_agent[] = 'Mozilla/5.0 (Windows; I; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20100101 Firefox/4.0';
    $user_agent[] = 'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.8.131 Version/11.10';
    $user_agent[] = 'Opera/9.80 (Macintosh; Intel Mac OS X 10.6.7; U; ru) Presto/2.8.131 Version/11.10';
    $user_agent[] = 'Mozilla/5.0 (Macintosh; I; Intel Mac OS X 10_6_7; ru-ru) AppleWebKit/534.31+ (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1';

    return $user_agent;
}

function getHost($url){
    $res = parse_url($url , PHP_URL_HOST);
    if($res !== null){
        return $res;
    }
    return 'cookie';
}
