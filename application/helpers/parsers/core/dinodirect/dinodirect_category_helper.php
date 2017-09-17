<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'ul.product_list li p a';
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
        
        
        /*if(count($result) < 1){
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
        }*/
        
        
        
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'div.about_pages a';

        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
		if (isset($next) && is_array($next) && count($next) > 0) {
		    foreach($next as $pos_next){
		        if(isset($pos_next['#text']) && trim($pos_next['#text']) == "Next" && isset($pos_next['href'])){
		            return $pos_next['href'];
		        }
		    }
		}
        return false;
}