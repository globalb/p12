<?php


function mspro_souq_title($html){
	    $res = explode('<h1 itemprop="name">' , $html);
        if(count($res) > 1){
            $res = explode('</h1>' , $res[1] , 2);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
    
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

function mspro_souq_description($html){
	    $res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('li#specs');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		$temp  = $pq->find('li#description');
		foreach ($temp as $block){
			$res .= $temp->html() . '<br />';
		}
		$temp  = $pq->find('li#videos');
		foreach ($temp as $block){
		    $res .= $temp->html() . '<br />';
		}

		// create styles
		/*$res = str_ireplace(array('<table>') , array('<table style="display: table;">') , $res);
		$styles = '<style>.LTEM_SPECIFICS {border: 1px solid #cacaca;margin-top: 15px;padding-top: 10px;padding-bottom: 10px;} .LTEM_SPECIFICS dd {float: left;padding-left: 13px;width: 196px;height: 22px;line-height: 22x;overflow: hidden;color: #4a4a4a;} .LTEM_SPECIFICS:after {clear: both;display: block;content: ".";}</style>';
		$res = $res . $styles;*/
		
		$res = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $res);
		$res = preg_replace(array("'&lt;script[^>]*?>.*?&lt;/script&gt'si"), Array(""), $res);
		$res = str_replace(array('<a href="javascript:void(0);" class="readmore description hide callapsed">Read more</a>') , array("") , $res);
		
		//echo $res;exit;
		
		return $res;
}


function mspro_souq_price($html){
	    $res = explode('<h3 class="price">' , $html);
        if(count($res) > 1){
        	$res = explode('class="currency' , $res[1] , 2);
        	if(count($res) > 1){
        	    $res = preg_replace("/[^0-9.]/", "",  $res[0]);
        		return (float) $res;
        	} 
        }
        return '';
}


function mspro_souq_sku($html){
		return mspro_souq_model($html); 
}

function mspro_souq_model($html){
    $res = explode('<input type="hidden" id="id_item" name="id_item" value="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
	return mspro_souq_sku($html);
}

function mspro_souq_ean($html){
    $res = explode('<input type="hidden" id="item_ean" name="item_ean" value="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    return '';
}


function mspro_souq_meta_description($html){
	  return  mspro_souq_title($html);
}

function mspro_souq_meta_keywords($html){
      return  mspro_souq_title($html);
}


function mspro_souq_main_image($html){
	$arr = mspro_souq_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_souq_other_images($html){
	$arr = mspro_souq_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}


function mspro_souq_get_images_arr($html){
		$out = array();
	
    	$instruction = 'div.gallary img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    			if(isset($pos_image['data-src']) && !is_array($pos_image['data-src'])){
    				$out[] = $pos_image['data-src'];
    			}elseif(isset($pos_image['data-lazy']) && !is_array($pos_image['data-lazy']) && strpos($pos_image['data-lazy'] , 'blank.gif') < 1){
    			     $out[] = $pos_image['data-lazy'];
    			}elseif(isset($pos_image['src']) && !is_array($pos_image['src']) && strpos($pos_image['src'] , 'blank.gif') < 1){
    			     $out[] = $pos_image['src'];
    			}
    		}
    	}
    	
    	return $out;
}



function mspro_souq_options($html){
    $out = array();

    $instruction = 'div.vip-product-list ul.product-grouped-list';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    // REPARE DROOPDOWNS
    $dropdowns_list = array();
    if(isset($res[0]['ul']) && is_array($res[0]['ul']) && count($res[0]['ul']) > 0){
        foreach($res[0]['ul'] as $pos_drop){
            $temp_values = array();
            if(isset($pos_drop['li']) && is_array($pos_drop['li']) && count($pos_drop['li']) > 0){
                foreach($pos_drop['li'] as $pos_drop_values){
                    if(isset($pos_drop_values['a']['#text']) && !is_array($pos_drop_values['a']['#text']) && strlen(trim($pos_drop_values['a']['#text'])) > 0){
                        $temp_values[] = $pos_drop_values['a']['#text'];
                    }
                }
            }
            if(count($temp_values) > 0){
                $dropdowns_list[] = $temp_values;
            }
        }
    }
    //echo '<pre>'.print_r($dropdowns_list , 1).'</pre>';exit;
    // WORK WITH OPTIONS
    if(isset($res[0]['li']) && is_array($res[0]['li']) && count($res[0]['li']) > 0){
        foreach($res[0]['li'] as $key => $pos_option){
            // check if name exists
            $option_name = false;
            if(isset($pos_option['a'][0]['#text'][0]) && !is_array($pos_option['a'][0]['#text'][0]) && strlen(trim($pos_option['a'][0]['#text'][0])) > 0 ){
                $option_name = $pos_option['a'][0]['#text'][0];
            }elseif(isset($pos_option['#text'][0]) && !is_array($pos_option['#text'][0]) && strlen(trim($pos_option['#text'][0])) > 0 ){
                $option_name = $pos_option['#text'][0];
            }
            // check if default value exists
            $option_default_value = false;
            if(isset($pos_option['a'][0]['span'][0]['#text']) && !is_array($pos_option['a'][0]['span'][0]['#text']) && strlen(trim($pos_option['a'][0]['span'][0]['#text'])) > 0 ){
                $option_default_value = $pos_option['a'][0]['span'][0]['#text'];
            }elseif(isset($pos_option['span'][0]['#text']) && !is_array($pos_option['span'][0]['#text']) && strlen(trim($pos_option['span'][0]['#text'])) > 0 ){
                $option_default_value = $pos_option['span'][0]['#text'];
            }
            if($option_name && $option_default_value){
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , trim($option_name) );
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                $OPTION['values'][] = array('name' => $option_default_value , 'price' => 0);;
                if(isset($dropdowns_list[$key]) && is_array($dropdowns_list[$key]) && count($dropdowns_list[$key]) > 0){
                    foreach($dropdowns_list[$key] as $additional_value){
                        $OPTION['values'][] = array('name' => $additional_value , 'price' => 0);
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


/*function mspro_souq_noMoreAvailable($html){
	return false;
}*/
