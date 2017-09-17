<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		
		$link = '.product-name a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        unset($parser);
        if (count($links) > 0){
        	foreach ($links as $value){
        		 $result[] = $value['href'];
        	}
        }
        
        return array_unique($result);
}


function parse_next_page($html , $task){
 		$nextPage = '.pages ol li a';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        unset($parser);
        foreach ($next as $value) {
            if ((isset($value['title'])) and ($value['title'] == 'Next')) return $value['href'];
        }
        return false;
}