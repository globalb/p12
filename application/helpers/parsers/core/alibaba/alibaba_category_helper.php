<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
    //echo $html;exit;
		$result = array();
		 
		$link = 'div#J-items-content h2';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
                if(isset($value['a'][0]['href'])){
                    $result[] = $value['a'][0]['href'];
                }
            }
        }
        
        
        //echo $html;exit;
        $link = 'ul#sm-offer-list li a.sm-offer-photoLink';
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
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
        //echo $task['url'];
        $out = false;
    
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
		unset($parser);
        if (isset($next['href'])){
        	$out = $next['href'];
        }
        
        return $out;
}