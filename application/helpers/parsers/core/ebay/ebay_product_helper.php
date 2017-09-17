<?php


function mspro_ebay_title($html){
    
    
    $instruction = 'h1[itemprop=name]';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    unset($parser);
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ) {
        $res = trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , $data[0]['#text']));
        //echo utf8_encode($res);exit;
        return utf8_decode($res);
    }
    if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) ) {
        $res = trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , $data[0]['#text'][0]));
        if (isset($data[0]['#text'][1]) && !is_array($data[0]['#text'][1]) ) {
            $res .= trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , $data[0]['#text'][1]));
        }
        //echo utf8_decode($res);exit;
        return utf8_decode($res);
    }
    
		$instruction = 'span#vi-lkhdr-itmTitl';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    ///echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
	    if (isset($data[0]['#text'])) {
	    	if(is_array($data[0]['#text'])){
	    		$text = '';
	    		foreach($data[0]['#text'] as $block){
	    			if(!is_array($block)){
	    				$text .= $block;
	    			}
	    		}
	    		return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) , $text));
	    	}else{
	    		return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
	    	}
        }
			
		$instruction = 'h1#itemTitle';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
		$instruction = 'h1.vi-is1-titleH1';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
		$instruction = 'h1.vi-it-itHd';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        
		$instruction = 'h3.tpc-titr';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        $res =  explode('name="twitter:title" content="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1]);
            if(count($res) > 1){
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);
            }
        
        }

        return '';
}

function mspro_ebay_description($html){
    // hack
    $exists_arr = array();
    $html = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $html);
    $html = preg_replace(array("'<object[^>]*?>.*?</object>'si"), Array(""), $html);
    
		$res = '';

		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div.itemAttr');
		foreach ($temp as $block){
			if(strpos($temp->text() , "Item specifics") > 0  || strpos($temp->text() , "Artikelmerkmale") > 0 ){
					$res .= '<div>' . $temp->html() . '</div>';
			}
		}
		
		$temp  = $pq->find('div.prodDetailDesc');
		foreach ($temp as $block){
			if(strpos($temp->text() , "Detailed item info") > 0 ){
					$res .= '<div>' . $temp->html() . '</div>';
			}
		}
		
		$temp  = $pq->find('div.vi-ia-attrTitle');
		foreach ($temp as $block){
				$res .= '<div>' . $temp->html() . '</div>';
		}

		$temp  = $pq->find('div.vi-iw');
		foreach ($temp as $block){
				$res .= '<div>' . $temp->html() . '</div>';
		}
		
		
		$temp_p  = $pq->find('div#ProductDesc');
		if (is_object($temp_p)){
				$res .= '<div>' .$temp_p->html().'</div>';
		}
		
		$temp_p  = $pq->find('div#itemdetails');
		if (is_object($temp_p)){
				$res .= '<div>' . $temp_p->html() . '</div>';
		}
		//echo $res;exit;
		
		// example: http://www.ebay.co.uk/itm/GENUINE-SAMSUNG-I9300-GALAXY-S3-REPLACEMENT-LCD-AMOLED-HD-DISPLAY-FRAME-WHITE-/161015082000?pt=UK_Replacement_Parts_Tools&hash=item257d3f2c10        
		$temp_p  = $pq->find('div#desc_div');
		if (is_object($temp_p)){
				//echo '<pre>'.print_r($temp_p , 1).'</pre>';exit;
		        $bl = $temp_p->html();
				if(!in_array($bl , $exists_arr) && strlen(trim($bl)) > 0){
    				$res .= '<div>' . $bl . '</div>';
    				$exists_arr[] = $bl;
				}
		}
		
		
		
		// try to get CUSTOM DESCRIPTION BY AJAX:
		$ajax_res = false;
		$tt_res = explode('<iframe id="desc_ifr"' , $html);
        if(count($tt_res) > 1){
            $tt_res = explode('src="' , $tt_res[1] , 2);
            if(count($tt_res) > 1){
                $tt_res = explode('"' , $tt_res[1] , 2);
                if(count($tt_res) > 1){
                    $ajax_res = getUrl($tt_res[0]);
                } 
            }
        }else{
            // try to get the address manually
            $itemID = mspro_ebay_NumberID($html);
            if($itemID && strlen($itemID) > 0){
                //echo 'http://vi.vipr.ebaydesc.com/ws/eBayISAPI.dll?ViewItemDescV4&item=' . $itemID;
                $ajax_res = getUrl('http://vi.vipr.ebaydesc.com/ws/eBayISAPI.dll?ViewItemDescV4&item=' . $itemID);
            }
        }
        //echo $ajax_res;
        $ajax_res = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $ajax_res);
        $ajax_res = preg_replace(array("'<CENTER[^>]*?>.*?</CENTER>'si"), Array(""), $ajax_res);
        $ajax_res = preg_replace(array("'<noscript[^>]*?>.*?</noscript>'si"), Array(""), $ajax_res);
        $t_ajax_res = explode('<span id="closeHtml"></span>' , $ajax_res);
        if(count($t_ajax_res) > 1){
            $ajax_res = $t_ajax_res[0];
        }
        if($ajax_res){
            $res .= '<div>' . $ajax_res . '</div>';
        }
        /*if($ajax_res){
            $pq = phpQuery::newDocumentHTML($ajax_res);
            $temp  = $pq->find('div#ds_div');
            foreach ($temp as $block){
                $bl = $temp_p->html();
                if(!in_array($bl , $exists_arr)){
                    $res .= $bl . '<br />';
                    $exists_arr[] = $bl;
                }
            }
        }*/
        //echo $res;exit;
		$res = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $res);
		$res = preg_replace(array("'<iframe[^>]*?>.*?</iframe>'si"), Array(""), $res);
		$res = preg_replace(array("'<object[^>]*?>.*?</object>'si"), Array(""), $res);
		$res = str_ireplace(array('<div></div>' , ' width: 100%;' , '<p class="MsoNormal">&nbsp;</p>' , '<a href="http://pages.ebay.co.uk/help/sell/contextual/condition_1.html" target="_blank" class="infoLink u-nowrap">' , 'See all condition definitions<b class="g-hdn">- opens in a new window or tab</b></a>' , '... <a href="javascript:;">Read more<b class="g-hdn">about the condition</b></a>') , array("") , $res);
		
		$res = preg_replace('#<!--.*-->#sUi', '', $res);
		//echo $res;exit;
		
		//return '';
		return $res;
}


function mspro_ebay_price($html){
		$instruction = 'span[itemprop=price]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
	    if (isset($data[0]['#text'])) {
	    	$price = trim($data[0]['#text']);
	    	$price = str_replace(array("Rs.") , array("") , $price);
	    	$price = preg_replace("/[^0-9,.]/", "",  $price);
	    	if(strpos($price , ",") > 0 && strpos($price , ".") > 0){
	    		$price = str_replace(array(",") , array("") , $price);
	    	}
	    	return (float) $price;
        }
        //exit;
        $res = explode('"discountedPrice":"US $' , $html , 2);
        if(count($res) > 1){
        	$res = explode('"' , $res[1] , 2);
        	if(count($res) > 1){
        		return (float) trim($res[0]);
        	}
        	
        }
        $res =  explode('itemprop="price"' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1]);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9,.]/", "",  $res[0]);
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res);
            }
        
        }
        return '';
}


function mspro_ebay_sku($html){
		return mspro_ebay_model($html); 
}

function mspro_ebay_model($html){
	$instruction = 'h2[itemprop=model]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ) {
	    	return trim($data[0]['#text']);
        }
        
        return mspro_ebay_NumberID($html);
}


function mspro_ebay_NumberID($html){
    $res =  explode(' data-itemid="' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim($res[0]);
        }
    
    }
    return '';
}


function mspro_ebay_meta_description($html){
	   $res =  explode('<meta  name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       
       $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       
	   $res =  explode('" name="description" ' , $html);
       if(count($res) > 1){
       		$res = explode('<meta  content="' , $res[0]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[count($res) - 1]);	
       		}
       		 
       }
       
       return '';
}

function mspro_ebay_meta_keywords($html){
       $res =  explode('<meta  name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
	
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       
	   $res =  explode('" name="keywords" ' , $html);
       if(count($res) > 1){
       		$res = explode('<meta  content="' , $res[0]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[count($res) - 1]);	
       		}
       		 
       }
       
       return '';
}


function mspro_ebay_main_image($html){
		$main_image = false;
	
		$instruction = 'center img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['src'])) {
	    	$main_image = trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$res[0]['src']));
        }
        // another variation 
		$instruction = 'img#icImg';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['src'])) {
	    	$main_image = trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$res[0]['src']));
        }
		
		// try to find out large image
		$temp = explode('enlargeZoomUrl":"' , $html);
		if(count($temp) > 1){
			$temp1 = explode('"}' , $temp[1]);
			if(count($temp1) > 1){ 
				$main_image = $temp1[0];
			}
		}
		
		return $main_image;	
}


function mspro_ebay_other_images($html){
		$out = array();
			// try to find out image from 
			// http://www.ebay.com/ctg/Vizio-E321VL-32-720p-HD-LCD-Television-/102546991?_pcatid=40&LH_ItemCondition=1000
			$instruction = 'div.pds-i table img';
		    $parser = new nokogiri($html);
		    $res = $parser->get($instruction)->toArray();
		    unset($parser);
		    if (isset($res) && is_array($res)) {
		    	foreach($res as $oth_imgs){
		    		$out[] = ebay_try_get_bigger($oth_imgs['src']);
		    	}
	        }
	       
			
			// try to find out other images
			$temp = explode('mnImgData":[' , $html);
			if(count($temp) > 1){
				$temp = explode('],' , $temp[1]);
					if(count($temp) > 1){
						//print_r(json_decode('['.$temp[0].']'));
						$ims = json_decode('['.$temp[0].']');
						foreach($ims as $valObj){
							if(!strpos($valObj->src , "imgNoImg")){
								$out[] = $valObj->src;
							}
						}
					}
			}
			
			
 			// another variation 
	        $res = explode('"maxImageUrl":"' , $html);
	        if(count($res) > 1){
	        	unset($res[0]);
	        	foreach($res as $pos_img){
	        		$res_t = explode('"' , $pos_img , 2);
	        		if(count($res_t) > 1){
	        			$out[] = ebay_try_get_bigger( str_ireplace(array("u002F") , array("") , $res_t[0] ) );
	        		}
	        	}
	        }
	        
			//print_r($out);
			
			// another variation 
			$instruction = 'td.tdThumb div img';
		    $parser = new nokogiri($html);
		    $res = $parser->get($instruction)->toArray();
		    unset($parser);
		    if (isset($res) && is_array($res)) {
		    	foreach($res as $oth_imgs){
		    		$out[] = ebay_try_get_bigger($oth_imgs['src']);
		    	}
	        }
	        
	        
	        // from seller's iframe dasc
			// example: http://www.ebay.co.uk/itm/GENUINE-SAMSUNG-I9300-GALAXY-S3-REPLACEMENT-LCD-AMOLED-HD-DISPLAY-FRAME-WHITE-/161015082000?pt=UK_Replacement_Parts_Tools&hash=item257d3f2c10
			$pq = phpQuery::newDocumentHTML($html);        
			$temp_p  = $pq->find('div#desc_div');
			if (is_object($temp_p)){
					$res = $temp_p->html();
					// get images array
					preg_match_all('/(<img[^<]+>)/Usi', $res, $images);
				    foreach ($images[0] as $index => $value) {
				    	$s = strpos($value, 'src="') + 5;
				        $e = strpos($value, '"', $s + 1);
				        $out[] =   substr($value, $s, $e - $s);
				    }
			}
			
			
			foreach ($out as $index => $value) {
			    if(strpos($value , "marketplaceadvisor.channeladvisor.com") > 1 || strpos($value , "images.channeladvisor.com") > 1){
			        unset($out[$index]);
			    }else{
			         $out[$index] = str_replace('\\' , "/" , $value);
			    }
			    if( strpos($value , "ebaystatic.com") > 0){unset($out[$index]);}
			    if( strpos($value , "url+") > 0){unset($out[$index]);}
			    if( strpos($value , "banners") > 0){unset($out[$index]);}
			    if( strpos($value , "flash_required.gif") > 0){unset($out[$index]);}
			    if( strpos($value , "/allssgood") > 0){unset($out[$index]);}
			    if( strpos($value , "rc =") > 0){unset($out[$index]);}
			    if( strpos($value , "tagline.gif") > 0){unset($out[$index]);}
			    if( strpos($value , "elcellonline.com/templateimages/") > 0){unset($out[$index]);}
			    if( $value == "id=" ){unset($out[$index]);}
			}
			//echo '<pre>'.print_r($out , 1).'</pre>';
			$out = clear_images_array($out);
			//echo '<pre>'.print_r($out , 1).'</pre>';exit;
			return $out;
}

function ebay_try_get_bigger($src){
 	if(strpos($src , "60_")){
 		$temp = explode("60_" , $src);
 		// get extension
 		$ext = explode("." , $temp[1] , 2);
 		return $temp[0].'60_12.'.$ext[1];
 	}
 	
 	$src = str_ireplace(array('\\') , array("/") , $src);
 	
 	if(strpos($src , '$_14')){
 		return str_ireplace(array('$_14') , array('$_57') , $src);
 	}else{
 		return $src;
 	}
 }
 
 
 
 function mspro_ebay_options($html){
     $out = array();
     //echo $html;
     
     $instruction = 'select.msku-sel';
     $parser = new nokogiri($html);
     $res = $parser->get($instruction)->toArray();
     //echo '<pre>'.print_r($res , 1).'</pre>';exit;
     unset($parser);
     if(is_array($res) && count($res) > 0){
         foreach($res as $pos_option){
             if(isset($pos_option['name']) && !is_array($pos_option['name']) && strlen(trim($pos_option['name'])) > 0 && isset($pos_option['option']) && is_array($pos_option['option']) && count($pos_option['option']) > 0){
                 $OPTION = array();
                 $OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['name']) );
                 $OPTION['type'] = "select";
                 $OPTION['required'] = true;
                 $OPTION['values'] = array();
                 foreach($pos_option['option'] as $option_value){
                     if(isset($option_value['#text']) && !is_array($option_value['#text']) && !(isset($option_value['value']) && $option_value['value'] == -1) ){
                         $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
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
 




function mspro_ebay_noMoreAvailable($html){
	return false;
}
