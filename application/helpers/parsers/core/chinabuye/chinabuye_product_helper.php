<?php


function mspro_chinabuye_title($html){
		$instruction = 'h1.product-name';
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

function mspro_chinabuye_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div.short-description');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
    	$temp  = $pq->find('div#productdes');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		// check for OLD-SRC
		preg_match_all('/(<img[^<]+>)/Usi', $res, $images);
		$image = array();
		//echo '<pre>'.print_r($images[0] , 1).'</pre>';
		if(isset($images[0]) && is_array($images[0]) && count($images[0]) > 0){
	        foreach ($images[0] as $index => $value) {
	        	$check = strpos($value, ' src="');
	        	if($check > 0){
	            	$s = $check + 6;
	        	}else{
	        		$s = strpos($value, 'src="') + 5;
	        	}
	            $e = strpos($value, '"', $s + 1);
	            $image[$value] =   substr($value, $s, $e - $s);
	        }
		}
		if(count($image) > 0){
			 foreach ($image as $index => $value) {
			 	$res = str_replace($index, '<img src="' . $value . '" />', $res);
			 }
		}
		//echo $res;exit;
		
		return $res;
}


function mspro_chinabuye_price($html){
		$instruction = 'div.product-info-box span.price';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if(isset($res[0]['#text']) && !is_array($res[0]['#text']) ){
			$price = preg_replace("/[^0-9,.]/", "",  $res[0]['#text']);
			return (float) $price;
		}
		return '';
}


function mspro_chinabuye_sku($html){
        $res = explode('sku=' , $html);
        if(count($res) > 1){
            $res_t = explode('"' , $res[1] , 2);
            if(count($res_t) > 1){
                return $res_t[0];
            }
        }
        return '';	 
}

function mspro_chinabuye_model($html){
		return mspro_chinabuye_sku($html);
}

function mspro_chinabuye_weight($html){
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
}


function mspro_chinabuye_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_chinabuye_meta_keywords($html){
      return  mspro_chinabuye_meta_description($html);
}


function mspro_chinabuye_main_image($html){
		$arr = chinabuye_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_chinabuye_other_images($html){
		$arr = chinabuye_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function chinabuye_get_images($html){
		$out = array();
		$instruction = 'div.product-img-box a';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    unset($parser);
	    if (is_array($res) && count($res) > 1) {
	    	foreach($res as $k => $v){
	    	    if(isset($v['onclick']) && !is_array($v['onclick']) && strlen($v['onclick']) > 0 && stripos($v['onclick'] , "opWin('htt") > 0){
	    	        $t_res = explode("popWin('" , $v['onclick']);
	    	        if(count($t_res) > 1){
	    	            $t_res = explode("'" , $t_res[1] , 2);
	    	            if(count($t_res) > 1){
	    	                //echo $t_res[0] . '<br />';
	    	                $t_res = getUrl($t_res[0]);
	    	                if($t_res){
	    	                    $tt_res = explode('<img src="' , $t_res);
	    	                    if(count($tt_res) > 1){
	    	                        $tt_res = explode('"' , $tt_res[1] , 2);
	    	                        if(count($tt_res) > 1){
	    	                            $out[] = $tt_res[0];
	    	                        }
	    	                    }else{
	    	                        $tt_res = explode('<img width="600" src="' , $t_res);
	    	                        if(count($tt_res) > 1){
	    	                            $tt_res = explode('"' , $tt_res[1] , 2);
	    	                            if(count($tt_res) > 1){
	    	                                $out[] = $tt_res[0];
	    	                            }
	    	                        }
	    	                    }
	    	                }
	    	            }
	    	        }
	    	    }elseif(isset($v['img'][0]["src"]) && !is_array($v['img'][0]["src"]) && strlen($v['img'][0]["src"]) > 0){
	    	        $out[] = chinabuye_process_image($v['img'][0]["src"]);
	    	    }
	    		/*if(isset($res[$k]["src"])){
	    			//$out[] = chinabuye_process_image($res[$k]["src"]);
	    			$out[] = $res[$k]["src"];
	    		}*/
	    	}
        }
        
        $out = array_unique($out);
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        
	    return $out;
}

function chinabuye_process_image($src ){
    return str_ireplace(array("860x666" , "thumbnail/70x70" , "400x400/") , array("550x426" , "image" , "") , $src);
}


function mspro_chinabuye_options($html){
    $out = array();
	
	$instruction = 'fieldset.product-options dl';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (isset($res[0]['dt']) &&  is_array($res[0]['dt']) && count($res[0]['dt']) > 0 && isset($res[0]['dd']) &&  is_array($res[0]['dd']) && count($res[0]['dd']) > 0){
	    $labels_array = $res[0]['dt'];
	    $options_array = $res[0]['dd'];
		foreach($labels_array as $key => $pos_option){
			if(isset($pos_option['label']['#text'][0]) && !is_array($pos_option['label']['#text'][0]) && isset($options_array[$key]['select'][0]['option']) && is_array($options_array[$key]['select'][0]['option']) &&  count($options_array[$key]['select'][0]['option']) > 1){
				$OPTION = array();
				$OPTION['name'] = trim($pos_option['label']['#text'][0]);
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				$option_values_array = $options_array[$key]['select'][0]['option'];
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
		}
	}
	
	
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}



function mspro_chinabuye_noMoreAvailable($html){
    if(strpos($html , 'Out of stock') > 0){
        return true;
    }
    return false;
}

