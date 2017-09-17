<?php


function mspro_tmart_title($html){
		$instruction = 'span[itemprop=name]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
 			//echo $data[0]['#text'];exit;
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        $instruction = 'div[itemprop=name]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
		unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
 			//echo $data[0]['#text'];exit;
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
		$instruction = 'h1[itemprop=name]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
 			//echo $data[0]['#text'];exit;
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        $instruction = 'span.J_ppname';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data['#text']) && !is_array($data['#text'])) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
            //echo $data[0]['#text'];exit;
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        return '';
}

function mspro_tmart_description($html){
		$out = '';
		// product-params if <div class="params"> not available
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div#productDescription');
		foreach ($temp as $block){
			$out .= $temp->html();
			break;
		}
		
		$temp  = $pq->find('div#description');
		foreach ($temp as $block){
			$out .= $temp->html();
			break;
		}
		
		$temp  = $pq->find('div#details');
		foreach ($temp as $block){
			$out .= '<br />' . $temp->html();
			break;
		}
		
		
		//echo $out;exit;
		
        return $out;
}


function mspro_tmart_price($html){
		$instruction = 'input[name=price]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
 		if ($data){
	        if (isset($data[0]['value']) && !is_array($data[0]['value'])) {
		    	return (float) str_ireplace( array('$') , array("") , trim($data[0]['value']) );
	        }
        }
	
        
        $res = explode('"price" : "' , $html);
        if(count($res) > 1){
        	$res = explode('"' , $res[1] , 2);
        	if(count($res) > 1){
        		return (float) trim($res[0]);
        	}
        }
        
		$instruction = 'span[itemprop=price]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
 		unset($parser);
        if ($data){
	        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		    	return (float) str_ireplace( array('$') , array("") , trim($data[0]['#text']) );
	        }
        }
        
 		$instruction = 'span#subtotal';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if ($data){
	        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		    	return (float) str_ireplace( array('$' , "CA") , array("" , "") , trim($data[0]['#text']) );
	        }
        }
        
		$instruction = 'span.font24';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if ($data){
	        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		    	return (float) str_ireplace( array('$', 'CA') , array("" , "") , trim($data[0]['#text']) );
	        }
        }

        return '';
}


function mspro_tmart_sku($html){
		$res =  explode('SKU:' , $html);
       if(count($res) > 1){
       		$res = explode('</' , $res[1]);
       		if(count($res) > 1){
       			return  strip_tags ( str_ireplace(array("&nbsp;" , "&amp;") , array(" " , "`") , trim( $res[0]) ));	
       		}
       		 
       }
       return '';
}

function mspro_tmart_model($html){
	return mspro_tmart_sku($html);
}


function mspro_tmart_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			if(strlen(trim($res[0])) > 5){
       				return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , trim($res[0]) );
       			}	
       		}
       		 
       }
	   $res =  explode('<meta property="og:description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_tmart_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_tmart_get_images_arr($html){
	$out = array(); 
	$instruction = 'div.product_img a.highslide';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    if(count($data) > 0 && is_array($data)){
    	foreach($data as $img){
    		if(isset($img['href'])){
    			$res_t = explode('?' , $img['href']);
    			if(count($res_t) > 1){
    				$out[] =  $res_t[0];
    			}else{
    				$out[] =  $img['href'];
    			}
    			
    		}
    	}
    }
    
    
    $instruction = 'div.production-img-small ul li a img';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	if(count($data) > 0 && is_array($data)){
    	foreach($data as $img){
    		if(isset($img['data-large'])){
    			$res_t = explode('?' , $img['data-large']);
    			if(count($res_t) > 1){
    				$out[] =  $res_t[0];
    			}else{
    				$out[] =  $img['data-large'];
    			}	
    		}elseif(isset($img['data-nor'])){
    			$res_t = explode('?' , $img['data-nor']);
    			if(count($res_t) > 1){
    				$out[] =  $res_t[0];
    			}else{
    				$out[] =  $img['data-nor'];
    			}
    		}elseif(isset($img['src'])){
    			$res_t = explode('?' , $img['src']);
    			if(count($res_t) > 1){
    				$out[] =  $res_t[0];
    			}else{
    				$out[] =  $img['src'];
    			}
    		}
    	}
    }
    
    // тырим фотки возможных опций
    $instruction = 'div.J_attrs_main ul li a img';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	if(isset($res) && is_array($res) && count($res) > 0){
		foreach($res as $pos_image){
			if(isset($pos_image['src']) && !is_array($pos_image['src'])){
				$image_src = $pos_image['src'];
				$image_check = explode('?' , $image_src);
				if(count($image_check) > 1){
					unset($image_check[count($image_check) - 1]);
					$image_src = implode('?' , $image_check);
				}
				$image_src = str_replace(array("_60x60") , array("_800x800") , $image_src);
				$out[] =  $image_src;
			}
		}
	}
    
	$out = clear_images_array($out);
   	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
}


function mspro_tmart_main_image($html){
	$arr = mspro_tmart_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_tmart_other_images($html){
	$arr = mspro_tmart_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}


function mspro_tmart_options($html){
	$out = array();
	$currentPrice = mspro_tmart_price($html);
	
	$instruction = 'div.J_attrs_main';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	
	if(is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['#text'][0]) && !is_array($pos_option['#text'][0]) && isset($pos_option['div'][0]['ul'][0]['li']) && is_array($pos_option['div'][0]['ul'][0]['li']) && count($pos_option['div'][0]['ul'][0]['li']) > 0){
				$pos_options = $pos_option['div'][0]['ul'][0]['li'];
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , $pos_option['#text'][0]);
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($pos_options as $option_value){
					if(isset($option_value['a'][0]['data-attrid']) && !is_array($option_value['a'][0]['data-attrid']) && isset($option_value['a'][0]['img'][0]['title']) && !is_array($option_value['a'][0]['img'][0]['title'])){
						$option_sku = $option_value['a'][0]['data-attrid'];
						$option_value_name = $option_value['a'][0]['img'][0]['title'];
						$OPTION['values'][] = array(
									'name' => $option_value_name,
									'price' => mspro_tmart_get_option_value_price( trim($option_sku) , $currentPrice , $html)
						);
					}
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
			}
		}
	}
	
	
	$instruction = 'div.J_attrs_sub';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	if(is_array($res) && count($res) > 0){
	    foreach($res as $pos_option){
	        if(isset($pos_option['#text'][0]) && !is_array($pos_option['#text'][0]) &&
	           isset($pos_option['div'][0]['ul'][0]['li']) && is_array($pos_option['div'][0]['ul'][0]['li']) && count($pos_option['div'][0]['ul'][0]['li']) > 0)
	        {
    	            $pos_options = $pos_option['div'][0]['ul'][0]['li'];
    	            $OPTION = array();
    	            $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['#text'][0]);
    	            $OPTION['type'] = "select";
    	            $OPTION['required'] = true;
    	            $OPTION['values'] = array();
    	            foreach($pos_options as $option_value){
    	                if(isset($option_value['a'][0]['data-attrid']) && !is_array($option_value['a'][0]['data-attrid']) && isset($option_value['a'][0]['img'][0]['title']) && !is_array($option_value['a'][0]['img'][0]['title'])){
    	                    $option_sku = $option_value['a'][0]['data-attrid'];
    	                    $option_value_name = $option_value['a'][0]['img'][0]['title'];
    	                    $OPTION['values'][] = array(
    	                        'name' => $option_value_name,
    	                        'price' => mspro_tmart_get_option_value_price( trim($option_sku) , $currentPrice , $html)
    	                    );
    	                }elseif(isset($option_value['a'][0]['data-attrid']) && !is_array($option_value['a'][0]['data-attrid']) && isset($option_value['a'][0]['b'][0]['#text']) && !is_array($option_value['a'][0]['b'][0]['#text'])){
    	                    $option_sku = $option_value['a'][0]['data-attrid'];
    	                    $option_value_name = $option_value['a'][0]['b'][0]['#text'];
    	                    $OPTION['values'][] = array(
    	                        'name' => $option_value_name,
    	                        'price' => mspro_tmart_get_option_value_price( trim($option_sku) , $currentPrice , $html)
    	                    );
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

function mspro_tmart_get_option_value_price($sku , $price , $html){
	$price = round($price , 2);
	//echo 'sku - '.$sku.'<br />';
	$res = explode('"'.$sku.'":' , $html , 2);
	if(count($res) > 1){
		$res = explode('"price":' , $res[1] , 2);
		if(count($res) > 1){
			$res = explode(',' , $res[1] , 2);
			if(count($res) > 1){
				$res = str_replace(array('"') , array('') , $res[0]);
				$res = round((float) trim($res) , 2);
				//echo 'price - '.$price.'<br />';
				//echo 'res - '.$res.'<br />';
				return round( ($res - $price) , 2);
			}
		}
	}
	return 0;
}


function mspro_tmart_noMoreAvailable($html){
	if(strpos($html , 'Sold Out') > 0){
		return true;
	}
	return false;
}

