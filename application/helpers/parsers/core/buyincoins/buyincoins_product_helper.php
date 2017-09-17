<?php


function mspro_buyincoins_title($html){
		$instruction = 'div.block h1';
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
        
        $instruction = 'div.title_details';
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

function mspro_buyincoins_description($html){
		$res = '';
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div.desc:first');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		return $res;
}


function mspro_buyincoins_price($html){
        $res =  explode('class="currPrice">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "", $res[0]);
                return (float) $price;
            }
        }
        $res =  explode('<meta name="twitter:data1" content="$' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "", $res[0]);
                return (float) $price;
            }
        }
        $res =  explode('price": "' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "", $res[0]);
                return (float) $price;
            }
        }
    
		$instruction = 'td.currPrice';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if(isset($res[0]['#text']) && !is_array($res[0]['#text']) ){
			return (float) str_ireplace(array("$" , "&euro;") , array("" , "") , $res[0]['#text']);
		}
		if(isset($res[0]['#text'][0]) && !is_array($res[0]['#text'][0]) ){
			return (float) str_ireplace(array("$" , "&euro;") , array("" , "") , $res[0]['#text'][0]);
		}
		return '';
}


function mspro_buyincoins_sku($html){
		$instruction = 'span#attr-sku';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		unset($parser);
		if (isset($res[0]['#text'])) {
		 	return trim($res[0]['#text']);
	    }
		return ''; 
}


function mspro_buyincoins_model($html){
        $res =  explode('Item Code:' , $html);
        if(count($res) > 1){
       		$res = explode('</div>' , $res[1] , 2);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , trim($res[0]) );	
       		} 
        }
        return '';
}


function mspro_buyincoins_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('" />' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , trim($res[0]) ) ;	
       		}
       		 
       }
       return '';
}

function mspro_buyincoins_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('" />' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , trim($res[0]) );	
       		}
       		 
       }
       return '';
}


function mspro_buyincoins_main_image($html){
		$imgs_arr = mspro_buyincoins_get_imgs_array($html);
		if(is_array($imgs_arr) && count($imgs_arr) > 0){
			return $imgs_arr[0];
		}
		return '';
}


function mspro_buyincoins_other_images($html){
		$imgs_arr = mspro_buyincoins_get_imgs_array($html);
		if(is_array($imgs_arr) && count($imgs_arr) > 1){
			unset($imgs_arr[0]);
			return $imgs_arr;
		}
		return array();
}



function mspro_buyincoins_get_imgs_array($html){
	$out = array();
		
		// first image
		$instruction = 'div#windowImg img';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if(isset($res[0]['data-zoom-image']) && !is_array($res[0]['data-zoom-image'])){
			$out[] = $res[0]['data-zoom-image'];
		}elseif(isset($res[0]['src']) && !is_array($res[0]['src'])){
			$out[] = $res[0]['src'];
		}
		
		// all othe images in slideshow
		$instruction = 'ul#picImgs li img';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if(is_array($res) && count($res) > 0){
			foreach($res as $image){
				if(isset($image['data-zoom-image']) && !is_array($image['data-zoom-image']) ){
					$out[] = $image['data-zoom-image'];
				}elseif(isset($image['src']) && !is_array($image['src']) ){
					$out[] = $image['src'];
				}
			}
		}
		
		$instruction = 'div.items li img';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if(is_array($res) && count($res) > 0){
		    foreach($res as $image){
		        if(isset($image['bimg']) && !is_array($image['bimg']) ){
		            $out[] = $image['bimg'];
		        }elseif(isset($image['src']) && !is_array($image['src']) ){
		            $out[] = $image['src'];
		        }
		    }
		}
		
		$out = clear_images_array($out);
		//echo '<pre>'.print_r($out , 1).'</pre>';exit;
		return $out;
}



function mspro_buyincoins_options($html){
	$out = array();
	
	$opt_arr = array();
	$res = explode('var attrJson = ' , $html , 2);
	if(count($res) > 1){
		$res = explode(';</script' , $res[1] , 2);
		if(count($res) > 1){
			@$res_t = json_decode($res[0] , 1);
			if (isset($res_t) && is_array($res_t) && count($res_t) > 0){
				foreach($res_t as $key => $pos_opt){
					if(isset($pos_opt['product_attribute_cls_name']) && isset($pos_opt['product_attribute']) && isset($pos_opt['product_attribute_price']) ){
						$p = false;
						if(is_array($pos_opt['product_attribute_price']) && isset($pos_opt['product_attribute_price'][1]) && !is_array($pos_opt['product_attribute_price'][1]) ){
							$p = (float) $pos_opt['product_attribute_price'][1];
						}
						$temp = array('value' => $pos_opt['product_attribute'] , 'price' => $p);
						if(!array_key_exists($pos_opt['product_attribute_cls_name'], $opt_arr)){
							$opt_arr[$pos_opt['product_attribute_cls_name']] = array();
						}
						$opt_arr[$pos_opt['product_attribute_cls_name']][] = $temp;
					}
				}
			}
		}
	}
	//echo '<pre>'.print_r($opt_arr , 1).'</pre>';exit;
	if (is_array($opt_arr) && count($opt_arr) > 0){
		$originalPrice = mspro_buyincoins_price($html);
		foreach($opt_arr as $opt_name => $option_values){
				$OPTION = array();
				$OPTION['name'] = $opt_name;
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($option_values as $option_value){
					$OPTION['values'][] = array('name' => $option_value['value'] , 'price' => buyincoins_get_option_price($option_value['price'] , $originalPrice) );
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
		}
	}
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}


function buyincoins_get_option_price($price , $originalPrice){
	if($price){
		$res = (float) ( (float) $price - $originalPrice);
		$res = round($res , 2);
		return $res;
	}
	return 0;
}


/*
function mspro_buyincoins_noMoreAvailable($html){
	return false;
}
*/