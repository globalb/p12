<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	//echo $html;
		$result = array();
	
		$link = 'h3.newaps a';
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
        
		$link = 'a.s-access-detail-page';
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
        
        $link = 'div.zg_title a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
 		if (isset($links) && is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
            	if(isset($value['href'])){
                	$result[] = trim($value['href']);
            	}
            }
        }
        
        
        
        
        // try to get pages like http://www.amazon.com/gp/aag/main?ie=UTF8&asin=&isAmazonFulfilled=1&isCBA=&marketplaceID=ATVPDKIKX0DER&orderID=&seller=A2O36BM2XBHY4R
        if(count($result) < 1 && strpos($task['url'] , 'aag') > 1 && strpos($task['url'] , 'marketplaceID=') > 1 && strpos($task['url'] , 'seller=') > 1){
            $itemsIDS = false;
            $numPage = 1;
            $numPageRes = explode("#mspro_amazon_page_num=" , $task['url']);
            if(count($numPageRes) > 1){
                $numPage = (int) trim($numPageRes[1]);
            }
            // get IDs
            $ajaxRes = getUrl("http://www.amazon.com/gp/aag/ajax/searchResultsJson.html" , array("seller" => mspro_amazon_get_seller($html), "currentPage" => $numPage, "useMYI" => '') );
            //echo $ajaxRes;
            if($ajaxRes && strpos($ajaxRes , '["') > 1){
                $ajaxResTemp = explode('["' , $ajaxRes);
                if(count($ajaxResTemp) > 1){
                    $ajaxResTemp = explode('"]' , $ajaxResTemp[1] , 2);
                    if(count($ajaxResTemp) > 1){
                        $itemsIDS = str_replace(array('","') , array(",") , $ajaxResTemp[0]);
                    }
                }
            }
            if($itemsIDS){
                $res = getUrl('http://www.amazon.com/gp/aag/ajax/asinRenderToJson.html?id=' . $itemsIDS . '&useMYI=0&numCellsInResultsSet=178&isExplicitSearch=0&merchantID=' . mspro_amazon_get_seller($html) . '&shovelerName=AAGProductWidget&maxCellsPerPage=18');
                if($res){
                    $res = json_decode($res , 1);
                    //echo 'r<pre>'.print_r($res , 1).'</pre>';exit;
                    if($res && is_array($res) && count($res) > 0){
                        $contentBlock ='';
                        foreach($res as $pos_block){
                            if(isset($pos_block['content'])){
                                $contentBlock .= $pos_block['content'];
                            }
                        }
                        $link = 'li.AAG_ProductTitle a';
                        $parser = new nokogiri($contentBlock);
                        $links = $parser->get($link)->toArray();
                        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
                        $n = 0;
                        if (isset($links) && is_array($links) && count($links) > 0) {
                            foreach ($links as $value) {
                                if(isset($value['href'])){
                                    $result[] = trim($value['href']);
                                    $n++;
                                }
                                /*if($n > 17){
                                    break;
                                }*/
                            }
                            
                        }
                    }
                }
            }
        }
        
        
       	//echo '<pre>'.print_r($result , 1).'</pre>';exit;
        return array_unique($result);
}

function mspro_amazon_get_seller($html){
    $res = explode(";seller=" , $html , 2);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return $res[0];
        }
    }
    return '';
}

function mspro_amazon_get_marketplace($html){
    $res = explode(";marketplaceID=" , $html , 2);
    if(count($res) > 1){
        $res = explode('&' , $res[1] , 2);
        if(count($res) > 1){
            return $res[0];
        }
    }
    return '';
}


function parse_next_page($html , $task){
		$base_url = parse_amazon_baseUrl($task['url']);
	
        $nextPage = 'a#pagnNextLink';
        $parser = new nokogiri($html);
        $next = reset($parser->get($nextPage)->toArray());
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        unset($parser);
		// echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next['href'])){
        	if(stripos($next['href'] , 'mazon.') < 1){
		    	$next['href'] = $base_url . $next['href'];
		    }
		    //echo $next['href'];exit;
        	return $next['href'];
        }

        
        // для случая когда пейджер без некста http://www.amazon.in/gp/bestsellers/books/ref=nav_shopall_books_bestsellers/275-4275075-1243834
        $current = 'li.zg_selected a';
        $parser = new nokogiri($html);
        $cur = reset($parser->get($current)->toArray());
        unset($parser);
		//echo '<pre>'.print_r($cur , 1).'</pre>';exit;
		if(isset($cur['page']) && is_numeric($cur['page'])){
			$cur = $cur['page'] + 1;
			$nextPage = 'a[page='.$cur.']';
        	$parser = new nokogiri($html);
        	$next = reset($parser->get($nextPage)->toArray());
        	unset($parser);
			//echo '<pre>'.print_r($next , 1).'</pre>';exit;
			if(isset($next['href'])){
				if(stripos($next['href'] , 'mazon.') < 1){
			    	$next['href'] = $base_url . $next['href'];
			    }
			    return $next['href'];
			}
		}
		
		
		// для случая когда имеем дело с АЯКС интерфейсом
		// try to get pages like http://www.amazon.com/gp/aag/main?ie=UTF8&asin=&isAmazonFulfilled=1&isCBA=&marketplaceID=ATVPDKIKX0DER&orderID=&seller=A2O36BM2XBHY4R
		if(strpos($task['url'] , 'aag') > 1 && strpos($task['url'] , 'marketplaceID=') > 1 && strpos($task['url'] , 'seller=') > 1){
		    $numPage = 1;
		    $initialURL = $task['url'];
		    $numPageRes = explode("#mspro_amazon_page_num=" , $task['url']);
		    if(count($numPageRes) > 1){
		        $initialURL = $numPageRes[0];
		        $numPage = (int) trim($numPageRes[1]);
		        $numPage = (string) $numPage;
		    }
		    $numPage++;
		    return $initialURL . '#mspro_amazon_page_num=' . $numPage;
		
		}
       

        return false;
}


function parse_amazon_baseUrl($url){
	$res_first = explode("amazon" , $url , 2);
	if(count($res_first) > 1){
		$res_parse = explode('/' , $res_first[1] , 2);
		return $res_first[0] . 'amazon' . $res_parse[0] . '/';
	}
	return $url;
}