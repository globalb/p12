<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'div.proImgBox a:first';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) && strpos($value['href'] , "avascript") < 1 && strpos($value['href'] , ":void") < 1 && strpos($value['href'] , "sammydress") > 1){
            		$result[] = $value['href'];
            	}
                
            }
        }
        
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'div.pages p a[rel=nofollow]';

        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
		if(is_array($next) && count($next) > 0){
		    foreach($next as $pos_link){
		        if (isset($pos_link['href']) && (strpos($pos_link['#text'] , ">>") > 0 || strpos($pos_link['#text'] , "ext") > 0 || strpos($pos_link['#text'] , "»") > 0) ){
		            return 'http://www.sammydress.com/'.trim($pos_link['href']);
		        }
		    }
		}
       

        unset($parser);

        return false;
}