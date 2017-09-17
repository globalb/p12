<?php


function mspro_overstock_title($html){
		$instruction = 'div[itemprop=name] h1';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        $instruction = 'span[itemprop=name] h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
        if (isset($data[0]['#text'])) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        
        $instruction = 'h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
        if (isset($data[0]['#text'])) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        return '';
}

function mspro_overstock_description($html){
		$res = ''; 
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('ul#details_descFull');
		foreach ($temp as $block){
			$t_res = $temp->html();
			$t_res = strip_tags($t_res , '<div><ul><li><b><br><br/><h4><img>');
			$t_res = utf8_decode($t_res);
			$t_res = preg_replace(array("'<a[^>]*?>.*?</a>'si"), Array(""), $t_res);
			$t_res = str_ireplace(array("Click here to view our") , array("") , $t_res);
			$res .= $t_res;
		}
		
		$temp  = $pq->find('div#specContainer div.col6span3');
		foreach ($temp as $block){
			$block = str_ireplace(array("<caption>" , "</caption>") , array("<h2>", "</h2>") , $temp->html());
			$res .= $block.'<br />';
		}
		
		
		$temp  = $pq->find('div.description');
		foreach ($temp as $block){
		    $block = str_ireplace(array("<caption>" , "</caption>") , array("<h2>", "</h2>") , $temp->html());
		    $res .= $block.'<br />';
		}
		
		$temp  = $pq->find('div#details_descMisc');
		foreach ($temp as $block){
			$css_insert = '<style type="text/css">#details_descMisc dt{float: left;width: 90px;clear: left;font-weight: bold;} div.product-brand-name {font-weight: bold;}</style>';
			$res .= $css_insert . '<div id="details_descMisc">' . strip_tags($temp->html() , '<dl><dd><dt><div><p><span>') . '</div><br />';
		}
		
		$temp  = $pq->find('div#productAttributes:first');
		foreach ($temp as $block){
			$t_res = $temp->html();
			if(strpos($t_res , 'attributeJson') < 1){
				$css_insert = '<style type="text/css">';
				$css_insert .= 'div#productAttributes_from_os {border: 1px solid #d6d1c9;}';
				$css_insert .= 'div#productAttributes_from_os .hd {background: none repeat scroll 0 0 #efece0;border-bottom: 1px solid #e1ded7;top: 0;height: 24px;}';
				$css_insert .= 'div#productAttributes_from_os .hd h4{float: left;margin: 2px 10px 0 0;}';
				$css_insert .= 'div#productAttributes_from_os .bd>ul{background-color: #f7f6f0;font-size: 12px;list-style-type: none;margin: 0;padding: 4px 10px;}';
				$css_insert .= 'div#productAttributes_from_os .bd>ul li{border-top: 1px solid #e7e5de;display: list-item;overflow: hidden;padding: 5px 0;}';
				$css_insert .= 'div#productAttributes_from_os .bd>ul li .attributeLabel{float: left;font-weight: bold;width: 33%;padding-right: 2%;}';
				$css_insert .= 'div#productAttributes_from_os .bd>ul li .attributeData{float: left;width: 65%;}';
				$css_insert .= 'div.hd{padding: 5px 11px;}';
				$css_insert .= '</style>';
				$t_res = preg_replace(array("'<span[^>]*?>.*?</span>'si"), Array(""), $t_res);
				$res .= '<div id="productAttributes_from_os">' . strip_tags($t_res , '<div><ul><li><h4>') . '</div><br />' . $css_insert;
			}
		}
		
		$temp  = $pq->find('section.content-section div.TS-content');
		$temp_exists = array();
		foreach ($temp as $block){
		    $block = str_ireplace(array("<caption>" , "</caption>" , ' class="hiddenTitle hide"') , array("<h2>", "</h2>" , '') , $temp->html());
		    if(!in_array($block , $temp_exists)){
		        $res .= '<div>' . $block . '</div><br /><br /><br />';
		        $temp_exists[] = $block;
		    }
		}
		

		$temp  = $pq->find('table.translation-table tbody');
		foreach ($temp as $block){
		    $block = str_ireplace(array("<caption>" , "</caption>") , array("<h2>", "</h2>") , $temp->html());
		    $res .= '<table>' . $block . '</table><br />';
		}
		
		return $res;
}


function mspro_overstock_price($html){
	$out = '';
 	$getAjax = true;
 	
 	$price = '';
 	
 	$instruction = 'span[itemprop=price]';
 	$parser = new nokogiri($html);
 	$res = $parser->get($instruction)->toArray();
 	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
 	if(isset($res[0]["#text"])){
 	    $price = preg_replace("/[^0-9.]/", "",  $res[0]["#text"]);
 	    return (float) $price;
 	}
 	
 	$instruction = 'span#nowprice';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	if(isset($res[0]['#text'][0]) && !is_array($res[0]['#text'][0]) ){
		//$price .= str_ireplace(array("") , array("") , $res[0]['#text'][0]);
		$price .= $res[0]['#text'][0];
	}
 	if(isset($res[0]['sup'][0]['#text']) && !is_array($res[0]['sup'][0]['#text']) ){
		$price .= $res[0]['sup'][0]['#text'];
	}
	if(strlen($price) > 2){
		$out = (float) $price;
		$getAjax = false;
	}

	// another variation
	///   http://dynamic.overstock.com/AjaxPrice?callback=jsonp&sku=MH0809R
	if($getAjax){
		$res = getUrl("http://dynamic.overstock.com/AjaxPrice?callback=jsonp&sku=".mspro_overstock_sku($html));
		if($res){
			$res = explode('"UnitPrice":"' , $res , 2);
			if(count($res) > 1){
				$res = explode('"' , $res[1] , 2);
				if(count($res) > 1){
					$out = (float) $res[0];
				}
			}
		}
	}
	
	
	$res = explode("productPrice: '" , $html);
	if(count($res) > 1){
	    $res = explode("'" , $res[1]);
	    if(count($res) > 1){
	        $res =  preg_replace("/[^0-9.-]/", "",  trim($res[0]) );
	        return (float) $res;
	    }
	
	}
	 
	$res = explode('sellingPrice: "' , $html);
	if(count($res) > 1){
	    $res = explode('"' , $res[1]);
	    if(count($res) > 1){
	        $res =  preg_replace("/[^0-9.-]/", "",  trim($res[0]) );
	        return (float) $res;
	    }
	     
	}
	
	
	return $out;
}


function mspro_overstock_sku($html){
	 $res = explode('productSKU : ' , $html);
	 if(count($res) > 1){
	     $res = explode('}' , $res[1] , 2);
	     if(count($res) > 1){
	         return trim($res[0]);
	     }
	 }
	 $res = explode(',"sku":"' , $html);
	 if(count($res) > 1){
	     $res = explode('"' , $res[1] , 2);
	     if(count($res) > 1){
	         return trim($res[0]);
	     }
	 }
	 $res = explode('{"shortSku":"' , $html);
	 if(count($res) > 1){
	     $res = explode('"' , $res[1] , 2);
	     if(count($res) > 1){
	         return trim($res[0]);
	     }
	 }
	 return '';
}

function mspro_overstock_model($html){
    $res = explode('ITEM# ' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    $res = explode('ecomm_prodid:' , $html);
    if(count($res) > 1){
        $res = explode(',' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    $res = explode('#:' , $html);
    if(count($res) > 1){
        $res = explode('<' , $res[1] , 2);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
	return mspro_overstock_sku($html);
}


function mspro_overstock_meta_description($html){
	   $res =  explode('Content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
	 $res = explode('" name="description">' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[0]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[count($res) - 1]);	
       		}
       		 
       }
       return '';
}

function mspro_overstock_meta_keywords($html){
       $res =  explode('Content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[2]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       $res = explode('" name="keyword">' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[0]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[count($res) - 1]);	
       		}
       		 
       }
       return '';
}


function mspro_overstock_main_image($html){
		$arr = overstock_get_images_arr($html);
    	if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}


function mspro_overstock_other_images($html){
		$arr = overstock_get_images_arr($html);
        if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}



function overstock_get_images_arr($html){
			$out = array();
			
			$instruction = 'div.thumb-frame ul li';
			$parser = new nokogiri($html);
			$data = $parser->get($instruction)->toArray();
			//echo '<pre>' . print_r($data , 1) . '</pre>';exit;
			unset($parser);
			if(isset($data) && is_array($data) && count($data) > 0){
			    foreach($data as $pos_image){
			        if(isset($pos_image['data-zoom-image']) && !is_array($pos_image['data-zoom-image']) && strlen($pos_image['data-zoom-image']) > 0){
			            $out[] = $pos_image['data-zoom-image'];
			        }elseif(isset($pos_image['data-max-img']) && !is_array($pos_image['data-max-img']) && strlen($pos_image['data-max-img']) > 0){
			            $out[] = $pos_image['data-max-img'];
			        }
			    }
			}
			if(count($out) > 0){
			    return $out;
			}
	
			// try to find out image from 
			// http://www.overstock.com/Home-Garden/Ethan-Home-Canterbury-White-Twin-size-Bed/6748692/product.html
			$res = explode("fullImagesJson=eval(" , $html);
			if(count($res) > 1){
				$res = explode(");" , $res[1]);
				if(count($res) > 1){
					$res = json_decode($res[0]);
					if($res && is_object($res)){
						if(isset($res->images) && is_array($res->images)){
							foreach($res->images as $img){
								$im = $img->childImages;
								if(is_array($im)){
									$im = $im[0];
									$im = $im->imagePath;
									$im = str_replace(array("_80" , "_320" , "_600" , "_1000"), array("" , "", "" , "") , $im);
									$out[] = "http://ak1.ostkcdn.com/images/products/".$im;
								}
								
							}
							
						}
						
					}
				}
			}
		
		if(count($out) > 0){
			return $out;
		}	
	
		 // default variation
        // http://www.overstock.com/Electronics/CyberpowerPC-Gamer-Xtreme-GUA250-w-AMD-FX-4100-3.6GHz-Gaming-Computer/6325729/product.html?recSet=a2ac3608-fbae-4de0-a4b0-a8a3a7ab3e9e#
		$res = explode("var imageHolder = " , $html);
		if(count($res) > 1){
			$res = explode(";" , $res[1]);
			if(count($res) > 1){
				$res = str_replace(array("[" , "]") , array("" , "") , $res[0]);
				$res = explode("," , $res);
				if(count($res > 0)){
					foreach($res as $img){
						if(strlen($img) > 5 && strpos($img , "imagenot") < 2){
							$out[] = str_replace(array("'") , array("") , $img);
						}
					}
				}
		
		
			}
		}
		//echo '<pre>'.print_r($out , 1).'</pre>';exit;
		usort($out, "mspro_overstock_sort_images");
		$out = array_unique($out);
		
		return $out;
}


function mspro_overstock_sort_images($a, $b) {
	
	$sort_array = array("L" => 1 , "M" => 2 , "P" => 3);
	
	
	$a = explode("/" , $a);
	$a = $a[count($a) - 1];
	$a = 3;
	if (array_key_exists(substr($a , 0 , 1) , $sort_array)) {
		$a = (int) $sort_array[$a];
	}
	
	$b = explode("/" , $b);
	$b = $b[count($b) - 1];
	$b = 3;
	if (array_key_exists(substr($b , 0 , 1) , $sort_array)) {
		$b = (int) $sort_array[$b];
	}
	
	
	
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}


function mspro_overstock_options($html){
	$out = array();
	$initial_price = mspro_overstock_price($html);
	
	$instruction = 'div#addCartWrap_productOptions select option';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 1){
				$OPTION = array();
				$OPTION['name'] = "Options";
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($res as $pos_option_value){
					$option_value_arr = overstock_get_option_value_name($pos_option_value , $initial_price);
					//echo '<pre>'.print_r($option_value_arr , 1).'</pre>';
					if(isset($option_value_arr['name']) && isset($option_value_arr['price'])){
					    $OPTION['values'][] = array('name' => $option_value_arr['name'] , 'price' => $option_value_arr['price']);
					}
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
	}
	
	$instruction = 'select.options-dropdown';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';
	unset($parser);
	if (is_array($res) && count($res) > 0){
	    foreach($res as $pos_option){
	        if(isset($pos_option['option']) && is_array($pos_option['option']) && count($pos_option['option']) > 1 && isset($pos_option['option'][0]['#text']) ){
	            $OPTION = array();
	            $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['option'][0]['#text']);
	            $OPTION['type'] = "select";
	            $OPTION['required'] = true;
	            $OPTION['values'] = array();
	            unset($pos_option['option'][0]);
	            foreach($pos_option['option'] as $option_value){
	                $option_value_arr = overstock_get_option_value_name($option_value , $initial_price);
	                //echo '<pre>'.print_r($option_value_arr , 1).'</pre>';
	                if(isset($option_value_arr['name']) && isset($option_value_arr['price'])){
	                    $OPTION['values'][] = array('name' => $option_value_arr['name'] , 'price' => $option_value_arr['price']);
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


function overstock_get_option_value_name($name,  $initial_price){
    $out = array();
    if(isset($name['#text']) && !is_array($name['#text']) && strlen(trim($name['#text'])) > 0){
        $name = $name['#text'];
        $res = explode('-' , $name);
        if(count($res) > 1){
            $price = $res[count($res) - 1];
            $name = substr($name , 0 , -(strlen($price) + 2) );
            $price = preg_replace("/[^0-9,.-]/", "",  $price);
            $price = $price - $initial_price;
            $out = array('name' => trim($name), 'price' => $price);
        }else{
            $out = array('name' => $name, 'price' => 0);
        }
    }
	return $out;
}


/*
function mspro_overstock_noMoreAvailable($html){
	return false;
}
*/