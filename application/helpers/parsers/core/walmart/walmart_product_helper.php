<?php


function mspro_walmart_title($html){
		$instruction = 'h1.productTitle';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
		if (isset($data[0]['span'][1]['#text']) && !is_array($data[0]['span'][1]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][1]['#text']));
        }
		if (isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][0]['#text']));
        }
        
        
		$instruction = 'h1.product-name';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 0) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
		if (isset($data[0]['span'][1]['#text']) && !is_array($data[0]['span'][1]['#text']) && strlen(trim($data[0]['span'][1]['#text'])) > 0) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][1]['#text']));
        }
		if (isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text']) && strlen(trim($data[0]['span'][0]['#text'])) > 0) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][0]['#text']));
        }
        if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 0) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        
        $instruction = 'h1[itemprop=name]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        return '';
}

function mspro_walmart_description($html){
		$res = '';
		
		$pq = phpQuery::newDocumentHTML($html);
		
		// Description
		$temp  = $pq->find('div[itemprop=description]');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		$temp  = $pq->find('div.about-product-section div.about-item-complete');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		//echo $res;exit;		
		// Specification
		$temp  = $pq->find('table.SpecTable');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		// Specification
		$temp  = $pq->find('section#productSpecs');
		foreach ($temp as $block){
			$res .= $temp->html();
		}
		
		
		$temp  = $pq->find('div.description');
		foreach ($temp as $block){
		    $res .= $temp->html();
		}
		
		$temp  = $pq->find('div#product-characteristics');
		foreach ($temp as $block){
		    $res .=  $temp->html();
		}
		
		
		$res = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $res);
		$res = str_replace(array('<div class="js-show-more-trigger show-more"><span>Show </span></div>' , '<section class="product-specs-section"><section class="product-specs"><div class="js-idml-video-container" data-idml-host="//www.walmart-content.com"></div></section>' , '</div> <div class="js-show-more-trigger show-more caret"><span>Show </span></div>'), array("" , "" , ""), $res);
		
		// remove "more" button
		$t_res = explode('<button type="button" class="more-characteristics btn-link">' , $res);
		if(is_array($t_res) && count($t_res) > 1){
		    $tt_res = explode('</button>' , $t_res[1]);
		    if(is_array($tt_res) && count($tt_res) > 1){
		        $res = $t_res[0] . $tt_res[1];
		    }
		}
		
		//remove MANULAS section
		$res = preg_replace(array("'<section class=\"product-specs-section slick-module\"><h3 class=\"idml-documents-main-title\">Manuals[^>]*?>.*?</section>'si"), Array(""), $res);
	
		//echo $res;exit;
		
		return $res;
}


function mspro_walmart_price($html){
	$res = explode('"price_store_price":["' , $html);
	if(count($res) > 1){
		$res = explode('"' , $res[1] , 2);
		if(count($res) > 1){
			return (float) trim($res[0]);
		}
	} 

	$res = explode('currentItemPrice:' , $html);
	if(count($res) > 1){
		$res = explode(',' , $res[1] , 2);
		if(count($res) > 1){
			return (float) trim( $res[0] );
		}
	} 
	
	
	$res = explode('<meta itemprop=price itemtype="http://schema.org/Product" content="' , $html);
	if(count($res) > 1){
	    $res = explode('"' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9.]/", "",  $res[0]);
	        return (float) trim( $res );
	    }
	}
	
	
	$res = explode('<meta itemprop="price" content="' , $html);
	if(count($res) > 1){
	    $res = explode('"' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9,]/", "",  $res[0]);
	        $res = str_replace(array(","), array(".") , $res);
	        return (float) trim( $res );
	    }
	}
	
	$res = explode('<strong class=display-price>$' , $html);
	if(count($res) > 1){
	    $res = explode('<' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9.]/", "",  $res[0]);
	        return (float) trim( $res );
	    }
	}
	
	$res = explode('<span class="payment-price"><strong><span class="int">' , $html);
	if(count($res) > 1){
	    $res = explode('>' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9,]/", "",  $res[0]);
	        $res = str_replace(array(","), array(".") , $res);
	        return (float) trim( $res );
	    }
	}
	
	$res = explode('data-product-price=' , $html);
	if(count($res) > 1){
	    $res = explode('>' , $res[1] , 2);
	    if(count($res) > 1){
	        return (float) trim( $res[0] );
	    }
	}
	
	$res = explode('<strong class=display-price>$' , $html);
	if(count($res) > 1){
	    $res = explode('<' , $res[1] , 2);
	    if(count($res) > 1){
	        $res = preg_replace("/[^0-9.]/", "",  $res[0]);
	        return (float) trim( $res );
	    }
	}
	
	return '';
}


function mspro_walmart_clear_price($price){
    $res = preg_replace("/[^0-9.]/", "",  $price);
    return $res;
}


function mspro_walmart_sku($html){
        $res =  explode('<span itemprop=sku>' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1]);
            if(count($res) > 1){
                return trim( $res[0] );
            }
        }
		$res =  explode('" data-ref-sku="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1]);
            if(count($res) > 1){
                return trim( $res[0] );
            }
        }
        
        $res =  explode(',"refProductSku":' , $html);
        if(count($res) > 1){
            $res = explode(',' , $res[1]);
            if(count($res) > 1){
                return trim( $res[0] );
            }
        }
        
    	return mspro_walmart_model($html);
}

function mspro_walmart_model($html){
	   $res =  explode('<meta itemprop=productID itemtype="http://schema.org/Product" content=' , $html);
       if(count($res) > 1){
       		$res = explode('/>' , $res[1]);
       		if(count($res) > 1){
       			return trim( $res[0] );	
       		}
       }
       
       $res =  explode('<td class="value-field Referencia-do-Modelo">' , $html);
       if(count($res) > 1){
           $res = explode('<' , $res[1]);
           if(count($res) > 1){
               return trim( $res[0] );
           }
       }
 
       return '';
}


function mspro_walmart_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
		$res =  explode('<meta name=description content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
		$res =  explode('<meta name="Description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       
       return '';
}

function mspro_walmart_meta_keywords($html){
       $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
 		$res =  explode('<meta name=keywords content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
		$res =  explode('<meta name="Keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       } 

       return mspro_walmart_meta_description($html);
}


function mspro_walmart_main_image($html){
	$arr = mspro_walmart_get_images_arr($html);
	if(isset($arr[0]) && strlen($arr[0]) > 0){
		return $arr[0];
	}
	return '';
}



function mspro_walmart_other_images($html){
	$arr = mspro_walmart_get_images_arr($html);
	if(count($arr) > 1){
		unset($arr[0]);
		return $arr;
	}
	return array();
}

function mspro_walmart_get_images_arr($html){
		$out = array();
		$res = explode("posterImages.push('" , $html);
		if(is_array($res) && count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$res_t = explode("');" , $block , 2);
				if(is_array($res_t) && count($res_t) > 0){
					$out[] = $res_t[0];
				}
			}
		}
		
		$instruction = 'img[itemprop=image]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data[0]['src'])){
	    	$out[] = $data[0]['src'];
	    }
	    
	    $instruction = 'div#carousel ul li img';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data) && is_array($data) && count($data) > 0){
	    	foreach($data as $pos_img){
	    		if(isset($pos_img['src'])){
	    			if(substr($pos_img['src'] , 0 , 2) == "//"){
	    				$pos_img['src'] = substr($pos_img['src'] , 2);
	    			}
	    			$out[] = $pos_img['src'];
	    		}
	    	}
	    }
	    
		$instruction = 'a.product-thumb';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data) && is_array($data) && count($data) > 0){
	    	foreach($data as $pos_image){
	    		if(isset($pos_image['href'])){
	    			$out[] = $pos_image['href'];
	    		}elseif(isset($pos_image['data-zoom-image'])){
	    			$out[] = $pos_image['data-zoom-image'];
	    		}elseif(isset($pos_image['data-hero-image'])){
	    			$out[] = $pos_image['data-hero-image'];
	    		}
	    	}
	    }
	    
	    $instruction = 'div#wm-pictures-carousel a';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
	    if(isset($data) && is_array($data) && count($data) > 0){
	        foreach($data as $pos_image){
	            if(isset($pos_image['data-zoom'])){
	                $out[] = $pos_image['data-zoom'];
	            }elseif(isset($pos_image['data-normal'])){
	                $out[] = $pos_image['data-normal'];
	            }
	        }
	    }
	     
	     
	    $instruction = 'img.js-product-primary-image';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
	    if(isset($data[0]['src']) && !is_array($data[0]['src']) && strlen($data[0]['src']) > 5){
	        $out[] = $data[0]['src'];
	    }
	     
	    $t_res = explode('","hero":"' , $html);
	    //echo count($t_res);exit;
	    if(is_array($t_res) && count($t_res) > 1){
	        unset($t_res[0]);
	        foreach($t_res as $pos_image){
	            $tt_res = explode('"' , $pos_image , 2);
	            if(is_array($tt_res) && count($tt_res) > 1){
	                if(strlen(trim($tt_res[0])) > 0){
	                    $out[] = $tt_res[0];
	                }
	            }
	        }
	    }
		
	    $out = clear_images_array($out);
		//echo '<pre>'.print_r($out , 1).'</pre>';exit;
		
	    
		return $out;
}

/*
function mspro_walmart_noMoreAvailable($html){
	return false;
}
*/