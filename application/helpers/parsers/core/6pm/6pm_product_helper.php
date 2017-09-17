<?php


function mspro_6pm_title($html){
		$res = explode('<title>' , $html , 2);
		if(count($res) > 1){
			$res = explode('</title>' , $res[1] , 2);
			if(count($res) > 1){
				$res = trim(str_ireplace(array("- 6pm.com"), array("") , $res[0]));
				if(strlen($res) > 2){
					return $res;
				}
			}
		}
     
        return '';
}

function mspro_6pm_description($html){
		$out = '';
		// product-params if <div class="params"> not available
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div.description');
		foreach ($temp as $block){
			$out .= $temp->html();
			break;
		}
        return $out;
}


function mspro_6pm_price($html){
		$res = explode('<div class="price">$' , $html , 2);
		if(count($res) > 1){
			$res = explode('</div>' , $res[1] , 2);
			if(count($res) > 1){
				$res = trim($res[0]);
				if(strlen($res) > 2){
					return (float) trim(str_ireplace(array(","), array("") , $res));
				}
			}
		}
		$res = explode('<div class="price" itemprop="price">$' , $html , 2);
		if(count($res) > 1){
		    $res = explode('</div>' , $res[1] , 2);
		    if(count($res) > 1){
		        $res = trim($res[0]);
		        if(strlen($res) > 2){
		            return (float) trim(str_ireplace(array(","), array("") , $res));
		        }
		    }
		}
		$res = explode('<div id="price" class="price" itemprop="price">$' , $html , 2);
		if(count($res) > 1){
		    $res = explode('</div>' , $res[1] , 2);
		    if(count($res) > 1){
		        $res = trim($res[0]);
		        if(strlen($res) > 2){
		            return (float) trim(str_ireplace(array(","), array("") , $res));
		        }
		    }
		}
		$res = explode('"now": \'$' , $html , 2);
		if(count($res) > 1){
		    $res = explode("'" , $res[1] , 2);
		    if(count($res) > 1){
		        $res = trim($res[0]);
		        if(strlen($res) > 2){
		            return (float) trim(str_ireplace(array(","), array("") , $res));
		        }
		    }
		}
		$res = explode('"nowInt": ' , $html , 2);
		if(count($res) > 1){
		    $res = explode(',' , $res[1] , 2);
		    if(count($res) > 1){
		        $res = trim($res[0]);
		        if(strlen($res) > 2){
		            return (float) trim(str_ireplace(array(","), array("") , $res));
		        }
		    }
		}

        return '';
}


function mspro_6pm_sku($html){
		$res = explode('SKU: #' , $html , 2);
		if(count($res) > 1){
			$res = explode('</span>' , $res[1] , 2);
			if(count($res) > 1){
				$res = trim($res[0]);
				if(strlen($res) > 2){
					return $res;
				}
			}
		}
        return '';
}

function mspro_6pm_model($html){
	return mspro_6pm_sku($html);
}


function mspro_6pm_meta_description($html){
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

function mspro_6pm_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return  mspro_6pm_meta_description($html);
}

function clear_6pm_meta_tags($str){
    return str_ireplace(array("6pm.com" , "shein.com" , "6pm" , "6pm" , "Free Shipping") , array("" , "") , $str);
}


function mspro_6pm_main_image($html){
		$arr = mspro_6pm_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_6pm_other_images($html){
		$arr = mspro_6pm_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function mspro_6pm_get_images($html){
		$out = array();
	 
	$instruction = 'div#productImages a';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    if(count($data) > 0 && is_array($data)){
    	foreach($data as $img){
    		if(isset($img['href']) && strpos($img['href'] , 'flvplayer') < 1){
    			$pos_big_img = str_replace(array("MULTIVIEW"), array("4x"), $img['href']);
    			@$size = getimagesize($pos_big_img);
    			if(@isset($size['mime'])){
    				$out[] =  $pos_big_img;
    			}else{
    				$out[] =  $img['href'];
    			}
    		}
    	}
    }
    
    $out = array_unique($out);
    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
    
    return $out;
}


function mspro_6pm_options($html){
    $out = array();
    
    $instruction = 'li#colors';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if(isset($res[0]['label'][0]['#text']) && !is_array($res[0]['label'][0]['#text']) && isset($res[0]['select'][0]['option']) && is_array($res[0]['select'][0]['option']) && count($res[0]['select'][0]['option']) > 0){
        $OPTION = array();
        $OPTION['name'] = str_replace( array(":") , array("") , trim($res[0]['label'][0]['#text']) );
        $OPTION['type'] = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        $option_values_array = $res[0]['select'][0]['option'];
        foreach($option_values_array as $option_value){
            if(isset($option_value['#text']) && !is_array($option_value['#text'])){
                $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
            }
        }
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
        }
    }elseif(isset($res[0]['label'][0]['#text']) && !is_array($res[0]['label'][0]['#text']) && isset($res[0]['p'][0]['#text']) && strlen($res[0]['p'][0]['#text']) > 0){
        $OPTION = array();
        $OPTION['name'] = str_replace( array(":") , array("") , trim($res[0]['label'][0]['#text']) );
        $OPTION['type'] = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        $option_values_array = explode("/" , $res[0]['p'][0]['#text']);
        if(is_array($option_values_array) && count($option_values_array) > 0){
            foreach($option_values_array as $option_value){
                $OPTION['values'][] = array('name' => $option_value , 'price' => 0);
            }
        }
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
        }
    }
	
	$instruction = 'li.dimensions';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['label'][0]['#text'][0]) && !is_array($pos_option['label'][0]['#text'][0]) && isset($pos_option['select'][0]['option']) && is_array($pos_option['select'][0]['option']) && count($pos_option['select'][0]['option']) > 1){
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['label'][0]['#text'][0]) );
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
			}elseif(isset($pos_option['label'][0]['#text']) && !is_array($pos_option['label'][0]['#text']) && isset($pos_option['p'][0]['#text']) && strlen($pos_option['p'][0]['#text']) > 0){
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['label'][0]['#text']) );
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				$option_values_array = explode("/" , $pos_option['p'][0]['#text']);
				foreach($option_values_array as $option_value){
				    $OPTION['values'][] = array('name' => trim($option_value) , 'price' => 0);
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
			}elseif(isset($pos_option['label'][0]['#text'][0]) && !is_array($pos_option['label'][0]['#text'][0]) && isset($pos_option['p'][0]['#text']) && strlen($pos_option['p'][0]['#text']) > 1){
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['label'][0]['#text'][0]) );
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				$option_values_array = explode("/" , $pos_option['p'][0]['#text']);
				foreach($option_values_array as $option_value){
				    $OPTION['values'][] = array('name' => trim($option_value) , 'price' => 0);
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


function mspro_6pm_weight($html){
    $res = explode('Weight: ' , $html , 2);
    if(count($res) > 1){
        $res = explode('</li>' , $res[1] , 2);
        if(count($res) > 1){
            $res = trim($res[0]);
            if(strlen($res) > 2){
                return mspro_6pm_clear_wlh($res);
            }
        }
    }
    return '';
}


function mspro_6pm_length($html){
    $res = explode('Length: ' , $html , 2);
    if(count($res) > 1){
        $res = explode('</li>' , $res[1] , 2);
        if(count($res) > 1){
            $res = trim($res[0]);
            if(strlen($res) > 2){
                return mspro_6pm_clear_wlh($res);
            }
        }
    }
    return '';
}



function mspro_6pm_width($html){
    $res = explode('Width: ' , $html , 2);
    if(count($res) > 1){
        $res = explode('</li>' , $res[1] , 2);
        if(count($res) > 1){
            $res = trim($res[0]);
            if(strlen($res) > 2){
                return mspro_6pm_clear_wlh($res);
            }
        }
    }
    return '';
}



function mspro_6pm_height($html){
    $res = explode('Height: ' , $html , 2);
    if(count($res) > 1){
        $res = explode('</li>' , $res[1] , 2);
        if(count($res) > 1){
            $res = trim($res[0]);
            if(strlen($res) > 2){
                return mspro_6pm_clear_wlh($res);
            }
        }
    }
    return '';
}


function mspro_6pm_clear_wlh($res){
    return str_ireplace(array("&frasl;" , "<sup>" , "</sup>" , "<sub>" , "</sub>") , array("/" , "" , "" , "" , "") , $res);
}

/*
function mspro_6pm_noMoreAvailable($html){
	return false;
}
*/
