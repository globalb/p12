<?php


function mspro_miniinthebox_getUrl($url){
    //return getUrl($url , false, true, false);
    $initialHTML = getUrl($url);
    $title = mspro_miniinthebox_title($initialHTML);
    if($initialHTML && (!$title || $title == false || trim($title) == '') ){
        return getUrl($url , false, true, false);
    }
    return $initialHTML;
}


function mspro_miniinthebox_title($html){
		$instruction = 'h1';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
	    if (isset($data[1]['#text']) && is_array($data[1]['#text']) && isset($data[1]['#text'][0]) && !is_array($data[1]['#text'][0]) && strlen($data[1]['#text'][0]) > 1) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[1]['#text'][0]));
        }
 		if (isset($data[0]['#text']) && is_array($data[0]['#text']) && isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen($data[0]['#text'][0]) > 1) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        return '';
}

function mspro_miniinthebox_description($html){
		$res = '';
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('section#Item_Description_Spc');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		$temp  = $pq->find('div#prod-description-specifications');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		$temp  = $pq->find('div.prod-description-feature-list');
		foreach ($temp as $block){
		    $res .= $temp->html();
		}

    	$temp  = $pq->find('div.prod_description_sizechart');
		foreach ($temp as $block){
			$res .= '<br/><br/>'.$temp->html();
		}
		
		
		
		//echo $res;exit;
		return $res;
}


function mspro_miniinthebox_price($html){
		$out = '';
		$instruction = 'li.currentPrice strong[itemprop=price]';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    unset($parser);
	    if (isset($res[0]['#text'])) {
	    	return trim($res[0]['#text']);
        }
        
        
		$res = explode("pvalue : '" , $html);
	    if(count($res) > 1){
	    	$res = explode("'" , $res[1] , 2);
	    	if(count($res) > 1){
	    		return (float) $res[0];
	    	}
	    }
        
		$instruction = 'strong[itemprop=price]';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    $price = '';
	    unset($parser);
	    if (isset($res[0])){
	    	if (isset($res[0]['#text']) && !is_array($res[0]['#text']) ) {
		    	$price .= trim(str_ireplace(array("$") , array("") , trim($res[0]['#text']) ));
	        }
		    if (isset($res[0]['#text'][0]) && !is_array($res[0]['#text'][0]) ) {
		    	$price .= trim(str_ireplace(array("$") , array("") , trim($res[0]['#text'][0]) ));
	        }
	    	if (isset($res[0]['sup'][0]['#text']) && !is_array($res[0]['sup'][0]['#text']) ) {
		    	$price .= trim(str_ireplace(array("$") , array("") , trim($res[0]['sup'][0]['#text']) ));
	        }
	        return (float) $price;
	    }
		return ''; 
}


function mspro_miniinthebox_sku($html){
		$instruction = 'span.prodItemId';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    unset($parser);
	    if (isset($res[0]['#text'])) {
	    	return trim($res[0]['#text']);
        }
        
		$res = explode('<span class="item-id">#' , $html);
	    if(count($res) > 1){
	    	$res = explode("</span>" , $res[1] , 2);
	    	if(count($res) > 1){
	    		return (float) $res[0];
	    	}
	    }
		return ''; 
}

function mspro_miniinthebox_model($html){
		$res =  explode('Item Code:' , $html);
        if(count($res) > 1){
       		$res = explode('</div>' , $res[1] , 2);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , trim($res[0]) );	
       		}
       		 
        }
        return mspro_miniinthebox_sku($html); 
}


function mspro_miniinthebox_meta_description($html){
	    $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_miniinthebox_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_miniinthebox_main_image($html){
		$arr = miniinthebox_get_images_arr($html);
    	if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}


function mspro_miniinthebox_other_images($html){
		$arr = miniinthebox_get_images_arr($html);
        if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}



function miniinthebox_get_images_arr($html){
		$out = array();
		
		$instruction = 'img.normal';
		$parser = new nokogiri($html);
		$res = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if(isset($res[0]['src']) && !is_array($res[0]['src']) ){
			$out[] = $res[0]['src'];
		}
		
		$instruction = 'div.productImg ul.widget a';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (is_array($res) && count($res) > 1) {
	    	foreach($res as $k => $v){
	    		if(isset($res[$k]["href"])){
	    			$out[] = $res[$k]["href"];
	    		}
	    	}
        }
        
        //if(count($out) < 1){
	        $instruction = 'ul.list li.item a img';
		    $parser = new nokogiri($html);
		    $res = $parser->get($instruction)->toArray();
		    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
		    unset($parser);
		    if (is_array($res) && count($res) > 1) {
		    	foreach($res as $k => $v){
		    		if(isset($res[$k]["data-normal"])){
		    			$out[] = $res[$k]["data-normal"];
		    		}elseif(isset($res[$k]["src"])){
		    			$out[] = $res[$k]["src"];
		    		}
		    	}
	        }
        //}
        
	        
 		$instruction = 'div.item img';
		 $parser = new nokogiri($html);
		 $res = $parser->get($instruction)->toArray();
		 //echo '<pre>'.print_r($res , 1).'</pre>';exit;
		 if (is_array($res) && count($res) > 1) {
		    	foreach($res as $k => $v){
		    		if(isset($res[$k]["data-normal"])){
		    			$out[] = $res[$k]["data-normal"];
		    		}/*elseif(isset($res[$k]["src"])){
		    			$out[] = $res[$k]["src"];
		    		}*/
		    	}
		 }
		 
       
		 $out = clear_images_array($out);
	    return $out;
}



function mspro_miniinthebox_options($html){
	$out = array();
	
	
	$instruction = 'ul.attributes li select';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['option']) && is_array($pos_option['option']) && count($pos_option['option']) > 1 && isset($pos_option['option'][0]['#text']) && !is_array($pos_option['option'][0]['#text']) && !isset($pos_option['disabled'])  ){
				$brutal_name = $pos_option['option'][0]['#text'];
				unset($pos_option['option'][0]);
				$opt_values =  $pos_option['option'];
				$OPTION = array();
				$OPTION['name'] = miniinthebox_get_option_name($brutal_name);
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($opt_values as $opt_value){
					if(isset($opt_value['#text']) && !is_array($opt_value['#text'])){
						$OPTION['values'][] = miniinthebox_get_option_value_name($opt_value['#text']);
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



function miniinthebox_get_option_name($brutal_name){
	$brutal_name = trim($brutal_name);
	$res = explode(" " , $brutal_name);
	if(count($res) > 1){
		unset($res[0]);
		$name = implode(" " , $res);
		return trim($name);
	}
	return $brutal_name;
}


function miniinthebox_get_option_value_name($name){
	$price = 0;
	$res = explode('(' , $name , 2);
	if(count($res) > 1){
		if(strpos($res[1] , 'USD') > 0 || strpos($res[1] , '$') > 0){
			$price = preg_replace("/[^0-9,.-]/", "",  $res[1]);
			$name = $res[0];
		}
	}
	$out = array('name' => addslashes($name), 'price' => $price);
	return $out;
}




