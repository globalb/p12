<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'div#search-results div.item-container a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
                if(isset($value['href']) && !is_array($value['href'])){
                   $result[] = $value['href'];
                }
            }
        }
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'a.next_page';

        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	if(strpos($next['href'] , "ioffer.") > 0){
        		return trim($next['href']);
        	}else{
        		return 'http://www.ioffer.com/'.trim($next['href']);
        	}
        }

        return false;
}