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
		 
		$link = 'ul#navlist li a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (count($links) > 0) {
            foreach ($links as $value) {
            	if( isset($value['href']) && !is_array($value['href']) ){
            	    if(strpos($value['href'] , 'sportsdirect.') > 0){
            	        $result[] = $value['href'];
            	    }else{
            	        $result[] = 'http://www.sportsdirect.com' . $value['href'];
            	    }
            	}
                
            }
        }
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        // FILTERING RESULTS (they are filtered at javascript at the page)
        if(count($result) > 0){
            $t_res = explode("Filter=" , $task['url']);
            if(count($t_res) > 1){
                $tt_res = explode("&" , $t_res[1]);
                if(count($tt_res) > 1){
                    $filters = $tt_res[0];
                }else{
                    $filters = $t_res[1];
                }
                
                $filteredAPIurl = sportsdirectGetFilteredUrl($html , $filters);
                //echo $filteredAPIurl;exit;
                if($filteredAPIurl){
                    $filterRes = getUrl($filteredAPIurl);
                    if($filterRes){
                        $filterRes = (array) json_decode($filterRes , 1);     
                        if(isset($filterRes['products']) && is_array($filterRes['products']) && count($filterRes['products']) > 0){
                            $result = array();
                            foreach($filterRes['products'] as $product){
                                if(isset($product['PrdUrl']) && !is_array($product['PrdUrl']) && strlen($product['PrdUrl']) > 0){
                                    if(strpos($product['PrdUrl'] , 'portsdirect.') > 0){
                                        $result[] = $product['PrdUrl'];
                                    }else{
                                        $result[] = 'http://www.sportsdirect.com/' . $product['PrdUrl'];
                                    }
                                }
                            }
                        }
                        //echo '<pre>' . print_r($filterRes , 1) . '</pre>';exit;
                    }
                }
            }
        }
        
        
       
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}





function parse_next_page($html , $task){
    //echo $task;exit;
    
        // hack for filtered results
        if(stripos($task['url'] , 'Filter=') > 0){
            $items = parse_products($html , $task);
            $itemsCount = 0;
            if(is_array($items)){
                $itemsCount = count($items);
            }
            $countPerPage = sportsdirectGetProductsPerPage($task['url']);
            //echo $itemsCount;
            //echo $countPerPage;
            if($itemsCount < $countPerPage){
                return false;
            }
        }
    
        $nextPage = 'a.NextLink';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next[0]['href']) && !is_array($next[0]['href'])){
                    if(strpos($next[0]['href'] , 'sportsdirect.') > 0){
            	        return $next[0]['href'];
            	    }else{
            	        return 'http://www.sportsdirect.com' . $next[0]['href'];
            	    }
        }
        return false;
}


function sportsdirectGetProductsPerPage($url){
    $res = explode('dppp=' , $url);
    if(count($res) > 1){
        $res = explode('&' , $res[1] , 2);
        return (int) $res[0];
    }
    return 100;
}


function sportsdirectGetFilteredUrl($html , $filters){
    $out = false;
    
    $instruction = 'div#productlistcontainer';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(isset($data[0]['data-category']) && 
        isset($data[0]['data-defaultpageno']) && 
        isset($data[0]['data-defaultpagelength']) && 
        isset($data[0]['data-defaultsortorder']) && 
        isset($data[0]['data-descfilter']) && 
        isset($data[0]['data-searchtermcategory']) && 
        isset($data[0]['data-fltrselectedcurrency'])
        ){
            $baseAPIurl = 'http://www.sportsdirect.com/DesktopModules/BrowseV2/API/BrowseV2Service/GetProductsInformation?';
            $baseAPIurl .= 'categoryName=' . trim($data[0]['data-category']);
            $baseAPIurl .= '&currentPage=' . $data[0]['data-defaultpageno'];
            $baseAPIurl .= '&productsPerPage=' . $data[0]['data-defaultpagelength'];
            $baseAPIurl .= '&sortOption=' . $data[0]['data-defaultsortorder'];
            $baseAPIurl .= '&selectedFilters=' . $filters;
            $baseAPIurl .= '&isSearch=false&descriptionFilter=' . $data[0]['data-descfilter'];
            $baseAPIurl .= '&columns=4&mobileColumns=2&clearFilters=false&pathName=' . sportsdirectGetFilteredUrlpathName($html);
            $baseAPIurl .= '&searchTermCategory=' . $data[0]['data-searchtermcategory'];
            $baseAPIurl .= '&selectedCurrency=' . $data[0]['data-fltrselectedcurrency'];
            $out = $baseAPIurl;
    }

    return $out;
}

function sportsdirectGetFilteredUrlpathName($html){
    $res = explode('<form method="post" action="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return urlencode($res[0]);
        }
    }
    return '';
}
