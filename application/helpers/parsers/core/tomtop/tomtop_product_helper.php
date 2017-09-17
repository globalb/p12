<?php


function mspro_tomtop_title($html){
	   $instruction = 'h1.productIntroduce_title';
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
        
        $instruction = 'h1 span[itemprop=name]';
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

function mspro_tomtop_description($html){
	    $res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div.Description:first');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
			break;
		}
		
		$temp  = $pq->find('div#description');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html().'</div>';
		    break;
		}
		
		// changing the images
		// get images array
		preg_match_all('/(<img[^<]+>)/Usi', $res, $images);
		$image = array();
		foreach ($images[0] as $index => $value) {
		    $s = strpos($value, 'src="') + 5;
		    $e = strpos($value, '"', $s + 1);
		    $image[substr($value, $s, $e - $s)] =   substr($value, $s, $e - $s);
		}
		//echo '<pre>'.print_r($image , 1).'</pre>';
		foreach ($image as $index => $value) {
		    if(strpos($value , "guphotos.com") < 1){
    		    if(strpos($value , "tomtop.com") < 1){
    		        $value = 'http://www.tomtop.com' . $value;
    		    }else{
    		        $value = str_ireplace(array('http://www.tomtop.com/') , array('http://74.86.127.114/') , $value);
    		    }
		    }
    		$res = str_replace($index, $value , $res);
		}
		
		//echo $res;exit;
		
		return $res;
}


function mspro_tomtop_price($html){
	    $res = explode('<div class="productSpecialPrice_NUM">' , $html);
        if(count($res) > 1){
        	$res = explode('<' , $res[1] , 2);
        	if(count($res) > 1){
        	    $price = preg_replace("/[^0-9.]/", "",  $res[0]);
        		return (float) $price;
        	} 
        }
        $res = explode('<p><span class="orange">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode('<input type="hidden" id="total-price-3" value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode('<span class="fz_orange pricelab" usvalue="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode('<p id="detailPrice" class="lineBlock price pricelab" itemprop="price" usvalue="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode(',"saleprice":' , $html);
        if(count($res) > 1){
            $res = explode(',' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        return '';
}


function mspro_tomtop_sku($html){
	$res = explode('<input type="hidden" name="csku" value="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    $res = explode('( Item#:' , $html);
    if(count($res) > 1){
        $res = explode(')' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    $res = explode('<input type="hidden" name="productSku" id="productSku" value="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    $res = explode(' product={"sku":"' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    return '';
}

function mspro_tomtop_model($html){
	return mspro_tomtop_sku($html);
}


function mspro_tomtop_meta_description($html){
       return mspro_tomtop_title($html);
}

function mspro_tomtop_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       }
       return  mspro_tomtop_title($html);
}


function mspro_tomtop_main_image($html){
	$arr = mspro_tomtop_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_tomtop_other_images($html){
	$arr = mspro_tomtop_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}


function mspro_tomtop_get_images_arr($html){
		$out = array();
	
	$instruction = 'div.productSmallPic ul li a';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if(isset($data) && is_array($data) && count($data) > 0){
		foreach($data as $pos_image){
			if(isset($pos_image['href']) && !is_array($pos_image['href'])){
				if(strpos($pos_image['href'] , "tomtop") < 1){
					$out[] = 'http://www.tomtop.com' . $pos_image['href'];
				}else{
					$out[] = $pos_image['href'];
				}
			}elseif(isset($pos_image['img']['src']) && !is_array($pos_image['img']['src'])){
			if(strpos($pos_image['img']['src'] , "tomtop") < 1){
					$out[] = 'http://www.tomtop.com' . $pos_image['img']['src'];
				}else{
					$out[] = $pos_image['img']['src'];
				}
			}
		}
	}
	
	$out = array_unique($out);
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	
	return $out;
}



function mspro_tomtop_options($html){
    $out = array();

    $instruction = 'div.productSpecialSize_box';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['div'][0]['#text']) && !is_array($pos_option['div'][0]['#text']) &&  isset($pos_option['ul'][0]['a']) && is_array($pos_option['ul'][0]['a']) && count($pos_option['ul'][0]['a']) > 0){
                $OPTION = array();
                $OPTION['name'] = ucfirst(str_replace( array(":") , array("") , $pos_option['div'][0]['#text']));
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['ul'][0]['a'] as $option_value){
                    if(isset($option_value['title']) && !is_array($option_value['title'])){
                        $OPTION['values'][] = array('name' =>trim($option_value['title']) , 'price' => 0);
                    }
                }
                if(count($OPTION['values']) > 0){
                    $out[] = $OPTION;
                }
            }
        }
    }
    
    $instruction = 'div.productSpecialColor_box';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['div'][0]['#text']) && !is_array($pos_option['div'][0]['#text']) &&  isset($pos_option['ul'][0]['a']) && is_array($pos_option['ul'][0]['a']) && count($pos_option['ul'][0]['a']) > 0){
                $OPTION = array();
                $OPTION['name'] = ucfirst(str_replace( array(":") , array("") , $pos_option['div'][0]['#text']));
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['ul'][0]['a'] as $option_value){
                    if(isset($option_value['title']) && !is_array($option_value['title'])){
                        $OPTION['values'][] = array('name' =>trim($option_value['title']) , 'price' => 0);
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
 function mspro_tomtop_noMoreAvailable($html){
	return false;
}
*/
