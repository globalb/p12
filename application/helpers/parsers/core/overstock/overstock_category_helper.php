<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		
		$link = 'a.pro-thumb';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href'])){
                	$result[] = $value['href'];
            	}
            }
        }
        
		//echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return array_unique($result);
}


function parse_next_page($html , $task){
        $nextPage = 'ul.pagination a.next';
        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	return trim($next['href']);
        }

        unset($parser);
        return false;
}