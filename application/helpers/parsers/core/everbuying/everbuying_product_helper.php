<?php


function mspro_everbuying_getUrl($url){
    //return getUrl($url , false, true, false);
    $initialHTML = getUrl($url);
    $title = mspro_everbuying_title($initialHTML);
    if($initialHTML && (!$title || $title == false || trim($title) == '') ){
        return getUrl($url , false, true, false);
    }
    return $initialHTML;
}

/*function mspro_everbuying_getUrl($url , $postData = false, $invisible = false){
    // return file_get_contents($url);
        $user_agent = getUserAgents();

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent[array_rand($user_agent)]);
		unset($user_agent);
		if(strpos($url , "tmall.") > 1 || strpos($url , "taobao.") > 1 || strpos($url , "taobaocdn.") > 1){
		     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		}
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if($postData){
			curl_setopt($ch , CURLOPT_POST, true);
			curl_setopt($ch , CURLOPT_POSTFIELDS, $postData);
		}
		if($invisible === true){
		    //echo 'invisible';
		    curl_setopt($ch, CURLOPT_PROXY, getProxY() );
		}
		if(strpos($url , 'ttps:') > 0){
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		// for Request Payload (CURL POST BUT NOT WITH ARRAY, BY JSON)
		if($postData && !is_array($postData) && strlen($postData) > 2){
		  curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ));
		}
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_ENCODING , ""); 
		//$html = _curl_redirect_exec($ch , $postData);
		$html = curl_exec($ch);
		curl_close($ch);
		//echo $html;exit;
		return $html;
}*/


function mspro_everbuying_title($html){
        //echo $html;
		$instruction = 'h1[itemprop=name]';
		$parser = new nokogiri($html);
		$data = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($data , 1).'</pre>';exit;
		if(isset($data[0]['#text']) && is_array($data[0]['#text']) && isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) ){
		    return trim($data[0]['#text'][0]);
		}
		if(isset($data[0]['#text']) && !is_array($data[0]['#text']) ){
			return trim($data[0]['#text']);
		}
		return '';
}

function mspro_everbuying_description($html){
		$res = '';
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div.prodet div.js_showtable:first');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		return $res;
}


function mspro_everbuying_price($html){
		$res = explode('fa_desc_sup' , $html);
        if(count($res) > 2){
        	$res = explode('<span>' , $res[count($res) - 2] , 2);
        	if(count($res) > 1){
        		$res = explode(' ' , $res[1] , 2);
        		if(count($res) > 1){
        			return (float) trim($res[0]);
        		}
        	} 
        }
        $instruction = 'span#unit_price2';
		$parser = new nokogiri($html);
		$data = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($data , 1).'</pre>';exit;
		if(isset($data[0]['#text']) && !is_array($data[0]['#text'])){
			return (float) trim($data[0]['#text']);
		}
		return '';
}


function mspro_everbuying_sku($html){
		return mspro_everbuying_model($html);
}

function mspro_everbuying_model($html){
        $instruction = 'input#goods_id';
		$parser = new nokogiri($html);
		$data = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($data , 1).'</pre>';exit;
		if(isset($data[0]['value']) && !is_array($data[0]['value'])){
			return trim($data[0]['value']);
		}
        
        return '';
}


function mspro_everbuying_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('" />' , $res[1]);
       		if(count($res) > 1){
       			$out = preg_replace("/[^a-zA-Z0-9_\-\s]/", "" ,  $res[0]); 
       			return utf8_encode( str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $out) );
			}	 
       }
       return '';
}

function mspro_everbuying_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('" />' , $res[1]);
       		if(count($res) > 1){
       			$out = preg_replace("/[^a-zA-Z0-9_\-\s]/", "" ,  $res[0]); 
       			return utf8_encode( str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $out) );
			}	 
       }
       return '';
}


function mspro_everbuying_weight($html){
        $out = array();
        
		$res = explode('eight</td>' , $html);
        if(count($res) > 1){
        	$res = explode('</td>' , $res[1] , 2);
        	if(count($res) > 1){
        		$weight = strip_tags($res[0]);
        		$out['weight_class_id'] = 2;
        		if(strpos($weight , "kilogram") > 1 || strpos($weight , "KG") > 1 || strpos($weight , "kg") > 1){$out['weight_class_id'] = 1;}
        		$out['weight'] = (float) preg_replace("/[^0-9,.]/", "",  $weight);;
        	} 
        }
        
        if(!isset($out['weight'])){
            $res = explode('Product weight:' , $html);
            if(count($res) > 1){
                $res = explode('</li>' , $res[1] , 2);
                if(count($res) > 1){
                    $weight = strip_tags($res[0]);
                    $out['weight_class_id'] = 2;
                    if(strpos($weight , "kilogram") > 1 || strpos($weight , "KG") > 1 || strpos($weight , "kg") > 1){$out['weight_class_id'] = 1;}
                    $out['weight'] = (float) preg_replace("/[^0-9,.]/", "",  $weight);;
                }
            }
        }
        
       /* if(!isset($out['weight'])){
            $res = explode('Product weight:' , $html);
            if(count($res) > 1){
                $res = explode('</li>' , $res[1] , 2);
                if(count($res) > 1){
                    $weight = strip_tags($res[0]);
                    $out['weight_class_id'] = 2;
                    if(strpos($weight , "kilogram") > 1 || strpos($weight , "KG") > 1 || strpos($weight , "kg") > 1){$out['weight_class_id'] = 1;}
                    $out['weight'] = (float) preg_replace("/[^0-9,.]/", "",  $weight);;
                }
            }
        }*/
        
        return $out;
}


function mspro_everbuying_main_image($html){
	$imgs_arr = mspro_everbuying_get_imgs_array($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 0){
		return $imgs_arr[0];
	}
	return '';	
}



function mspro_everbuying_other_images($html){
	$imgs_arr = mspro_everbuying_get_imgs_array($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 1){
		unset($imgs_arr[0]);
		return $imgs_arr;
	}
	return array();
}



function mspro_everbuying_get_imgs_array($html){
	$out = array();
	
	$instruction = 'div#js_jqzoom ul li img';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	if(isset($data) && is_array($data) && count($data) > 0) {
		foreach($data as $pos_img){
			if(isset($pos_img['src'])){
				$out[] = $pos_img['src'];
			}
		}
	}
	
	$out = clear_images_array($out);
	return $out;
}


function mspro_everbuying_options($html){
	$out = array();
	
	$instruction = 'div#catePageList p';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if(isset($res) && is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['label'][0]['#text']) &&
			 isset($pos_option['select'][0]['option']) &&
			 is_array($pos_option['select'][0]['option']) &&
			 count($pos_option['select'][0]['option']) > 1 &&
			 !(isset($pos_option['style']) && trim($pos_option['style']) == "display:none") )
		{
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['label'][0]['#text']) );
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				unset($pos_option['select'][0]['option'][0]);
				foreach($pos_option['select'][0]['option'] as $option_value){
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
	
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}




function mspro_everbuying_noMoreAvailable($html){
    if(stripos($html , 'out_of_stock_new.gif') > 0){
        echo 'wriehferufh';exit;
        return true;
    }
	return false;
}

