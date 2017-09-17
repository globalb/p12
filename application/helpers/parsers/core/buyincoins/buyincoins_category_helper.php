<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	  	$result = array();
	  
		$link = 'div.prodLists ul li a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);

        if (count($links) > 0) {
            foreach ($links as $value) {
            	if(strpos($value['href'], 'item') > 0){
	            	if(strpos($value['href'], 'buyincoins') < 1){
	            		$value['href'] = 'http://buyincoins.com/' . trim($value['href']);
	            	}
	                $result[] = $value['href'];
            	}
            }
        }

        return array_unique($result);
}


function parse_next_page($html , $task){
        $nextPage = 'li.next a';

        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
		// echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	if(strpos($next['href'], 'buyincoins') < 1){
	        	return 'http://buyincoins.com/' . trim($next['href']);
	        }
        	return trim($next['href']);
        }
        unset($parser);

        return false;
}