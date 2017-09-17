<?php

function mspro_flipkart_getUrl($url) {
    $out = array('html' => false, 'data' => false);
    $initHtml = getUrl($url);
    $out['html'] = $initHtml;
    $itemId = explode('<link rel="canonical" href="' , $initHtml);
    if(count($itemId) > 1){
        $itemId = explode('/p/' , $itemId[1] , 2);
        if(count($itemId) > 1){
            $itemId = explode('"' , $itemId[1] , 2);
            if(count($itemId) > 1){
                $itemId = $itemId[0];
                $t_itemId = explode('?' , $itemId , 2);
                if(count($t_itemId) > 1){
                    $itemId = $t_itemId[0];
                }
                $postData = array('requestContext' => array('itemId' => $itemId, 'sessionContext' => array('pids' => array('HIPE5SV8EMVXYSDH' , 'CAMDF4FHEHKYNSHY') )));
                $postData = array('requestContext' => array('itemId' => $itemId));
                //$postData = array('requestContext' => array('itemId' => $itemId, 'sessionContext' => array() ));
                //$postData = array('requestContext' => array('itemId' => $itemId));
                //$postData = array('itemId' => $itemId, 'sessionContext' => array('pids' => array('HIPE5SV8EMVXYSDH' , 'CAMDF4FHEHKYNSHY') ));
                //$postData = array('itemId' => $itemId);
                //$postData = array('itemId' => $itemId, 'sessionContext' => array('pids' => array() ));
                $postData = json_encode($postData);
                echo '<pre>'.var_dump($postData , 1).'</pre>';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.flipkart.com/api/3/page/dynamic/product');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                //curl_setopt($ch , CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_COOKIE, 'AMCVS_17EB401053DAF4840A490D4C%40AdobeOrg=1; AMCV_17EB401053DAF4840A490D4C%40AdobeOrg=-227196251%7CMCIDTS%7C17033%7CMCMID%7C91495323037027273132382196665614450062%7CMCAAMLH-1471958978%7C6%7CMCAAMB-1472209916%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1471612316s%7CNONE%7CMCAID%7CNONE; s_sq=%5B%5BB%5D%5D; gpv_pn=CameraAccessory%3ATamron%20AF%2070%20-%20300%20mm%20F%2F4-5.6%20Di%20LD%20Macro%201%3A2%20for%20Nikon%20Digital%20SLR%20%20Lens; gpv_pn_t=Product%20Page; s_cc=true; S=d1t18P2NTUz8/P2QqHC5NTQoVfZj+zgQe3BxIxFPYnEh5f+ij2jYWs6ebfNVzwzhdPUVU/ZHzbR8zC8OOEwnibjFlTg==; VID=2.VI551C9DC93B70476299DE96A649C96925.1471618550.VS6FFFB0E7C76A4A7F904833B13D425082; NSID=2.SI219884FB5E88487AB84748B4D41D90BD.1471618550.VI551C9DC93B70476299DE96A649C96925; JSESSIONID=15tg70o3nygastliiet3cniib; T=TI147135418091095901488156900328732728366256615866015820411553326509; SN=2.VI551C9DC93B70476299DE96A649C96925.SI219884FB5E88487AB84748B4D41D90BD.VS6FFFB0E7C76A4A7F904833B13D425082.1471618551; atl=atlx_v4');
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36s');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($postData)
                    )
                );
                 
                $result = curl_exec($ch);
                $info = curl_getinfo($ch);
                echo '<pre>'.var_dump($info).'</pre>';
                if($result){
                    $result = (array) json_decode($result , 1);
                }
                echo '<pre>'.print_r($result , 1).'</pre>';exit;
                
            }
        }
    }
    echo $initHtml;
    echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
}


function mspro_flipkart_title($html){
        $out = '';
        
		$instruction = 'h1[itemprop=name]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;	
	    unset($parser);
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	$out .= trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }elseif(isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	    	$out .= trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }else{
            $instruction = 'h1';
            $parser = new nokogiri($html);
            $data = $parser->get($instruction)->toArray();
            //echo '<pre>'.print_r($data , 1).'</pre>';exit;
            unset($parser);
            if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
                $out .= trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
            }elseif(isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
                $out .= trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
            }
        }
        
        $instruction = 'span.subtitle';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
            $out .= ' ' . trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }elseif(isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
            $out .= ' ' . trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
    	return $out;
}

function mspro_flipkart_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
			
		$temp  = $pq->find('div#description');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div#keyFeatures');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div#specifications');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div.description');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
        $temp  = $pq->find('div.productSpecs');
		$css = '.specTable{width:100%;font-size:13px;margin:0 0 30px 0}.specTable td,.specTable th{padding:6px;vertical-align:top;text-align:left}.specTable .groupHead{background-color:#f2f2f2;font-weight:bold;text-transform:uppercase}.specTable .specsKey{width:25%;border-bottom:1px dotted #c9c9c9;border-right:1px solid #c9c9c9}.specTable .specsValue{border-bottom:1px dotted #c9c9c9;border-left:1px solid #c9c9c9}.specTable td:only-child{border-left:none;border-right:0}.keyFeaturesList{list-style-type:disc;padding-left:20px}';
		$css = '<style type="text/css">'.$css.'</style>';
		$i = 0;
		foreach ($temp as $block){
			$res .= $temp->html() . '<br />';
			$i++;
		}
		if($i > 0){
		    $res .= $css;
		}
	
		return $res;
}


function mspro_flipkart_price($html){
		$instruction = 'meta[itemprop=price]';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';exit;	
	    unset($parser);
	    if (isset($data[0]['content']) && !is_array($data[0]['content'])) {
	    	$price = preg_replace("/[^0-9.]/", "",  trim($data[0]['content']) );
	    	return (float) $price;
        }
        
        $instruction = 'span.selling-price';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
            $price = str_replace(array("Rs.") , array("") , trim($data[0]['#text']) );
            $price = str_replace(array(",") , array(".") , $price );
            $price = preg_replace("/[^0-9.]/", "",  $price );
            return (float) $price;
        }
        
        $instruction = 'span#exchangePrice';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
            $price = str_replace(array(",") , array(".") , trim($data[0]['#text']) );
            $price = preg_replace("/[^0-9.]/", "",  $price );
            return (float) $price;
        }
        
		return '';
}


function mspro_flipkart_meta_description($html){
	   $res =  explode('<meta name="Description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_flipkart_meta_keywords($html){
       $res =  explode('<meta name="Keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return mspro_flipkart_meta_description($html);
}

function mspro_flipkart_model($html){
    $res = explode('<td class="specsKey">Model Name</td>' , $html);
    if(count($res) > 1){
        $res = explode('</td>' , $res[1]);
        if(count($res) > 1){
            $out = strip_tags($res[0]);
            return trim($out);
        }
    }
    $res = explode('<td class="specsKey">ISBN-13</td>' , $html);
    if(count($res) > 1){
        $res = explode('</td>' , $res[1]);
        if(count($res) > 1){
            $out = strip_tags($res[0]);
            return trim($out);
        }
    }
    return mspro_flipkart_sku($html);
}

function mspro_flipkart_sku($html){
    $res = explode('&sku[0]=' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            $out = strip_tags($res[0]);
            return trim($out);
        }
    }
    return '';
}

function mspro_flipkart_manufacturer($html){
    $res =  explode('"brand": "' , $html);
    if(count($res) > 1){
        $res = explode('"' , $res[1]);
        if(count($res) > 1){
            return trim( $res[0] );
        }
    }
    return '';
}


function mspro_flipkart_main_image($html){
		$instruction = 'div.image-wrapper img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;	
	    unset($parser);
		if (isset($res[0]['src'])) {
	    	$main_image = trim($res[0]['src']);
	    	return $main_image;
        }
		 if (isset($res[0]['data-zoom-src']) && !empty($res[0]['data-zoom-src'])) {
	    	$main_image = trim($res[0]['data-zoom-src']);
	    	return $main_image;
        }
        
        $instruction = 'div#mprodimg-id img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;	
	    unset($parser);
		if (isset($res[0]['data-src'])) {
	    	$main_image = trim($res[0]['data-src']);
	    	return $main_image;
        }
        
         $instruction = 'div.imgWrapper img';
		 $parser = new nokogiri($html);
		 $res = $parser->get($instruction)->toArray();
		 //echo '<pre>'.print_r($res , 1).'</pre>';
		 if (isset($res[0]['data-zoomimage']) && !is_array($res[0]['data-zoomimage']) ) {
		   	$main_image = trim($res[0]['data-zoomimage']);
		 }elseif (isset($res[0]['data-src']) && !is_array($res[0]['data-src']) ) {
		   	$main_image = trim($res[0]['data-src']);
		 }
		 return $main_image;

}


function mspro_flipkart_other_images($html){
		$out = array();
			$instruction = 'div#multi-images img';
		    $parser = new nokogiri($html);
		    $res = $parser->get($instruction)->toArray();
		    //echo '<pre>'.print_r($res , 1).'</pre>';	
		    unset($parser);
			if (isset($res[0]['src'])) {
		    	foreach($res as $oth_imgs){
		    		if(isset($oth_imgs['src']) && strpos($oth_imgs['src'] , 'ajax-loader') < 1){
		    			$out[] = flipkart_try_get_bigger($oth_imgs['src']);
		    		}
		    	}
	        }
			if (isset($res[0]['data-carousel-src'])) {
		    	foreach($res as $oth_imgs){
		    		if(isset($oth_imgs['data-carousel-src']) && strpos($oth_imgs['data-carousel-src'] , 'ajax-loader') < 1){
		    			$out[] = flipkart_try_get_bigger($oth_imgs['data-carousel-src']);
		    		}
		    	}
	        }
	        
	        
	        $instruction = 'div.imgWrapper img';
		    $parser = new nokogiri($html);
		    $res = $parser->get($instruction)->toArray();
		    //echo '<pre>'.print_r($res , 1).'</pre>';
		    unset($parser);
		    if (isset($res) && is_array($res) && count($res) > 1) {
		    	unset($res[0]);
		    	foreach($res as $pos_img){
		    		if(isset($pos_img['data-zoomimage']) && !is_array($pos_img['data-zoomimage'])  ){
		    			$out[] = $pos_img['data-zoomimage'];
		    		}elseif(isset($pos_img['data-src']) && !is_array($pos_img['data-src'])  ){
		    			$out[] = $pos_img['data-src'];
		    		}
		    	}
		    }	
	        
		    $out = clear_images_array($out);
		    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	        return $out;
}


 function flipkart_try_get_bigger($src){
 	return str_replace(array("100x100") , array("400x400") , $src);
 }
 
 
 function mspro_flipkart_options($html){
     $out = array();
 
    $instruction = 'div.multiSelectionWidget';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if (is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
		    // COLOR OPTION
			if(isset($pos_option['div'][0]['#text']) &&
			   !is_array($pos_option['div'][0]['#text']) &&
			   strlen(trim($pos_option['div'][0]['#text'])) > 0 &&
			   isset($pos_option['div'][1]['div'][0]['title']) &&
			   !is_array($pos_option['div'][1]['div'][0]['title']) &&
			   strlen(trim($pos_option['div'][1]['div'][0]['title'])) > 0 ){
        				$OPTION = array();
        				$OPTION['name'] = str_replace( array(":" , "Select") , array("" , "") , trim($pos_option['div'][0]['#text']) );
        				$OPTION['type'] = "select";
        				$OPTION['required'] = true;
        				$OPTION['values'] = array();
        				$pos_options = array();
        				$pos_options[] = $pos_option['div'][1]['div'][0]['title'];
        				if(isset($pos_option['div'][1]['a']) && is_array($pos_option['div'][1]['a']) && count($pos_option['div'][1]['a']) > 0){
        				    foreach($pos_option['div'][1]['a'] as $pos_adiitional_option){
        				        if(isset($pos_adiitional_option['div'][0]['title']) && !is_array($pos_adiitional_option['div'][0]['title']) && strlen(trim($pos_adiitional_option['div'][0]['title'])) > 0){
        				            $pos_options[] = $pos_adiitional_option['div'][0]['title'];
        				        }
        				    }
        				}
        				foreach($pos_options as $option_value){
        					$OPTION['values'][] = array('name' => $option_value , 'price' => 0);
        				}
        				if(count($OPTION['values']) > 0){
        					$out[] = $OPTION;
        				}
			}elseif(isset($pos_option['div'][0]['#text']) &&
    			   !is_array($pos_option['div'][0]['#text']) &&
    			   strlen(trim($pos_option['div'][0]['#text'])) > 0 &&
    			   isset($pos_option['div'][1]['div'][0]['div'][0]['data-selectorvalue']) &&
    			   !is_array($pos_option['div'][1]['div'][0]['div'][0]['data-selectorvalue']) &&
    			   strlen(trim($pos_option['div'][1]['div'][0]['div'][0]['data-selectorvalue'])) > 0 ){
            				$OPTION = array();
            				$OPTION['name'] = str_replace( array(":" , "Select" , "&nbsp;" , "\n" , "\r") , array("" , "" , "" , "" , "") , trim($pos_option['div'][0]['#text']) );
            				$OPTION['type'] = "select";
            				$OPTION['required'] = true;
            				$OPTION['values'] = array();
            				$pos_options = array();
            				$pos_options[] = $pos_option['div'][1]['div'][0]['div'][0]['data-selectorvalue'];
            				if(isset($pos_option['div'][1]['a']) && is_array($pos_option['div'][1]['a']) && count($pos_option['div'][1]['a']) > 0){
            				    foreach($pos_option['div'][1]['a'] as $pos_adiitional_option){
            				        if(isset($pos_adiitional_option['div'][0]['div'][0]['data-selectorvalue']) && !is_array($pos_adiitional_option['div'][0]['div'][0]['data-selectorvalue']) && strlen(trim($pos_adiitional_option['div'][0]['div'][0]['data-selectorvalue'])) > 0){
            				            $pos_options[] = $pos_adiitional_option['div'][0]['div'][0]['data-selectorvalue'];
            				        }
            				    }
            				}
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
 
 
 function mspro_flipkart_noMoreAvailable($html){
	if(strpos($html , "out-of-stock-status") > 0){
		return true;
	}
	return false;
}
 
