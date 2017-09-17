<?php


function merge_custom_markets($possible_markets , $cmarkets = false){
	if($cmarkets && is_array($cmarkets) && count($cmarkets) > 0){
			foreach($cmarkets as $cmarket){
				$merge = false;
				foreach($possible_markets as $key =>  $possible_market){
					// если находим такой маркет в своём конфиге, то сливаем значения url_aliases и fields
					if($possible_market['name'] == $cmarket['name']){
						foreach($cmarket['url_aliases'] as $custom_url_alias){
							$possible_markets[$key]['url_aliases'][] = $custom_url_alias;
						}
						foreach($cmarket['fields'] as $custom_field){
							$possible_markets[$key]['fields'][] = $custom_field;
						}
						$possible_markets[$key]['url_aliases']= array_unique($possible_markets[$key]['url_aliases']);
						$possible_markets[$key]['fields'] = array_unique($possible_markets[$key]['fields']);
						$merge = true;
					}
				}
				// если не находим то просто добавляем
				if(!$merge){
					$possible_markets[] = $cmarket;
				}
			}
	}
	return $possible_markets;
}