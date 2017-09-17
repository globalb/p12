<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'div#divProdBlock a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) && strpos($value['href'] , "avascript") < 1 && strpos($value['href'] , ":void") < 1 && strpos($value['href'] , "cPath=") < 1){
            	    $res = $value['href'];
            	    $t_res = explode("&" , $res);
            	    if(count($t_res) > 1){
            	        $res = $t_res[0];
            	    }
            		$result[] = $res;
            	}
                
            }
        }
        
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return $result;
}


function parse_next_page($html , $task){
    
        $nextPage = 'span.paging a';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
		unset($parser);
		if(isset($next) && is_array($next) && count($next) > 0){
		    foreach($next as $pos_next){
		        if(isset($pos_next['title']) && !is_array($pos_next['title']) && stripos($pos_next['title'] , "ext Page") > 0 && isset($pos_next['href']) ){
		            $result = $pos_next['href'];
		            //echo $result;exit;
		            return $result;
		        }
		    }
		}
        return false;
}

