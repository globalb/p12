<?php


function mspro_sunsky_title($html){
        $instruction = 'title';
        $parser = new nokogiri($html);
        $data = $parser->get($instruction)->toArray();
        //echo '<pre>'.print_r($data , 1).'</pre>';exit;
        unset($parser);
	    if (isset($data['#text']) && !is_array($data['#text'])) {
	    	return trim(str_replace(array("&nbsp;" , "&amp;" , 'SUNSKY - ') , array(" " , "`" , "") ,$data['#text']));
        }
 		if (isset($data[0]['#text']) && !is_array($data[0]['#text'])) {
 			//echo $data[0]['#text'];exit;
	    	return trim(str_replace(array("&nbsp;" , "&amp;" , "SUNSKY - ") , array(" " , "`"  , "") ,$data[0]['#text']));
        }
        
        return '';
}

function mspro_sunsky_description($html){
		$out = '';
		
		$res = explode('id=overview>' , $html , 2);
		if(count($res) > 1){
			$res = explode('<DIV style="clear:both">' , $res[1] , 2);
			if(count($res) > 1){
				$out .= '<div>' . $res[0] . '</div>';
			}
		}
		//echo $out;exit;
		
		$res = explode('<H3>More Pictures</H3>' , $html , 2);
		if(count($res) > 1){
		    $res = explode('<DIV style="clear:both">' , $res[1] , 2);
		    if(count($res) > 1){
		        $res = explode('<IMG src="' , $res[0]);
		        if(isset($res) && is_array($res) && count($res) > 1){
		            unset($res[0]);
		            foreach($res as $pos_image){
		                $t_res = explode('"' , $pos_image , 2);
		                if(count($t_res) > 1){
		                    $out .= '<img src="' . $t_res[0] . '" /><br />';
		                }
		            }
		        }
		    }
		}
		
		// remove TPO OF THE PAGE button
		$t_res = explode('<DIV style="float:right;">' , $out);
		if(count($t_res) > 1){
		    $tt_res = explode('</DIV>' , $t_res[1] , 2);
		    if(count($tt_res) > 1){
		        $out = $t_res[0] . $tt_res[1];
		    }
		}
		
        return $out;
}


function mspro_sunsky_price($html){
		$res = explode('SPAN class="bold red">$' , $html , 2);
    	if(count($res) > 1){
    		$res = explode('</SPAN>' ,$res[1] , 2);
    		if(count($res) > 1){
    			return (float) trim($res[0]); 
    		}
    	}
    	return '';
}


function mspro_sunsky_sku($html){
	    $res = explode('Item #: <B>' , $html);
        if(count($res) > 1){
        	$res = explode('</B>' , $res[1] , 2);
        	if(count($res) > 1){
        		return trim($res[0]);
        	}
        }
		return '';
}

function mspro_sunsky_model($html){
		return mspro_sunsky_sku($html);
}


function mspro_sunsky_meta_description($html){
	   $res =  explode('<META name=description content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}

function mspro_sunsky_meta_keywords($html){
       $res =  explode('<META name=keywords content="' , $html);
       if(count($res) > 1){
       		$res = explode('"' , $res[1]);
       		if(count($res) > 1){
       			return str_replace(array("&nbsp;" , "&amp;") , array(" " , "`") , $res[0]);	
       		}
       		 
       }
       return '';
}


function mspro_sunsky_main_image($html){
		$arr = sunsky_get_images($html);
		if(isset($arr[0]) && strlen($arr[0]) > 0){
			return $arr[0];
		}
		return '';
}



function mspro_sunsky_other_images($html){
		$arr = sunsky_get_images($html);
		if(count($arr) > 1){
			unset($arr[0]);
			return $arr;
		}
		return array();
}


function sunsky_get_images($html){
		$out = array();
		
		
		
		$res = explode('<IMG id=mainImg src="' , $html);
		if(is_array($res) && count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$res_t = explode('"' , $block , 2);
				if(is_array($res_t) && count($res_t) > 0){
					$out[] = $res_t[0];
				}
			}
		}
			

		$res = explode('<IMG src="http://img.sunsky-online.com/upload/store/detail_l/' , $html);
		if(is_array($res) && count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$res_t = explode('"' , $block , 2);
				if(is_array($res_t) && count($res_t) > 0){
					$out[] = 'http://img.sunsky-online.com/upload/store/detail_l/'.$res_t[0];
				}
			}
		}
		
		$res = explode('<IMG width="150" height="150" src="' , $html);
		if(is_array($res) && count($res) > 1){
			unset($res[0]);
			foreach($res as $block){
				$res_t = explode('"' , $block , 2);
				if(is_array($res_t) && count($res_t) > 0){
					$out[] = $res_t[0];
				}
			}
		}
		
		$out = array_unique($out);
		//echo '<pre>'.print_r($out , 1).'</pre>';exit;
		
		return $out;
}


/*
function mspro_sunsky_options($html){
	$out = array();
	
	$opt_arr = array();
	$instruction = 'input#productAttributesJson';
	$parser = new nokogiri($html);
	$res = $parser->get($instruction)->toArray();
	//echo '<pre>'.print_r($res , 1).'</pre>';exit;
	if(isset($res[0]['value']) && !is_array($res[0]['value']) ){
		@$res_t = json_decode($res[0]['value']);
		if (isset($res_t) && is_array($res_t) && count($res_t) > 0){
			foreach($res_t as $pos_opt){
				if(isset($pos_opt->name) && isset($pos_opt->value) && isset($pos_opt->soldOut) && $pos_opt->soldOut < 1 && isset($pos_opt->supc) ){
					$temp = array('value' => $pos_opt->value , 'supc' => $pos_opt->supc);
					if(!array_key_exists($pos_opt->name, $opt_arr)){
						$opt_arr[$pos_opt->name] = array();
					}
					$opt_arr[$pos_opt->name][] = $temp;
				}
			}
		}
	}
	//echo '<pre>'.print_r($opt_arr , 1).'</pre>';exit;
	unset($parser);
	if (is_array($opt_arr) && count($opt_arr) > 0){
		$catid = sunsky_get_catid($html);
		$originalPrice = mspro_sunsky_price($html);
		foreach($opt_arr as $opt_name => $option_values){
				$OPTION = array();
				$OPTION['name'] = $opt_name;
				$OPTION['type'] = "select";
				$OPTION['required'] = true;
				$OPTION['values'] = array();
				foreach($option_values as $option_value){
					$OPTION['values'][] = array('name' => $option_value['value'] , 'price' => sunsky_get_option_price($option_value['supc'] , $catid , $originalPrice) );
				}
				if(count($OPTION['values']) > 0){
					$out[] = $OPTION;
				}
		}
	}
	//echo '<pre>'.print_r($out , 1).'</pre>';exit;
	return $out;
}
*/


/*
function mspro_sunsky_noMoreAvailable($html){
	return false;
}
*/
