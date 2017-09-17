<?php


function mspro_miniinthebox_category_getUrl($url){
    return getUrl($url , false, true, false);
    $initialHTML = getUrl($url);
    $title = mspro_aliexpress_title($initialHTML);
    if($initialHTML && (!$title || $title == false || trim($title) == '') ){
        return getUrl($url , false, true, false);
    }
    return $initialHTML;
}

function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
    //echo 'HTML:' . $html;exit;
		$result = array();
		
		$link = 'div.product-list dl.prod-item dd.prod-name a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
                	$result[] = $value['href'];
            	}
            }
         }
         
         
		$link = 'div.product-list div.ns-item dd.prod-name a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
                	$result[] = $value['href'];
            	}
            }
         }
         
        $link = 'div.product-list dd.prod-name a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
		unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
                	$result[] = $value['href'];
            	}
            }
        }
        
        $link = 'div.search-list dl.item-block a.p-box';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
                if(isset($value['href']) && !is_array($value['href'])){
                    $result[] = $value['href'];
                }
            }
        }
        
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return array_unique($result);
}


function parse_next_page($html , $task){
        //print_r($url['task']);exit;
        $nextPage = 'div.pagination li.next a';

        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
		 //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	return trim($next['href']);
        }

        unset($parser);

        return false;
}