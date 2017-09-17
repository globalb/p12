<?php


function mspro_focalprice_title($html){
	$instruction = 'h1#productName';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	unset($parser);
	if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	 	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
    }
 	if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	   	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
    }   
    return '';
}

function mspro_focalprice_description($html){
	$res = '';
	$pq = phpQuery::newDocumentHTML($html);
	$temp  = $pq->find('div.description_m:first');
	foreach ($temp as $block){
		$res .= $temp->html();
	}
	return $res;
}


function mspro_focalprice_price($html){
	$out = '';
 	$getAjax = true;
 	
 	$price = '';
 	$instruction = 'span#nowprice';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	if(isset($res[0]['#text'][0]) && !is_array($res[0]['#text'][0]) ){
		//$price .= str_ireplace(array("") , array("") , $res[0]['#text'][0]);
		$price .= $res[0]['#text'][0];
	}
 	if(isset($res[0]['sup'][0]['#text']) && !is_array($res[0]['sup'][0]['#text']) ){
		$price .= $res[0]['sup'][0]['#text'];
	}
	if(strlen($price) > 2){
		$out = (float) $price;
		$getAjax = false;
	}

	// another variation
	///   http://dynamic.focalprice.com/AjaxPrice?callback=jsonp&sku=MH0809R
	if($getAjax){
		$res = getUrl("http://dynamic.focalprice.com/AjaxPrice?callback=jsonp&sku=".mspro_focalprice_sku($html));
		if($res){
			$res = explode('"UnitPrice":"' , $res , 2);
			if(count($res) > 1){
				$res = explode('"' , $res[1] , 2);
				if(count($res) > 1){
					$out = (float) $res[0];
				}
			}
		}
	}
	return $out;
}


function mspro_focalprice_sku($html){
		$instruction = 'em#sku';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['#text'])) {
	    	return trim($res[0]['#text']);
        }
		return ''; 
}

function mspro_focalprice_model($html){
	return mspro_focalprice_sku($html);
}


function mspro_focalprice_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_focalprice_meta_keywords($html){
       return mspro_focalprice_meta_description($html);
}


function mspro_focalprice_weight($html){
    $out = array();
    $res = explode(' Weight:' , $html);
    if(count($res) > 1){
        $res = explode('</tr>' , $res[1] , 2);
        if(count($res) > 1){
            $weight = $res[0];
            $weight = preg_replace("/[^A-Za-z0-9,.]/", "",  strip_tags($weight) );
            $weight = str_ireplace("nbsp", "" , $weight);
            $out['weight_class_id'] = 2;
            if(strpos($weight , "kilogram") > 1 || strpos($weight , "Kg") > 1){$out['weight_class_id'] = 1;}
            $out['weight'] = (float) preg_replace("/[^0-9,.]/", "",  $weight);
        }
    }
    return $out;
}

function mspro_focalprice_dimensions($html){
    $out = array();
    $res = explode('>Dimensions' , $html);
    if(count($res) > 1){
        $res = explode('</tr>' , $res[1] , 2);
        if(count($res) > 1){
            $dims = str_replace("&nbsp;" , "" , strip_tags($res[0]) );
            $t_res = explode("x" , $dims);
            if(count($t_res) > 2){
                $out['length'] = (float) preg_replace("/[^0-9,.]/", "", $t_res[0]);
                $out['width'] = (float) preg_replace("/[^0-9,.]/", "",  $t_res[1]);
                $out['height'] = (float) preg_replace("/[^0-9,.]/", "", $t_res[2]);
            }
            $out['length_class_id'] = 1;
            if(strpos($dims , "mm") > 1 ){$out['length_class_id'] = 2; }
            if(strpos($dims , "inch") > 1 ){$out['length_class_id'] = 3; }
        }
    }
    return $out;
}


function mspro_focalprice_main_image($html){
		$instruction = 'ul#imgs li img';
		$parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['jqimg2'])) {
	    	$main_image = trim($res[0]['jqimg2']);
	    	return str_ireplace(array("860x666") , array("550x426") , $main_image); 
        }
        return '';
}


function mspro_focalprice_other_images($html){
		$out = array();
		$instruction = 'ul#imgs li img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (is_array($res) && count($res) > 1) {
	    	
	    	$first_img = $res[0]["jqimg2"];
		    // add images from color options
	    	$current_sku = mspro_focalprice_sku($html);
	    	$instruction_options = 'div.pro_attribute a';
			$parser_options = new nokogiri($html);
			$res_options = $parser_options->get($instruction_options)->toArray();
			//echo '<pre>'.print_r($res , 1).'</pre>';exit;
			unset($parser_options);
			if(is_array($res_options) && count($res_options) > 1){
				foreach($res_options as $option){
					if(isset($option['key']) && !is_array($option['key']) && $option['key'] !== $current_sku){
						$option_image = str_replace(array($current_sku) , array($option['key']) , $first_img);
						//print_r(getimagesize($option_image));
						if(@is_array(getimagesize($option_image))){
							$out[] = str_ireplace(array("860x666") , array("550x426") ,$option_image);
						} 
					}
				}
			}
			
	    	unset($res[0]);
	    	foreach($res as $k => $v){
	    		if(isset($res[$k]["jqimg2"])){
	    			$out[] = str_ireplace(array("860x666") , array("550x426") , $res[$k]["jqimg2"]); 
	    		}
	    	}
	    	
        }
        
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        $out = clear_images_array($out);
	    return $out;
}




function mspro_focalprice_options($html){
	$out = array();
	
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, 'http://www.focalprice.com/ProductDetail/GetProductGroup');
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, "sku=" . mspro_focalprice_sku($html));
	    $outAjax = curl_exec($curl);
	    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	    curl_close($curl);
	    if($outAjax){
	        $outAjax = json_decode($outAjax , 1);
	        if(isset($outAjax) && is_array($outAjax) && isset($outAjax['Result']) ){
    	        $instruction = 'div.pro_attribute';
    	        $parser = new nokogiri($outAjax['Result']);
    	        $res = $parser->get($instruction)->toArray();
    	        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    	        unset($parser);
    	        if (is_array($res) && count($res) > 0){
    	            foreach($res as $pos_option){
    	                if(isset($pos_option['span'][0]['#text']) && isset($pos_option['a']) && is_array($pos_option['a']) && count($pos_option['a']) > 0){
    	                    $OPTION = array();
    	                    $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['span'][0]['#text']);
    	                    $OPTION['type'] = "select";
    	                    $OPTION['required'] = true;
    	                    $OPTION['values'] = array();
    	                    foreach($pos_option['a'] as $option_value){
    	                        if(isset($option_value['#text']) && !is_array($option_value['#text'])){
    	                            $OPTION['values'][] = array('name' => $option_value['#text'] , 'price' => 0);
    	                        }elseif(isset($option_value['#text'][0]) && !is_array($option_value['#text'][0])){
    	                            $OPTION['values'][] = array('name' => $option_value['#text'][0] , 'price' => 0);
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
	
	$instruction = 'div.pro_attribute';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['span'][0]['#text']) && isset($pos_option['a']) && is_array($pos_option['a']) && count($pos_option['a']) > 0){
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , $pos_option['span'][0]['#text']);
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($pos_option['a'] as $option_value){
					if(isset($option_value['#text']) && !is_array($option_value['#text'])){
						$OPTION['values'][] = array('name' => $option_value['#text'] , 'price' => 0);
					}elseif(isset($option_value['#text'][0]) && !is_array($option_value['#text'][0])){
						$OPTION['values'][] = array('name' => $option_value['#text'][0] , 'price' => 0);
					}
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
			}
		}
	}
	
	// NOW TRY TO GET IT BY AJAX
	if(count($out) < 1){
		$res = getUrl('http://www.focalprice.com/ProductDetail/GetProductGroup' , array('sku' => mspro_focalprice_sku($html) ));
		$ajaxHTML = explode('"Result":"' , $res);
		if(count($ajaxHTML) > 1){
			$ajaxHTML =  explode('"}' , $ajaxHTML[1]);
			if(count($ajaxHTML) > 1){
				$ajaxHTML = $ajaxHTML[0];
			}
		}
		if(!is_array($ajaxHTML)){
			$ajaxHTML = strip_slashes($ajaxHTML);
			$instruction = 'div.pro_attribute';
			$parser = new nokogiri($ajaxHTML);
			$res = $parser->get($instruction)->toArray();
			unset($parser);
			if(is_array($res) && count($res) > 0){
				foreach($res as $pos_option){
					if(isset($pos_option['span']) && isset($pos_option['span']) && is_array($pos_option['a']) && count($pos_option['a']) > 0){
						$OPTION = array();
						$OPTION['name'] = str_replace( array(":") , array("") , $pos_option['span'][0]['#text']);
						$OPTION['type'] = "select";
						$OPTION['required'] = true;
						$OPTION['values'] = array();
						foreach($pos_option['a'] as $option_value){
							if(isset($option_value['#text']) && !is_array($option_value['#text'])){
								$OPTION['values'][] = array('name' => $option_value['#text'] , 'price' => 0);
							}elseif(isset($option_value['#text'][0]) && !is_array($option_value['#text'][0]) && $option_value['class'] !== "outof"){
								$OPTION['values'][] = array('name' => $option_value['#text'][0] , 'price' => 0);
							}
						}
						if(count($OPTION['values']) > 0){
							$out[] = $OPTION;
						}
					}
				}
			}
			//echo '1<pre>'.print_r($out , 1).'</pre>';exit;
		}
	}
	
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}


function mspro_focalprice_noMoreAvailable($html){
	/*if(strpos($html , "Sold Out") > 0){
		return true;
	}*/
	//return false;
	
	$res = getUrl('http://dynamic.focalprice.com/QueryStockStatus?sku='.mspro_focalprice_sku($html));
	//echo $res;exit;
	if($res){
		if(strpos((string) $res , "Sold Out") > 0){
			//echo 'got';exit;
			return true;
		}
	}
	
	return false;
}
