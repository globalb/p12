<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'div.products_wrapper a.prodLink';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) ){
            		$result[] = $value['href'];
            	}
                
            }
        }
        
        
        if(count($result) < 1){
            $link = 'div.product_listing_outer a.prodLink';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) ){
                        $result[] = $value['href'];
                    }
            
                }
            }
        }
        
        $link = 'div#products section div a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
                if( isset($value['href']) && !is_array($value['href']) ){
                    $result[] = $value['href'];
                }
        
            }
        }
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'link[rel=next]';
        $parser = new nokogiri($html);
        $t_next = $parser->get($nextPage)->toArray();
        $next = reset($t_next);
        // echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
            return 'http://www.snapdeal.com/'.trim($next['href']);
        }
    
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $t_next = $parser->get($nextPage)->toArray();
        $next = reset($t_next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
        	return 'http://www.snapdeal.com/'.trim($next['href']);
        }

        return false;
}