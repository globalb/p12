<?php


function mspro_modlily_title($html){
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
        return '';
}

function mspro_modlily_description($html){
	   $res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#EC_SHOP_DESCRIPTION');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		// remove converter
		$tres = explode('<ul class="conversionWrap">' , $res , 2);
		if(count($tres) > 1){
		    $tt_res = explode('</ul>' , $tres[1] , 2);
		    if(count($tt_res) > 1){
		        $res = $tres[0] . $tt_res[1];
		    }
		}
		
		return $res;
}


function mspro_modlily_price($html){
	    $res = explode('google_totalvalue = parseFloat(' , $html);
        if(count($res) > 1){
        	$res = explode(')' , $res[1] , 2);
        	if(count($res) > 1){
        	    $price = preg_replace("/[^0-9.]/", "",  $res[0]); 
        		return (float) $price;
        	} 
        }
        return '';
}


function mspro_modlily_sku($html){
	return mspro_modlily_model($html);
}

function mspro_modlily_model($html){
    $res = explode("var google_prodid = '" , $html);
    if(count($res) > 1){
        $res = explode("'" , $res[1] , 2);
        if(count($res) > 1){
            $t_res = explode("-" , $res[0] , 2);
            if(count($t_res) > 1){
                return trim($t_res[0]);
            }else{
                return trim($res[0]);
            }
    
        }
    }
    $res = explode('</span><em style="color:#B0B0B0">' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    
    return '';
}


function mspro_modlily_meta_description($html){
	   $res =  explode('<meta name="Description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			$out = preg_replace("/[^a-zA-Z0-9.,_\-\s]/", "" ,  $res[0]); 
       			return str_ireplace(array("&nbsp;" , "&amp;" , "at modlily.com") , array(" " , "`" , "") , $out);
			}	 
       }
       return '';
}

function mspro_modlily_meta_keywords($html){
      $res =  explode('<meta name="Keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1 && strlen( $res[0]) > 1){
       			return str_ireplace(array("&nbsp;" , "&amp;" , "modlily.com") , array(" " , "`", "") , $res[0]);	
       		}
       		 
       }
       return  mspro_modlily_meta_description($html , $url);
}


function mspro_modlily_main_image($html){
	$arr = mspro_modlily_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_modlily_other_images($html){
	$arr = mspro_modlily_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}


function mspro_modlily_get_images_arr($html){
		$out = array();
	
    	$instruction = 'span#js_jqzoom img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data[0]['src']) && !is_array($data[0]['src']) && strlen($data[0]['src']) > 0){
    	    $out[] = $data[0]['src'];
    	}
    	
    	$instruction = 'ul.js_scrollableDiv li img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    			if(isset($pos_image['data-big-img']) && !is_array($pos_image['data-big-img'])){
    				$out[] = $pos_image['data-big-img'];
    			}elseif(isset($pos_image['src']) && !is_array($pos_image['src'])){
    			    $out[] = $pos_image['src'];
    			}
    		}
    	}
    	
    	// get color options images
    	$instruction = 'div.pro_property table tr';
    	$parser = new nokogiri($html);
    	$res = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
    	unset($parser);
    	if (is_array($res) && count($res) > 0){
    	    foreach($res as $pos_option){
    	        if(isset($pos_option['td'][0]['#text']) && !is_array($pos_option['td'][0]['#text']) && strpos($pos_option['td'][0]['#text'] , "olor") > 0 && isset($pos_option['td'][1]['a']) && is_array($pos_option['td'][1]['a']) && count($pos_option['td'][1]['a']) > 0){
    	            foreach($pos_option['td'][1]['a'] as $option_value){
    	                if(isset($option_value['img'][0]['src']) && !is_array($option_value['img'][0]['src'])){
    	                    $out[] = $option_value['img'][0]['src'];
    	                }
    	            }
    	        }
    	    }
    	}
    	
    	 $out = clear_images_array($out);
    	 //echo '<pre>'.print_r($out , 1).'</pre>';exit;
    	 
	     return $out;
}



function mspro_modlily_options($html){
    $out = array();
    $already_exists_options = array();

    $instruction = 'div.pro_property table tr';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['td'][0]['#text']) && !is_array($pos_option['td'][0]['#text']) && !in_array(trim($pos_option['td'][0]['#text']) , $already_exists_options) && isset($pos_option['td'][1]['a']) && is_array($pos_option['td'][1]['a']) && count($pos_option['td'][1]['a']) > 0){
                $already_exists_options[] = trim($pos_option['td'][0]['#text']); 
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['td'][0]['#text']);
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['td'][1]['a'] as $option_value){
                    if(isset($option_value['title']) && !is_array($option_value['title'])){
                        $OPTION['values'][] = array('name' => $option_value['title'] , 'price' => 0);
                    }
                }
                if(count($OPTION['values']) > 0){
                    $out[] = $OPTION;
                }
            }
            
            if(isset($pos_option['td'][0]['#text']) && !is_array($pos_option['td'][0]['#text']) && !in_array(trim($pos_option['td'][0]['#text']) , $already_exists_options) && isset($pos_option['td'][1]['div']['a']) && is_array($pos_option['td'][1]['div']['a']) && count($pos_option['td'][1]['div']['a']) > 0){
                $already_exists_options[] = trim($pos_option['td'][0]['#text']);
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['td'][0]['#text']);
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['td'][1]['div']['a'] as $option_value){
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
    
    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
    
}


/*
function mspro_modlily_noMoreAvailable($html){
	return false;
}
*/
