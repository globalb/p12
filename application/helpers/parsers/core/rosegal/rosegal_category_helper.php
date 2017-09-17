<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'div.classrwrap a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href'])){
            		$result[] = $value['href'];
            	}
            }
        }
        
        $link = 'div.product_lists ul li a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
                if(isset($value['href']) && strlen($value['href']) > 10 && stripos($value['href'] , 'vascript') < 1){
                    $result[] = $value['href'];
                }
            }
        }
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'p.listspan a';

        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next) && is_array($next) && count($next) > 0) {
            foreach ($next as $value) {
                if(isset($value['href']) && isset($value['#text']) && strpos($value['#text'] , "»") > 0 && strpos($value['#text'] , "ext") > 0){
                    return 'http://www.rosegal.com/' . $value['href'];
                }
        	}
        }

        return false;
}