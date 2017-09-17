<?php


function mspro_tinydeal_title($html){
		$instruction = 'h1#productName';
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

function mspro_tinydeal_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#productDescription');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		/*
		$res = preg_replace(array("'<style[^>]*?>.*?</style>'si"), Array(""), $res);
		*/
		
		$res = str_ireplace(array('<textarea class="data-lazyload">' , "</textarea>") , array("" , "") , $res);
		$res = str_ireplace(array('<div class="invisible" style="margin:0;padding:0;">
     
</div>'), array(""), $res); 
		
		return $res;
}


function mspro_tinydeal_price($html){
		$res = explode('itemprop="price" >' , $html);
        if(count($res) > 1){
        	$res = explode('</span>' , $res[1] , 2);
        	if(count($res) > 1){
        		return trim(  $res[0] );
        	}
        }
        return '';
}


function mspro_tinydeal_sku($html){
		return mspro_tinydeal_model($html);
}

function mspro_tinydeal_model($html){
		$instruction = 'ul#productDetailsList li strong';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
		if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        return '';
}


function mspro_tinydeal_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return  '';
}

function mspro_tinydeal_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return  mspro_tinydeal_meta_description($html);
}


function mspro_tinydeal_main_image($html){
	$imgs_arr = tinydeal_get_images($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 0){
		return $imgs_arr[0];
	}
	return '';	
}



function mspro_tinydeal_other_images($html){
	$imgs_arr = tinydeal_get_images($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 1){
		unset($imgs_arr[0]);
		return $imgs_arr;
	}
	return array();
}


function tinydeal_get_images($html){
	$out = array();
	
	$instruction = 'ul.product_list_li_ul li img';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if(isset($data) && is_array($data) && count($data) > 0){
		foreach($data as $pos_image){
			if(isset($pos_image['imgb']) && !is_array($pos_image['imgb'])){
				$out[] = $pos_image['imgb'];
			}
		}
	}
	
	$out = clear_images_array($out);
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	
	return $out;
}


function mspro_tinydeal_options($html){
	$out = array();
	
	$instruction = 'div#productAttributes dl';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['dt'][0]['#text'][0]) && !is_array($pos_option['dt'][0]['#text'][0]) && isset($pos_option['dd'][0]['div']) && is_array($pos_option['dd'][0]['div']) && count($pos_option['dd'][0]['div']) > 0){
				$name = str_replace( array(":") , array("") , $pos_option['dt'][0]['#text'][0]);
				$OPTION = array();
				$OPTION['name'] = trim($name);
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				$option_values = $pos_option['dd'][0]['div'];
				foreach($option_values as $option_value){
					if(isset($option_value['a']['#text']) && !is_array($option_value['a']['#text'])){
						$OPTION['values'][] = array('name' => $option_value['a']['#text'] , 'price' => 0);
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



/*
function mspro_tinydeal_noMoreAvailable($html){
	return false;
}
*/
