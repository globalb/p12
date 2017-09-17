<?php


function mspro_snapdeal_title($html){
		$instruction = 'h1[itemprop=name]';
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

function mspro_snapdeal_description($html){
		$out = '';
		
		// product-params if <div class="params"> not available
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div.spec-section:first');
		foreach ($temp as $block){
			$result = $temp->html();
			//echo strpos($result , "Highlights") . '<br />' . $result . '<br />';
			if(strpos($result , "Highlights") > 1){
				$out .= $result . '<br /><br /><br />';
				break;
			}
		}
		
		$temp  = $pq->find('div.detailssubbox');
		foreach ($temp as $block){
			$out .= $temp->html();
			break;
		}
		
        return $out;
}


function mspro_snapdeal_price($html){
		$instruction = 'span[itemprop=price]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if ($data){
	        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
				$price = preg_replace("/[^0-9.]/", "",  trim($data[0]['#text']) );
		    	return (float) $price;
	        }
        }
        return '';
}


function mspro_snapdeal_sku($html){
        $res =  explode('<input type="hidden" id="pppid" value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1]);
            if(count($res) > 1){
                return trim($res[0]);
            }
        }
		return mspro_snapdeal_model($html); 
}

function mspro_snapdeal_model($html){
		$instruction = 'div#pppid';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
 		if ($data){
	        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		    	return trim($data[0]['#text']);
	        }
        }
        $res =  explode('Model</td>' , $html);
        if(count($res) > 1){
            $res = explode('</td>' , $res[1]);
            if(count($res) > 1){
                return str_replace(array("<td>") , array("") , trim($res[0]) );
            }
        }
        $res =  explode('SKU Code :' , $html);
        //echo count($res);exit;
        if(count($res) > 1){
            $res = explode('<' , $res[count($res) - 1] , 2);
            //echo print_r($res);exit;
            if(count($res) > 1){
                $model = str_replace(array("<td>") , array("") , trim($res[0]) );
                //echo $model;exit;
                return $model;
            }
        }
        return '';
}


function mspro_snapdeal_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_snapdeal_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_snapdeal_main_image($html){
		$arr = snapdeal_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_snapdeal_other_images($html){
		$arr = snapdeal_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function snapdeal_get_images($html){
		$out = array(); 
		$instruction = 'img[itemprop=image]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(count($data) > 0 && is_array($data)){
	    	foreach($data as $img){
	    		if(isset($img['src'])){
	    			$out[] =  $img['src'];
	    		}elseif(isset($img['lazysrc'])){
	    			$out[] =  $img['lazysrc'];
	    		}elseif(isset($img['lazySrc'])){
	    			$out[] =  $img['lazySrc'];
	    		}
	    	}
	    }
	     
		$instruction = 'img.jqzoom';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data) && is_array($data) && count($data) > 0 ){
	    	foreach($data as $img){
	    		if(isset($img['src'])){
	    			$out[] =  $img['src'];
	    		}elseif(isset($img['lazysrc'])){
	    			$out[] =  $img['lazysrc'];
	    		}elseif(isset($img['lazySrc'])){
	    			$out[] =  $img['lazySrc'];
	    		}
	    	}
	    }
	     
	    $out = clear_images_array($out);
	    return $out;
}


function mspro_snapdeal_options($html){
	$out = array();
	
	$opt_arr = array();
	$instruction = 'input#productAttributesJson';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	if(isset($res[0]['value']) && !is_array($res[0]['value']) ){
		@$res_t = json_decode($res[0]['value']);
		if (isset($res_t) && is_array($res_t) && count($res_t) > 0){
			foreach($res_t as $pos_opt){
				if(isset($pos_opt->name) && isset($pos_opt->value) && isset($pos_opt->soldOut) && $pos_opt->soldOut < 1 && isset($pos_opt->supc) ){
					$temp = array('value' => $pos_opt->value , 'supc' => $pos_opt->supc);
					if(!array_key_exists($pos_opt->name, $opt_arr)){
						$opt_arr[$pos_opt->name] = array();
					}
					$opt_arr[$pos_opt->name][] = $temp;
				}
			}
		}
	}
	//echo '<pre>'.print_r($opt_arr , 1).'</pre>';exit;
	unset($parser);
	if (is_array($opt_arr) && count($opt_arr) > 0){
		$catid = snapdeal_get_catid($html);
		$originalPrice = mspro_snapdeal_price($html);
		foreach($opt_arr as $opt_name => $option_values){
				$OPTION = array();
				$OPTION['name'] = $opt_name;
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($option_values as $option_value){
					$OPTION['values'][] = array('name' => $option_value['value'] , 'price' => snapdeal_get_option_price($option_value['supc'] , $catid , $originalPrice) );
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
		}
	}
	
	if(count($out) < 1){
	    $instruction = 'div#product-attr-options';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
	    unset($parser);
	    if (is_array($res) && count($res) > 0){
	        $catid = snapdeal_get_catid($html);
	        $originalPrice = mspro_snapdeal_price($html);
	        foreach($res as $pos_opt){
	            if(isset($pos_opt['span'][0]['div'][0]['span'][0]['#text']) && !is_array($pos_opt['span'][0]['div'][0]['span'][0]['#text']) && strlen(trim($pos_opt['span'][0]['div'][0]['span'][0]['#text'])) > 0 && isset($pos_opt['span'][0]['ul'][0]['li']) && is_array($pos_opt['span'][0]['ul'][0]['li']) && count($pos_opt['span'][0]['ul'][0]['li']) > 0){
	                $OPTION = array();
	                $OPTION['name'] = trim(str_ireplace(array("Select") , array("") , $pos_opt['span'][0]['div'][0]['span'][0]['#text']) );
	                $OPTION['type'] = "select";
	                $OPTION['required'] = true;
	                $OPTION['values'] = array();
	                foreach($pos_opt['span'][0]['ul'][0]['li'] as $option_value){
	                    if(isset($option_value['#text']) && isset($option_value['supc'])){
	                       $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => snapdeal_get_option_price($option_value['supc'] , $catid , $originalPrice) );
	                    }elseif(isset($option_value['data-val']) && isset($option_value['supc'])){
	                       $OPTION['values'][] = array('name' => trim($option_value['data-val']) , 'price' => snapdeal_get_option_price($option_value['supc'] , $catid , $originalPrice) );
	                    }elseif(isset($option_value['title']) && isset($option_value['supc'])){
	                       $OPTION['values'][] = array('name' => trim($option_value['title']) , 'price' => snapdeal_get_option_price($option_value['supc'] , $catid , $originalPrice) );
	                    }
	                }
	                if(count($OPTION['values']) > 0){
	                    $out[] = $OPTION;
	                }
	            }
	        }
	    }
	}
	
	
    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}

function snapdeal_get_catid($html){
		$instruction = 'div#categoryId';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
 		if ($data){
	        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		    	return trim($data[0]['#text']);
	        }
        }
}

function snapdeal_get_option_price($supc , $catid , $originalPrice){
	$res = getUrl("http://www.snapdeal.com/acors/json/gvbps?supc=$supc&catId=$catid");
	if($res){
		$res = explode('sellingPrice":' , $res , 2);
		if(count($res) > 1){
			$res = explode(',' , $res[1] , 2);
			if(count($res) > 1){
				return (float) ( (float) $res[0] - $originalPrice);
			}
		}
	}
	return 0;
	//echo $res;exit;	
}


/*
function mspro_snapdeal_noMoreAvailable($html){
	return false;
}
*/
