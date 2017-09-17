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
	
		$link = 'div.product_tile_font a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href']) && !is_array($value['href'])){
            	    if(strpos( $value['href'] , "chinavasion") > 0){
            	        $out[] = $value['href'];
            	    }else{
            	        $out[] = 'https://www.chinavasion.com' . $value['href'];
            	    }
            	}
            }
        }
        
        
        if(count($out) < 1){
            $link = 'div#lst div.product_tile a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (is_array($links) && count($links) > 0) {
                foreach ($links as $value) {
                    if(isset($value['href']) && !is_array($value['href'])){
                        if(strpos( $value['href'] , "chinavasion") > 0){
                            $out[] = $value['href'];
                        }else{
                            $out[] = 'https://www.chinavasion.com' . $value['href'];
                        }
                    }
                }
            }
        }
       
        $out = array_unique($out);
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        
        return $out;
}


function parse_next_page($html , $task){
        $nextPage = 'span.arrow a';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
		if(isset($next) && is_array($next) && count($next) > 0){
		    foreach($next as $pos_next_link){
		        if (isset($pos_next_link['href']) && isset($pos_next_link['#text']) && !is_array($pos_next_link['#text']) && strpos($pos_next_link['#text'] , 'ext') > 0){
		            if(strpos( $pos_next_link['href'] , "chinavasion") > 0){
		                return trim($pos_next_link['href']);
		            }else{
		                return 'https://www.chinavasion.com' . trim($pos_next_link['href']);
		            }
		        }
		    }
		}
        return false;
}