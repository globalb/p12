<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		 echo $html;
    	$link = 'ul#list-items li .detail h3 a';
        $parser = new nokogiri($html);
        $result = array();
        $links = $parser->get($link)->toArray();
        unset($parser);
        
         if (count($links) < 1) {
         	$link = 'ul#list-items li .detail h2 a';
	        $parser = new nokogiri($html);
	        $links = $parser->get($link)->toArray();
	        unset($parser);
	        
	        if(count($links) < 1){
	        	$link = 'div.info h3 a.product';
		        $parser = new nokogiri($html);
		        $links = $parser->get($link)->toArray();
		        unset($parser);
		        
		        if(count($links) < 1){
		        	$link = 'div.info h3 a.history-item';
			        $parser = new nokogiri($html);
			        $links = $parser->get($link)->toArray();
			        unset($parser);
			        
			        if(count($links) < 1){
			        	$link = 'div.detail h3 a';
				        $parser = new nokogiri($html);
				        $links = $parser->get($link)->toArray();
				        unset($parser);
				        
				        if(count($links) < 1){
				            $link = 'ul#list-items li.list-item h3 a';
				            $parser = new nokogiri($html);
				            $links = $parser->get($link)->toArray();
				            unset($parser);
				        }
			        }
		        }
	        }
         }
        //echo count($links);exit;

        if (count($links) > 0) {
            foreach ($links as $value) {
                if(isset($value['href'])){
                    $r = $value['href'];
                    if(substr($r , 0 , 2) == "//"){
                        $r = 'http:' . $r;
                    }
                    $result[] = $r;
                }
            }
        }
        
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
        $nextPage = 'div.ui-pagination-navi a.ui-pagination-next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href']) && strlen($next['href']) > 10){
        	return aliexpress_prepare_next_page(trim($next['href']));
        }
        unset($parser);
    	
    	
    	$nextPage = 'div.pagination a.page-next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href']) && strlen($next['href']) > 10){
        	return aliexpress_prepare_next_page(trim($next['href']));
        }

        unset($parser);
        
        
    	
        $nextPage = 'span#new-list-pg a.pg-next-btn';

        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);

        if (strpos($next['class'], 'pg-next-btn-disable') === false) return aliexpress_prepare_next_page(trim($next['href']) );

        unset($parser);
        
        
        
        $nextPage = 'a.page-next';

        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());

        if (strpos($next['class'], 'pg-next-btn-disable') === false) return aliexpress_prepare_next_page(trim($next['href']));

        unset($parser);
        return false;
}

function aliexpress_prepare_next_page($url){
    if(substr($url , 0 , 2) == "//"){
        return 'http:' . $url;
    }
    return $url;
}