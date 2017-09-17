<?php


function mspro_amazon_title($html){
		$instruction = 'span#btAsinTitle';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    //echo '<pre>'.print_r($data , 1).'</pre>';
	    unset($parser);
		if (isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text']) && strlen(trim($data[0]['span'][0]['#text'])) > 3  ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][0]['#text']));
        }
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) && strlen(trim($data[0]['#text'])) > 3  ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0]) && strlen(trim($data[0]['#text'][0])) > 3 ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
		if (isset($data[0]['span'][0]['#text'][0]) && !is_array($data[0]['span'][0]['#text'][0]) && strlen(trim($data[0]['span'][0]['#text'][0])) > 5 ) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][0]['#text'][0]));
        }
        
        
		$instruction = 'h1#title';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	    // echo '<pre>'.print_r($data , 1).'</pre>';exit;
	    unset($parser);
 		if (isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][0]['#text']));
        }
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        $instruction = 'span#productTitle';
	    $parser = new nokogiri($html);
	    $data = $parser->get($instruction)->toArray();
	     // echo '<pre>'.print_r($data , 1).'</pre>';exit;
		unset($parser);
 		if (isset($data[0]['span'][0]['#text']) && !is_array($data[0]['span'][0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['span'][0]['#text']));
        }
	    if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text']));
        }
 		if (isset($data[0]['#text'][0]) && !is_array($data[0]['#text'][0])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$data[0]['#text'][0]));
        }
        
        
        return '';
}

function mspro_amazon_description($html){
		$res = '';
		$html = str_ireplace(array( '<td class="lAttr">&nbsp;</td>' , '<td class="lAttr"> </td>' , "&nbsp;", '<span class="caretnext">&#155;</span>') , array("") , $html);
		
		$t_res =  explode('bookDescEncodedData = "' , $html);
       if(count($t_res) > 1){
       		$t_res = explode('",' , $t_res[1]);
       		if(count($t_res) > 1){
       			$res .= urldecode($t_res[0]);	
       		}
       		 
       }
		
		$pq = phpQuery::newDocumentHTML($html);
		$temp  = $pq->find('div#feature-bullets');
		foreach ($temp as $block){
			$res .= utf8_decode($temp->html()).'<br />';
		}
		
		
		$res_t = explode('%3Cdiv%20class%3D%22bucket%22%20id%3D%22productDescription%22%3E' , $html , 2);
		if(count($res_t) > 1){
		    $res_tt = explode('%3Cscript%3E' ,$res_t[1] , 2);
		    if(count($res_tt) > 1){
		        $urlencodedBlock = $res_tt[0];
		    }else{
		        $res_tt = explode('%3C%2Fdiv%3E%0A%20%20%3C%2Fbody%3E' ,$res_t[1] , 2);
		        if(count($res_tt) > 1){
		            $urlencodedBlock = $res_tt[0];
		        }
		    }
		    if(isset($urlencodedBlock)){
		      $res .= utf8_decode(urldecode($urlencodedBlock));
		    }
		}
		//echo $res;exit;
		
		// for books
		$temp  = $pq->find('div#bookDescription_feature_div noscript div');
		foreach ($temp as $block){
		    $book_desc = utf8_decode($temp->html());
		    if(stripos($book_desc , '<') < 1){
		        $res .= $book_desc . '<br />';
		    }
		}
		//echo $res;exit;
		
        $t_res = explode(' <td class="bucket">' , $html);
        if(count($t_res) > 1){
            $t_res = explode('<b>Average' , $t_res[1]);
            if(count($t_res) > 0){
                $block = trim($t_res[0]);
                //echo $block;
                if( stripos($block , "Features") > 0 || stripos($block , "roduct details") > 0){
                    $res .= $block . '</li></ul></div>';
                    $res = str_replace(array('<li></li>') , array(""), $res);
                } 
            }
        }
        //echo $res;exit;
		
		$temp  = $pq->find('div.bucket');
		foreach ($temp as $block){
			$te1 = pq($block)->find('h2');
			$te2 = pq($block)->find('strong');
			//echo pq($te1)->text();
			//echo pq($te2)->text();
			$t = pq($block)->find('div.content');
			$t_info = pq($block)->find('div.buying');
			if(	strpos(pq($te1)->text() , "Specifications") > 0  || strpos(pq($te2)->text() , "Specifications") > 0  ){
				$res .= '<h2>Product Specifications</h2><br />'.pq($t)->html().'<br />';
			}
			if(	strpos(pq($te1)->text() , "ook Description") > 0  || strpos(pq($te2)->text() , "ook Description") > 0  ){
				$res .= '<h2>Book Description</h2><br />'.pq($t_info)->html().'<br />'.pq($t)->html().'<br />';
			}
		}
		//echo $res;exit;
		
		
		
		// http://www.amazon.com/Nixon-A083-000-Stainless-Steel-Analog-Black/dp/B001IX88W0/ref=lp_5777489011_1_4/185-6766368-8608223?s=watches&ie=UTF8&qid=1386084399&sr=1-4
		$temp  = $pq->find('div#technicalSpecifications_feature_div');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		
		
		$temp  = $pq->find('div#productDescription');
		foreach ($temp as $block){
			$res .= utf8_decode($temp->html()).'<br />';
		}
		
		
    	$temp  = $pq->find('div#technical-data');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		
		$temp  = $pq->find('div#dp_productDescription_container_div');
		foreach ($temp as $block){
			$res .= '<h2>Product Description</h2>' . $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div#detailBullets_feature_div ul.a-vertical');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$tt = array();
		$temp  = $pq->find('div.techD div.pdClearfix div.pdTab');
		foreach ($temp as $block){
			$b = $temp->html();
			if( !in_array($b , $tt) ){
				$res .= utf8_decode($b).'<br />';
				$tt[] = $b;
			}
		}
		//echo $res;exit;

		// cut  Date first available at Amazon
		$tt_res = explode('<li>
<b> Date first available at Amazon' , $res);
		if(count($tt_res) > 1){
		    $ttt_res = explode('</li>' , $tt_res[1] , 2);
		    if(count($ttt_res) > 1){
		        $res = $tt_res[0] . $ttt_res[1];
		    }
		}
		$tt_res = explode('<li><b> Date first available at Amazon' , $res);
		if(count($tt_res) > 1){
		    $ttt_res = explode('</li>' , $tt_res[1] , 2);
		    if(count($ttt_res) > 1){
		        $res = $tt_res[0] . $ttt_res[1];
		    }
		}
		$res = str_replace("<li>
</li>", "" , $res);
		
		//echo $res;exit;
		// From the Manufacturer
		$manuf_temp_arr = array();
		$temp  = $pq->find('div#aplusProductDescription div.apm-centerthirdcol');
		foreach ($temp as $block){
		    $manuf_desc = utf8_decode($temp->html());
		    if(!in_array($manuf_desc , $manuf_temp_arr)){
		      $res .= $manuf_desc . '<br />';
		      $manuf_temp_arr[] = $manuf_desc;
		    }
		}
		unset($manuf_temp_arr);
		
		
		/*if(strpos($url , "zon.co.jp/") > 0){
			return enc_jp($res);
		}else{
			return utf8_encode($res);
		}*/
		// delete customer_reviews and SalesRank from table
		$res = str_ireplace( array('<td class="label">') , array("<td>") , $res);
		$res = preg_replace(array("'<tr class=\"average_customer_reviews\"[^>]*?>.*?</tr>'si"), Array(""), $res);
		$res = preg_replace(array("'<tr id=\"SalesRank\"[^>]*?>.*?</tr>'si"), Array(""), $res);
		$res = str_ireplace(array('<a id="seeMoreDetailsLink" class="a-link-normal" href="#productDetails">See more product details</a>' , "&nbsp;" , '<td class="lAttr">&nbsp;</td>' , '<td class="lAttr"> </td>' , 'See more technical details' , '<div class="emptyClear"> </div>') , array("") , $res);
		$res = str_ireplace(array("<noscript>" , "</noscript>" , '<span class="caretnext">?</span>') , array("" , "" , "") , $res);
		$res = preg_replace(array("'<script[^>]*?>.*?</script>'si"), Array(""), $res);
		$res = preg_replace(array("'<div class=\"seeAll\"[^>]*?>.*?</div>'si"), Array(""), $res);
		//echo $res;exit;
		$res = preg_replace(array("'<div id=\"fbExpanderMoreButtonSection\" class=\"a-section aok-hidden\">.*?</span></span></span>'si"), Array(""), $res);
		/*$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?></a>!is", "\\2", $res);
		$res = preg_replace("!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is", "\\2", $res);*/
		$res = preg_replace('#<a.*>.*</a>#USi', '', $res);
		//echo $res;exit;
		/*if(strpos($url , "zon.co.jp/") > 0){
		    return enc_jp($res);
		}else{
		    return utf8_encode($res);
		}*/
		if(substr($res , -6) == '<br />'){
		    $res = substr($res ,0 , -6);
		}
		$res = utf8_encode($res);
		$res = '<div>' . $res . '</div>';
		//echo $res;exit;
		
		return $res;
}

function mspro_enc_jp($text){
    return iconv('SHIFT_JIS', 'UTF-8', $text);
}


function mspro_amazon_price($html){
    $indian = strpos($html , 'amazon.in') > 0?true:false;

    $instruction = 'span#actualPriceValue b';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    $data = reset($data);
    unset($parser);
    if (isset($data['#text'])) return mspro_amazon_clear_price($data['#text'] , $indian);

    $instruction = 'span.olpCondLink span.price';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    $data = reset($data);
    unset($parser);
    if (isset($data[0]['#text'])) return mspro_amazon_clear_price($data[0]['#text'] , $indian);
    if (isset($data['#text'])) return mspro_amazon_clear_price($data['#text'] , $indian);


    $instruction = 'div#priceBlock span.pa_price';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    $data = reset($data);
    unset($parser);
    if (isset($data[0]['#text'])) return mspro_amazon_clear_price($data[0]['#text'] , $indian);
    if (isset($data['#text'])) return mspro_amazon_clear_price($data['#text'] , $indian);

    $instruction = 'span#priceblock_ourprice';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    $data = reset($data);
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ){
        return mspro_amazon_clear_price($data[0]['#text'] , $indian);
    }
    if (isset($data['#text']) && !is_array($data['#text']) ){
        return (float) mspro_amazon_clear_price(trim($data['#text']) , $indian);
    }
    if (isset($data['#text'][0]) && !is_array($data['#text'][0]) ){
        return mspro_amazon_clear_price($data['#text'][0] , $indian);
    }


    $instruction = 'span.a-color-price';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    $data = reset($data);
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    if (isset($data['#text']) && !is_array($data['#text']) ){
        return (float) mspro_amazon_clear_price(trim($data['#text']) , $indian);
    }
    if (isset($data['#text']) && is_array($data['#text']) && isset($data['#text'][1]) && !is_array($data['#text'][1]) ){
        return (float) mspro_amazon_clear_price(trim($data['#text'][1]) , $indian);
    }
    if (isset($data['#text'][0]) && !is_array($data['#text'][0]) ){
        return (float) mspro_amazon_clear_price(trim($data['#text'][0]) , $indian);
    }


    $instruction = 'span.price';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    $data = reset($data);
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ){
        return mspro_amazon_clear_price($data[0]['#text'] , $indian);
    }
    if (isset($data['#text']) && !is_array($data['#text']) ){
        return (float) mspro_amazon_clear_price(trim($data['#text']) , $indian);
    }


    $instruction = 'b.priceLarge';
    $parser = new nokogiri($html);
    $data = $parser->get($instruction)->toArray();
    $data = reset($data);
    //echo '<pre>'.print_r($data , 1).'</pre>';exit;
    unset($parser);
    if (isset($data[0]['#text']) && !is_array($data[0]['#text']) ){
        return mspro_amazon_clear_price($data[0]['#text'] , $indian);
    }
    if (isset($data['#text']) && !is_array($data['#text']) ){
        return (float) mspro_amazon_clear_price(trim($data['#text']) , $indian);
    }

    return '';
}

function mspro_amazon_clear_price($price , $indian){
	$price = preg_replace("/[^0-9,.]/", "",  $price);
	if( (strpos($price , ",") > 0 && strpos($price , ".") > 0) || $indian == true){
	    $price = str_replace("," , "" , $price);
	}else{
	    $price = str_replace("," , "." , $price);
	}
	//$price = str_replace("" , "" , $price);
	return (float)  $price;
}

function mspro_amazon_sku($html){
		return mspro_amazon_model($html);
}

function mspro_amazon_model($html){
	$res = explode('<b>Item model number:</b>' , $html , 2);
	if(count($res) > 1){
		$res = explode('</li>' ,$res[1] , 2);
		if(count($res) > 1){
			return trim($res[0]); 
		}
	}
	$res = explode('Item model number</td><td class="value">' , $html , 2);
	if(count($res) > 1){
		$res = explode('</td>' ,$res[1] , 2);
		if(count($res) > 1){
			return trim($res[0]); 
		}
	}
	$res = explode('Item model number:' , $html , 2);
	if(count($res) > 1){
	    $res = explode('</li>' ,$res[1] , 2);
	    if(count($res) > 1){
	        return trim(strip_tags(trim($res[0])));
	    }
	}
	$res = explode('Item model number' , $html , 2);
	if(count($res) > 1){
	    $res = explode('</td>' ,$res[1] , 2);
	    if(count($res) > 1){
	        return trim(strip_tags(trim($res[0])));
	    }
	}
	$res = explode('ISBN-13:</b>' , $html , 2);
	if(count($res) > 1){
		$res = explode('</' ,$res[1] , 2);
		if(count($res) > 1){
			return trim($res[0]); 
		}
	}
	$res = explode('Manufacturer Part Number</td><td class="value">' , $html , 2);
	if(count($res) > 1){
	    $res = explode('</' ,$res[1] , 2);
	    if(count($res) > 1){
	        return trim($res[0]);
	    }
	}
	return '';
}

function mspro_amazon_manufacturer($html){
    $res = explode('field-lbr_brands_browse-bin=' , $html , 2);
    if(count($res) > 1){
        $res = explode('"' ,$res[1] , 2);
        if(count($res) > 1){
            return str_ireplace("+" , " " , trim($res[0]) );
        }
    }
    return '';
}


function mspro_amazon_weight($html){
        $out = array();
		$res = explode('Boxed-product Weight:</b>' , $html);
        if(count($res) > 1){
        	$res = explode('<' , $res[1] , 2);
        	if(count($res) > 1){
        		$weight = $res[0];
        		$out['weight_class_id'] = 2;
        		if(strpos($weight , "kilogram") > 1 || strpos($weight , "Kg") > 1){$out['weight_class_id'] = 1;}
        		$out['weight'] = (float) preg_replace("/[^0-9,.]/", "",  $weight);
        	} 
        }
        return $out;
}

function mspro_amazon_dimensions($html){
    $out = array();
    $res = explode('Product Dimensions:' , $html);
        if(count($res) > 1){
        	$res = explode('</li>' , $res[1] , 2);
        	if(count($res) > 1){
        		$dims = str_replace("</b>" , "" , $res[0]);
        		$t_res = explode(";" , $dims);
        		if(count($t_res) > 1){ $dims = $t_res[0]; }
        		$tt_res = explode("x" , $dims);
        		if(count($tt_res) > 2){
        		    $out['length'] = (float) preg_replace("/[^0-9,.]/", "", $tt_res[0]);
        		    $out['width'] = (float) preg_replace("/[^0-9,.]/", "",  $tt_res[1]);
        		    $out['height'] = (float) preg_replace("/[^0-9,.]/", "", $tt_res[2]);
        		}
        		$out['length_class_id'] = 1;
        		if(strpos($dims , "mm") > 1 ){$out['length_class_id'] = 2; }
        		if(strpos($dims , "inch") > 1 ){$out['length_class_id'] = 3; }
        	} 
        }
    return $out;
}


function mspro_amazon_meta_description($html){
	    $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       				return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
			}	 
       }
       return '';
}

function mspro_amazon_meta_keywords($html){
        $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return  mspro_amazon_meta_description($html);
}


function mspro_amazon_main_image($html){
		
		// сразу проверяем вариант когда только 1 главное изображение через data:image/jpeg;base64,
		$instruction = 'img#imgBlkFront';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
		if (isset($res[0]['src']) && !is_array($res[0]['src']) ) {
	    	return $res[0]['src'];
        }
        
        // проверка на ОЧЕНЬ большое изображение
        $instruction = 'img#landingImage';
        $parser = new nokogiri($html);
        $res = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
        unset($parser);
        if (isset($res[0]['data-old-hires']) && !is_array($res[0]['data-old-hires']) && strlen(trim($res[0]['data-old-hires'])) > 0 ) {
            return $res[0]['data-old-hires'];
        }elseif(isset($res[0]['data-a-dynamic-image']) && !is_array($res[0]['data-a-dynamic-image']) && strlen(trim($res[0]['data-a-dynamic-image'])) > 0){
            $img_arr = (array) json_decode($res[0]['data-a-dynamic-image'] , 1);
            $checking_size = 40;
            if(is_array($img_arr) && count($img_arr) > 0){
                foreach($img_arr as $key => $value){
                    if(isset($value[0]) && (int) $value[0] > $checking_size){
                        $checking_size = $value[0];
                        $checking = $key;
                    }
                }
                if(isset($checking)){
                    return $checking;
                }
            }
        }
	    
	
		$instruction = 'td#prodImageCell a img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['src']) && !is_array($res[0]['src']) ) {
	    	$main_image = trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$res[0]['src']));
	    	return mspro_amazon_try_get_bigger($main_image);
        }
	
		$instruction = 'div.thumbs-bottom img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['src']) &&  !is_array($res[0]['src']) ) {
	    	$main_image = trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$res[0]['src']));
	    	return mspro_amazon_try_get_bigger($main_image);
        }
        
        // another variation 
		$instruction = 'img#main-image';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['src']) &&  !is_array($res[0]['src']) ) {
	    	$main_image = trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$res[0]['src']));
	    	return mspro_amazon_try_get_bigger($main_image);
        }
        
        
		// another variation
	    $res = explode('"large":"' , $html);
	    if(count($res) > 1){
	    	unset($res[0]);
	       	foreach($res as $img){
	       		$res_t = explode('"' , $img , 2);
	       		if(count($res_t) > 1){
	       			return mspro_amazon_try_get_bigger($res_t[0]);break;
	       		}
	       	}
	    }
  
        // another variation
        $res = explode('<img src="http://ecx.images-amazon.com/images/', $html ); 
        if(count($res) > 1){
        	$res = $res[1];
        	$res = explode('"' , $res);
        	return mspro_amazon_try_get_bigger("http://ecx.images-amazon.com/images/".$res[0]);
        }
        
		$instruction = 'div.kib-ma-container img';
	    $parser = new nokogiri($html);
	    $res = $parser->get($instruction)->toArray();
	    unset($parser);
	    if (isset($res[0]['src']) &&  !is_array($res[0]['src']) ) {
	    	$main_image = trim(str_replace(array("&nbsp;" , "&amp;") , array(" " , "`" ) ,$res[0]['src']));
	    	return mspro_amazon_try_get_bigger($main_image);
        }
        
        return false;
}


function mspro_amazon_other_images($html , $url , $color_images = false){
		$out = array();
		
		// GET COLOR IMAGES
		if($color_images == true){
    		$res = explode('data["colorImages"] = ' , $html);
    		if(count($res) > 1){
    		    $res = explode(';' , $res[1]);
    		    if(count($res) > 1){
    		        $res = (array) json_decode($res[0] , 1);
    		        //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    		        if (isset($res) && is_array($res) && count($res) > 0) {
    		            foreach($res as $key => $oth_imgs){
    		                if(is_array($oth_imgs) && count($oth_imgs) > 0){
    		                    foreach($oth_imgs as $pos_imgs){
    		                        if(isset($pos_imgs['hiRes']) && !is_array($pos_imgs['hiRes']) && strlen($pos_imgs['hiRes']) > 5){
    		                            $out[] = $pos_imgs['hiRes'];
    		                        }elseif(isset($pos_imgs['large']) && !is_array($pos_imgs['large']) && strlen($pos_imgs['large']) > 5){
    		                            $out[] = $pos_imgs['large'];
    		                        }
    		                    }
    		                }
    		            }
    		        }
    		        //echo '<pre>'.print_r(array_unique($res) , 1).'</pre>';exit;
    		    }
    		}
		}
			
			// another variation
			$instruction = 'div.tiny a[target=AmazonHelp]';
		    $parser = new nokogiri($html);
		    $res = $parser->get($instruction)->toArray();
		    unset($parser);
		    //print_r($res);exit;
		    if (isset($res[0]['href'])) {
		    	$ch = curl_init ($res[0]['href']); 
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
				$images_html = curl_exec ($ch);
				
				$temp = explode('fetchImage("alt' , $images_html);
				if(count($temp) > 1){
					foreach($temp as $t_key => $t_val){
						if($t_key > 0){
							$tt_temp= explode('" )' , $t_val);
							if(count($tt_temp) > 1){
								$ttt_temp = explode('", "' , $tt_temp[0]);
								if(count($ttt_temp) > 1){
									$out[] = mspro_amazon_try_get_bigger($ttt_temp[1]);
								}
							}
						}
					}
				}
		    	
	        }
			if(count($out) > 0){
	        	return array_unique($out);
	        }
	        
	      
	
			$instruction = 'div.thumbs-bottom img';
		    $parser = new nokogiri($html);
		    $res = $parser->get($instruction)->toArray();
		    unset($parser);
		    if (isset($res[0]['src'])) {
		    	foreach($res as $oth_imgs){
		    		$out[] = mspro_amazon_try_get_bigger($oth_imgs['src']);
		    	}
	        }
	        if(count($out) > 0){
	        	return array_unique($out);
	        }
	        
	        // another variation
			$instruction = 'div.thumb-strip img';
		    $parser = new nokogiri($html);
		    $res = $parser->get($instruction)->toArray();
		    unset($parser);
		    if (isset($res[0]['src'])) {
		    	foreach($res as $oth_imgs){
		    		$out[] = mspro_amazon_try_get_bigger($oth_imgs['src']);
		    	}
	        }
			if(count($out) > 0){
	        	return array_unique($out);
	        }
	        
			$res = explode("setRgAg('" , $html);
			unset($res[0]);
			if(count($res) > 0){
				foreach($res as $peace){
					if(substr($peace , 0 , 4) == "http"){
						$tech = explode("'" , $peace);
						if(count($tech) > 1){
							$out[] = mspro_amazon_try_get_bigger($tech[0]);
						}
					}
				}
			}
			if(count($out) > 0){
	        	return array_unique($out);
	        }
	        
	        
	        
	        // another variation
			$instruction = '.thumb-strip img';
		    $parser = new nokogiri($html);
		    $res = $parser->get($instruction)->toArray();
		    unset($parser);
		    if (isset($res[0]['src'])) {
		    	foreach($res as $oth_imgs){
		    		$out[] = mspro_amazon_try_get_bigger($oth_imgs['src']);
		    	}
	        }
			if(count($out) > 0){
	        	return array_unique($out);
	        }
	        
	        
	        $res = explode('var colorImages = ' , $html);
	        if(count($res) > 1){
	            $res = explode(';' , $res[1]);
	            if(count($res) > 1){
	                $res = (array) json_decode($res[0] , 1);
	                if (isset($res['initial']) && is_array($res['initial']) && count($res['initial']) > 0) {
	                    foreach($res['initial'] as $oth_imgs){
	                        if(isset($oth_imgs['hiRes']) && !is_array($oth_imgs['hiRes']) && strlen($oth_imgs['hiRes']) > 5){
	                            $out[] = $oth_imgs['hiRes'];
	                        }elseif(isset($oth_imgs['large']) && !is_array($oth_imgs['large']) && strlen($oth_imgs['large']) > 5){
	                            $out[] = $oth_imgs['large'];
	                        }elseif(isset($oth_imgs['landing'][0]) && !is_array($oth_imgs['landing'][0]) && strlen($oth_imgs['landing'][0]) > 5){
	                            $out[] = $oth_imgs['landing'][0];
	                        }
	                    }
	                }
	                //echo '<pre>'.print_r(array_unique($res) , 1).'</pre>';exit;
	            }
	        }
	        if(count($out) > 0){
	            return array_unique($out);
	        }
	        
	        
	        // now PARSE SEPARATELY "large":" AND "hiRes":"
	        // another variation
            // TRY TO CUT THE MANY IMAGES FROM COLOR BLOCK
	        $t_res = explode('data["colorImages"] = ' , $html);
	        if(count($t_res) > 1){
	            $tt_res = explode('};' , $t_res[1] , 2);
	            if(count($tt_res) > 1){
	                $colorImagesBlock = $tt_res[0] . '}';
	                @$colorImagesBlock = (array) json_decode($colorImagesBlock , 1);
	                if(isset($colorImagesBlock) && is_array($colorImagesBlock) && count($colorImagesBlock) < 8){
	                    foreach($colorImagesBlock as $key => $pos_imgs){
	                        if(isset($pos_imgs[0]['hiRes']) && !is_array($pos_imgs[0]['hiRes']) && strlen($pos_imgs[0]['hiRes']) > 0){
	                            $out[] = $pos_imgs[0]['hiRes'];
	                        }
	                    }
	                }else{
	                    // remove this block from HTML
	                    $html = $t_res[0] . $tt_res[1];
	                }
	                //echo '<pre>' . print_r($colorImagesBlock , 1) . '</pre>';exit;
	            }
	        }
	        $res = explode('"large":"' , $html);
	        if(count($res) > 1){
	            unset($res[0]);
	            foreach($res as $img){
	                $res_t = explode('"' , $img , 2);
	                if(count($res_t) > 1){
	                    $out[] = $res_t[0];
	                }
	            }
	        }
	        //echo '<pre>'.print_r(array_unique($out) , 1).'</pre>';exit;
	        if(count($out) > 0){
	            return array_unique($out);
	        }
	        
	        
	        // another variation
	        $res = explode('"hiRes":"' , $html);
	        if(count($res) > 1){
	        	unset($res[0]);
	        	foreach($res as $img){
	        		$res_t = explode('"' , $img , 2);
	        		if(count($res_t) > 1){
	        			$out[] = $res_t[0];
	        		}
	        	}
	        }
	        if(count($out) > 0){
	            return array_unique($out);
	        }
	        
	        
			// another variation
	        $res = explode('registerImage(' , $html);
	        if(count($res) > 1){
	        	unset($res[0]);
	        	foreach($res as $img){
	        		$res_t = explode('"' , $img);
	        		if(count($res_t) > 3){
	        			// echo $res_t[3];
	        			if(strpos($res_t[3] , 'ttp') > 0){
	        				$out[] = $res_t[3];
	        			}
	        		}
	        	}
	        }
	        
	        
	        // another variation
	        $instruction = 'div#thumbs-image img';
	        $parser = new nokogiri($html);
	        $res = $parser->get($instruction)->toArray();
	        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	        if (isset($res) && is_array($res) && count($res) > 0){
	            foreach($res as $oth_imgs){
	                $out[] = mspro_amazon_try_get_bigger($oth_imgs['src']);
	            }
	        }
	         
	        // another variation
	        $instruction = 'div.kib-ma-container img';
	        $parser = new nokogiri($html);
	        $res = $parser->get($instruction)->toArray();
	        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
	        if (isset($res) && is_array($res) && count($res) > 0){
	            foreach($res as $oth_imgs){
	                $out[] = mspro_amazon_try_get_bigger($oth_imgs['src']);
	            }
	        }
	        
	       // echo '<pre>'.print_r(array_unique($out) , 1).'</pre>';exit;
	        $out = clear_images_array($out);
	        return $out;
}

 function mspro_amazon_try_get_bigger($src){
 	$res = explode("." , $src);
 	$out = array();
 	foreach($res as $part){
 		if(substr($part , 0 , 1) !== "_" && substr($part , -1) !== "_"){
 			$out[] = $part;
 		}
 	}
 	return implode("." , $out);
 }
 
 
 function mspro_amazon_options($html){
     $out = array();

     // SIZES
    $instruction = 'select#native_dropdown_selected_size_name option';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if(isset($res) && is_array($res) && count($res) > 0){
        $OPTION = array();
        $OPTION['name'] = trim(str_replace( array(":") , array("") , "Size"));
        $OPTION['type'] = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        foreach($res as $option_value){
            if(isset($option_value['#text']) && !is_array($option_value['#text']) && strlen(trim($option_value['#text'])) > 0 && isset($option_value['class']) && $option_value['class'] == "dropdownAvailable" ){
                $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
            }
        }
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
        }
    }
    
    // COLORS
    $colors_option_exists = false;
    $instruction = 'select#native_dropdown_selected_color_name option';
    $parser = new nokogiri($html);
    $res = $parser->get($instruction)->toArray();
    //echo '<pre>'.print_r($res , 1).'</pre>';exit;
    unset($parser);
    if(isset($res) && is_array($res) && count($res) > 0){
        $OPTION = array();
        $OPTION['name'] = trim(str_replace( array(":") , array("") , "Color"));
        $OPTION['type'] = "select";
        $OPTION['required'] = true;
        $OPTION['values'] = array();
        foreach($res as $option_value){
            if(isset($option_value['#text']) && !is_array($option_value['#text']) && strlen(trim($option_value['#text'])) > 0 && isset($option_value['class']) && $option_value['class'] == "dropdownAvailable" ){
                $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
            }
        }
        if(count($OPTION['values']) > 0){
            $out[] = $OPTION;
            $colors_option_exists = true;
        }
    }
    
    if(!$colors_option_exists){
        $res = explode('data["colorImages"] = ' , $html);
        if(count($res) > 1){
            $res = explode(';' , $res[1]);
            if(count($res) > 1){
                $res = (array) json_decode($res[0] , 1);
                //echo '<pre>'.print_r($res , 1).'</pre>';exit;
                if (isset($res) && is_array($res) && count($res) > 0) {
                    $OPTION = array();
                    $OPTION['name'] = trim(str_replace( array(":") , array("") , "Option"));
                    $OPTION['type'] = "select";
                    $OPTION['required'] = true;
                    $OPTION['values'] = array();
                    foreach($res as $key => $color_data){
                        $OPTION['values'][] = array('name' => $key , 'price' => 0);
                    }
                    if(count($OPTION['values']) > 0){
                        $out[] = $OPTION;
                        $colors_option_exists = true;
                    }
                }
                //echo '<pre>'.print_r(array_unique($res) , 1).'</pre>';exit;
            }
        }
    }
    
    //echo '<pre>'.print_r($out , 1).'</pre>';exit;
    return $out;
    
 }
 


function mspro_amazon_noMoreAvailable($html){
    if(stripos($html , '>Currently unavailable.') > 0){
        return true;
    }
	return false;
}
