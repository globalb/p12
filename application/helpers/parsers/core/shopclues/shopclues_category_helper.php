<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'div.products-grid div.grid-product a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if(strpos($value['href'] , "shopclues.") < 1){
            	    $result[] = 'http://www.shopclues.com' . $value['href'];
            	}else{
            	    $result[] = $value['href'];
            	}
            }
        }    
        
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}





function parse_next_page($html , $task){
    //echo $task;exit;
        $nextPage = 'div.pagination a[name=pagination]';

        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next) && is_array($next) && count($next) > 0){
            foreach($next as $pos_next_link){
                if(trim($pos_next_link['#text']) == "Next"){
                    $result = trim($pos_next_link['href']);
                    if(strpos($result , "shopclues.") < 1){
                        return 'http://www.shopclues.com' . $result;
                    }else{
                        return $result;
                    }
                }
            }
        }
        return false;
}


