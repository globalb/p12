<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	 	$result = array();
	 
		$link = 'a.abox';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $link) {
            	if(stripos($link['href'] , 'holesale7.') < 1){
            		$link['href'] = 'http://www.wholesale7.net/' . $link['href'];
            	}
            	 $result[] = $link['href'];
            }
        }
        
        if(count($result) < 1){
        	$link = 'div.list_h a';
        	$parser = new nokogiri($html);
        	$links = $parser->get($link)->toArray();
        	//echo '<pre>'.print_r($links , 1).'</pre>';exit;
	        if (isset($links) && is_array($links) && count($links) > 0) {
	            foreach ($links as $link) {
	            	if(isset($link['href']) && stripos($link['href'] , "avascript:") < 1){
		            	if(stripos($link['href'] , 'holesale7.') < 1){
		            		$link['href'] = 'http://www.wholesale7.net/' . $link['href'];
		            	}
		            	 $result[] = $link['href'];
	            	}
	            }
	        }
        }
        
        if(count($result) < 1){
            $link = 'div.content a.pica';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            if (isset($links) && is_array($links) && count($links) > 0) {
                foreach ($links as $link) {
                    if(isset($link['href']) && stripos($link['href'] , "avascript:") < 1){
                        if(stripos($link['href'] , 'holesale7.') < 1){
                            $link['href'] = 'http://www.wholesale7.net/' . $link['href'];
                        }
                        $result[] = $link['href'];
                    }
                }
            }
        }
        
        if(count($result) < 1){
            $link = 'ul.grid a.pica';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            if (isset($links) && is_array($links) && count($links) > 0) {
                foreach ($links as $link) {
                    if(isset($link['href']) && stripos($link['href'] , "avascript:") < 1){
                        if(stripos($link['href'] , 'holesale7.') < 1){
                            $link['href'] = 'http://www.wholesale7.net/' . $link['href'];
                        }
                        $result[] = $link['href'];
                    }
                }
            }
        }
        
       $result = array_unique($result);
       //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'a.next';
        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
        	if(stripos($next['href'] , 'holesale7.') < 1){
		    	$next['href'] = 'http://www.wholesale7.net/' . $next['href'];
		    }
		    return $next['href'];
        }
        
        
        $nextPage = 'div.page a[rel=nofollow]';
        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
            if(stripos($next['href'] , 'holesale7.') < 1){
                $next['href'] = 'http://www.wholesale7.net/' . $next['href'];
            }
            return $next['href'];
        }

       

        return false;
}