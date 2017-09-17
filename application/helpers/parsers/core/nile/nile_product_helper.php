<?php


function mspro_nile_title($html){
    $res =  explode('<meta property="og:description" content="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);
        }
    
    }
    return '';
}

function mspro_nile_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#tabs-a');
		foreach ($temp as $block){
			$res .= '<div>' . $temp->html() . '</div>';
		}
		
		$res = str_ireplace(array('src="../images' , 'src="images' , "<noscript>" , "</noscript>") , array('src="http://www.nile.com.my/images' , 'src="http://www.nile.com.my/images', ""  , "") , $res);
		
		//echo $res;exit;
		
		return $res;
}


function mspro_nile_price($html){
    $res =  explode('class="productSpecialPrice">' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1]);
        if(count($res) > 1){
            $price = preg_replace("/[^0-9.]/", "",  $res[0]);
       		return (float) $price;
        }
    
    }
    return '';
}


function mspro_nile_sku($html){
		return mspro_nile_model($html); 
}

function mspro_nile_model($html){
		$res =  explode('<input type="hidden" id="product_id" value=' , $html);
       if(count($res) > 1){
       		$res = explode('>' , $res[1]);
       		if(count($res) > 1){
       			return $res[0];	
       		}
       		 
       }
       return '';
}


function mspro_nile_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_nile_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_nile_main_image($html){
		$arr = nile_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_nile_other_images($html){
		$arr = nile_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function nile_get_images($html){
        $out = array();
    	
    	$instruction = 'div#prodImg_block a';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    			if(isset($pos_image['href']) && !is_array($pos_image['href'])){
    				$out[] = 'http://www.nile.com.my/' . $pos_image['href'];
    			}
    		}
    	}
	     
	    $out = clear_images_array($out);
	    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	    return $out;
}

/*
function mspro_nile_options($html){
    $out = array();
	
	$instruction = 'ul.click-attr select';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['id']) && $pos_option['id'] == "select_custom_size" && isset($pos_option['option']) && is_array($pos_option['option']) && count($pos_option['option']) > 1){
				$OPTION = array();
				$OPTION['name'] = nile_get_option_name($pos_option['option']);
				if(!$OPTION['name']){
				    continue;
				}
				$options = $pos_option['option'];
				unset($options[0]);
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($options as $option_value){
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
	
	
	$instruction = 'ul.click-attr li';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
	    foreach($res as $pos_option){
	        if(isset($pos_option['div'][0]['strong'][0]['#text']) && 
	           !is_array($pos_option['div'][0]['strong'][0]['#text']) && 
	           strlen(trim($pos_option['div'][0]['strong'][0]['#text'])) > 0 && 
	           isset($pos_option['div'][0]['ul'][0]['li']) && 
	           is_array($pos_option['div'][0]['ul'][0]['li']) && 
	           count($pos_option['div'][0]['ul'][0]['li']) > 0){
    	            $OPTION = array();
    	            $OPTION['name'] = str_ireplace(array(":") , array("") , trim($pos_option['div'][0]['strong'][0]['#text']) );
    	            $OPTION['type'] = "select";
    	            $OPTION['required'] = true;
    	            $OPTION['values'] = array();
    	            foreach($pos_option['div'][0]['ul'][0]['li'] as $option_value){
    	                if(isset($option_value['a'][0]['title']) && !is_array($option_value['a'][0]['title']) && strlen(trim($option_value['a'][0]['title'])) > 0){
    	                    $OPTION['values'][] = array('name' => trim($option_value['a'][0]['title']) , 'price' => 0);
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
*/



/*
function mspro_nile_noMoreAvailable($html){
	return false;
}
*/
