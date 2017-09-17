<?php


function mspro_wholesale7_title($html){
		$instruction = 'div.top1 h1';
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
        
        $instruction = 'h2';
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

function mspro_wholesale7_description($html){
		$res = '';
		$pq = phpQuery::newDocumentHTML($html);
		
		$temp  = $pq->find('div#LtemSpecifics');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		$temp  = $pq->find('div#ItemSpecifics');
		foreach ($temp as $block){
			$res .= '<div class="LTEM_SPECIFICS">' . $temp->html().'</div><br />';
		}
		
		$temp  = $pq->find('div.showmain');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		$temp  = $pq->find('div#Picture');
		foreach ($temp as $block){
			$res .= $temp->html().'<br />';
		}
		
		// TRYING TO CUT THE CONVERTER BLOCK
		$t_res = explode('<div style="width:100%;">' , $res , 2);
		if(count($t_res) > 1){
			$tt_res = explode('</script>' , $t_res[1]);
			if(count($tt_res) > 1){
				$res = $t_res[0] . $tt_res[1];
			}
		}
		
		// create styles
		$res = str_ireplace(array('<table>') , array('<table style="display: table;">') , $res);
		$styles = '<style>.LTEM_SPECIFICS {border: 1px solid #cacaca;margin-top: 15px;padding-top: 10px;padding-bottom: 10px;} .LTEM_SPECIFICS dd {float: left;padding-left: 13px;width: 196px;height: 22px;line-height: 22x;overflow: hidden;color: #4a4a4a;} .LTEM_SPECIFICS:after {clear: both;display: block;content: ".";}</style>';
		$res = $res . $styles;
		
		
		// ADD ABSOLUTE PATHS TO THE IMAGES IN DESCRIPTION
		preg_match_all('/(<img[^<]+>)/Usi', $res, $images);
		//echo '<pre>'.print_r($images , 1).'</pre>';exit;
		$image = array();
        foreach ($images[0] as $index => $value) {
            if(strpos($value, ' src="') > 0){
                $s = strpos($value, 'src="') + 5;
                $e = strpos($value, '"', $s + 1);
                $image[$value] =   substr($value, $s, $e - $s);
            }else{
                $s = strpos($value, 'data-original="') + 15;
                $e = strpos($value, '"', $s + 1);
                $image[$value] =   substr($value, $s, $e - $s);
            }
        }
        //echo '<pre>'.print_r($image , 1).'</pre>';exit;
        foreach ($image as $old_img_html => $img_src) {
        	if(strpos($img_src , "wholesale7") < 1){
        		$img_src = 'http://www.wholesale7.net/' . $img_src;
        	}
        	$res = str_replace($old_img_html , '<img src="' . $img_src . '" />' , $res);
        }
        
        // remove converter
        $tres = explode('<div class="calc">' , $res , 2);
        if(count($tres) > 1){
            $tt_res = explode('<script' , $tres[1] , 2);
            if(count($tres) > 1){
                $res = $tres[0] . '<script' . $tt_res[1];
            }
        }
        
        //echo $res;exit;
		
		return $res;
}


function mspro_wholesale7_price($html){
	$res = explode('<meta property="product:price:amount" content="' , $html);
        if(count($res) > 1){
        	$res = explode('"' , $res[1] , 2);
        	if(count($res) > 1){
        		return (float) $res[0];
        	} 
        }
        return '';
}


function mspro_wholesale7_sku($html){
		return mspro_wholesale7_model($html); 
}

function mspro_wholesale7_model($html){
		$res = explode('Item No.' , $html);
        if(count($res) > 1){
        	$res = explode('<' , $res[1] , 2);
        	if(count($res) > 1){
        		return trim($res[0]);
        	} 
        }
        return '';
}

function mspro_wholesale7_weight($html){
		$out = array();
		
		$res = explode('Weight:</em><i>' , $html);
        if(count($res) > 1){
        	$res = explode('</i>' , $res[1] , 2);
        	if(count($res) > 1){
        		$weight = $res[0];
        		$out['weight_class_id'] = 2;
        		if(strpos($weight , "kilogram") > 1){$out['weight_class_id'] = 1;}
        		$out['weight'] = (float) preg_replace("/[^0-9,.]/", "",  $weight);;
        	} 
        }
        
        $res = explode('This item weighs <span>' , $html);
        if(count($res) > 1){
            $res = explode('<' , $res[1] , 2);
            if(count($res) > 1){
                $weight = $res[0];
                $out['weight_class_id'] = 2;
                if(strpos($weight , "kilogram") > 1){$out['weight_class_id'] = 1;}
                $out['weight'] = (float) preg_replace("/[^0-9,.]/", "",  $weight);;
            }
        }
        
        return $out;
}


function mspro_wholesale7_meta_description($html){
	   $res =  explode('<meta name="description" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			$out = preg_replace("/[^a-zA-Z0-9_\-\s]/", "" ,  $res[0]); 
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $out);
			}	 
       }
       return '';
}

function mspro_wholesale7_meta_keywords($html){
      $res =  explode('<meta name="keywords" content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return  mspro_wholesale7_meta_description($html , $url);
}


function mspro_wholesale7_main_image($html){
	$imgs_arr = mspro_wholesale7_images_array($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 0){
		return $imgs_arr[0];
	}
	return '';	
}



function mspro_wholesale7_other_images($html){
	$imgs_arr = mspro_wholesale7_images_array($html);
	if(is_array($imgs_arr) && count($imgs_arr) > 1){
		unset($imgs_arr[0]);
		return $imgs_arr;
	}
	return array();
}

function mspro_wholesale7_images_array($html){
	$out = array();
	
	$instruction = 'div#showArea a';
	$parser = new nokogiri($html);
	$data = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($data , 1).'</pre>';exit;
	unset($parser);
	if(isset($data) && is_array($data) && count($data) > 0){
		foreach($data as $pos_image){
			if(isset($pos_image['href']) && !is_array($pos_image['href']) && strpos($pos_image['href'] , 'avascript:void(0') < 1){
				if(strpos($pos_image['href'] , "wholesale7") < 1){
					$out[] = 'http://www.wholesale7.net/' . $pos_image['href'];
				}else{
					$out[] = $pos_image['href'];
				}
			}elseif(isset($pos_image['img'][0]['data-normal']) && !is_array($pos_image['img'][0]['data-normal']) && strpos($pos_image['img'][0]['data-normal'] , 'avascript:void(0') < 1){
			    if(strpos($pos_image['img'][0]['data-normal'] , "wholesale7") < 1){
					$out[] = 'http://www.wholesale7.net/' . $pos_image['img'][0]['data-normal'];
				}else{
					$out[] = $pos_image['img'][0]['data-normal'];
				}
			}elseif(isset($pos_image['img'][0]['src']) && !is_array($pos_image['img'][0]['src']) && strpos($pos_image['img'][0]['src'] , 'avascript:void(0') < 1){
			    if(strpos($pos_image['img'][0]['src'] , "wholesale7") < 1){
					$out[] = 'http://www.wholesale7.net/' . $pos_image['img'][0]['src'];
				}else{
					$out[] = $pos_image['img'][0]['src'];
				}
			}elseif(isset($pos_image['img']['src']) && !is_array($pos_image['img']['src']) && strpos($pos_image['img']['src'] , 'avascript:void(0') < 1){
			    if(strpos($pos_image['img']['src'] , "wholesale7") < 1){
			        $out[] = 'http://www.wholesale7.net/' . $pos_image['img']['src'];
			    }else{
			        $out[] = $pos_image['img']['src'];
			    }
			}
		}
	}
	
	$out = clear_images_array($out);
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	
	return $out;
}

function mspro_wholesale7_options($html){
	$out = array();
	
	$instruction = 'div.top2 p';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if(isset($res) && is_array($res) && count($res) > 0){
		foreach($res as $pos_option){
			if(isset($pos_option['em'][0]['#text']) && isset($pos_option['a']) && is_array($pos_option['a']) && count($pos_option['a']) > 0 ){
				$OPTION = array();
				$OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['em'][0]['#text']) );
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($pos_option['a'] as $option_value){
					if(isset($option_value['span']['#text']) && !is_array($option_value['span']['#text'])){
						$OPTION['values'][] = array('name' => trim($option_value['span']['#text']) , 'price' => 0);
					}
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
			}
		}
	}
	
	$instruction = 'div.showProperty_middle dl';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	unset($parser);
	if(isset($res) && is_array($res) && count($res) > 0){
	    foreach($res as $pos_option){
	        if(isset($pos_option['dt'][0]['#text']) && !is_array($pos_option['dt'][0]['#text']) && isset($pos_option['dd']) && is_array($pos_option['dd']) && count($pos_option['dd']) > 0 ){
	            $OPTION = array();
	            $OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['dt'][0]['#text']) );
	            $OPTION['type'] = "select";
	            $OPTION['required'] = true;
	            $OPTION['values'] = array();
	            foreach($pos_option['dd'] as $option_value){
	                if(isset($option_value['#text']) && !is_array($option_value['#text'])){
	                    $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
	                }elseif(isset($option_value['img']['title']) && !is_array($option_value['img']['title'])){
	                    $OPTION['values'][] = array('name' => trim($option_value['img']['title']) , 'price' => 0);
	                }
	            }
	            if(count($OPTION['values']) > 0){
	                $out[] = $OPTION;
	            }
	        }elseif(isset($pos_option['dt'][0]['#text'][0]) && !is_array($pos_option['dt'][0]['#text'][0]) && isset($pos_option['dd']) && is_array($pos_option['dd']) && count($pos_option['dd']) > 0 ){
	            $OPTION = array();
	            $OPTION['name'] = str_replace( array(":") , array("") , trim($pos_option['dt'][0]['#text'][0]) );
	            $OPTION['type'] = "select";
	            $OPTION['required'] = true;
	            $OPTION['values'] = array();
	            foreach($pos_option['dd'] as $option_value){
	                if(isset($option_value['#text']) && !is_array($option_value['#text'])){
	                    $OPTION['values'][] = array('name' => trim($option_value['#text']) , 'price' => 0);
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
function mspro_wholesale7_noMoreAvailable($html){
	return false;
}
*/
