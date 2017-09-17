<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'table#ListingTable tr td h2 a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) ){
            	       if(strpos($value['href'] , 'lelong.co') > 0){
                            $result[] = $value['href'];
                        }else{
                            $result[] = 'http://www.lelong.com.my' . $value['href'];
                        }
            	}
                
            }
        }
        
        
        if(count($result) < 1){
            $link = 'a.zname';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) ){
                        if(strpos($value['href'] , 'lelong.co') > 0){
                            $result[] = $value['href'];
                        }else{
                            $result[] = 'http://www.lelong.com.my' . $value['href'];
                        }
                    }
            
                }
            }
        }
        
        if(count($result) < 1){
            $link = 'div.right-section div.list-view a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) && stripos($value['href'] , 'list.') < 1  && stripos($value['href'] , '/merchant/') < 1){
                        if(strpos($value['href'] , 'lelong.co') > 0){
                            $result[] = $value['href'];
                        }else{
                            $result[] = 'http://www.lelong.com.my' . $value['href'];
                        }
                    }
        
                }
            }
        }
        
        if(count($result) < 1){
            $link = 'div#item4inline ul li div.catalogImg-wrap a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>edr'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) && stripos($value['href'] , 'list.') < 1  && stripos($value['href'] , '/merchant/') < 1){
                        if(strpos($value['href'] , 'lelong.co') > 0){
                            $result[] = $value['href'];
                        }else{
                            $result[] = 'http://www.lelong.com.my' . $value['href'];
                        }
                    }
        
                }
            }
        }
        
        if(count($result) < 1){
            $link = 'div.right-section div.list-view div.pic-box a';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>edr'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) && stripos($value['href'] , 'list.') < 1  && stripos($value['href'] , '/merchant/') < 1){
                        if(strpos($value['href'] , 'lelong.co') > 0){
                            $result[] = $value['href'];
                        }else{
                            $result[] = 'http://www.lelong.com.my' . $value['href'];
                        }
                    }
        
                }
            }
        }
        
        if(count($result) > 0){
            foreach($result as $key => $val){
                if(substr($val , 0 , 2) == "//"){
                    $result[$key] = 'http:' . $val;
                }
            }
        }
        
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}


function parse_next_page($html , $task){
        
        $nextPage = 'tr.paging td a';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
		unset($parser);
		if (isset($next) && is_array($next) && count($next) > 0) {
		    foreach ($next as $value) {
		        if (isset($value['href']) && strlen($value['href']) > 0 && isset($value['#text']) && !is_array($value['#text']) && trim($value['#text']) == "Next"){
		            $out = trim($value['href']);
		            if(strpos($out , 'lelong.co') > 0){
		                return $out;
		            }else{
		                return 'http://www.lelong.com.my' . $out;
		            }
		        }
		    }
		}
		
		$nextPage = 'div.pagination a.next';
		$parser = new nokogiri($html);
		$next = $parser->get($nextPage)->toArray();
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
		unset($parser);
		if(isset($next[0]['href']) && !is_array($next[0]['href']) && strlen($next[0]['href']) > 0){
		    if(strpos($next[0]['href'] , 'lelong.co') > 0){
		        return $next[0]['href'];
		    }else{
		        return 'http://www.lelong.com.my/' . $next[0]['href'];
		    }
		}
		

        return false;
}