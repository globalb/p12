<?php


function mspro_lazada_title($html){
    //echo $html;exit;
		$instruction = 'h1#prod_title';
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
        
        $instruction = 'h1[itemprop=name]';
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
        
        $instruction = 'div.product-info-name';
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

function mspro_lazada_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#productDetails');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		$desc_exists = array();
		$temp  = $pq->find('div.description-detail');
		foreach ($temp as $block){
		    $temp_block = $temp->html();
		    if(!in_array($temp_block , $desc_exists)){
		        $res .= '<div>' . $temp_block . '</div>';
		        $desc_exists[] = $temp_block;
		    }
		}
		
		$temp  = $pq->find('div.product-description__inbox');
		foreach ($temp as $block){
		    $res .= '<div><h2 class="product-description__title">Specifications of ' . mspro_lazada_title($html) . '</h2>' . $temp->html() . '</div>';
		}
		
		$temp  = $pq->find('table.specification-table');
		foreach ($temp as $block){
		    $res .= '<div><h3>General Features:</h3><table class="specification">' . $temp->html() . '</table></div>';
		}
		
		$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $res);
		$res = str_replace(array("<noscript>", "</noscript>") , array("" , "") , $res);
		//echo $res;exit;
		
		
		// WORKING WITH IMAGES
		// get images array
		preg_match_all('/(<img[^<]+>)/Usi', $res, $images);
		//echo '<pre>'.print_r($images[0] , 1).'</pre>';exit;
		if(isset($images[0] ) && is_array($images[0] ) && count($images[0] ) > 0){
    		foreach ($images[0] as $index => $value) {
    		    $s = strpos($value, 'src="') + 5;
    		    $e = strpos($value, '"', $s + 1);
    		    if($s > 5){
    		        $res = str_ireplace($value , '<img src="' . substr($value, $s, $e - $s) . '" alt="" />' , $res);
    		    }else{
    		        $res = str_ireplace($value , "" , $res);
    		    }
    		}
		}
		
		//echo $res;exit;
	
		return $res;
}


function mspro_lazada_price($html){
        $res = explode('<span class="product-price">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",  $res[0]); 
        		return (float) $price;
            }
        }
	   $res = explode('id="special_price_box">' , $html);
        if(count($res) > 1){
        	$res = explode('<' , $res[1] , 2);
        	if(count($res) > 1){
        	    $res = preg_replace("/[^0-9.]/", "",  $res[0]); 
        		return (float) $res;
        	} 
        }
        $res = explode('id="product_price" class="hidden">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $res;
            }
        }
        $res = explode('itemprop="price" content="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = preg_replace("/[^0-9.]/", "",  $res[0]);
                return (float) $res;
            }
        }
        return '';
}


function mspro_lazada_sku($html){
        $res = explode('"pdt_simplesku":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        $res = explode('"pdt_sku":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        $res = explode('"sku":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        $res = explode('data-simple-sku="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        $res = explode('data-config-sku="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        
        return '';
}

function mspro_lazada_model($html){
		 $res = explode('<td class="bold">Model</td>' , $html);
        if(count($res) > 1){
            $res = explode('</td>' , $res[1] , 2);
            if(count($res) > 1){
                $res = strip_tags($res[0]);
                return trim($res);
            }
        }
        return mspro_lazada_sku($html);
}

function mspro_lazada_weight($html){
		$out = array();
			
		$instruction = 'div[itemprop=weight] meta[itemprop=value]';
		$parser = new nokogiri($html);
		$data = $parser->get($instruction)->toArray();
		//echo '<pre>'.print_r($data , 1).'</pre>';exit;
		unset($parser);
		if(isset($data[0]['content']) && !is_array($data[0]['content']) && strlen(trim($data[0]['content'])) > 0){
		    $res = preg_replace("/[^0-9.]/", "",  $data[0]['content']); 
		    $out['weight'] = (float) $res;
		    $out['weight_class_id'] = 1;
		}
        
        return $out;
}


function mspro_lazada_meta_description($html){
	  $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;" , 'lazada' , "Lazada") , array(" " , "`" , "" , "") , $res[0]);
			}	 
       }
       $res =  explode('<meta name="description"' , $html);
       if(count($res) > 1){
           $res = str_ireplace(array('content="') , array(""), $res[1]);
           $res = explode('"' , trim($res) );
           if(count($res) > 1){
               return str_replace(array("&nbsp;" , "&amp;" , 'lazada' , "Lazada") , array(" " , "`" , "" , "") , $res[0]);
           }
       }
       return '';
}

function mspro_lazada_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       $res =  explode('<meta name="keywords"' , $html);
       if(count($res) > 1){
           $res = str_ireplace(array('content="') , array(""), $res[1]);
           $res = explode('"' , trim($res) );
           if(count($res) > 1){
               return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);
           }
       
       }
       return  mspro_lazada_meta_description($html);
}


function mspro_lazada_main_image($html){
	$imgs_arr = mspro_lazada_images_array($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 0){
		return $imgs_arr[0];
	}
	return '';	
}



function mspro_lazada_other_images($html){
	$imgs_arr = mspro_lazada_images_array($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 1){
		unset($imgs_arr[0]);
		return $imgs_arr;
	}
	return array();
}

function mspro_lazada_images_array($html){
    //echo $html;exit;
	$out = array();
	
	$instruction = 'ul.prd-moreImagesList li';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if(isset($data[0]['div']) && is_array($data[0]['div']) && count($data[0]['div']) > 0){
	    foreach($data[0]['div'] as $pos_image){
	        if(isset($pos_image['div'][0]['data-zoom-image']) && !is_array($pos_image['div'][0]['data-zoom-image'])){
	            $out[] = $pos_image['div'][0]['data-zoom-image'];
	        }elseif(isset($pos_image['div'][0]['data-big']) && !is_array($pos_image['div'][0]['data-big'])){
	            $out[] = $pos_image['div'][0]['data-big'];
	        }elseif(isset($pos_image['div'][0]['data-swap-image']) && !is_array($pos_image['div'][0]['data-swap-image'])){
	            $out[] = $pos_image['div'][0]['data-swap-image'];
	        }
	    }
	}
	
	$instruction = 'ul.prd-moreImagesList li';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if(isset($data[0]['span']) && is_array($data[0]['span']) && count($data[0]['span']) > 0){
		foreach($data[0]['span'] as $pos_image){
			if(isset($pos_image['span'][0]['data-zoom-image']) && !is_array($pos_image['span'][0]['data-zoom-image'])){
				$out[] = $pos_image['span'][0]['data-zoom-image'];
			}elseif(isset($pos_image['span'][0]['data-swap-image']) && !is_array($pos_image['span'][0]['data-swap-image'])){
			    $out[] = $pos_image['span'][0]['data-swap-image'];
			}
		}
	}
	
	$instruction = 'div.swiper-slide img';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if(isset($data) && is_array($data) && count($data) > 0){
	    foreach($data as $pos_img){
	        if(isset($pos_img['data-largeurl']) && !is_array($pos_img['data-largeurl']) && strlen(trim($pos_img['data-largeurl'])) > 0){
	            $out[] = $pos_img['data-largeurl'];
	        }
	    }
	}
	
	$instruction = 'div.product-image-container img';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if(isset($data[0]['src']) && !is_array($data[0]['src']) && strlen(trim($data[0]['src'])) > 0){
	    $out[] = $data[0]['src'];
	}
	
	
	// color images
	$instruction = 'ul.prd-colorList li';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if(isset($data) && is_array($data) && count($data) > 0){
	    foreach($data as $pos_image){
	        if(isset($pos_image['a'][0]['span'][0]['img'][0]['data-large-image']) && !is_array($pos_image['a'][0]['span'][0]['img'][0]['data-large-image'])){
	            $out[] = $pos_image['a'][0]['span'][0]['img'][0]['data-large-image'];
	        }
	    }
	}
	
	$instruction = 'ul.prd-moreImagesList div.productImage';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if(isset($data) && is_array($data) && count($data) > 0){
	    foreach($data as $pos_img){
	        if(isset($pos_img['data-zoom-image']) && !is_array($pos_img['data-zoom-image']) && strlen(trim($pos_img['data-zoom-image'])) > 0){
	            $out[] = $pos_img['data-zoom-image'];
	        }elseif(isset($pos_img['data-big']) && !is_array($pos_img['data-big']) && strlen(trim($pos_img['data-big'])) > 0){
	            $out[] = $pos_img['data-big'];
	        }
	    }
	}
	
	$out = array_unique($out);
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	
	return $out;
}



function mspro_lazada_options($html){
	$out = array();
	
	
	// ONLY SIZE OPTION
	$instruction = 'span.select_product-size';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if(isset($res[0]['select'][0]['option']) && is_array($res[0]['select'][0]['option']) && count($res[0]['select'][0]['option']) > 1){
	            $OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , 'Size' );
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				$option_values = $res[0]['select'][0]['option'];
				unset($option_values[0]);
				foreach($option_values as $option_value){
					if(isset($option_value['#text']) && !is_array($option_value['#text'])){
						$OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
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
function mspro_lazada_noMoreAvailable($html){
	return false;
}
*/
