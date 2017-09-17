<?php


function mspro_pandawill_title($html){
        $instruction = 'h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
    
		$instruction = '.product-shop .product-name h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        
		$instruction = '.cjpro_name h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        return '';
}

function mspro_pandawill_description($html){
		$res = '';
		preg_match_all('|<div class="std">(.*)</div>|isU', $html, $result, PREG_SET_ORDER);
		if(isset($result[0][0])){
        	$res .= $result[0][0];
		}
	
	    preg_match_all('|<div class="cjpro_wen">(.*)</div>|isU', $html, $result, PREG_SET_ORDER);
	    if(isset($result[0][0])){
        	$res .= $result[0][0];
	    }
        
        /* хак для вот такик приколов на панде <img style="width: 650px; height: 448px;" alt="ONDA V975m" taikoo_lazy_src="http://pic.pandawill.com/media/banner/650x448x2014-01-07_17_15_52ONDA,P20V975m.jpg.=U36_=T83Wi7p+dWsQKG044Hl.jpg" src="/taikoo_static/1.Hy2LQaukh5.gif" onload="taikoo.lazyLoadImages.loadIfVisible(this);"> */
        preg_match_all('/(<img[^<]+>)/Usi', $res, $images);
        if(isset($images[0]) && is_array($images[0]) && count($images[0]) > 0){
        	foreach($images[0] as $index => $img){
        		$src = strpos($img, ' src="');
        		$lazy_src = strpos($img, 'taikoo_lazy_src="');
        		$lazy_check = strpos($img, 'onload="taikoo.lazyLoadImages.loadIfVisible(this);"');
        		if($src > 5 && $lazy_src > 5 && $lazy_check > 5){
        			// убираем onload
        			$new_img = str_replace('onload="taikoo.lazyLoadImages.loadIfVisible(this);"' , "", $img);
        			// находим адрес реального img
        			$real_src_start = $lazy_src + 17;
        			$real_src_end = strpos($img, '"', $real_src_start + 1);
        			$real_src = substr($img, $real_src_start , $real_src_end - $real_src_start);
        			// находим адрес фейкового img
        			$fuck_src_start = $src + 6;
        			$fuck_src_end = strpos($img, '"', $fuck_src_start + 1);
        			$fuck_src = substr($img, $fuck_src_start , $fuck_src_end - $fuck_src_start);
        			// удаляем то что в taikoo_lazy_src
        			$string_to_delete = substr($img, $lazy_src , strlen($real_src) + 18);
        			$new_img = str_replace($string_to_delete , "", $new_img); 
        			// меняем то что в src на то что в taikoo_lazy_src
        			$new_img = str_replace($fuck_src , $real_src, $new_img);
        			$res = str_replace($img , $new_img, $res);
        		}
        	}
        }
        
        $pq = phpQuery::newDocumentHTML($html);
        $temp  = $pq->find('div#section-1');
        foreach ($temp as $block){
            $res .= '<div>' . $temp->html() . '</div>';
        }
		$temp  = $pq->find('div[data-id=1]');
		foreach ($temp as $block){
			$res .= '<div>' . $temp->html() . '</div>';
		}
            	
        
        //echo "res:" . $res;exit;
        
        return $res;
}


function mspro_pandawill_price($html){
    $res = explode('"price": "' , $html , 2);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1 && strlen(trim($res[0])) ){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
            return (float) $price;
        }
    }
	$res = explode('<span class="price">' , $html , 2);
	if(count($res) > 1){
        $res = explode('</span>' , $res[1] , 2);
        if(count($res) > 1 && strlen(trim($res[0])) ){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]); 
            return (float) $price;
        }
    }
	return '';
}


function mspro_pandawill_sku($html){
        $res = explode('<div style="float:left;"><a>item#:</a>sku' , $html , 2);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1 && strlen(trim($res[0])) ){
                return (int) $res[0];
            }
        }
        
        $res = explode('item#: sku' , $html , 2);
        if(count($res) > 1){
        	$res = explode('</div>' , $res[1] , 2);
        	if(count($res) > 1 && strlen(trim($res[0])) ){
        	   return (int) $res[0];
        	}
        }
        return '';
}

function mspro_pandawill_model($html){
    return mspro_pandawill_sku($html);
}


function mspro_pandawill_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_pandawill_meta_keywords($html){
       $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_pandawill_main_image($html){
		$instruction = 'p.product-image a';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
        if ($data) {
            $data = reset($data);
            return $data['href'];
        }
        
        $instruction = 'img#image';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if(isset($data) && is_array($data) && isset($data[0]['data-bigimg']) &&!is_array($data[0]['data-bigimg'])){
            return $data[0]['data-bigimg'];
        }elseif(isset($data) && is_array($data) && isset($data[0]['src']) &&!is_array($data[0]['src']) ){
            return $data[0]['src'];
        }
        
        return '';
}


function mspro_pandawill_other_images($html){
		$out = array();
		
		$instruction = 'ul.gallery-media-slider li a';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
        if ($data)
        foreach ($data as $value) {
            $start = strpos($value['onclick'], "'") + 1;
            $end = strrpos($value['onclick'], "'");
            if (($start !== false) and ($end !== false) and ($end > $start)){
            	$t_res = substr($value['onclick'], $start, $end - $start);
            	$tt_res = explode("', '" , $t_res);
            	if(count($tt_res) > 0 && strpos($tt_res[1] , "ttp://") > 0){
            		$out[] = $tt_res[1];
            	}else{
            		$out[] = $t_res;
            	}
            }
        }
        
        $instruction = 'div.more-views ul li';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if(isset($data) && is_array($data) && count($data) > 0){
            foreach($data as $posImg){
                if(isset($posImg['a'][0]['img'][0]['data-bigimg']) && !is_array($posImg['a'][0]['img'][0]['data-bigimg']) && strlen(trim($posImg['a'][0]['img'][0]['data-bigimg'])) > 0 ){
                    $out[] = $posImg['a'][0]['img'][0]['data-bigimg'];
                }elseif(isset($posImg['a'][0]['img'][0]['data-img']) && !is_array($posImg['a'][0]['img'][0]['data-img']) && strlen(trim($posImg['a'][0]['img'][0]['data-img'])) > 0 ){
                    $out[] = $posImg['a'][0]['img'][0]['data-img'];
                }elseif(isset($posImg['a'][0]['img'][0]['src']) && !is_array($posImg['a'][0]['img'][0]['src']) && strlen(trim($posImg['a'][0]['img'][0]['src'])) > 0 ){
                    $out[] = $posImg['a'][0]['img'][0]['src'];
                }
            }
        }
        
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        
        return $out;
}



/*function mspro_pandawill_options(){
	$res = array();
	$option = array(
					'name' => "color",
					'required' => true,
					'type' => "select",
					'values' => array(
									array('name' => "white", 'price' => -3.1),
									array('name' => "black", 'price' => 67.2), 
									)  
				);
	$res[] = $option;
	return $res;
}*/


