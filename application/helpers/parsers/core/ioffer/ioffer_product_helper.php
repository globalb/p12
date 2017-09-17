<?php


function mspro_ioffer_title($html){
	$instruction = 'h1.item-title';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data['#text']));
        }
 		
        return '';
}

function mspro_ioffer_description($html){
	   $res = '';
	   	    
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div#item-description');
		foreach ($temp as $block){
				$res .= pq($block)->html();
				break;
		}
		
	    return strip_tags ($res , '<div><p><table><br>') ;
}


function mspro_ioffer_price($html){
	    $instruction = 'div.buy-now-price span.converted-price';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
        if(isset($data[0]['#text']) && !is_array($data[0]['#text']) ){
            $price = str_ireplace(array(",") , array(".") , $data[0]['#text'] );
            $price = preg_replace("/[^0-9.]/", "", $price);
            return (float) $price;
        }
        
		return ''; 
}


function mspro_ioffer_sku($html){
        $res =  explode('<input id="item_id" name="item_id" type="hidden" value="' , $html);
        if(count($res) > 1){
            $res = explode('"' , $res[1]);
            if(count($res) > 1){
                return trim(str_ireplace(array("SKU") , array(" ") , $res[0]) );
            }
        
        }
        return '';
}

function mspro_ioffer_model($html){
	return mspro_ioffer_sku($html);
}


function mspro_ioffer_meta_description($html){
	   $res =  explode('<meta content="' , $html);
       if(count($res) > 2){
       		$res = explode('"' , $res[3] , 2);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_ioffer_meta_keywords($html){
      $res =  explode('<meta content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[2] , 2);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_ioffer_main_image($html){
	   $instruction = 'div.main-image img';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);

        if ($data) {
            $data = reset($data);
            return mspro_ioffer_prepare_image($data['src']);
        }
        
        
        return '';
}


function mspro_ioffer_other_images($html){
	   $result = array();
        
        $instruction = 'div.other-images ul li';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
   		if(count($data) > 1 && isset($data[0]) ){
   			reset($data);
   			unset($data[0]);
   			foreach($data as $img){
   				if(isset($img['data-src'])){
   					$result[] = mspro_ioffer_prepare_image($img['data-src']);
   				}	
   			}
        }

        return $result;
}

function mspro_ioffer_prepare_image($src){
    return str_replace(array("cdn104") , array("cdn102")  , $src);
}

function mspro_ioffer_options($html){
    $out = array();

    $instruction = 'div.specifics-color select option';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 1 && isset($res[0]['#text']) &&  !is_array($res[0]['#text']) && strlen($res[0]['#text']) > 0){
        $OPTION = array();
        $OPTION['name'] = str_replace( array(":") , array("") , trim(utf8_decode($res[0]['#text'])) );
        unset($res[0]);
        $OPTION['type'] = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        $values_exists = array();
        foreach($res as $option_value){
            if(isset($option_value['#text']) && !is_array($option_value['#text']) && strlen($option_value['#text']) && isset($option_value['value']) && strlen(trim($option_value['value'])) > 0 && !in_array(utf8_decode($option_value['#text']) , $values_exists) ){
                $OPTION['values'][] = array('name' => trim(utf8_decode($option_value['#text'])) , 'price' => 0);
                $values_exists[] = utf8_decode($option_value['#text']);
            }
        }
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
        }
    }
    
    $instruction = 'div.specifics-size select option';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if (is_array($res) && count($res) > 1 && isset($res[0]['#text']) &&  !is_array($res[0]['#text']) && strlen($res[0]['#text']) > 0){
        $OPTION = array();
        $OPTION['name'] = str_replace( array(":") , array("") , trim(utf8_decode($res[0]['#text'])) );
        unset($res[0]);
        $OPTION['type'] = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        $values_exists = array();
        foreach($res as $option_value){
            if(isset($option_value['#text']) && !is_array($option_value['#text']) && strlen($option_value['#text']) && isset($option_value['value']) && strlen(trim($option_value['value'])) > 0 && !in_array(utf8_decode($option_value['#text']) , $values_exists) ){
                $OPTION['values'][] = array('name' => trim(utf8_decode($option_value['#text'])) , 'price' => 0);
                $values_exists[] = utf8_decode($option_value['#text']);
            }
        }
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
        }
    }
    
    
    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
    
}


/*function mspro_ioffer_noMoreAvailable($html){
	return false;
}*/
