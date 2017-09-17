<?php


function mspro_sheinside_title($html){
		$instruction = 'h1';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        $instruction = 'h2.name';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
     
        return '';
}

function mspro_sheinside_description($html){
		$out = '';
		
		$res = explode('<div class="description ' , $html);
		if(count($res) > 1){
		    unset($res[0]);
		    foreach($res as $block){
		        $t_res = explode('<div class="000">' , $block , 2);
		        if(count($t_res) > 1){
		            $block = $t_res[0];
		        }
		        //echo $block;
		        if(stripos($block , '">Description<') > 0 || stripos($block , '>model Measurements<') > 0){
		            $out .= '<div class="' . $block;
		        }
		    }
		}
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div.ItemSpecificationCenter');
		foreach ($temp as $block){
		    $out .= '<div>' . $temp->html() . '</div>';
		}
		
		$temp  = $pq->find('div.goods_att_content');
		foreach ($temp as $block){
		    $out .= '<div>' . $temp->html() . '</div>';
		}
		
		$out = str_ireplace(array('<span class="vis iconfont">&#xe644;</span>' , '<span class="hid iconfont">&#xe643;</span>' , '<div class="blank12"></div>') , array("") , $out);
		
		//echo $out;exit;
		
		return $out;
}


function mspro_sheinside_price($html){
		 $res = explode('ecomm_totalvalue:' , $html);
        if(count($res) > 1){
        	$res = explode('}' , $res[count($res) - 1] , 2);
        	if(count($res) > 1){
        		return (float) trim($res[0]);
        	} 
        }
        return '';
}


function mspro_sheinside_sku($html){
		return mspro_sheinside_model($html); 
}

function mspro_sheinside_model($html){
	$instruction = 'span#productCodeSpan';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
    }
	return '';
}


function mspro_sheinside_meta_description($html){
	    $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return clear_sheinside_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));
			}	 
       }
       return '';
}

function mspro_sheinside_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1 && strlen($res[0]) > 2){
       			return clear_sheinside_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));	
       		}
       		 
       }
       return  mspro_sheinside_meta_description($html);
}

function clear_sheinside_meta_tags($str){
    return str_ireplace(array("sheinside.com" , "shein.com" , "sheinside" , "sheinside" , "Free Shipping") , array("" , "") , $str);
}


function mspro_sheinside_main_image($html){
		$arr = sheinside_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_sheinside_other_images($html){
		$arr = sheinside_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function sheinside_get_images($html){
		$out = array();
	
    	$instruction = 'div.other_Imgs img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    			if(isset($pos_image['data-src']) && !is_array($pos_image['data-src'])){
    				$out[] = $pos_image['data-src'];
    			}elseif(isset($pos_image['imgb']) && !is_array($pos_image['imgb'])){
    				$out[] = $pos_image['imgb'];
    			}elseif(isset($pos_image['bigimg']) && !is_array($pos_image['bigimg'])){
    				$out[] = $pos_image['bigimg'];
    			}
    		}
    	}
    	
    	$instruction = 'div.img-ctn img.pro-img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    	    foreach($data as $pos_image){
    	        if(isset($pos_image['imgb']) && !is_array($pos_image['imgb'])){
    	            $out[] = $pos_image['imgb'];
    	        }elseif(isset($pos_image['data-src']) && !is_array($pos_image['data-src'])){
    	            $out[] = $pos_image['data-src'];
    	        }elseif(isset($pos_image['bigimg']) && !is_array($pos_image['bigimg'])){
    	            $out[] = $pos_image['bigimg'];
    	        }elseif(isset($pos_image['src']) && !is_array($pos_image['src'])){
    	            $out[] = $pos_image['src'];
    	        }
    	    }
    	}
    	
    	$instruction = 'div.swiper-slide img.pro-img-lg';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    	    foreach($data as $pos_image){
    	        if(isset($pos_image['imgb']) && !is_array($pos_image['imgb'])){
    	            $out[] = $pos_image['imgb'];
    	        }elseif(isset($pos_image['src']) && !is_array($pos_image['src'])){
    	            $out[] = $pos_image['src'];
    	        }elseif(isset($pos_image['bigimg']) && !is_array($pos_image['bigimg'])){
    	            $out[] = $pos_image['bigimg'];
    	        }
    	    }
    	}
    	
    	$out = array_unique($out);
    	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
    	
    	return $out;
}


function mspro_sheinside_options($html){
    $out = array();
	
	$instruction = 'div.good_cart_titlle';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['#text'][0]) && !is_array($pos_option['#text'][0]) && isset($pos_option['select'][0]['option']) && is_array($pos_option['select'][0]['option']) && count($pos_option['select'][0]['option']) > 1){
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['#text'][0]) );
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				$option_values_array = $pos_option['select'][0]['option'];
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
			// another variation
			if(isset($pos_option['div']) && is_array($pos_option['div']) && count($pos_option['div']) > 0){
			    foreach($pos_option['div'] as $posOption){
        			if(isset($posOption['div'][0]['em'][0]['#text']) && !is_array($posOption['div'][0]['em'][0]['#text']) && isset($posOption['div'][1]['div']) && is_array($posOption['div'][1]['div']) && count($posOption['div'][1]['div']) > 0){
        			    $OPTION = array();
        			    $OPTION['name'] = str_replace( array(":") , array("") , trim($posOption['div'][0]['em'][0]['#text']) );
        			    $OPTION['type'] = "select";
        			    $OPTION['required'] = true;
        			    $OPTION['values'] = array();
        			    $option_values_array = $posOption['div'][1]['div'];
        			    foreach($option_values_array as $option_value){
        			        if(isset($option_value['#text'][0]) && !is_array($option_value['#text'][0])){
        			            $OPTION['values'][] = array('name' => trim($option_value['#text'][0]) , 'price' => 0);
        			        }
        			    }
        			    if(count($OPTION['values']) > 0){
        			        $out[] = $OPTION;
        			    }
        			}
			    }
			}
		}
	}
	
	$instruction = 'div.size';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
	    foreach($res as $pos_option){
	        if(isset($pos_option['span'][0]['#text']) && !is_array($pos_option['span'][0]['#text']) && strlen(trim($pos_option['span'][0]['#text'])) > 0 && isset($pos_option['ul'][0]['label']) && is_array($pos_option['ul'][0]['label']) && count($pos_option['ul'][0]['label']) > 0){
	            $OPTION = array();
	            $OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['span'][0]['#text']) );
	            $OPTION['type'] = "select";
	            $OPTION['required'] = true;
	            $OPTION['values'] = array();
	            foreach($pos_option['ul'][0]['label'] as $option_value){
	                if(isset($option_value['value-caption']) && !is_array($option_value['value-caption'])){
	                    $OPTION['values'][] = array('name' => trim($option_value['value-caption']) , 'price' => 0);
	                }elseif(isset($option_value['for']) && !is_array($option_value['for'])){
	                    $OPTION['values'][] = array('name' => trim($option_value['for']) , 'price' => 0);
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




/*
function mspro_sheinside_noMoreAvailable($html){
	return false;
}
*/
