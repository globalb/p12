<?php


function mspro_sportsdirect_title($html){
		$instruction = 'div#productDetails div.title h1 span';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
       	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
		if (isset($data[1]['#text'][0]) && !is_array($data[1]['#text'][0])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[1]['#text'][0]));
        }
		if (isset($data[0]['span'][0]['#text'][0]) && !is_array($data[0]['span'][0]['#text'][0])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][0]['#text'][0]));
        }
        return '';
}

function mspro_sportsdirect_description($html){
		$out = '';
		
		// Item Description
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div.infoTabPage span[itemprop=description]:first');
		foreach ($temp as $block){
			$out .= $temp->html();
			break;
		}
		
		$out = str_ireplace(array("For our full range of " , " visit SportsDirect"), array("" , ""), $out);
		$out = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $out);
		
		//echo $out;exit;
        return $out;
}


function mspro_sportsdirect_price($html){
		$res =  explode("'productPrice' : '" , $html);
         if(count($res) > 1){
           		$res = explode("'" , $res[1]);
           		if(count($res) > 1){
           			return (float) trim($res[0]);	
           		}
         }
        return '';
}


function mspro_sportsdirect_sku($html){
		return mspro_sportsdirect_model($html); 
}



function mspro_sportsdirect_model($html){
	$res =  explode(",'productId' : '" , $html);
    if(count($res) > 1){
        $res = explode("'" , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);	
        }
    }
    $res =  explode('idParamName : "productId",    idParamValue   : "' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }
    $res =  explode('data-productid="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    }

	return '';
}


function mspro_sportsdirect_meta_description($html){
	    $res =  explode('<meta id="MetaDescription" name="DESCRIPTION" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return clear_sportsdirect_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));
			}	 
       }
       return '';
}

function mspro_sportsdirect_meta_keywords($html){
      $res =  explode('<meta id="MetaKeywords" name="KEYWORDS" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1 && strlen($res[0]) > 2){
       			return clear_sportsdirect_meta_tags(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]));	
       		}
       		 
       }
       return  mspro_sportsdirect_meta_description($html);
}

function clear_sportsdirect_meta_tags($str){
    return str_ireplace(array("sportsdirect.com" , "sportsdirect" , "Free Shipping") , array("" , "" , "") , $str);
}


function mspro_sportsdirect_main_image($html){
		$arr = sportsdirect_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_sportsdirect_other_images($html){
		$arr = sportsdirect_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function sportsdirect_get_images($html){
		$out = array();
		$res = explode('"Href":"' , $html);
		if(count($res) > 1){
			foreach($res as $block){
				$res_im = explode('"' , $block , 2);
				if(count($res_im) > 1){
					$out[] = $res_im[0];
				} 
			}
		}
		
		$instruction = 'ul#piThumbList li a';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
       // echo '<pre>'.print_r($data , 1).'</pre>';exit;
       	if(isset($data) && is_array($data) && count($data) > 0 ){
       		foreach($data as $pos_image){
       			if(isset($pos_image['srczoom']) && !is_array($pos_image['srczoom'])){
       				$out[] = $pos_image['srczoom'];
       			}elseif(isset($pos_image['href']) && !is_array($pos_image['href'])){
       				$out[] = $pos_image['href'];
       			}
       		}
       	}
       	
       	
       	// try to get color images
       	$t_res = mspro_sportsdirect_get_dataVariants_array($html);
       	if(isset($t_res) && is_array($t_res) && count($t_res) > 0){
       	    foreach($t_res as $pos_color_var){
       	        if(isset($pos_color_var['ProdImages'])){
       	            $varImages = $pos_color_var['ProdImages'];
       	            // get main image of this Varinat
       	            if(isset($varImages['ImgUrlXXLarge']) && !is_array($varImages['ImgUrlXXLarge']) && strlen(trim($varImages['ImgUrlXXLarge'])) > 0){
       	                $out[] = $varImages['ImgUrlXXLarge'];
       	            }elseif(isset($varImages['ImgUrlXLarge']) && !is_array($varImages['ImgUrlXLarge']) && strlen(trim($varImages['ImgUrlXLarge'])) > 0){
       	                $out[] = $varImages['ImgUrlXLarge'];
       	            }elseif(isset($varImages['ImgUrl']) && !is_array($varImages['ImgUrl']) && strlen(trim($varImages['ImgUrl'])) > 0){
       	                $out[] = $varImages['ImgUrl'];
       	            }
       	            // get alternative image of this Varinat
       	            if(isset($varImages['AlternateImages']) && is_array($varImages['AlternateImages']) && count($varImages['AlternateImages']) > 0){
       	                foreach($varImages['AlternateImages'] as $altVarImage){
       	                    if(isset($altVarImage['ImgUrlXXLarge']) && !is_array($altVarImage['ImgUrlXXLarge']) && strlen(trim($altVarImage['ImgUrlXXLarge'])) > 0){
       	                        $out[] = $altVarImage['ImgUrlXXLarge'];
       	                    }elseif(isset($altVarImage['ImgUrlXLarge']) && !is_array($altVarImage['ImgUrlXLarge']) && strlen(trim($altVarImage['ImgUrlXLarge'])) > 0){
       	                        $out[] = $altVarImage['ImgUrlXLarge'];
       	                    }elseif(isset($altVarImage['ImgUrlLarge']) && !is_array($altVarImage['ImgUrlLarge']) && strlen(trim($altVarImage['ImgUrlLarge'])) > 0){
       	                        $out[] = $altVarImage['ImgUrlLarge'];
       	                    }
       	                }
       	            }
       	        }
       	    }
       	    //echo '<pre>'.print_r($t_res , 1).'</pre>';exit;
       	}
       	 
       	$out = array_unique($out);
       	//echo '<pre>'.print_r($out , 1).'</pre>';exit;

        return $out;
}


function mspro_sportsdirect_options($html){
    $out = array();
    //echo $html;
	
    // COLOR
	$instruction = 'div#divColour';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
    if(isset($res[0]['select'][0]['option']) && is_array($res[0]['select'][0]['option']) && count($res[0]['select'][0]['option']) > 0 && isset($res[0]['div'][0]['span'][0]['#text']) && !is_array($res[0]['div'][0]['span'][0]['#text']) && strlen(trim($res[0]['div'][0]['span'][0]['#text'])) > 0){
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , trim($res[0]['div'][0]['span'][0]['#text']) );
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				if(isset($res[0]['select'][0]['option']['#text']) && !is_array($res[0]['select'][0]['option']['#text']) && strlen(trim($res[0]['select'][0]['option']['#text'])) > 0){
				    $OPTION['values'][] = array('name' => trim($res[0]['select'][0]['option']['#text']) , 'price' => 0);
				}elseif(!isset( $res[0]['select'][0]['option']['#text'] )){
				    foreach($res[0]['select'][0]['option'] as $option_value){
				        if(isset($option_value['#text']) && !is_array($option_value['#text'])){
				            $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
				        }
				    }
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
			}
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;

	// SIZE
	$instruction = 'div#VariantChooser';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if(isset($res[0]['div'][0]['div'])){
	    $res = $res[0]['div'][0]['div'];
	    if(isset($res[0]['label'][0]['#text']) && !is_array($res[0]['label'][0]['#text']) && strlen(trim($res[0]['label'][0]['#text'])) > 0 && isset($res[1]['select'][0]['option']) && is_array($res[1]['select'][0]['option']) && count($res[1]['select'][0]['option']) > 1){
	        $OPTION = array();
	        $OPTION['name'] = str_replace( array(":") , array("") , trim($res[0]['label'][0]['#text']) );
	        $OPTION['type'] = "select";
	        $OPTION['required'] = true;
	        $OPTION['values'] = array();
	        $pos_opt_vals = $res[1]['select'][0]['option'];
	        unset($pos_opt_vals[0]);
	        foreach($pos_opt_vals as $option_value){
	            if(!(isset($option_value['class']) && trim($option_value['class']) == "greyOut")){
    	            if(isset($option_value['#text']) && !is_array($option_value['#text'])){
    	                $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
    	            }elseif(isset($option_value['title']) && !is_array($option_value['title'])){
    	                $OPTION['values'][] = array('name' => trim($option_value['title']) , 'price' => 0);
    	            }elseif(isset($option_value['value']) && !is_array($option_value['value'])){
    	                $OPTION['values'][] = array('name' => trim($option_value['value']) , 'price' => 0);
    	            }
	            }
	        }
	        if(count($OPTION['values']) > 0){
	            $out[] = $OPTION;
	        }
	    }
	}
	
	// SIZE - another variation
	$instruction = 'ul.sizeButtons li';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if(isset($res) && is_array($res) && count($res) > 0){
	    $OPTION = array();
	    $OPTION['name'] = "Size";
	    $OPTION['type'] = "select";
	    $OPTION['required'] = true;
	    $OPTION['values'] = array();
	    foreach($res as $option_value){
	        if(!(isset($option_value['class']) && stripos($option_value['class'] , "reyOut") > 0)){
	            if(isset($option_value['data-text']) && !is_array($option_value['data-text'])){
	                $OPTION['values'][] = array('name' => trim($option_value['data-text']) , 'price' => 0);
	            }elseif(isset($option_value['a'][0]['#text']) && !is_array($option_value['a'][0]['#text'])){
	                $OPTION['values'][] = array('name' => trim($option_value['a'][0]['#text']) , 'price' => 0);
	            }
	        }
	    }
	    if(count($OPTION['values']) > 0){
	        $out[] = $OPTION;
	    }
	}
			
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	
	
	return $out;
}




/*
function mspro_sportsdirect_noMoreAvailable($html){
	return false;
}
*/


function  mspro_sportsdirect_get_dataVariants_array($html){
    $out = false;
    
    $res = explode('data-variants="' , $html);
    if(count($res) > 1){
        $res = explode('">' , $res[1] , 2);
        if(count($res) > 1){
            //echo $res[0];exit;
            $out = (array) json_decode( str_replace(array("&quot;") , array('"') , addslashes($res[0]) ) , 1 );
            //echo '<pre>'.print_r($out , 1).'</pre>';exit;
        }
    }
    
    return $out;
}

