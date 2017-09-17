<?php


function mspro_oasap_title($html){
		$instruction = 'div.product-detail h2';
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
        
        $instruction = 'h2';
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
        
        return '';
}

function mspro_oasap_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('p.description');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		$exists = array();
		$temp  = $pq->find('div.description');
		foreach ($temp as $block){
		    $bl = $temp->html();
		    if(!in_array($bl , $exists)){
		      $res .= $bl;
		      $exists[] = $bl;
		    }
		}
		
		$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $res);
		
		
		return $res;
}


function mspro_oasap_price($html){
        $res = explode('<meta property="og:price:amount" content="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        
		$res = explode('<span class="saleprice red">' , $html);
        if(count($res) > 1){
        	$res = explode('<' , $res[1] , 2);
        	if(count($res) > 1){
        	    $price = preg_replace("/[^0-9.]/", "",  $res[0]);
        		return (float) $price;
        	} 
        }
        
        return '';
}


function mspro_oasap_sku($html){
    	return mspro_oasap_model($html);
}

function mspro_oasap_model($html){
	   $res = explode('id="product-code">Product Code:' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
        
        return '';
}


function mspro_oasap_meta_description($html){
	    $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return clear_oasap_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));
			}	 
       }
       return '';
}

function mspro_oasap_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return clear_oasap_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));	
       		}
       		 
       }
       return  mspro_oasap_meta_description($html);
}

function clear_oasap_meta_tags($str){
    return str_ireplace(array("oasap" , "Free Shipping") , array("" , "") , $str);
}


function mspro_oasap_main_image($html){
		$arr = oasap_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_oasap_other_images($html){
		$arr = oasap_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function oasap_get_images($html){
        $out = array();
    	
        // MAIN IMAGE
        $instruction = 'a#zoom_a';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if(isset($data[0]['href']) && !is_array($data[0]['href']) && strlen($data[0]['href']) > 0){
            $out[] = $data[0]['href'];
        }elseif(isset($data[0]['img'][0]['src']) && !is_array($data[0]['img'][0]['src']) && strlen($data[0]['img'][0]['src']) > 0){
            $out[] = $data[0]['img'][0]['src'];
        }
        
        // CAROUSEL
    	$instruction = 'div.product-images ul#product_small_images li a';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    			if(isset($pos_image['rel']) && !is_array($pos_image['rel'])){
    				$t_res = explode("largeimage: '" , $pos_image['rel']);
    				if(count($t_res) > 1){
    				    $t_res = explode("'" , $t_res[1] , 2);
    				    if(count($t_res) > 1){
    				        $out[] = $t_res[0];
    				    }
    				}
    			}elseif(isset($pos_image['img']['src']) && !is_array($pos_image['img']['src'])){
    			    $out[] = $pos_image['img']['src'];
    			}
    		}
    	}
    	
    	
    	// ANOTHER CAROUSEL
    	$instruction = 'ul.jcarousel-skin-tango li a';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    	    foreach($data as $pos_image){
    	        if(isset($pos_image['rel']) && !is_array($pos_image['rel'])){
    	            $t_res = explode("largeimage: '" , $pos_image['rel']);
    	            if(count($t_res) > 1){
    	                $t_res = explode("'" , $t_res[1] , 2);
    	                if(count($t_res) > 1){
    	                    $out[] = $t_res[0];
    	                }
    	            }
    	        }elseif(isset($pos_image['img']['src']) && !is_array($pos_image['img']['src'])){
    	            $out[] = $pos_image['img']['src'];
    	        }
    	    }
    	}
    	
        $out = array_unique($out);
    	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
    	
    	return $out;
}



function mspro_oasap_options($html){
    $out = array();
	
    
    // COLORS
	$instruction = 'div.color_detail';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
    if(isset($res[0]['label'][0]['#text']) && !is_array($res[0]['label'][0]['#text']) && isset($res[0]['select'][0]['option']) && is_array($res[0]['select'][0]['option']) && count($res[0]['select'][0]['option']) > 1){
		$OPTION = array();
		$OPTION['name'] = str_replace( array(":") , array("") , trim($res[0]['label'][0]['#text']) );
		$OPTION['type'] = "select";
		$OPTION['required'] = true;
		$OPTION['values'] = array();
		$option_values_array = $res[0]['select'][0]['option'];
		unset($option_values_array[0]);
		foreach($option_values_array as $option_value){
			if(isset($option_value['#text']) && !is_array($option_value['#text'])){
				$OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
			}
		}
		if(count($OPTION['values']) > 0){
			$out[] = $OPTION;
		}
	 }
	 
	 // SIZES
	 $instruction = 'div.size_detail';
	 $parser = new nokogiri($html);
	 $res = $parser->get($instruction)->toArray();
	 //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	 unset($parser);
	 if(isset($res[0]['label'][0]['#text']) && !is_array($res[0]['label'][0]['#text']) && isset($res[0]['select'][0]['option']) && is_array($res[0]['select'][0]['option']) && count($res[0]['select'][0]['option']) > 1){
	     $OPTION = array();
	     $OPTION['name'] = str_replace( array(":") , array("") , trim($res[0]['label'][0]['#text']) );
	     $OPTION['type'] = "select";
	     $OPTION['required'] = true;
	     $OPTION['values'] = array();
	     $option_values_array = $res[0]['select'][0]['option'];
	     unset($option_values_array[0]);
	     foreach($option_values_array as $option_value){
	         if(isset($option_value['#text']) && !is_array($option_value['#text'])){
	             $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
	         }
	     }
	     if(count($OPTION['values']) > 0){
	         $out[] = $OPTION;
	     }
	 }
	
	
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}




function mspro_oasap_noMoreAvailable($html){
	return false;
}

