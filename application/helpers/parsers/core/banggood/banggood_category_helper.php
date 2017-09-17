<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	//echo $html;exit;
		$out = array();
	
		$link = 'div.grid li.name a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
            		$out[] = $value['href'];
            	}
            }
        }
        
		$link = 'div.product_grid dd.name a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
            		$out[] = $value['href'];
            	}
            }
        }
        
        $link = 'span.title a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
		unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
            		$out[] = $value['href'];
            	}
            }
        }
        
        $link = 'div.product_gallery ul li a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
                if(isset($value['href']) && !is_array($value['href'])){
                    $out[] = $value['href'];
                }
            }
        }
        
        $out = array_unique($out);
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        
        return $out;
}


function parse_next_page($html , $task){
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href']) && $next['href'] !== 'javascript:void(0);' && $next['href'] !== '#'){
        	return trim($next['href']);
        }
        
        $nextPage = 'div.page_num a';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if(isset($next) && is_array($next) && count($next) > 0){
            foreach($next as $pos_next){
                if (isset($pos_next['href']) && isset($pos_next['title']) && (trim($pos_next['title']) == 'Next page' || (isset($pos_next['span']['class']) && trim($pos_next['span']['class']) == "arrow_d") ) ){
                    return trim($pos_next['href']);
                }
            }
        }
        

        return false;
}