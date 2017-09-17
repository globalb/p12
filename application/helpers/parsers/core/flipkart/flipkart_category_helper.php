<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
	    //echo 'TASK' . $task['url'] . ' - <br />HTML:' . $html;
		$link = 'div.product-unit a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
         	foreach($links as $link){
         		if(isset($link['href']) && !is_array($link['href'])){
         			if( ! ( isset($link['data-tracking-id']) && $link['data-tracking-id'] == "prd_img" ) ){
         				$result[] = 'http://www.flipkart.com' . $link['href'];
         			}
         		} 
         	}
        }
        
        
		$link = 'a.lu-title';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
         	foreach($links as $link){
         		if(isset($link['href']) && !is_array($link['href'])){
         			if( ! ( isset($link['data-tracking-id']) && $link['data-tracking-id'] == "prd_img" ) ){
         				$result[] = 'http://www.flipkart.com' . $link['href'];
         			}
         		} 
         	}
        }
        
        
        
        $link = 'a.fk-display-block';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
		if (isset($links) && is_array($links) && count($links) > 0) {
         	foreach($links as $link){
         		if(isset($link['href']) && !is_array($link['href'])){
         			if( ! ( isset($link['data-tracking-id']) && $link['data-tracking-id'] == "prd_img" ) && isset($link['href']) && !strpos($link['href'] , 'vascript:void(0)') > 0  && !strpos($link['href'] , '=facets.') > 0 ){
         				$result[] = 'http://www.flipkart.com' . $link['href'];
         			}
         		} 
         	}
        }
        
        foreach($result as $key => $value){
            if(stripos($value , '/mobile-apps') > 0){
                unset($result[$key]);
            }
        }
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;


        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	return 'http://flipkart.com'.trim($next['href']);
        }
        
        
        return false;
}