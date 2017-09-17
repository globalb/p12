<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
        $base = get_walmart_base($task['url']);
        if(!$base){
            $base = "http://www.walmart.com/";
        }
        //echo $base;exit;
        
        
		$result = array();
	
		$link = 'a.js-product-image';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if(isset($links) && is_array($links) && count($links) > 0){
        	foreach($links as $link){
        		if(isset($link['href'])){
        			if(stripos($link['href'] , "almart.com") < 1){
        				$link['href'] = $base . $link['href'];
        			}
        			$result[] = $link['href'];
        		}
        	}
        }
        
        $link = 'div.shelf-thumbs article.product a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if(isset($links) && is_array($links) && count($links) > 0){
            foreach($links as $link){
                if(isset($link['href'])){
                    if(stripos($link['href'] , "almart.") < 1){
                        $link['href'] = $base . $link['href'];
                    }
                    $result[] = $link['href'];
                }
            }
        }

        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}


function get_walmart_base($url){
    $out = false;
    $res = parse_url($url);
    if(isset($res) && is_array($res) && isset($res['scheme']) && strlen(trim($res['scheme'])) > 0 && isset($res['host']) && strlen(trim($res['host'])) > 0){
        $out = $res['scheme'] . '://' . $res['host'] . '/';
    } 
    return $out;
}


function parse_next_page($html , $task){
		$base_url = parse_walmart_baseUrl($task['url']);
		
		$base = get_walmart_base($task['url']);
		if(!$base){
		    $base = "http://www.walmart.com/";
		}
		
        $nextPage = 'a.paginator-btn-next';
        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
        	if(stripos($next['href'] , "almart.") < 1){
        		$next['href'] = $base_url . $next['href'];
        	}
        	return $next['href'];
        }
        
        
        $nextPage = 'link[rel=next]';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next[0]['href']) && !is_array($next[0]['href']) && strlen(trim($next[0]['href'])) > 0){
            $out = $next[0]['href'];
            if(stripos($next[0]['href'] , "almart.") < 1){
                $out = $base . $out;
            }
            //echo $out;exit;
            return $out;
        }
        
        
        $nextPage = 'a#loadmore';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next[0]['href']) && !is_array($next[0]['href']) && strlen(trim($next[0]['href'])) > 0){
            $out = $next[0]['href'];
            if(stripos($next[0]['href'] , "almart.") < 1){
                $out = $base . $out;
            }
            //echo $out;exit;
            return $out;
        }

        return false;
}


function parse_walmart_baseUrl($url){
	$res = explode("?" , $url);
	if(count($res) > 1){
		return $res[0];
	}
	return $url;
}