<?php


function mspro_taobao_title($html){
    
    //echo $html;exit;
        $res =  explode('<span itemprop="name" class="t-title">' , $html);
        //echo count($res);
        if(count($res) > 1){
            //echo 'o';
            $res = explode('<' , $res[1] , 2);
            //echo count($res);
            if(count($res) > 1){
                //echo 'd';echo $res[0];
                $res = $res[0];
                //echo 'TITLE : ' . $res . '</br>';exit;
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);
            }
        }
		$res =  explode('<title>' , $html , 2);
       	if(count($res) > 1){
       		$res = explode('</title>' , $res[1] , 2);
       		if(count($res) > 1){
       			$res = taobao_enc($res[0] , $html);
       			$res_n = explode('-tmall.com' , $res , 2);
       			if(count($res_n) > 1){
       				return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res_n[0]);
       			}else{
       				return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);
       			}	
       		}
       		 
       	}
       	$res =  explode('<meta name="keywords" content="' , $html);
       	//echo count($res);
       	if(count($res) > 1){
       	    //echo 'o';
       	    $res = explode('"' , $res[1] , 2);
       	    //echo count($res);
       	    if(count($res) > 1){
       	        //echo 'd';//echo $res[0];
       	        $res = taobao_enc($res[0] , $html);
       	        //echo 'TITLE : ' . $res . '</br>';exit;
       	        return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);
       	    }
       	}
       	return '';
}

function mspro_taobao_description($html){
				$out = '';
		
		// DIV#J_AttrList  SCRAPING
		$res =  explode('<ul id="J_AttrUL">' , $html , 2);
       	if(count($res) > 1){
       		$res = explode('</ul>' , $res[1] , 2);
       		if(count($res) > 1){
       			$res = taobao_enc($res[0] , $html);
       			$out .= '<ul>'.str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res).'</ul>';
       		}
       		 
       	}
       	
       	
		$res =  explode('<ul class="attributes-list">' , $html , 2);
       	if(count($res) > 1){
       		$res = explode('</ul>' , $res[1] , 2);
       		if(count($res) > 1){
       			$res = taobao_enc($res[0] , $html);
       			$out .= str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);
       		}
       		 
       	}
		
       	
       	
		// Item Description
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div#tab_description');
		foreach ($temp as $block){
			$out .= taobao_enc($temp->html() , $html);
		}
		
		
		// Item Description
		/*$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div#description');
		foreach ($temp as $block){
			$out .= taobao_enc($temp->html() , $html);
		}*/
	
		
		
		$additional_result = explode("window, document,['" , $html , 2);
    	if(count($additional_result) > 1){
    		$additional_result = explode("']);" , $additional_result[1] , 2);
    	}else{
    		$additional_result = explode("s=document.createElement('script');s.async = true;s.src=" , $html , 2);
    		if(count($additional_result) > 1){
    			$additional_result = explode('";' , $additional_result[1] , 2);
    			if(count($additional_result) > 1){
    				$additional_result = str_ireplace(array('"') , array("") , $additional_result[0]);
    			}else{
    				$additional_result = false;
    			}
    		}else{
    			$additional_result = false;
    		}
    	}
    	
    	
    	// try to get another way
    	if(!$additional_result){
    	    $t_additional_result = explode('dynamicScript("https:" === location.protocol ? "' , $html , 2);
    	    if(count($t_additional_result) > 1){
    	        $t_additional_result = explode(':"' , $t_additional_result[1] , 2);
    	        if(count($t_additional_result) > 1){
    	            $t_additional_result = $t_additional_result[1];
    	            $t_additional_result = explode('"' , $t_additional_result , 2);
    	            if(count($t_additional_result) > 1){
    	                $additional_result = $t_additional_result[0];
    	                if(substr($additional_result ,  0 , 2) == "//"){
    	                    $additional_result = substr($additional_result , 2);
    	                }
    	            }
    	        }
    	    }
    	}
    	
    	
    	if($additional_result){
    		$additional_result = getUrl($additional_result);
    		
    		$additional_result = str_ireplace(array("var desc='") , array("") ,$additional_result);
    		$additional_result = substr( $additional_result , 0 , -2);
    		//echo 'add:'.$additional_result;exit;
    		$out .= taobao_enc($additional_result , $html);
    	}
    	//echo 'add:'.$additional_result;exit;
		
		
		// get #J_DivItemDesc div content 
    	$pq = phpQuery::newDocumentHTML($html);
    	$temp  = $pq->find('div#J_DivItemDesc');
		foreach ($temp as $block){
				$out .= taobao_enc(pq($block)->html() , $html).'<br />';
		}
		//echo $out;exit;
		
		
		
		// TRY TO GET WHOLE DESCRIPTION
		$WHOLE_DESC = false;
		$t_res = explode('"descUrl":"' , $html);
		if(count($t_res) > 1){
		    $t_res = explode('"' , $t_res[1] , 2);
		    if(count($t_res) > 1){
		      $target_url = $t_res[0];
		      if(substr($target_url, 0 , 2) == "//"){
		          $target_url = substr($target_url , 2);
		      }
		      $tt_res = getUrl($target_url);
		      if($tt_res){
		          if(strpos($tt_res , "desc='") > 1){
		              $desc_res = str_ireplace("var desc='" ,  "" , substr( taobao_enc($tt_res , "2") , 0 , -2) );
		              //echo $desc_res;exit;
		              $t_desc_res = explode('_end"></a></div>' , $desc_res);
		              if(count($t_desc_res) > 1){
		                  $desc_res = $t_desc_res[count($t_desc_res) - 1];
		              }else{
		                  $t_desc_res = explode('_end"></a> </div>' , $desc_res);
		                  if(count($t_desc_res) > 1){
		                      $t_desc_res = $t_desc_res[count($t_desc_res) - 1];
		                      //echo 'DESC : ' . $t_desc_res . '<br />';
		                      $desc_res = $t_desc_res;
		                  }else{
		                      $t_desc_res = explode('_end"></a><p>' , $desc_res);
		                      if(count($t_desc_res) > 1){
		                          $t_desc_res = $t_desc_res[count($t_desc_res) - 1];
		                          //echo 'DESC : ' . $t_desc_res . '<br />';
		                          $desc_res = '<p>' . $t_desc_res;
		                      }
		                  }
		              }
		              //echo $desc_res;exit;
		              $out .= $desc_res;
		              $WHOLE_DESC = true;
		          }
		      }
		    }
		}
		//echo $out;exit;
		if(!$WHOLE_DESC){
		    $t_res = explode('descUrl : "' , $html);
		    if(count($t_res) > 1){
		        $t_res = explode('"' , $t_res[1] , 2);
		        if(count($t_res) > 1){
		            $target_url = $t_res[0];
		            if(substr($target_url, 0 , 2) == "//"){
		                $target_url = substr($target_url , 2);
		            }
		            //echo $target_url;exit;
		            $tt_res = getUrl($target_url);
		            //echo $tt_res;exit;
		            if($tt_res){
		                if(strpos($tt_res , "desc='") > 1){
		                    $out .= str_ireplace("var desc='" ,  "" , substr(taobao_enc($tt_res , "2") , 0 , -2) );
		                    $WHOLE_DESC = true;
		                }
		            }
		        }
		    }
		}
		
		//echo $out;exit;
		if(!$WHOLE_DESC){
		    $t_res = explode(' descUrl: "' , $html);
		    if(count($t_res) > 1){
		        $t_res = explode('"' , $t_res[1] , 2);
		        if(count($t_res) > 1){
		            $target_url = $t_res[0];
		            if(substr($target_url, 0 , 2) == "//"){
		                $target_url = substr($target_url , 2);
		            }
		            //echo $target_url;exit;
		            $tt_res = getUrl($target_url);
		            //echo $tt_res;exit;
		            if($tt_res){
		                if(strpos($tt_res , "desc='") > 1){
		                    $out .= str_ireplace("var desc='" ,  "" , substr(taobao_enc($tt_res , "2") , 0 , -2) );
		                    $WHOLE_DESC = true;
		                }
		            }
		        }
		    }
		}
		
		
		// TRY TO GET WHOLE DESCRIPTION PT2
		if(!$WHOLE_DESC){
		    $t_res = explode('apiItemDesc":"' , $html);
		    if(count($t_res) > 1){
		        $t_res = explode('"' , $t_res[1] , 2);
		        if(count($t_res) > 1){
		            $tt_res = getUrl($t_res[0]);
		            if($tt_res){
		                if(strpos($tt_res , "desc='") > 1){
		                    $out .= str_ireplace("var desc='" ,  "" , substr( taobao_enc($tt_res , $html) , 0 , -2) );
		                    $WHOLE_DESC = true;
		                }
		            }
		        }
		    }
		}
		
		
		$out = '<div>' . str_ireplace(array('<img class="desc_anchor" id="desc-module-1" src="https://assets.alicdn.com/kissy/1.0.0/build/imglazyload/spaceball.gif">' , 'align="absmiddle"' , "</p>'") , array('' , '' , "</p>") , $out) . '</div>';
		//echo $out;exit;
		
        return $out;
}


function mspro_taobao_price($html){
    //echo stripos($html, 'defaultItemPrice":"');exit;
        $res =  explode('defaultItemPrice":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9.]/", "",  $res[0]);
                $price = (float) str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);
                //echo $price;exit;
                return $price;
            }
        
        }
        $res =  explode('<input type="hidden" name="current_price" value= "' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9,.]/", "",  $res[0]);
                return (float) str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);
            }
        
        }
        $res =  explode('<meta property="product:price:amount" content="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);
            }
        
        }
        return '';
}


function mspro_taobao_sku($html){
        $res =  explode('<div id="J_isku" data-spm="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9.]/", "",  $res[0]);
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "") , $res);
            }
        }
        $res =  explode('{"skuId":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9.]/", "",  $res[0]);
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "") , $res);
            }
        
        }
		return ''; 
}

function mspro_taobao_model($html){
        return mspro_taobao_sku($html);
}


function mspro_taobao_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			$res = taobao_enc($res[0] , $html);
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);	
       		}
       		 
       }
       return '';
}

function mspro_taobao_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1] , 2);
       		if(count($res) > 1){
       			$res = taobao_enc($res[0] , $html);
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);	
       		}
       		 
       }
       return '';
}


function mspro_taobao_main_image($html){
        $imgs_arr = mspro_taobao_get_images_arr($html);
        //echo ' IMAGES : <pre>'.print_r($imgs_arr , 1).'</pre>';
        if(is_array($imgs_arr) && count($imgs_arr) > 0){
            return $imgs_arr[0];
        }
        return '';
}


function mspro_taobao_other_images($html){
        $imgs_arr = mspro_taobao_get_images_arr($html);
        if(is_array($imgs_arr) && count($imgs_arr) > 1){
            unset($imgs_arr[0]);
            return $imgs_arr;
        }
        return array();
}




function mspro_taobao_get_images_arr($html){
        //echo $html;exit;
        $out = array();
        
        $instruction = 'ul#J_ThumbNav li a img';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        if(is_array($data) && count($data) > 0){
            foreach($data as $im){
                if(isset($im['src'])){
                    $out[] = taobao_prepare_img($im['src']);
                }elseif(isset($im['data-src'])){
                    $out[] = taobao_prepare_img($im['data-src']);
                }
            }
        }
    
        if(count($out) < 1){
            $instruction = 'ul#J_UlThumb li a img';
            $parser = new nokogiri($html);
            $data = $parser->get($instruction)->toArray();
            //echo '<pre>'.print_r($data , 1).'</pre>';exit;
            if(is_array($data) && count($data) > 0){
                foreach($data as $im){
                    if(isset($im['src'])){
                        $out[] = taobao_prepare_img($im['src']);
                    }elseif(isset($im['data-src'])){
                        $out[] = taobao_prepare_img($im['data-src']);
                    }
                }
            }
        }
    
        //echo '<pre>'.print_r($out , 1).'</pre>';
        if(count($out) < 1){
            $res =  explode('class="tb-gallery"' , $html , 2);
            if(count($res) > 1){
                $res = explode('</div>' , $res[1] , 2);
                if(count($res) > 1){
                    $res = explode('background-image:url(' , $res[0]);
                    unset($res[0]);
                    if(count($res) > 0){
                        foreach($res as $bl){
                            $r = explode(')"' , $bl , 2);
                            if(count($r) > 1){
                                if(strpos($r[0] , 'ttp://') > 0){
                                    $out[] = taobao_prepare_img($r[0]);
                                }
                            }
                        }
                    }
                }
                 
            }
        }
         
         
        $out = clear_images_array($out);
        //echo ' IMAGES : <pre>'.print_r($out , 1).'</pre>';exit;
        return $out;
}


function taobao_prepare_img($img){
		if(substr($img , 0 , 2) == "//"){
            $img = substr($img , 2);
        }
		$img = str_ireplace(array("_60x60.jpg" , "_360x360.jpg" , "_40x40.jpg", "_50x50.jpg", "_70x70.jpg" , " ") , array("" , "" , "" , "" , "_600x600.jpg" , "%20") , $img);
		$img = str_ireplace(array("_60x60q") , array("_460x460q") , $img);
		//return rawurlencode($img);
		return $img;
}

function taobao_enc($text, $html = false){
    //return $text;
	if(isset($html) && (strpos($html , '<meta charset="utf-8"') < 1 || $html == "2") ){
	    return @iconv('GBK', 'UTF-8', $text);
	}
	return $text;
}

function mspro_taobao_options($html){
    //echo $html;exit;
    $out = array();

    $instruction = 'ul.J_TSaleProp';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['data-property']) && !is_array($pos_option['data-property']) && strlen($pos_option['data-property']) > 0 && isset($pos_option['li']) && is_array($pos_option['li']) && count($pos_option['li']) > 0){
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , taobao_enc($pos_option['data-property']));
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['li'] as $option_value){
                    if(isset($option_value['data-value']) && stripos($option_value['data-value'] , ':-1') > 0){
                        continue;
                    }
                    $opt_array = array();
                    if(isset($option_value['title']) && !is_array($option_value['title'])){
                        $opt_array = array('name' => taobao_enc($option_value['title']) , 'price' => 0);
                    }elseif(isset($option_value['a']['span']['#text']) && !is_array($option_value['a']['span']['#text'])){
                        $opt_array = array('name' => taobao_enc($option_value['a']['span']['#text']) , 'price' => 0);
                    }elseif(isset($option_value['a'][0]['span'][0]['#text']) && !is_array($option_value['a'][0]['span'][0]['#text'])){
                        $opt_array = array('name' => taobao_enc($option_value['a'][0]['span'][0]['#text']) , 'price' => 0);
                    }
                    // get image if exists
                    if(isset($option_value['a']['style']) && stripos($option_value['a']['style'] , 'ckground:url(//') > 0){
                        $opt_image = mspro_taobao_get_option_image($option_value['a']['style']);
                        if($opt_image !== false){
                            $opt_array['image'] = $opt_image;
                            $OPTION['original_type'] = 'image';
                        }
                    }elseif(isset($option_value['a'][0]['style']) && stripos($option_value['a'][0]['style'] , 'ckground:url(//') > 0){
                        $opt_image = mspro_taobao_get_option_image($option_value['a'][0]['style']);
                        if($opt_image !== false){
                            $opt_array['image'] = $opt_image;
                            $OPTION['original_type'] = 'image';
                        }
                    }
                    if(isset($opt_array['name'])){
                        $OPTION['values'][] = $opt_array;
                    }
                }
                if(count($OPTION['values']) > 0){
                    $out[] = $OPTION;
                }
            }
        }
    }
    
    $instruction = 'div#J_SKU dl';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 0){
        foreach($res as $pos_option){
            if(isset($pos_option['dt'][0]['#text']) && !is_array($pos_option['dt'][0]['#text']) && strlen($pos_option['dt'][0]['#text']) > 0 && isset($pos_option['dd'][0]['ul'][0]['li']) && is_array($pos_option['dd'][0]['ul'][0]['li']) && count($pos_option['dd'][0]['ul'][0]['li']) > 0){
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , taobao_enc($pos_option['dt'][0]['#text']));
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['dd'][0]['ul'][0]['li'] as $option_value){
                    if(isset($option_value['a'][0]['title']) && !is_array($option_value['a'][0]['title'])){
                        $OPTION['values'][] = array('name' => taobao_enc($option_value['a'][0]['title']) , 'price' => 0);
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

function mspro_taobao_get_option_image($str){
    $res = explode('kground:url(//' , $str);
    if(count($res) > 1){
        $res = explode(')' , $res[1] , 2);
        if(count($res) > 1){
            $image = str_ireplace(array("_40x40q90.jpg") , array("") , $res[0]);
            return 'http://' . $image;
        }
    }
    return false;
}

/*
function mspro_taobao_noMoreAvailable($html){
	return false;
}
*/
