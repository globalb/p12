<?php


function mspro_sammydress_title($html){
		$instruction = 'h1[itemprop=name]';
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

function mspro_sammydress_description($html){
		$res = '';
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp = $pq->find('div.js_p_infoBlack');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		
		$res = preg_replace(array("'<ul class=\"conversionWrap[^>]*?.*?/ul>'si"), Array(""), $res);
		
		$t_res = explode('<div class="chart-table-wrap' , $res , 2);
		if(count($t_res) > 1){
		    $res = $t_res[0];
		}
		
		return $res;
}


function mspro_sammydress_price($html){
		 $instruction = 'span#unit_price';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';
	    if(isset($data[0]['orgp']) && !is_array($data[0]['orgp'])){
	        $price = preg_replace("/[^0-9,.]/", "",  $data[0]['orgp']);
	        return $price;
	    }elseif(isset($data[0]['#text']) && !is_array($data[0]['#text'])){
	        $price = preg_replace("/[^0-9,.]/", "",  $data[0]['#text']);
	        return $price;
	    }
        return '';
}


function mspro_sammydress_sku($html){
		return mspro_sammydress_model($html); 
}

function mspro_sammydress_model($html){
		$res =  explode('id="save_goodsId" value="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_sammydress_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_sammydress_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_sammydress_main_image($html){
		$arr = sammydress_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_sammydress_other_images($html){
		$arr = sammydress_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function sammydress_get_images($html){
        $out = array();
    	
    	$instruction = 'ul.js_scrollableDiv li img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    			if(isset($pos_image['data-big-img']) && !is_array($pos_image['data-big-img'])){
    				$out[] = $pos_image['data-big-img'];
    			}elseif(isset($pos_image['data-normal-img']) && !is_array($pos_image['data-normal-img'])){
    			     $out[] = $pos_image['data-normal-img'];
    			}elseif(isset($pos_image['src']) && !is_array($pos_image['src'])){
    			     $out[] = $pos_image['src'];
    			}
    		}
    	}
	     
	    $out = clear_images_array($out);
	    return $out;
}


function mspro_sammydress_options($html){
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
				$OPTION['name'] = sammydress_get_option_name($pos_option['option']);
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

function sammydress_get_option_name($options_array){
        if(isset($options_array[0]['#text']) && !is_array($options_array[0]['#text'])){
            $res = explode("Please Select" , $options_array[0]['#text']);
            if(count($res) > 1){
                return trim($res[1]);
            }
        }
        return false;
}



function mspro_sammydress_noMoreAvailable($html){
    if(stripos($html , 'soleout/en_soleout.') > 0){
        return true;
    }
	return false;
}
