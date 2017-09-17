<?php


function mspro_chinavasion_title($html){
	   $instruction = 'h1';
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

function mspro_chinavasion_description($html){
	   $res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#overview');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		$temp  = $pq->find('div#specs');
		foreach ($temp as $block){
			$res .= $temp->html(). '<br />';
		}
		
		return $res;
}


function mspro_chinavasion_price($html){
        $res = explode('<span class="ccy" id=\'current_price_div\' ><span class=\'ccy\'>' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9,.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode('<span class="discount"><span class=\'ccy\'>' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9,.]/", "",  $res[0]);
                return (float) $price;
            }
        }
	    $res = explode('<span class="pricerange"><span class=\'ccy\'>' , $html);
        if(count($res) > 1){
        	$res = explode('</' , $res[1] , 2);
        	if(count($res) > 1){
        	    $price = preg_replace("/[^0-9,.]/", "",  $res[0]); 
        		return (float) $price;
        	} 
        }
        $res = explode('<td>1  or more for </td><td> <span class=\'ccy\'>' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9,.]/", "",  $res[0]);
                return (float) $price;
            }
        }
        $res = explode("id='discount_price_div' style='display: none;'><span class='ccy'>" , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9,.]/", "",  $res[0]);
                return (float) $price;
            }
        }   
        return '';
}


function mspro_chinavasion_sku($html){
	return mspro_chinavasion_model($html); 
}

function mspro_chinavasion_model($html){
	   $res = explode('<span class="code">' , $html);
        if(count($res) > 1){
        	$res = explode('<' , $res[1] , 2);
        	if(count($res) > 1){
        		return trim($res[0]);
        	} 
        }
        return '';
}


function mspro_chinavasion_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);		
			}	 
       }
       return '';
}

function mspro_chinavasion_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return  mspro_chinavasion_meta_description($html);
}


function mspro_chinavasion_main_image($html){
	$arr = mspro_chinavasion_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}


function mspro_chinavasion_other_images($html){
	$arr = mspro_chinavasion_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}


function mspro_chinavasion_get_images_arr($html){
    	$out = array();
    	//echo $html;
    	
    	$instruction = 'div#selektor img';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';
    	unset($parser);
    	if(isset($data) && is_array($data) && count($data) > 0){
    		foreach($data as $pos_image){
    			if(isset($pos_image['src']) && !is_array($pos_image['src'])){
    				$out[] = mspro_chinavasion_prepare_image_src($pos_image['src']);
    			}
    		}
    	}
    	   
	    $out = clear_images_array($out);
	    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	    
	    return $out;
}

function mspro_chinavasion_prepare_image_src($src){

    // EXTENSION OF IMAGE
    $ext = '.jpg';
    if(strpos($src , '.JPG') > 0){
        $ext = '.JPG';
    }
    
    $res = str_ireplace(array(".thumb_140x140.jpg" , ".thumb_70x70.jpg" , '.jpg') , array("" , "" , "") , $src);
    $t_res = explode("/" , $res);
    if(count($t_res) > 1){
        $res = $t_res[count($t_res) - 1];
    }
    if(strpos($res , '_with_a_') > 0){
        $t_res = explode("_with_a_" , $res);
        if(count($t_res) > 1){
            $res = $t_res[count($t_res) - 1];
        }
    }elseif(strpos($res , '_with_') > 0){
        $t_res = explode("_with_" , $res);
        if(count($t_res) > 1){
            $res = $t_res[count($t_res) - 1];
        }
    }
    /*else{
        $t_res = explode("_" , $res);
        if(count($t_res) > 1){
            $res = $t_res[count($t_res) - 1];
        }
    }*/
    $res = 'https://cdn.chv.me/images/' . $res  . $ext;
    //echo $res .'<br />';
    return $res;
}



function mspro_chinavasion_options($html){
    $out = array();
    return $out;
    
}


/*
 function mspro_chinavasion_noMoreAvailable($html){
	return false;
}
*/
