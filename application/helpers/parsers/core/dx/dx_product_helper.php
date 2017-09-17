<?php


function mspro_dx_title($html){
    //echo $html;
		$instruction = 'span[itemprop=name]';
    	$parser = new nokogiri($html);
    	$data = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
    	unset($parser);
    	if(isset($data[0]['#text']) && !is_array($data[0]['#text']) ){
    		return trim($data[0]['#text']);
    	}elseif(isset($data[0]['title']) && !is_array($data[0]['title'])){
    		return trim($data[0]['title']);
    	}
    	
    	$res = explode('<title>' , $html);
            if(count($res) > 1){
            	$res = explode('</title>' , $res[1] , 2);
            	if(count($res) > 1){
            		return trim($res[0]);
            	} 
            }
    	return '';
}

function mspro_dx_description($html){
		$res = '';
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div#overview div.pinfo_content');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html() . '</div>';
		}
		//echo utf8_encode($res);exit;
		
		$temp  = $pq->find('div#specification div.pinfo_content');
		foreach ($temp as $block){
		    $res .= '<div>' . $temp->html() . '</div>';
		}
		
		//echo $res;exit;
		
		return $res;
}


function mspro_dx_price($html){
		$res = explode('"price": "' , $html);
		if(count($res) > 1){
			$res = explode('"' , $res[1] , 2);
			if(count($res) > 1){
				return (float) str_ireplace( array(",") , array(".") , trim($res[0]) );
			}
		}
		
		$res = explode('pvalues : [' , $html);
		if(count($res) > 1){
			$res = explode(']' , $res[1] , 2);
			if(count($res) > 1){
				return (float) str_ireplace( array(",") , array(".") , trim($res[0]) );
			}
		}
		
		$res = explode('itemprop="price">' , $html);
		if(count($res) > 1){
			$res = explode('</span>' , $res[1] , 2);
			if(count($res) > 1){
				return (float) str_ireplace( array(",") , array(".") , trim($res[0]) );
			}
		}
		 
		return '';
}


function mspro_dx_sku($html){	
        $instruction = 'span#sku';
		$parser = new nokogiri($html);
		$data = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($data , 1).'</pre>';exit;
		if(isset($data[0]['#text']) && !is_array($data[0]['#text']) ){
			return trim($data[0]['#text']);
		}
		
		
		$res = explode('sku="' , $html);
		if(count($res) > 1){
			$res = explode('"' , $res[1] , 2);
			if(count($res) > 1){
				return trim($res[0]);
			}
		}
		
		return ''; 
}

function mspro_dx_model($html){
        return mspro_dx_sku($html);
}


function mspro_dx_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('" />' , $res[1]);
       		if(count($res) > 1){
       			return utf8_encode( str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]) );
			}	 
       }
       return '';
}

function mspro_dx_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('" />' , $res[1]);
       		if(count($res) > 1){ 
       			return utf8_encode( str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]) );
			}	 
       }
       return '';
}


function mspro_dx_main_image($html){
		$arr = dx_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_dx_other_images($html){
		$arr = dx_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function dx_get_images($html){
	    $out = array();
	    
	    $instruction = 'ul.product-small-images li a';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data) && is_array($data) && count($data) > 0) {
	        foreach($data as $pos_img){
	            if(isset($pos_img['href'])){
	                $out[] = $pos_img['href'];
	            }
	        }
	    }
	    
	    if( is_array($out) && count($out) > 0 ){
	        foreach($out as $key => $value){
	            if(substr($value , 0 , 2) == '//'){
	                $out[$key] = substr($value , 2);
	            }
	        }
	    }
	    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	    
	    $out = clear_images_array($out);
	    return $out;
}


/*
function mspro_dx_options($html){
    
}
*/


/*
function mspro_dx_noMoreAvailable($html){
	return false;
}
*/
