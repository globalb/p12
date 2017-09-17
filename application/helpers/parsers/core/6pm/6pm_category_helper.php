<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'div.goods_aImg a';
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
        

        $link = 'div#searchResults a.product';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        if (count($links) > 0) {
            foreach ($links as $value) {
                if( isset($value['href']) && !is_array($value['href']) ){
                    if(stripos($value['href'] , '6pm.c') > 0){
                        $result[] = $value['href'];
                    }else{
                        $result[] = 'http://www.6pm.com' . $value['href'];
                    }
                }
        
            }
        }
        
        // GETTING THE REST BE AJAX
        /*$moreProducts = true;
        $numPart = 1;
        $nowPage = mspro_sheinside_get_Nowpage($task['url']);
        while($moreProducts){
            $dataArr = array("model" => mspro_sheinside_get_model($html),
                             "action" =>  mspro_sheinside_get_action($html),
                             "page" => $nowPage,
                             "Nowpage" => $nowPage,
                             "part" => "$numPart",
                             "ship" => "undefined",
                             "searchType" => 0,
                             "cat_id" => mspro_sheinside_get_cat_id($html),
                             "mk" => mspro_sheinside_get_mk($html),
                           );
            $attr_str = mspro_sheinside_get_attr_str($html);
            if(strlen(trim( (string) $attr_str)) > 0){
                $dataArr["attr_str"] = $attr_str;
            }
            if(strpos($html , '&html_mark=attribute') > 0){
                $dataArr["html_mark"] = "attribute";
            }
            //echo '<pre>'.print_r($dataArr , 1).'</pre>';exit;
            $numPart++;
            $ajaxRes = getUrl("http://www.sheinside.com/index.php" , $dataArr);
            //echo $ajaxRes;exit;
            $t_res = explode('{' , (string) $ajaxRes , 2);
            if(count($t_res) > 1){
                $ajaxArr = json_decode('{' . $t_res[1] , 1);
                //echo '<pre>'.print_r($ajaxArr , 1).'</pre>';exit;
                if(isset($ajaxArr['info']['goods_arr']) && is_array($ajaxArr['info']['goods_arr']) && count($ajaxArr['info']['goods_arr']) > 0 ){
                    foreach($ajaxArr['info']['goods_arr'] as $pos_product){
                        if(isset($pos_product['url']) && !is_array($pos_product['url'])){
                            $result[] = $pos_product['url'];
                        }
                    }
                }else{
                    $moreProducts = false;
                }
            }else{
                $moreProducts = false;
            }
            //echo '<pre>r'.print_r($ajaxRes , 1).'</pre>';exit;
        }   */
        
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;
        
        return $result;
}





function parse_next_page($html , $task){
    //echo $task;exit;
        $nextPage = 'div.pagecurrents2 a';
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        unset($parser);
		//echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next) && is_array($next) && count($next) > 0){
            foreach($next as $pos_next_link){
                if(trim($pos_next_link['#text']) == "Next"){
                    return trim($pos_next_link['href']);
                }
            }
        }
        
        
        $nextPage = 'div.pagination a.arrow';      
        $parser = new nokogiri($html);
        $next = $parser->get($nextPage)->toArray();
        unset($parser);
        //echo '<pre>'.print_r($next , 1).'</pre>';exit;
        if (isset($next) && is_array($next) && count($next) > 0){
            foreach($next as $pos_next_link){
                if(isset($pos_next_link['href']) && isset($pos_next_link['#text']) && trim($pos_next_link['#text']) == "»"){
                    if(stripos($pos_next_link['href'] , '6pm.c') > 0){
                        return $pos_next_link['href'];
                    }else{
                        return 'http://www.6pm.com' . $pos_next_link['href'];
                    }
                }
            }
        }
        
        return false;
}



/***********************  UTILITY   *************************/

function mspro_sheinside_get_model($html){
    $html = str_replace(array("model=common") , array("") , $html);
    $res =  explode('data: "model=' , $html);
    if(count($res) > 1){
        $res = explode('&' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    return 'category';
}


function mspro_sheinside_get_cat_id($html){
    $res =  explode('<div id="cat_id" style="display:none" cat_id="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    return '';
}

function mspro_sheinside_get_mk($html){
    $res =  explode('<div id="mk" style="display:none" mk="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    return '';
}

function mspro_sheinside_get_attr_str($html){
    $res =  explode('<div id="attr_str" style="display:none" attr_str="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    return '';
}


function mspro_sheinside_get_action($html){
    $model_category = mspro_sheinside_get_model($html);
    $res =  explode('data: "model=' . $model_category . '&action=' , $html);
    if(count($res) > 1){
        $res_t = $res[1];
        $res = explode('&' , $res_t);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    return '';
}


function mspro_sheinside_get_Nowpage($url){
    // two variants
    // Accessories-c-1765-p5.html
    // attribute-290_29013-page3-1727.html
    if(strpos($url , '-page') > 0){
        $res = explode('-page' , $url);
        if(count($res) > 1){
            $res = $res[count($res) - 1];
            $t_res = explode('-' , $res);
            if(count($t_res) > 1){
                $out = preg_replace("/[^0-9]/", "",  $t_res[0]);
                return (int) $out;
            }else{
                $out = str_replace(array(".html") , array("") , $t_res[0]);
                $out = preg_replace("/[^0-9]/", "",  $out);
                return (int) $out;
            }
        }
    }else{
        $res = explode('-p' , $url);
        if(count($res) > 1){
            $res = $res[count($res) - 1];
            $t_res = explode('-' , $res);
            if(count($t_res) > 1){
                $out = preg_replace("/[^0-9]/", "",  $t_res[0]);
                return (int) $out;
            }else{
                $out = str_replace(array(".html") , array("") , $t_res[0]);
                $out = preg_replace("/[^0-9]/", "",  $out);
                return (int) $out;
            }
        }
    }
    return '1';
}



