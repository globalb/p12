<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		//echo $html;exit;
		 
		$link = 'a.grid_pro_t';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
 		unset($parser);
        if(isset($links) && is_array($links) && count($links) > 0){
        	foreach($links as $link){
        		if(isset($link['href'])){
        			if(strpos($link['href'] , "verbuying") < 1){
        				$link['href'] = 'http://www.everbuying.net'.$link['href'];
        			}
        			$result[] = $link['href'];
        		}
        	}
        }
       
		$link = 'a.list_pro_t';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
 		unset($parser);
        if(isset($links) && is_array($links) && count($links) > 0){
        	foreach($links as $link){
        		if(isset($link['href'])){
        			if(strpos($link['href'] , "verbuying") < 1){
        				$link['href'] = 'http://www.everbuying.net'.$link['href'];
        			}
        			$result[] = $link['href'];
        		}
        	}
        }
        
		//echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'div.pages a';

        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        //$next = $next[count($next) - 1];
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
		unset($parser);
		foreach($next as $pages_link){
            if (isset($pages_link['href']) && isset($pages_link['#text']) && strpos($pages_link['#text'] , "ext") > 0 ){
            	if(strpos($pages_link['href'] , "verbuying") < 1){
            		$pages_link['href'] = 'www.everbuying.net'.$pages_link['href'];
            	}
            	return $pages_link['href'];
            }
		}

        return false;
}