<?php


function mspro_alibaba_title($html){
        $instruction = 'span.ma-title-text';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data['#text']) && !is_array($data['#text'])) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
    
		$instruction = 'h1#productTitle';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        
        
		$instruction = 'h1.fn';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
        if (isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text'])) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][0]['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        
        $instruction = 'h1';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data['#text']) && !is_array($data['#text'])) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
        if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && $data[0]['#text'] !== "Alibaba.com" ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
        if (isset($data[1]['#text']) && !is_array($data[1]['#text']) && $data[1]['#text'] !== "Alibaba.com" ) {
            return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[1]['#text']));
        }
        
        
        
        
	   $res =  explode('<h1 class="d-title">' , $html);
       if(count($res) > 1){
       		$res = explode('</h1>' , $res[1] , 2);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , alibaba_enc($res[0]) );	
       		}
       		 
       }
        
        return '';
}

function mspro_alibaba_description($html){
        $html = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $html);
        $html = preg_replace(array("'<noscript[^>]*?>.*?</noscript>'si"), Array(""), $html);
    
        $out = '';
        $pq = phpQuery::newDocumentHTML($html);
        $temp  = $pq->find('div.detail-content');
        foreach ($temp as $block){
            $out .= '<div>' . pq($block)->html() . '</div>';
        }
        
		/*****  TRY THE SIMPLEST WAY  *********/
		$res = explode('J-product-detail' , $html , 2);
		if(count($res) > 1){
			$res = explode('<div class="ui-tab-pane' , substr($res[1] , 2) , 2);
			if(count($res) > 1){
				$out .= '<div><div><div>' . $res[0] ;
				$out = str_replace(array('itemprop="description">') , array("") , $out);
			}
			
			/*$temp  = $pq->find('div#J-product-detail div.box-first');
			foreach ($temp as $block){
			    $bl =  pq($block)->html();
			    $out .= $bl;
			}*/
			//echo $out;exit;

		}else{
			/*****  ANOTHER  *********/
			$temp  = $pq->find('#J-product-detail');
			foreach ($temp as $block){
					$out .= pq($block)->html();
					//break;
			}
			
			
			/*****  AND THE LATEST  *********/
			if(strlen($out) < 10){
				preg_match_all('|<table class="dbtable">(.*)</table>|isU', $html, $result, PREG_SET_ORDER);
		        if(is_array($result) && isset($result[0])){
		        	$test = preg_replace('/(<img[^<]+>)/Usi', '', $result[0]);
		        	$out .= '<table>'.$test[1].'</table>';
		        }
		        
		        $instruction = 'p.description';
			    $parser = new nokogiri($html);
			    $data = $parser->get($instruction)->toArray();
			    $data = reset($data);
			  	if (isset($data['#text'])){
			  		if(is_array($data['#text'])){
			  			foreach($data['#text'] as $val){
			  				if(!is_array($val)){
			  					$out .=  '<div>' . $val.'</div>';
			  				}
			  			}
			  		}else{
			  			$out .=  $data['#text'];
			  		}
			  	}
			    unset($parser);
			    
			    
				$pq = phpQuery::newDocumentHTML($html);
				$temp  = $pq->find('div#richTextDescription');
				foreach ($temp as $block){
						$out .= pq($block)->html();
						break;
				}
				
				
				// GET SPEC FOR CHINA ALIBABA 1688.com
				$res_t =  explode('<div id="mod-detail-attributes" class=' , $html);
		       if(count($res_t) > 1){
		       		$res_t = explode('<table>' , $res_t[1] , 2);
		       		if(count($res_t) > 1){
		       			$res_t = explode('</table>' , $res_t[1] , 2);
		       			if(count($res_t) > 1){
		       				$out .= '<table>' . str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , alibaba_enc($res_t[0]) ) . '</table>';
		       			}	
		       		}
		       		 
		       }
				
				
			}
		}
		
		
		// TRY TO GET IMAGES IN DESCRIPTION
		$res = explode('id="desc-lazyload-container"' , $html , 2);
		if(count($res) > 1){
			$res = explode('data-tfs-url="' , $res[1]);
			if(count($res) > 1){
				$res = explode('"' , $res[1] , 2);
				if(count($res) > 1){
					$ajaxUrl = $res[0];
					//echo $ajaxUrl;
					$ajaxRes = getUrl($ajaxUrl);
					//echo $ajaxRes;exit;
					if($ajaxRes){
						$ajaxRes = str_ireplace(array("var desc='" , 'var offer_details={"content":"') , array("") , $ajaxRes);
						$ajaxRes = trim($ajaxRes);
						if(substr($ajaxRes , -2) == "';"){ $ajaxRes = substr($ajaxRes , 0 , -2); }
						if(substr($ajaxRes , -3) == '"};'){ $ajaxRes = substr($ajaxRes , 0 , -3); }
						//echo $ajaxRes;exit;
						/*
						$ajaxRes = preg_replace(array("'<table[^>]*?>.*?</table>'si"), Array(""), $ajaxRes);
						*/
						//echo $ajaxRes.'<br />';
						$tres = explode('src="' , stripslashes($ajaxRes) );
						//print_r($tres);
						if(count($tres) > 1){
							unset($tres[0]);
							foreach($tres as $pos_img){
								$ttres = explode('"' , $pos_img , 2);
								if(count($ttres) > 1){
									$out .= '<img src="'. $ttres[0] .'" /><br />';
								}
							}
						}
					}
				}
			}
		}

		//echo $out;exit;
		// check for DATA-SRC images 
		preg_match_all('/(<img[^<]+>)/Usi', $out, $images);
		$t_image = array();
		if(isset($images[0]) && is_array($images[0]) && count($images[0]) > 0){
	        foreach ($images[0] as $index => $value) {
	        	$check = strpos($value, 'data-src="');
	        	if($check > 0){
	        		$s = $check + 10;
		            $e = strpos($value, '"', $s + 1);
		            $t_image[$value] =   substr($value, $s, $e - $s);
	        	} 
	        }
		}
		//echo '<pre>'.print_r($t_image , 1).'</pre>';exit;
		if(count($t_image) > 0){
			 foreach ($t_image as $index => $value) {
			 	if(substr($value , 0 , 2) == "//"){
			 		$value = 'http:' . $value;
			 	}
			 	$out = str_replace($index , '<img src="' . $value . '" />' , $out);
			 }
		}
		
		//echo $out;exit;
		
		//
		$t_res = explode('<div class="ls-icon rfq">' , $out);
		if(count($t_res) > 1){
			$out = $t_res[0];
		}
		
		//echo $out;exit;
				
	    return $out;
}


function mspro_alibaba_model($html) {
    $res =  explode('Model Number:' , $html);
    if(count($res) > 1){
        $res = explode('<div class="ellipsis" title="' , $res[1] , 2);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0] );
            }
        }
    }
    $res =  explode('>Model Number:' , $html);
    if(count($res) > 1){
        $res = explode('<span class="attr-value" title="' , $res[1] , 2);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0] );
            }
        }
    }
    
    $res =  explode('Model NO.:' , $html);
    if(count($res) > 1){
        $res = explode('<div class="ellipsis" title="' , $res[1] , 2);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0] );
            }
        }
    }
    $res =  explode('>Model NO.:' , $html);
    if(count($res) > 1){
        $res = explode('<span class="attr-value" title="' , $res[1] , 2);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0] );
            }
        }
    }
    
    return '';
}

function mspro_alibaba_sku($html) {
    return mspro_alibaba_model($html);
}


function mspro_alibaba_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       //print_r($res);exit;
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , alibaba_enc($res[0]) );	
       		}
       		 
       }
       return '';
}

function mspro_alibaba_meta_keywords($html){
        $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , alibaba_enc($res[0]) );	
       		}
       		 
       }
       return '';
}

function alibaba_enc($text){
	//return $text;
	return iconv('GBK', 'UTF-8', $text);
} 


function mspro_alibaba_main_image($html){
		$instruction = 'img#bigImage';

        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();

        unset($parser);

        if ($data) {
            $data = reset($data);
            return alibaba_prepare_big_image($data['src']);
        }
        
        // may be img_nav
        $instruction = 'ul.image-nav img';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        if(count($data) > 0 && isset($data[0]) && isset($data[0]['src'])){
        	return alibaba_prepare_big_image($data[0]['src']);
        }elseif(isset($data['src'])){
        	return alibaba_prepare_big_image($data['src']);
        }
		//print_r($data);exit;
        unset($parser);
        
        
        
        // may be img,pic
        $instruction = 'img.pic';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        if(count($data) > 0 && isset($data[0]) && isset($data[0]['src'])){
        	return alibaba_prepare_big_image($data[0]['src']);
        }elseif(isset($data['src'])){
        	return alibaba_prepare_big_image($data['src']);
        }
		//print_r($data);exit;
        unset($parser);
        
        
		$res =  explode('<meta property="og:image" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       
       
       // CHINA VERSION (1688.com) - IMAGES IN DESCRIPTION
        	$instruction = 'li.tab-trigger';
	        $parser = new nokogiri($html);
	        $data = $parser->get($instruction)->toArray();
	        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	   		if(count($data) > 1 && is_array($data) ){
	   			reset($data);
	   			foreach($data as $img){
	   				if(isset($img['data-imgs'])){
	   					$res_t = explode('"original":"' , $img['data-imgs'] , 2);
	   					if(count($res_t) > 1){
	   						$res_t = explode('"' , $res_t[1] , 2);
	   						if(count($res_t) > 1){
	   							return $res_t[0];break;
	   						}
	   					}
	   					
	   				}
	   			}
	   		}	
        
        $instruction = 'a[trace=largepic] img';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    // echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    if(isset($data[0]['src']) && !is_array($data[0]['src']) ){
	    	return $data[0]['src'];
	    }
        
        
        return '';
}

function mspro_alibaba_price($html){
        $res =  explode('id="J-price">' , $html);
        if(count($res) > 1){
            $res = explode('</' , $res[1]);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9,.]/", "",   $res[0] );
                return (float) trim($price);
            }
        
        }
        
        $res =  explode('<meta property="og:product:price" content="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",   $res[0] );
                return (float) trim($price);
            }
        
        }
        
        $res =  explode('"discountPrice":"' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",   $res[0] );
                return (float) trim($price);
            }
        
        }
        
        $res =  explode('<span class="value price-length-6">' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $price = preg_replace("/[^0-9.]/", "",   $res[0] );
                return (float) trim($price);
            }
        
        }
    
		$instruction = 'span[itemprop=highPrice]';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
			$price = preg_replace("/[^0-9,.]/", "",   $data[0]['#text']);
	    	return (float) trim($price);
        }
        
        
        return '';
}


function mspro_alibaba_other_images($html){
		$result = array();
		
		/*$instruction = 'div#richTextDescription img';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        unset($parser);
        if ($data){
        	$i = 1;
        	reset($data);
	        foreach ($data as $value) {
	        	if($i % 2 !== 1){
	            	$result[] = $value['src'];	
	        	}
	        	$i++;
	        }
        }*/
        
        $instruction = 'ul.image-nav img';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
   		if(count($data) > 1 && isset($data[0]) ){
   			reset($data);
   			unset($data[0]);
   			foreach($data as $img){
   				if(isset($img['src'])){
   					$result[] = alibaba_prepare_big_image($img['src']);
   				}	
   			}
        }
        
 
        if(count($result) < 1){
        	$instruction = 'ul.inav li.item img';
	        $parser = new nokogiri($html);
	        $data = $parser->get($instruction)->toArray();
	   		if(count($data) > 1 && isset($data[0]) ){
	   			reset($data);
	   			unset($data[0]);
	   			foreach($data as $img){
	   				if(isset($img['src'])){
	   					$result[] = alibaba_prepare_big_image($img['src']);
	   				}
	   			}
	   		}	
        }
        
        
        // CHINA VERSION (1688.com) - IMAGES IN DESCRIPTION
		if(count($result) < 1){
        	$instruction = 'li.tab-trigger';
	        $parser = new nokogiri($html);
	        $data = $parser->get($instruction)->toArray();
	        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	   		if(count($data) > 1 && is_array($data) ){
	   			reset($data);
	   			unset($data[0]);
	   			foreach($data as $img){
	   				if(isset($img['data-imgs'])){
	   					$res_t = explode('"original":"' , $img['data-imgs'] , 2);
	   					if(count($res_t) > 1){
	   						$res_t = explode('"' , $res_t[1] , 2);
	   						if(count($res_t) > 1){
	   							$result[] = $res_t[0];
	   						}
	   					}
	   					
	   				}
	   			}
	   		}	
        }
        
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}

function mspro_alibaba_options($html){
    $out = array();
    
    $sku_string = explode('var iDetailData =' , $html);
    if(count($sku_string) > 1){
        $sku_string = explode('};' , $sku_string[1] , 2);
        if(count($sku_string) > 1){
            $sku_string = trim($sku_string[0]) . '}';
        }
    }
    $sku_string = @(array) json_decode(alibaba_enc($sku_string) , 1);
    if(is_array($sku_string) && isset($sku_string['sku']['skuProps']) && is_array($sku_string['sku']['skuProps']) && count($sku_string['sku']['skuProps']) > 0){
        foreach($sku_string['sku']['skuProps'] as $pos_option){
            if(isset($pos_option['prop']) && !is_array($pos_option['prop']) && isset($pos_option['value']) && is_array($pos_option['value']) && count($pos_option['value']) > 0){
                $OPTION = array();
                $OPTION['name'] = str_replace( array(":") , array("") , $pos_option['prop']);
                $OPTION['type'] = "select";
                $OPTION['required'] = true;
                $OPTION['values'] = array();
                foreach($pos_option['value'] as $option_value){
                    if(isset($option_value['name']) && !is_array($option_value['name'])){
                        $OPTION['values'][] = array('name' => $option_value['name'] , 'price' => 0);
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

function alibaba_prepare_big_image($src){
        if(substr($src, 0 , 2) == "//"){
            $src = 'http:' . $src;
        }
    	$src = str_replace(array("_250x250.jpg" , "_50x50.jpg") , array("")  , $src);
    	return $src;
}


function mspro_alibaba_noMoreAvailable($html){
	return false;
}
