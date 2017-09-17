<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
	//echo $html;exit;
		$out = array();
	
		$link = 'ul li a.publiceImg';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
                if(isset($value['href']) && !is_array($value['href'])){
            	    if(strpos( $value['href'] , "tomtop.") > 0){
            	        $out[] = $value['href'];
            	    }else{
            	        $out[] = 'http://www.tomtop.com' . $value['href'];
            	    }
            	}
            }
        }
        
        $link = 'ul.categoryProductList li a.productImg';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $value) {
                if(isset($value['href']) && !is_array($value['href'])){
                    if(strpos( $value['href'] , "tomtop.") > 0){
                        $out[] = $value['href'];
                    }else{
                        $out[] = 'http://www.tomtop.com' . $value['href'];
                    }
                }
            }
        }
        
        $out = array_unique($out);
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        
        return $out;
}


function parse_next_page($html , $task){
    $link = 'li.pageN a';
    $parser = new nokogiri($html);
    $links = $parser->get($link)->toArray();
    //echo '<pre>'.print_r($links , 1).'</pre>';exit;
    if(isset($links[0]['href']) && !is_array($links[0]['href'])){
        if(strpos( $links[0]['href'] , "tomtop.") > 0){
            return $links[0]['href'];
        }else{
           return 'http://www.tomtop.com' . $links[0]['href'];
        }
    }
    
    $url = $task['url'];
    $t_res = explode("?" , $url);
    if(count($t_res) > 1){
        $url = $t_res[0];
    }
    $next_page_number = get_next_page_number($html);
    if($next_page_number && $next_page_number > 1){
        $page_limit = get_page_limit($html);
        if(!$page_limit){
            $page_limit = '30';
        }
        $out = $url . '?limit=' . $page_limit . '&p=' . $next_page_number;
        echo $out;exit;
        return $out;
    }
    return false;
}



function get_next_page_number($html){
    $res = explode('<em class="arPageN" data-page="' , $html , 2);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return (int) $res[0];
        }
    }
    return false;
}

function get_page_limit($html){
    $link = 'select[name=limit] option';
    $parser = new nokogiri($html);
    $links = $parser->get($link)->toArray();
    //echo '<pre>'.print_r($links , 1).'</pre>';exit;
    unset($parser);
    if (isset($links) && is_array($links) && count($links) > 0) {
        foreach($links as $pos_link){
            if(isset($pos_link['selected']) && isset($pos_link['value']) && trim($pos_link['selected']) == "selected" && !is_array($pos_link['value']) ){
                return trim($pos_link['value']);
            }
        }
    }
    
    
    return false;
}
