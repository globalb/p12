<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	 	$result = array();
	 
		$link = 'div.component-product_list a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $link) {
            	if(isset($link['href'])){
            		 $result[] = $link['href'];
            	}
            }
        }
       
        
       $result = array_unique($result);
       //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'a.next_link';
        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
        	if(stripos($next['href'] , 'lazada.') < 1){
        	    $domain = parse_domain($task['url']);
		    	$next['href'] = $domain . $next['href'];
		    }
		    return $next['href'];
        }
        

        return false;
}


function parse_domain($url){
    $res = @parse_url($url , PHP_URL_HOST);
    if(isset($res) && is_string($res) && strlen(trim($res)) > 1){
        return 'http://' . $res;
    }
    return 'http://www.lazada.com.my';
}