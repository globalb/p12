<?php


function mspro_rosegal_title($html){
	$instruction = 'h1';
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

function mspro_rosegal_description($html){
	$res = '';
	
	$pq = phpQuery::newDocumentHTML($html);
	$temp  = $pq->find('span[itemprop=description]');
	foreach ($temp as $block){
		$res .= $temp->html();
	}
	
	$pq = phpQuery::newDocumentHTML($html);
	$temp  = $pq->find('div[itemprop=description]');
	foreach ($temp as $block){
	    $res .= '<div>' . $temp->html() . '</div>';
	}
	
	
	$pq = phpQuery::newDocumentHTML($html);
	$temp  = $pq->find('div.size_chart_inner');
	foreach ($temp as $block){
	    $res .= '<div>' . $temp->html() . '</div>';
	}
	
	//echo $res;exit;

	
	return $res;
}


function mspro_rosegal_price($html){
        $res =  explode('ecomm_totalvalue:' , $html);
       if(count($res) > 1){
       		$res = explode(',' , $res[1]);
       		if(count($res) > 1){
       		    $price = preg_replace("/[^0-9.]/", "",  trim($res[0]) );
       			return (float) $price;	
       		}
       		 
       }
       $res =  explode('<span itemprop="price" content="' , $html);
       if(count($res) > 1){
           $res = explode('"' , $res[1]);
           if(count($res) > 1){
               $price = preg_replace("/[^0-9.]/", "",  trim($res[0]) );
               return (float) $price;
           }
       
       }
       
       return '';
}


function mspro_rosegal_sku($html){
		return mspro_rosegal_model($html);
}

function mspro_rosegal_model($html){
        $res =  explode('<input type="hidden" id="hidden-goodsId" value="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return trim($res[0]);
       		}
       }
       $res =  explode('<input name="goods_id" id="inquire_goods_id" type="hidden" value="' , $html);
       if(count($res) > 1){
           $res = explode('"' , $res[1]);
           if(count($res) > 1){
               return trim($res[0]);
           }
       }
       $res =  explode('var aff_goods_id = "' , $html);
       if(count($res) > 1){
           $res = explode('"' , $res[1]);
           if(count($res) > 1){
               return trim($res[0]);
           }
       }
       return false;
}


function mspro_rosegal_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_ireplace(array("&nbsp;" , "&amp;" , "at RoseGal.com") , array(" " , "`" , "") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_rosegal_meta_keywords($html){
       return mspro_rosegal_meta_description($html);
}



function mspro_rosegal_weight($html){
    $out = array();
    $res = explode('<strong>Weight:</strong>' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1] , 2);
        if(count($res) > 1){
            $weight = $res[0];
            $weight = preg_replace("/[^A-Za-z0-9,.]/", "",  strip_tags($weight) );
            $weight = str_ireplace("nbsp", "" , $weight);
            $out['weight_class_id'] = 2;
            if(strpos($weight , "kilogram") > 1 || strpos($weight , "Kg") > 1 || strpos($weight , "KG") > 1){
                $out['weight_class_id'] = 1;
            }
            $out['weight'] = (float) preg_replace("/[^0-9,.]/", "",  $weight);
        }
    }
    return $out;
}
/*
function mspro_rosegal_dimensions($html){
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
*/


function mspro_rosegal_main_image($html){
	$arr = mspro_rosegal_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_rosegal_other_images($html){
	$arr = mspro_rosegal_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}


function mspro_rosegal_get_images_arr($html){
    $out = array();
    
    $instruction = 'span.jqzoom img';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(count($data) > 0 && is_array($data)){
        foreach($data as $img){
            if(isset($img['jqimg'])){
                $res_t = explode('?' , $img['jqimg']);
                if(count($res_t) > 1){
                    $out[] =  $res_t[0];
                }else{
                    $out[] =  $img['jqimg'];
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


    $instruction = 'div#js_goodImg_list a img';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(count($data) > 0 && is_array($data) && count($data) > 0){
        foreach($data as $img){
            if(isset($img['bigimg'])){
                $res_t = explode('?' , $img['bigimg']);
                if(count($res_t) > 1){
                    $out[] =  $res_t[0];
                }else{
                    $out[] =  $img['bigimg'];
                }
            }elseif(isset($img['imgb'])){
                $res_t = explode('?' , $img['imgb']);
                if(count($res_t) > 1){
                    $out[] =  $res_t[0];
                }else{
                    $out[] =  $img['imgb'];
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
    
    $instruction = 'div#goods_thumb_content ul li';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if(count($data) > 0 && is_array($data)){
        foreach($data as $img){
            if(isset($img['data-bigimg'])){
                $res_t = explode('?' , $img['data-bigimg']);
                if(count($res_t) > 1){
                    $out[] =  $res_t[0];
                }else{
                    $out[] =  $img['data-bigimg'];
                }
            }elseif(isset($img['data-zoomimg'])){
                $res_t = explode('?' , $img['data-zoomimg']);
                if(count($res_t) > 1){
                    $out[] =  $res_t[0];
                }else{
                    $out[] =  $img['data-zoomimg'];
                }
            }
        }
    }


    $out = clear_images_array($out);
   	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
}



function mspro_rosegal_options($html){
	$out = array();
	
	$instruction = 'table tr td';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['strong'][0]['#text']) && !is_array($pos_option['strong'][0]['#text']) && strlen($pos_option['strong'][0]['#text']) > 0 && isset($pos_option['select'][0]['option']) && is_array($pos_option['select'][0]['option']) && count($pos_option['select'][0]['option']) > 1){
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , $pos_option['strong'][0]['#text']);
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				$pos_options_values = $pos_option['select'][0]['option'];
				unset($pos_options_values[0]);
				foreach($pos_options_values as $option_value){
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
	
	$instruction = 'span.size_list a';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if(isset($res) && is_array($res) && count($res) > 0){
	    $OPTION = array();
	    $OPTION['name'] = str_replace( array(":") , array("") , "Size");
	    $OPTION['type'] = "select";
	    $OPTION['required'] = true;
	    $OPTION['values'] = array();
	    foreach($res as $option_value){
	        if(isset($option_value['#text']) && !is_array($option_value['#text']) && !(isset($option_value['class']) && trim($option_value['class']) == "disabled") ){
	            $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
	        }
	    }
	    if(count($OPTION['values']) > 0){
	        $out[] = $OPTION;
	    }
	}
	
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}


function mspro_rosegal_noMoreAvailable($html){
	return false;
}
