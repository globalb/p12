<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();

		$res = explode('<h3>' , $html);
		if(count($res) > 1){
		    unset($res[0]);
		    foreach($res as $pos_block){
		        $t_res = explode('</h3>' , $pos_block , 2);
		        if(count($t_res) > 1){
		            $t_res = explode('href="' , $t_res[0]);
		            if(count($t_res) > 1){
		                $t_res = explode('"' , $t_res[1] , 2);
		                if(count($t_res) > 1){
		                    $result[] = $t_res[0];
		                }
		            }
		        }
		    }
		    
		}
		
		
		/*$link = 'div.productinfodisplay';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) ){
            		$result[] = $value['href'];
            	}
                
            }
        }*/
        
        
        /*if(count($result) < 1){
            $link = 'div.product_listing_outer a.prodLink';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (count($links) > 0) {
                foreach ($links as $value) {
                    if( isset($value['href']) && !is_array($value['href']) ){
                        $result[] = $value['href'];
                    }
            
                }
            }
        }*/
        
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}


function parse_next_page($html , $task){
        $url = $task['url'];
        $cat_id = sunsky_getCatId($html);
        
        // next pages
        if($cat_id){
            if(strpos($url , '&page=') > 1){
                $res = explode('&page=' , $url);
                if(count($res) > 1){
                    return $res[0] . '&page=' .  (int) ( (int) $res[1] + 1);
                }
            }else{
                // first page
                return 'http://www.sunsky-online.com/product/default!search.do?categoryId=' . $cat_id . '&page=2';
            }
        }

        return false;
}


function sunsky_getCatId($html){
    $res = explode('name="categoryId" value="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return $res[0];
        }
    }
    
    $res = explode('search.do?categoryId=' , $html);
    if(count($res) > 1){
        $res = explode('&' , $res[1] , 2);
        if(count($res) > 1){
            return $res[0];
        }
    }
    
    return false;
}