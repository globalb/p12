<?php


function mspro_etsy_title($html){
        $instruction = 'div#item-title h1';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        
		$instruction = 'span[itemprop=name]';
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

function mspro_etsy_description($html){
        $res = '';
		
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#item-description .section-content');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div#description');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}

		//cut contact span
		$res_cut = explode('<p class="description-contact">' , $res);
		if(count($res_cut) > 1){
			$res = $res_cut[0];
		}
		//echo $res;exit;
		
		$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $res);
		
		return $res;
}


function mspro_etsy_price($html){
        $res =  explode('<meta itemprop="price" content="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1]);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        
        }
    
    
		$instruction = 'div.item-amount span.currency-value';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;	
		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		    $price = preg_replace("/[^0-9,.]/", "",  $data[0]['#text']);
	    	return (float) trim(str_replace(array(",") , array(".") , $price));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
 		    $price = preg_replace("/[^0-9,.]/", "", $data[0]['#text'][0]);
 		    return (float) trim(str_replace(array(",") , array(".") , $price));
        }
        
        
		$instruction = 'span#listing-price span.currency-value';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;	
		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		    $price = preg_replace("/[^0-9,.]/", "",  $data[0]['#text']);
		    return (float) trim(str_replace(array(",") , array(".") , $price));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
 		    $price = preg_replace("/[^0-9,.]/", "",  $data[0]['#text'][0]);
 		    return (float) trim(str_replace(array(",") , array(".") , $price));
        }
        
        return '';
}


function mspro_etsy_sku($html){
        $res = explode('","listing_id":' , $html);
        if(count($res) > 1){
            $res_t = explode(',' , $res[1] , 2);
            if(count($res_t) > 1){
                return $res_t[0];
            }
        }
        return '';	 
}

function mspro_etsy_model($html){
		return mspro_etsy_sku($html);
}

/*function mspro_etsy_weight($html){
    $out = array();
    //$res = explode('Weight:' , $html);
    $res = preg_split("/Weight:/", $html);
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    if(count($res) > 1){
        $res = explode('</li>' , $res[1] , 2);
        if(count($res) > 1){
            $weight = $res[0];
            $out['weight_class_id'] = 2;
            if(strpos($weight , "kilogram") > 1 || strpos($weight , "kg") > 1){$out['weight_class_id'] = 1;}
            $out['weight'] = (float) preg_replace("/[^0-9.]/", "",  $weight);;
        }
    }
    return $out;
}*/


function mspro_etsy_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_etsy_meta_keywords($html){
      return  mspro_etsy_meta_description($html);
}


function mspro_etsy_main_image($html){
	$arr = mspro_etsy_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_etsy_other_images($html){
	$arr = mspro_etsy_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}



function mspro_etsy_get_images_arr($html){
		$out = array();
		
        $instruction = 'div#item-thumbs img';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		unset($parser);
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if (isset($res[0]['src'])) {
		  	foreach($res as $oth_imgs){
		   		$out[] = mspro_etsy_try_get_bigger($oth_imgs['src']);
		   	}
	    }
	       
	       
	    $instruction = 'ul#image-carousel li';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    if(is_array($data) && count($data) > 0){
	    	foreach($data as $img){
	    	   if(isset($img['data-full-image-href']) && strlen($img['data-full-image-href']) > 5){
	    			$out[] = mspro_etsy_try_get_bigger($img['data-full-image-href']);
	    		}elseif(isset($img['data-large-image-href']) && strlen($img['data-large-image-href']) > 5){
	    			$out[] = mspro_etsy_try_get_bigger($img['data-large-image-href']);
	    		}
	    	}
	    }
        
        $out = array_unique($out);
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        
	    return $out;
}

function mspro_etsy_try_get_bigger($src){
    return str_replace(array("75x75") , array("fullxfull") , $src);
}


function mspro_etsy_options($html){
    $out = array();
	
	$instruction = 'div#variations div.item-variation-option';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['label'][0]['#text']) && !is_array($pos_option['label'][0]['#text']) && strlen($pos_option['label'][0]['#text']) > 0 && isset($pos_option['select'][0]['option']) && is_array($pos_option['select'][0]['option']) && count($pos_option['select'][0]['option']) > 1){
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['label'][0]['#text']);
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                $pos_options = $pos_option['select'][0]['option'];
                unset($pos_options[0]);
                $originalPrice = mspro_etsy_price($html);
                foreach($pos_options as $option_value){
                    if(isset($option_value['#text']) && !is_array($option_value['#text'])){
                        $OPTION['values'][] = array('name' => mspro_etsy_getOptionText($option_value['#text']) , 'price' => mspro_etsy_getOptionPrice($option_value['#text'], $originalPrice));
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

function mspro_etsy_getOptionText($option_value){
    $res = preg_replace("/(\\[.*\\])/U" , "" , $option_value);
    return trim($res);
}

function mspro_etsy_getOptionPrice($option_value, $originalPrice){
    $currPrice = false;
    $res = explode('[' , $option_value);  
    if(count($res) > 1){
        $res = explode(']' , $res[1] , 2);
        if($res > 1){
            $res = preg_replace("/[^0-9,.]/", "",  $res[0]);
            $currPrice = str_ireplace(array(",") , array(".") , $res);
        }
    }
    if($currPrice && $currPrice !== $originalPrice){
        return (float) $currPrice - $originalPrice;
    }
    return 0;
}


function mspro_etsy_noMoreAvailable($html){
    if(strpos($html , 'Out of stock') > 0){
        return true;
    }
    return false;
}

