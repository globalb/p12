<?php


function mspro_dinodirect_title($html){
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
        
        
        $instruction = 'h1.tit18';
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

function mspro_dinodirect_description($html){
		$out = '';
		
		// product-params if <div class="params"> not available
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('li#DescLi');
		foreach ($temp as $block){
			$out .= $temp->html();
			break;
		}
		
		// check for LAZY-SRC
		preg_match_all('/(<img[^<]+>)/Usi', $out, $images);
		$image = array();
		//echo '<pre>'.print_r($images[0] , 1).'</pre>';
		if(isset($images[0]) && is_array($images[0]) && count($images[0]) > 0){
		    foreach ($images[0] as $index => $value) {
		        $t_res = explode('lazy-src="' , $value);
		        if(count($t_res) > 1){
		            $t_res = explode('"' , $t_res[1] , 2);
		            if(count($t_res) > 1){
		                // get title and alt
		                $alt = false;
		                $alt_res = explode('alt="' , $value);
		                if(count($alt_res) > 1){
		                    $alt_res = explode('"' , $alt_res[1] , 2);
		                    if(count($alt_res) > 1){
		                        $alt = $alt_res[0];
		                    }
		                }
		                $new_img = '<img src="' . $t_res[0] . '" ' . ($alt?'alt="'.$alt.'" title="'.$alt.'"':"") . ' />';
		                $out = str_replace($value , $new_img , $out);
		            }
		        }
		    }
		}
		
		
        return $out;
}


function mspro_dinodirect_price($html){
		$instruction = 'span[itemprop=price]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if ($data){
	        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
		    	return (float) trim($data[0]['#text']);
	        }
        }
        return '';
}


function mspro_dinodirect_sku($html){
        $res =  explode('SKU:' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1]);
            if(count($res) > 1){
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);
            }
        
        }
        return '';
}

function mspro_dinodirect_model($html){
		return mspro_dinodirect_sku($html);
}


function mspro_dinodirect_meta_description($html){
	  $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_dinodirect_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_dinodirect_main_image($html){
		$arr = dinodirect_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_dinodirect_other_images($html){
		$arr = dinodirect_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function dinodirect_get_images($html){
		$out = array();
		//echo $html;exit;
		
		// get the main image src
		$main_image_src = false;
		$image_res = explode('rel="image_src" href="' , $html);
		if(count($image_res) > 1){
		    $image_res = explode('"' , $image_res[1]);
		    if(count($image_res) > 1){
		        $main_image_src = $image_res[0];
		        $out[] = $main_image_src;
		    }
		}
		// now trying to get other images
		if($main_image_src){
    		$number_image = 1;
    		$next_image = true;
    		while($next_image){
    		    $t_res = explode("-big." , $main_image_src);
    		    if(count($t_res) > 1){
        		    $next_image_src = substr($t_res[0] , 0 , -1) .  "$number_image-big." . $t_res[1];
        		    //echo $next_image_src . '<br />';
        		    $number_image++;
        		    if(@getimagesize($next_image_src)){
        		        $out[] = $next_image_src;
        		    }else{
        		        $next_image = false;
        		    }
    		    }
    		}
		}
		//echo '<pre>'.print_r($out , 1).'</pre>';exit;
		
		// if no images in slideshow
		if(count($out) < 2){
            $res = '';
    		// product-params if <div class="params"> not available
    		$pq = phpQuery::newDocumentHTML($html);
    		$temp  = $pq->find('li#DescLi');
    		foreach ($temp as $block){
    			$res = $temp->html();
    			break;
    		}
    		
    		// check for LAZY-SRC
    		preg_match_all('/(<img[^<]+>)/Usi', $res, $images);
    		$image = array();
    		//echo '<pre>'.print_r($images[0] , 1).'</pre>';
    		if(isset($images[0]) && is_array($images[0]) && count($images[0]) > 0){
    		    foreach ($images[0] as $index => $value) {
    		        $t_res = explode('lazy-src="' , $value);
    		        if(count($t_res) > 1){
    		            $t_res = explode('"' , $t_res[1] , 2);
    		            if(count($t_res) > 1){
    		                if(strpos($t_res[0] , "NoneWatermark") < 1){
    		                   $out[] = $t_res[0];
    		                }
    		            }
    		        }
    		    }
    		}
		}
     
		//echo '<pre>'.print_r($images , 1).'</pre>';exit;
        return $out;
}


function mspro_dinodirect_options($html){
        $out = array();
        
        $temp_arr = array();
    	
    	$instruction = 'div.newHopp';
    	$parser = new nokogiri($html);
    	$res = $parser->get($instruction)->toArray();
    	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
    	unset($parser);
    	if (is_array($res) && count($res) > 0){
    	    foreach($res as $pos_option){
    	        if(isset($pos_option['name']) && !is_array($pos_option['name']) && strlen(trim($pos_option['name'])) > 0 && isset($pos_option['#text']) && !is_array($pos_option['#text']) && strlen(trim($pos_option['#text'])) > 0){
    	            if(!isset($temp_arr[$pos_option['name']])){
    	                $temp_arr[$pos_option['name']] = array();
    	            }
    	            if(!in_array($pos_option['#text'] , $temp_arr[$pos_option['name']])){
    	               $temp_arr[$pos_option['name']][] = $pos_option['#text'];
    	            }
    	        }
    	    }
    	}
    	//echo '<pre>'.print_r($temp_arr , 1).'</pre>';exit;
    	
    	
    	
    	if (is_array($temp_arr) && count($temp_arr) > 0){
    		foreach($temp_arr as $key => $pos_options){
    			if(count($pos_options) > 0){
    				$OPTION = array();
    				$OPTION['name'] = $key;
    				$OPTION['type'] = "select";
    				$OPTION['required'] = true;
    				$OPTION['values'] = array();
    				foreach($pos_options as $option_value){
    					$OPTION['values'][] = array('name' => $option_value , 'price' => 0);
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
function mspro_dinodirect_noMoreAvailable($html){
	return false;
}
*/
