<?php


function mspro_aliexpress_getUrl($url){
    //return getUrl($url , false, true, false);
    $initialHTML = getUrl($url);
    $title = mspro_aliexpress_title($initialHTML);
    if($initialHTML && (!$title || $title == false || trim($title) == '') ){
        return getUrl($url , false, true, false);
    }
    return $initialHTML;
}

function mspro_aliexpress_title($html){

		$res = explode('itemprop="name">' , $html , 2);
		if(count($res) > 1){
			$res = explode('</h' , $res[1] , 2);
			if(count($res) > 1){
				return $res[0];
			}
		}
		
		$instruction = 'h1#product-name';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
 			//echo $data[0]['#text'];exit;
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
		$instruction = 'h1.product-name';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
 			//echo $data[0]['#text'];exit;
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        return '';
}

function mspro_aliexpress_description($html){
		$out = '';
		//echo $html;exit;
		
		// Item Specific
		preg_match_all('|<div class="params">(.*)</div>|isU', $html, $result, PREG_SET_ORDER);
		if(isset($result[0][1])){
			$out .= $result[0][1];
		}

		
		// product-params if <div class="params"> not available
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div.product-params');
		foreach ($temp as $block){
		    if(stripos($temp->html() , "ui-attr-list") < 1){
			     $out .= '<div>' . $temp->html() . '</div>';
		    }
		}
		
		if(strlen(trim($out)) < 5){
		    $temp  = $pq->find('div#j-product-desc');
		    foreach ($temp as $block){
		        if(stripos($temp->html() , "ui-attr-list") < 1){
		            $out .= '<div>' . $temp->html() . '</div>';
		        }
		    }
		}
		
		// Item Description
		$temp  = $pq->find('div#custom-description');
		foreach ($temp as $block){
			$out .= $temp->html();
		}

		// try to get custom description by ajax
		$prod_id = explode('id="hid-product-id" value="' , $html , 2);
		if(count($prod_id) > 1){
			$prod_id = explode('"' , $prod_id[1] , 2);
			if(count($prod_id) > 1){
				$custom_desc_url = 'http://www.aliexpress.com/getDescModuleAjax.htm?productId=' . $prod_id[0] . '&productSrc=is';
				$custom_desc_res = getUrl($custom_desc_url);
				if($custom_desc_res){
					$custom_desc_res = str_ireplace(array("window.productDescription='") , array("") , $custom_desc_res);
					$custom_desc_res = substr(trim($custom_desc_res) , 0 , -2);
					// убрать ссылки из рекламного блока
					$custom_desc_res = preg_replace ("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $custom_desc_res);
					if(stripos($out , '<div class="loading32"></div>') > 0){
					   $out = str_replace(array('<div class="loading32"></div>') , array($custom_desc_res) , mspro_aliexpress_leaveJustOneBlock($out , '<div class="loading32"></div>' , "first") );
					}elseif(stripos($out , '<div class="loading32 desc-loading"></div>') > 0){
					   $out = str_replace(array('<div class="loading32 desc-loading"></div>') , array($custom_desc_res) , mspro_aliexpress_leaveJustOneBlock($out , '<div class="loading32 desc-loading"></div>' , "first") );
					}
				}
			}
		}

		// Packaging Details
		$temp  = $pq->find('div#pdt-pnl-packaging');
		foreach ($temp as $block){
			$out .= $temp->html();
		}
		
		// ТЕПЕРЬ ПРОБУЕМ ТАК:
		if(strlen($out) < 10){
			$res = explode('<div id="product-desc" class="product-desc">' , $html);
			if(count($res) > 1){
				$res = explode('<div id="transaction-history">' , $res[1]);
				if(count($res) > 1){
					$out .= $res[0];
				}
			}
		}
		
		// try to remove advertisement block
		$out = preg_replace(array("'<div style=\"border: 1.0px solid #dedede;vertical-align: top;text-align: left;color: #666666;width: 120.0px;padding: 10.0px 15.0px;margin: 10.0px 10.0px 0 0;word-break: break-all;display: inline-block;\"[^>]*?>.*?</div>'si"), Array(""), $out);
		$out = preg_replace(array("'<div style=\"max-width: 650.0px;overflow: hidden;font-size: 0;clear: both;\"[^>]*?>.*?</div>'si"), Array(""), $out);
		// убрать ссылки из блока
		$out = preg_replace ("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $out);
		// clear
		$out = str_replace(array("<p>	&nbsp; </p>") , array("") , $out);
		
		
		//echo '2:' . $out;exit;
        return $out;
}

function mspro_aliexpress_leaveJustOneBlock($html , $block, $number = 1){
    switch($number){
        case "first":
        case 1:
            $res = explode($block , $html);
            if(count($res) > 1){
                $out = $res[0];
                unset($res[0]);
                $out .= $block . implode("" , $res);
                return $out;
            }
        case "last":
            break;
        default:
            break;
    }
    return $html;
}

function mspro_aliexpress_price($html){
    //echo $html;exit;
	
		$instruction = 'span[itemprop=price]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ) {
         	$price = mspro_aliexpress_prepare_price( $data[0]['#text'] );
        	return $price;
         }
	
	
		$instruction = 'input#sku-price-store';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
        if (isset($data['value']) && !is_array($data['value']) ) {
        	$price = mspro_aliexpress_prepare_price( $data['value'] );
        	return $price;
        }

        $instruction = 'span#sku-price';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if ($data){
        	if(isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text'])){
        		$price = mspro_aliexpress_prepare_price( $data[0]['span'][0]['#text'] );
        	}elseif(isset($data['span'][0]['#text']) && !is_array($data['span'][0]['#text'])){
        		$price = mspro_aliexpress_prepare_price( $data['span'][0]['#text'] );
        	}elseif(isset($data['#text']) && !is_array($data['#text'])){
        		$price = mspro_aliexpress_prepare_price( $data['#text'] );
        	}elseif(isset($data[0]['#text']) && !is_array($data[0]['#text'])){
        		$price = mspro_aliexpress_prepare_price( $data[0]['#text'] );
        	}
        	//echo $price;exit;
        	return $price;
        }
        
        $instruction = 'span#j-sku-price';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if ($data){
            if(isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text'])){
                $price = mspro_aliexpress_prepare_price( $data[0]['span'][0]['#text'] );
            }elseif(isset($data['span'][0]['#text']) && !is_array($data['span'][0]['#text'])){
                $price = mspro_aliexpress_prepare_price( $data['span'][0]['#text'] );
            }elseif(isset($data['#text']) && !is_array($data['#text'])){
                $price = mspro_aliexpress_prepare_price( $data['#text'] );
            }elseif(isset($data[0]['#text']) && !is_array($data[0]['#text'])){
                $price = mspro_aliexpress_prepare_price( $data[0]['#text'] );
            }
            //echo $price;exit;
            return $price;
        }
        
        return '';
}

function mspro_aliexpress_prepare_price($price){
    $t_res = explode(' - ' , $price);
    if(count($t_res) > 1){
        $price = $t_res[0];
    }
    if(stripos($price , ".") > 0 && stripos($price , ",") > 0){
    	$res = preg_replace("/[^0-9.]/", "",  $price);
    }else{
        $res = preg_replace("/[^0-9,.]/", "",  $price);
        $res = str_replace("," , "." , $res);
    }
	$res = round( (float) $res , 2);
	return $res;
}


function mspro_aliexpress_sku($html){
		$res =  explode('<input type="hidden" name="objectId" value="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return $res[0];	
       		}
       		 
       }
       return '';
}

function mspro_aliexpress_model($html){
	return mspro_aliexpress_sku($html);
}


function mspro_aliexpress_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_aliexpress_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_aliexpress_main_image($html){
    $arr = mspro_aliexpress_get_images($html);
    if(isset($arr[0]) && strlen($arr[0]) > 0){
        return $arr[0];
    }
    return '';
}



function mspro_aliexpress_other_images($html){
    $arr = mspro_aliexpress_get_images($html);
    if(count($arr) > 1){
        unset($arr[0]);
    }
    return $arr;
}


function mspro_aliexpress_get_images($html){
        $out = array();
		// imageURL
		$start = strpos($html, 'imageURL');
        $end = strpos($html, ']', $start);
        
        
        if($start > 0){
	        $images = substr($html, $start, $end - $start);
	        $images = substr($images, strpos($images, '[') + 1);
	        //$images = str_replace("'", '"', $images);
	        $images = explode(',', $images);
	
	        if (count($images) > 0){
		        foreach ($images as $index => $value) {
		            $images[$index] = trim(str_replace(array('"' , "'"), array('' , ''), $value));
		        }
	        }
	        $images = ali_prepare_img_array($images);
	        if (count($images) > 0) {
	            foreach($images as $image){
	                $out[] = $image;
	            }
	        }
        }
        
        //imageBigViewURL
		$start = strpos($html, 'imageBigViewURL');
        $end = strpos($html, ']', $start);

        if($start > 0){
	        $images = substr($html, $start, $end - $start);
	        $images = substr($images, strpos($images, '[') + 1);
	        //$images = str_replace("'", '"', $images);
	        $images = explode(',', $images);
	
	        if (count($images) > 0){
		        foreach ($images as $index => $value) {
		            $images[$index] = trim(str_replace(array('"' , "'"), array('' , ''), $value));
		        }
			}
	        $images = ali_prepare_img_array($images);
	        if (count($images) > 0) {
	           foreach($images as $image){
	                $out[] = $image;
	            }
	        }
        }
        
        // ADDITIONAL IMAGES FOR DIFFERENT  COLORS
        /*$instruction = 'ul#sku-color li a.sku-value';
        $parser = new nokogiri($html);
        $data_additional = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if(isset($data_additional) && is_array($data_additional) && count($data_additional) > 0){
            foreach($data_additional as $pos_image){
                if(isset($pos_image['img']['bigpic']) && !is_array($pos_image['img']['bigpic']) && strlen(trim($pos_image['img']['bigpic'])) > 0){
                    $out[] = $pos_image['img']['bigpic'];
                }
            }
        }else{
            $instruction = 'ul.sku-attr-list li.item-sku-image a';
            $parser = new nokogiri($html);
            $data_additional = $parser->get($instruction)->toArray();
            //echo '<pre>'.print_r($data , 1).'</pre>';exit;
            unset($parser);
            if(isset($data_additional) && is_array($data_additional) && count($data_additional) > 0){
                foreach($data_additional as $pos_image){
                    if(isset($pos_image['img']['bigpic']) && !is_array($pos_image['img']['bigpic']) && strlen(trim($pos_image['img']['bigpic'])) > 0){
                        $out[] = $pos_image['img']['bigpic'];
                    }
                }
            }
        }*/
        
        $out = array_unique($out);
        //echo '<pre>'.print_r($out , 1).'</pre>';exit;

        return $out;
}


function ali_prepare_img_array($arr){
	foreach($arr as $key => $val){
		if(strpos($val , "f IE" ) > 0){
			unset($arr[$key]);
		}else{
			$arr[$key] = str_ireplace(array("_350x350.jpg") , array("") , $val);
		}
	}
	return (array) $arr;
}



function mspro_aliexpress_options($html){
	$out = array();
	
	// need decode to utf-8
	$need_decode = false;
	if(strpos($html , 'ru.aliexpress.') > 0){
	    $need_decode = true;
	}
	
	// get prices block
	$originalPrice = mspro_aliexpress_price($html);
	//echo $originalPrice;
	$priceBlockSemafor = false;
	$priceBlockRes = explode('skuProducts=' , $html , 2);
	if(count($priceBlockRes) > 1){
		$priceBlockRes = explode('skuAttrIds' , $priceBlockRes[1] , 2);
		if(count($priceBlockRes) > 1){
			$priceBlockRes = $priceBlockRes[0];
			$priceBlockSemafor = true;
		}
	}
	
	
	$instruction = 'div#product-info-sku';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>s'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if(isset($res[0]['dl']) && is_array($res[0]['dl']) && count($res[0]['dl'])> 0){
		$res = $res[0]['dl'];
		$optionNumber = 1;
		foreach($res as $pos_option){
		    //echo '<pre>'.print_r($pos_option , 1).'</pre>';exit;
			if(isset($pos_option['dt'][0]['#text']) && !is_array($pos_option['dt'][0]['#text']) && isset($pos_option['dd'][0]['ul'][0]['li']) && is_array($pos_option['dd'][0]['ul'][0]['li']) && count($pos_option['dd'][0]['ul'][0]['li']) > 0){
				//$opt_name = str_replace( array(":") , array("") , ali_options_get_name($optionNumber , $html) );
			    $opt_name = trim($pos_option['dt'][0]['#text']);
				$opt_name = $need_decode?mspro_ali_decode($opt_name):$opt_name;
				$optionNumber++;
				$opt_values = $pos_option['dd'][0]['ul'][0]['li'];
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , $opt_name);
				$type = "select";
				$originalType = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($opt_values as $option_value){
					if(isset($option_value['a']['span']['#text']) && !is_array($option_value['a']['span']['#text']) && isset($option_value['a']['id']) && !is_array($option_value['a']['id']) ){
						$opt_SKU = trim($option_value['a']['id']);
						$optPrice = 0;
						if($priceBlockSemafor && $optionNumber < 3){
							$optPrice = ali_options_get_price($opt_SKU , $priceBlockRes , $originalPrice);
						}
						$optName = $option_value['a']['span']['#text'];
						if($optName){
							$OPTION['values'][] = array(
									'name' =>  $need_decode?mspro_ali_decode($optName):$optName,
									'price' => $optPrice
							);
						}
					}elseif(isset($option_value['a']['img']['src']) && !is_array($option_value['a']['img']['src']) && isset($option_value['a']['title']) && !is_array($option_value['a']['title']) && isset($option_value['a']['id']) && !is_array($option_value['a']['id']) ){
					    // for colors WITH IMAGES
					    $type = 'select';
					    $originalType = 'image';
					    $opt_SKU = $option_value['a']['id'];
					    $optPrice = 0;
					    if($priceBlockSemafor && $optionNumber < 3){
					        $optPrice = ali_options_get_price($opt_SKU , $priceBlockRes , $originalPrice);
					    }
					    $optName = $option_value['a']['title'];
					    $optImage = str_ireplace("_50x50.jpg" , "" , $option_value['a']['img']['src']);
					    if($optName){
					        $OPTION['values'][] = array(
					            'name' =>  $need_decode?mspro_ali_decode($optName):$optName,
					            'image' => $optImage,
					            'price' => $optPrice
					        );
					    }
					}elseif(isset($option_value['a']['title']) && !is_array($option_value['a']['title']) && isset($option_value['a']['id']) && !is_array($option_value['a']['id']) ){
					    $opt_SKU = $option_value['a']['id'];
					    $optPrice = 0;
					    if($priceBlockSemafor && $optionNumber < 3){
					        $optPrice = ali_options_get_price($opt_SKU , $priceBlockRes , $originalPrice);
					    }
					    $optName = $option_value['a']['title'];
					    if($optName){
					        $OPTION['values'][] = array(
					            'name' =>  $need_decode?mspro_ali_decode($optName):$optName,
					            'price' => $optPrice
					        );
					    }
					}
				}
				$OPTION['type'] = $type;
				$OPTION['original_type'] = $originalType;
				//echo '<pre>s'.print_r($OPTION , 1).'</pre>';exit;
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
				//echo '<pre>'.print_r($opt_values , 1).'</pre>';exit;
			}
		}
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	}
	
	
	// ANOTHER VARIATION
	if(count($out) < 1){
	    $instruction = 'div#j-product-info-sku';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>s'.print_r($res , 1).'</pre>';exit;
	    unset($parser);
	    if(isset($res[0]['dl']) && is_array($res[0]['dl']) && count($res[0]['dl'])> 0){
	        $res = $res[0]['dl'];
	        $optionNumber = 1;
	        foreach($res as $pos_option){
	            //echo '<pre>'.print_r($pos_option , 1).'</pre>';exit;
	            if(isset($pos_option['dt'][0]['#text']) && !is_array($pos_option['dt'][0]['#text']) && isset($pos_option['dd'][0]['ul'][0]['li']) && is_array($pos_option['dd'][0]['ul'][0]['li']) && count($pos_option['dd'][0]['ul'][0]['li']) > 0){
	                //$opt_name = str_replace( array(":") , array("") , ali_options_get_name($optionNumber , $html) );
	                $opt_name = trim($pos_option['dt'][0]['#text']);
	                $opt_name =  $need_decode?mspro_ali_decode($opt_name):$opt_name;
	                $optionNumber++;
	                $opt_values = $pos_option['dd'][0]['ul'][0]['li'];
	                $OPTION = array();
	                $OPTION['name'] = str_replace( array(":") , array("") , $opt_name);
	                $type = "select";
	                $originalType = "select";
	                $OPTION['required'] = true;
	                $OPTION['values'] = array();
	                foreach($opt_values as $option_value){
	                    if(isset($option_value['a']['span']['#text']) && !is_array($option_value['a']['span']['#text']) && isset($option_value['a']['id']) && !is_array($option_value['a']['id']) ){
	                        $opt_SKU = trim($option_value['a']['id']);
	                        $optPrice = 0;
	                        if($priceBlockSemafor && $optionNumber < 3){
	                            $optPrice = ali_options_get_price($opt_SKU , $priceBlockRes , $originalPrice);
	                        }
	                        $optName = $option_value['a']['span']['#text'];
	                        if($optName){
	                            $OPTION['values'][] = array(
	                                'name' =>  $need_decode?mspro_ali_decode($optName):$optName,
	                                'price' => $optPrice
	                            );
	                        }
	                    }elseif(isset($option_value['a']['img']['src']) && !is_array($option_value['a']['img']['src']) && isset($option_value['a']['title']) && !is_array($option_value['a']['title']) && isset($option_value['a']['id']) && !is_array($option_value['a']['id']) ){
	                        // for colors WITH IMAGES
	                        $type = 'select';
	                        $originalType = 'image';
	                        $opt_SKU = $option_value['a']['id'];
	                        $optPrice = 0;
	                        if($priceBlockSemafor && $optionNumber < 3){
	                            $optPrice = ali_options_get_price($opt_SKU , $priceBlockRes , $originalPrice);
	                        }
	                        $optName = $option_value['a']['title'];
	                        $optImage = str_ireplace("_50x50.jpg" , "" , $option_value['a']['img']['src']);
	                        if($optName){
	                            $OPTION['values'][] = array(
	                                'name' =>  $need_decode?mspro_ali_decode($optName):$optName,
	                                'image' => $optImage,
	                                'price' => $optPrice
	                            );
	                        }
	                    }elseif(isset($option_value['a']['title']) && !is_array($option_value['a']['title']) && isset($option_value['a']['id']) && !is_array($option_value['a']['id']) ){
	                        $opt_SKU = $option_value['a']['id'];
	                        $optPrice = 0;
	                        if($priceBlockSemafor && $optionNumber < 3){
	                            $optPrice = ali_options_get_price($opt_SKU , $priceBlockRes , $originalPrice);
	                        }
	                        $optName = $option_value['a']['title'];
	                        if($optName){
	                            $OPTION['values'][] = array(
	                                'name' =>  $need_decode?mspro_ali_decode($optName):$optName,
	                                'price' => $optPrice
	                            );
	                        }
	                    }
	                }
	                $OPTION['type'] = $type;
	                $OPTION['original_type'] = $originalType;
	                //echo '<pre>s'.print_r($OPTION , 1).'</pre>';exit;
	                if(count($OPTION['values']) > 0){
	                    $out[] = $OPTION;
	                }
	                //echo '<pre>'.print_r($opt_values , 1).'</pre>';exit;
	            }
	        }
	        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    }
	}
	
	
	
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}



function ali_options_get_price($sku , $priceBlock , $price){
	$sku_res = explode('-' , $sku);
	if(count($sku_res) > 1){
		$sku = $sku_res[count($sku_res) - 1];
	}
	
	$res = explode(':'.$sku , $priceBlock);
	if(count($res) > 1){
		$res = $res[1];
		$res = explode('actSkuMultiCurrencyCalPrice":"' , $res , 2);
		if(count($res) > 1){
			$res = explode('"' , $res[1] , 2);
			if(count($res) > 1){
				$res = (float) $res[0];
				//echo 'res - '. $res .'<br />';
				return round( ($res - $price) , 2);
			}
		}
	}
	
	return 0;
}



function ali_options_get_name($num , $html){
	$res = explode('<dt class="pp-dt-ln sku-title">' , $html);
	if(isset($res[$num])){
		$res = explode('</dt>' , $res[$num] , 2);
		if(count($res) > 1){
			return trim($res[0]);
		}
	}
	return '';
}

function ali_option_value_get_name($sku , $html){
	$res = explode('<a class="sku-value attr-checkbox" id="'.$sku.'" href="javascript:void(0)"><span>' , $html  , 2);
	if(count($res) > 1){
		$res = explode('</span>' , $res[1] , 2);
		if(count($res) > 1){
			return trim($res[0]);
		}
	}
	return false;
}


function mspro_aliexpress_attributes($html , $url){
    $out = array();
    
    // need decode to utf-8
    $need_decode = false;
    if(strpos($html , 'ru.aliexpress.') > 0){
        $need_decode = true;
    }

    $instruction = 'div.product-params';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    foreach($data as $attrGroup){
        //echo $i;
        if(isset($attrGroup['h2'][0]['#text']) && !is_array($attrGroup['h2'][0]['#text']) && isset($attrGroup['div'][0]['dl']) && is_array($attrGroup['div'][0]['dl']) && count($attrGroup['div'][0]['dl']) > 0 ){
            foreach($attrGroup['div'][0]['dl'] as $posAttr){
                $ATTR = array();
                $group = trim(str_replace( array(":") , array("") , $attrGroup['h2'][0]['#text'] ));
                $ATTR['group'] = $need_decode === true?mspro_ali_decode($group):$group;
                if(isset($posAttr['dt'][0]['#text']) && !is_array($posAttr['dt'][0]['#text']) && isset($posAttr['dd'][0]['#text']) && !is_array($posAttr['dd'][0]['#text'])){
                    $name = trim(str_replace( array(":") , array("") , $posAttr['dt'][0]['#text'] ));
                    $value = trim(str_replace( array(":") , array("") , $posAttr['dd'][0]['#text'] ));
                    $ATTR['name'] =  $need_decode === true?mspro_ali_decode($name):$name;
                    $ATTR['value'] = $need_decode === true?mspro_ali_decode($value):$value;
                    $out[] = $ATTR;
                }
               
            } 
        }
    }
    
    
    
    if(count($out) < 1){
        $instruction = 'div#j-product-desc';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        foreach($data as $attrGroup){
            //echo $i;
            if(isset($attrGroup['h2'][0]['#text']) && !is_array($attrGroup['h2'][0]['#text']) && isset($attrGroup['div'][0]['dl']) && is_array($attrGroup['div'][0]['dl']) && count($attrGroup['div'][0]['dl']) > 0 ){
                foreach($attrGroup['div'][0]['dl'] as $posAttr){
                    $ATTR = array();
                    $group = trim(str_replace( array(":") , array("") , $attrGroup['h2'][0]['#text'] ));
                    $ATTR['group'] = $need_decode === true?mspro_ali_decode($group):$group;
                    if(isset($posAttr['dt'][0]['#text']) && !is_array($posAttr['dt'][0]['#text']) && isset($posAttr['dd'][0]['#text']) && !is_array($posAttr['dd'][0]['#text'])){
                        $name = trim(str_replace( array(":") , array("") , $posAttr['dt'][0]['#text'] ));
                        $value = trim(str_replace( array(":") , array("") , $posAttr['dd'][0]['#text'] ));
                        $ATTR['name'] =  $need_decode === true?mspro_ali_decode($name):$name;
                        $ATTR['value'] = $need_decode === true?mspro_ali_decode($value):$value;
                        $out[] = $ATTR;
                    }
                     
                }
            }elseif(isset($attrGroup['div'][0]['div'][0]['#text']) && !is_array($attrGroup['div'][0]['div'][0]['#text']) && isset($attrGroup['div'][0]['div'][1]['ul'][0]['li']) && is_array($attrGroup['div'][0]['div'][1]['ul'][0]['li']) && count($attrGroup['div'][0]['div'][1]['ul'][0]['li']) > 0 ){
                foreach($attrGroup['div'][0]['div'][1]['ul'][0]['li'] as $posAttr){
                    $ATTR = array();
                    $group = trim(str_replace( array(":") , array("") , $attrGroup['div'][0]['div'][0]['#text'] ));
                    $ATTR['group'] = $need_decode == true?mspro_ali_decode($group):$group;
                    if(isset($posAttr['span'][0]['#text']) && !is_array($posAttr['span'][0]['#text']) && isset($posAttr['span'][1]['#text']) && !is_array($posAttr['span'][1]['#text'])){
                        $name = trim(str_replace( array(":") , array("") , $posAttr['span'][0]['#text'] ));
                        $value = trim(str_replace( array(":") , array("") , $posAttr['span'][1]['#text'] ));
                        $ATTR['name'] =  $need_decode === true?mspro_ali_decode($name):$name;
                        $ATTR['value'] = $need_decode === true?mspro_ali_decode($value):$value;
                        $out[] = $ATTR;
                    }
                     
                }
            }
        }
    } 

   //echo '<pre>'.print_r($out , 1).'</pre>';exit;

    return $out;
}

function mspro_ali_decode($str){
    //echo $str;
    //return $str;
    return utf8_decode($str);
}



