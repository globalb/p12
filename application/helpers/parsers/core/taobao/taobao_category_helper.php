<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();

		// first of all we are trying to get listing by AJAX
		$res = explode('<input id="J_ShopAsynSearchURL" type="hidden" value="' , $html);
		if(count($res) > 1){
		    $res = explode('"' , $res[1] , 2);
		    if(count($res) > 1){
		        $res = getUrl('https://' . parse_url($task['url'], PHP_URL_HOST) . str_ireplace(array("amp;") , array("") , $res[0]) );
		        if($res && is_string($res) && strlen($res) > 20){
		            $resHtml = stripslashes($res);
		            //echo $resHtml;
		            $link = 'a.item-name';
		            $parser = new nokogiri($resHtml);
		            $links = $parser->get($link)->toArray();
		            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
		            unset($parser);
		            if (is_array($links) && count($links) > 0) {
		                foreach ($links as $value) {
		                    if(isset($value['href'])){
		                        $result[] = $value['href'];
		                    }
		                }
		            }
		        }
		    }
		}

        // hack for search taobao pages
        if(count($result) < 1 && stripos($html , '<input type="hidden" id="J_FirstAPI" value="') > 0 && stripos($task['url'] , 'search/search.htm') > 0 ){
            $searchAjaxUrl = explode('<input type="hidden" id="J_FirstAPI" value="' , $html);
            if(count($searchAjaxUrl) > 1){
                $searchAjaxUrl = explode('"' , $searchAjaxUrl[1] , 2);
                if(count($searchAjaxUrl) > 1){
                    $searchAjaxUrl = 'http://world.taobao.com/search/json.htm?' . $searchAjaxUrl[0];
                    $searchAjaxResult = getUrl($searchAjaxUrl);
                    if($searchAjaxResult){
                        $searchAjaxResult = str_ireplace(array('if(window.__jsonp_cb){__jsonp_cb('), array("") , trim($searchAjaxResult) );
                        if(substr($searchAjaxResult , -2) == ")}"){
                            $searchAjaxResult = substr($searchAjaxResult , 0 , -2);
                        }
                        $searchAjaxResult = (array) json_decode( utf8_encode($searchAjaxResult) , 1);
                        if(isset($searchAjaxResult['itemList']) && is_array($searchAjaxResult['itemList']) && count($searchAjaxResult['itemList']) > 0){
                            foreach($searchAjaxResult['itemList'] as $pos_item){
                                if(isset($pos_item['href']) && !is_array($pos_item['href']) && strlen(trim($pos_item['href'])) > 0){
                                    $result[] = $pos_item['href'];
                                }
                            }
                        }
                        //echo '<pre>'.print_r($searchAjaxResult , 1).'</pre>';exit;
                    }
                }
            }
        }
        
        if(count($result) < 1){
            $link = 'div#J_ItemList div.product a.productImg';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (is_array($links) && count($links) > 0) {
                foreach ($links as $value) {
                    if(isset($value['href'])){
                        $result[] = $value['href'];
                    }
                }
            }
        }
        
        if(count($result) < 1){
            $link = 'a.item-name';
            $parser = new nokogiri($html);
            $links = $parser->get($link)->toArray();
            //echo '<pre>'.print_r($links , 1).'</pre>';exit;
            unset($parser);
            if (is_array($links) && count($links) > 0) {
                foreach ($links as $value) {
                    if(isset($value['href'])){
                        $result[] = $value['href'];
                    }
                }
            }
        }
        
        
        $results = array_unique($result);
        foreach($results as $key => $result){
            if(substr($result , 0 , 2) == "//"){
                $results[$key] = 'https:' . $result;
            }
        }
        
        

        
        //echo '<pre>'.print_r($results , 1).'</pre>';exit;
        return $results;
}


function parse_next_page($html , $task){
    
        // first of all we are trying to get listing by AJAX
        $res = explode('<input id="J_ShopAsynSearchURL" type="hidden" value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = getUrl('https://' . parse_url($task['url'], PHP_URL_HOST) . str_ireplace(array("amp;") , array("") , $res[0]) );
                if($res && is_string($res) && strlen($res) > 20){
                    $resHtml = stripslashes($res);
                    //echo $resHtml;
                    $link = 'a.next';
                    $parser = new nokogiri($resHtml);
                    $link = $parser->get($link)->toArray();
                    //echo '<pre>'.print_r($link , 1).'</pre>';exit;
                    unset($parser);
                    if(isset($link[0]['href']) && !is_array($link[0]['href']) && strlen($link[0]['href']) > 10){
                        $result = $link[0]['href'];
                        if(substr($result , 0 , 2) == "//"){
                            $result = 'https:' . $result;
                        }
                        //echo $result;exit;
                        return $result;
                    }
                }
            }
        }
    
        $nextPage = 'a.ui-page-s-next';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        $next = reset($next);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
        if (isset($next['href'])){
            if(stripos($task['url'] , 'search_shopitem.') > 0 && stripos($next['href'] , 'search_shopitem.') < 1){
                $link = 'https://list.tmall.com/search_shopitem.htm' . $next['href'];
            }elseif(stripos($task['url'] , 'search_product.') > 0 && stripos($next['href'] , 'search_product.') < 1){
                $link = 'https://list.tmall.com/search_product.htm' . $next['href'];
            }else{
                $link = $next['href'];
            }
            //echo 'LINK : ' . $link;exit;
        	return $link;
        }
        
        // hack for search taobao pages
        if( stripos($task['url'] , '&s=') > 0 && stripos($task['url'] , 'search/search.htm') > 0 ){
            $t_res = explode('&s=' , $task['url']);
            if(count($t_res) > 1){
                $tt_res = explode('&' , $t_res[1] , 2);
                if(count($tt_res) > 1){
                    $nextNumber  = 60 + (int) $tt_res[0];
                    $link = $t_res[0] . '&s=' . $nextNumber . '&' . $tt_res[1];
                    //echo "link : " . $link;exit;
                    return $link;
                }
            }
        }
        
        //exit;

        return false;
}