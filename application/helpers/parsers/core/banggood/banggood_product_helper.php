<?php


function mspro_banggood_title($html){
	$instruction = 'div.product_layout h1';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
    }
 	if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	   	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
    }
        
    $instruction = 'h1[itemprop=name]';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	   	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , $data[0]['#text']));
    }

	$instruction = 'div.pro_right h1';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
    }
 	if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	   	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
    }
    
    return '';
}

function mspro_banggood_description($html){
	$res = '';
		
	$pq = phpQuery::newDocumentHTML($html);
	$temp  = $pq->find('div.description');
	foreach ($temp as $block){
		$res .= $temp->html();
	}
	
	$temp  = $pq->find('div#tab-description');
	foreach ($temp as $block){
		$res .= $temp->html();
	}
	
	$temp = $pq->find('div.good_tabs_box div.list:first');
	foreach ($temp as $block){
		$res .= $temp->html();
	}
	
	/*$res = preg_replace('/<img(?:\\s[^<>]*)?>/i', '', $res);*/
	
	/*$res = preg_replace('/<img(?:\\s[^<>]*)?>/i', '', $res);*/
	$res = str_ireplace(array('<div id="coupon_banner"></div>') , array("") , $res);
	
	// remove banner
	$t_res = explode('<p style="background: url(\'http://img.banggood.com/' , $res);
	if(count($t_res) > 1){
	    $tt_res = explode('</p>' , $t_res[1] , 2);
	    if(count($tt_res) > 1){
	        $res = $t_res[0] . $tt_res[1];
	    }
	}
	
	$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $res);
	//echo $res;exit;
	
	return $res;
}


function mspro_banggood_price($html){
	    $res =  explode(',ecomm_totalvalue:' , $html);
        if(count($res) > 1){
            $res = explode(',' , $res[1]);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) trim($price);
            }
        
        }
    
		$instruction = 'div[itemprop=price]';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    unset($parser);
	    if (isset($res[0]['oriprice'])) {
	        return (float) trim($res[0]['oriprice']);
	    }elseif (isset($res[0]['#text'])) {
	    	return (float) trim($res[0]['#text']);
        }
        
        $instruction = 'div.pro_price';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if (isset($res[0]['b'][0]['#text'])) {
	    	return (float) trim($res[0]['b'][0]['#text']);
        }
        
		return ''; 
}


function mspro_banggood_sku($html){
        $res =  explode('<span class="sku">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1]);
            if(count($res) > 1){
                return trim(str_ireplace(array("SKU") , array(" ") , $res[0]) );
            }
        
        }
	   $instruction = 'span[itemprop=sku]';
        $parser = new nokogiri($html);
        $res = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
        if(isset($res[0]['#text']) && !is_array($res[0]['#text']) && strlen($res[0]['#text']) > 1){
            $res = trim( str_ireplace(array("SKU", '<span itemprop="sku">' , '" >') , array("" , "" , "") , $res[0]['#text']));
            return $res;
        }
        
        $res = explode('>SKU:' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $res = trim( str_ireplace(array("SKU" , '<span itemprop="sku">' , '" >') , array("" , "" , "") , $res[0]));
                return $res;
            }
        }
    
		$instruction = 'em#sku';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['#text'])) {
	        $res = trim( str_ireplace(array("SKU", '<span itemprop="sku">' , '" >') , array("" , "" , "") , $res[0]['#text']) );
	    	return $res;
        }
       
		return ''; 
}

function mspro_banggood_model($html){
    $res =  explode('"id": "' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    
    }
    $res =  explode('<li class="productid"><b>Product ID: <span>' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    
    }
	return mspro_banggood_sku($html);
}


function mspro_banggood_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_banggood_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_banggood_main_image($html){
	$arr = mspro_banggood_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_banggood_other_images($html){
	$arr = mspro_banggood_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}


function mspro_banggood_get_images_arr($html){
		$out = array();
		
		$instruction = 'div#big img:first';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    if(isset($res[0]['src']) && !is_array($res[0]['src'])){
	    	$out[] = $res[0]['src'];
	    }
		
		$instruction = 'ul#bigImgDatas li img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (is_array($res) && count($res) > 1) {
	    	unset($res[0]);
	    	foreach($res as $k => $v){
	    		if(isset($res[$k]["src"])){
	    			//$out[] = $res[$k]["src"];
	    			$out[] = str_replace('thumb/large', 'images', $res[$k]["src"]);
	    		}
	    	}
        }
        
        $instruction = 'div.good_photo_min ul li a';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
        if (is_array($res) && count($res) > 0) {
	    	foreach($res as $pos_img){
	    		if($pos_img['big']){
	    			$out[] = str_replace('thumb/large', 'images', $pos_img['big']);
	    		}elseif(isset($pos_img['ref'])){
	    			$out[] = str_replace('thumb/large', 'images', $pos_img['ref']);
	    		}
	    	}
        }
        
		$instruction = 'li.pic img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    if (is_array($res) && count($res) > 0) {
	    	foreach($res as $pos_img){
	    		if(isset($pos_img['src']) && stripos($pos_img['src'] , 'grey.gif') < 1){
	    			$out[] = $pos_img['src'];
	    		}
	    	}
	    }
	    
	    
	    $instruction = 'div.image_additional a';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    unset($parser);
	    if (is_array($res) && count($res) > 0) {
	        foreach($res as $pos_img){
	            if(isset($pos_img['data-origin'])){
	                $out[] = $pos_img['data-origin'];
	            }elseif(isset($pos_img['img'][0]['data-normal'])){
	                $out[] = $pos_img['img'][0]['data-normal'];
	            }
	        }
	    }
        
	    $out = clear_images_array($out);
	    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	    return $out;
}



function mspro_banggood_options($html){
    $out = array();

    $instruction = 'tr.pro_attr_content';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['th'][0]['span']['#text']) && !is_array($pos_option['th'][0]['span']['#text']) &&  isset($pos_option['td'][0]['ul'][0]['li']) && is_array($pos_option['td'][0]['ul'][0]['li']) && count($pos_option['td'][0]['ul'][0]['li']) > 0){
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['th'][0]['span']['#text']);
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['td'][0]['ul'][0]['li'] as $option_value){
                    if(isset($option_value['span'][0]['#text']) && !is_array($option_value['span'][0]['#text'])){
                        $OPTION['values'][] = array('name' => $option_value['span'][0]['#text'] , 'price' => 0);
                    }elseif(isset($option_value['span'][0]['img'][0]['title']) && !is_array($option_value['span'][0]['img'][0]['title'])){
                        $OPTION['values'][] = array('name' => $option_value['span'][0]['img'][0]['title'] , 'price' => 0);
                    }
                }
                if(count($OPTION['values']) > 0){
                    $out[] = $OPTION;
                }
            }
        }
    }
    
    $instruction = 'div.item_box';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        $originalPrice =  mspro_banggood_price($html);
        foreach($res as $pos_option){
            if(isset($pos_option['option_id']) &&
               (isset($pos_option['class']) && strpos($pos_option['class'] , 'attr') > 0) &&
               ( (isset($pos_option['div'][0]['#text']) && !is_array($pos_option['div'][0]['#text'])) || (isset($pos_option['div'][0]['#text'][0]) && !is_array($pos_option['div'][0]['#text'][0])) ) &&
               isset($pos_option['a']) && is_array($pos_option['a']) && count($pos_option['a']) > 0)
            {
                    $OPTION = array();
                    if(is_array($pos_option['div'][0]['#text']) && isset($pos_option['div'][0]['#text'][0]) && !is_array($pos_option['div'][0]['#text'][0])){
                        $name = str_replace( array(":") , array("") , $pos_option['div'][0]['#text'][0]);
                    }else{
                        $name = str_replace( array(":") , array("") , $pos_option['div'][0]['#text']);
                    }
                    $OPTION['name'] = $name;
                    $OPTION['type'] = "select";
                    $OPTION['required'] = true;
                    $OPTION['values'] = array();
                    foreach($pos_option['a'] as $option_value){
                        if(isset($option_value['ori_name']) && !is_array($option_value['ori_name'])){
                            $price = 0;
                            if(isset($option_value['oriPrice']) && ((float) $option_value['oriPrice'] < 0 ||  (float) $option_value['oriPrice'] > 0)){
                                $price = (float) $option_value['oriPrice'];
                            }elseif(isset($option_value['oriprice']) && isset($option_value['oriprice']) && isset($option_value['price_prefix']) ){
                                if(strpos($option_value['price_prefix'] , "+") > -1){
                                    $price = (float) $option_value['oriprice'];
                                }else{
                                    $price = 0 - (float) $option_value['oriprice'];
                                }
                            }
                            $OPTION['values'][] = array('name' => $option_value['ori_name'] , 'price' => $price);
                        }
                    }
                    if(count($OPTION['values']) > 0){
                        $out[] = $OPTION;
                    }
                }
        }
    }
    
    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
    
}


/*function mspro_banggood_noMoreAvailable($html){
	return false;
}*/
