<?php


function parse_category($html  , $task){
		$out = array();
		$out['products'] = parse_products($html , $task);
		$out['next_page'] = parse_next_page($html , $task);
		return $out;
}


function parse_products($html , $task){
		$result = array();
		 
		$link = 'ul.item-box-wrapper li div.placard a';
        $parser = new nokogiri($html);
        $links = $parser->get($link)->toArray();
        //echo '<pre>'.print_r($links , 1).'</pre>';exit;
        unset($parser);
        foreach ($links as $value) {
            if(isset($value['href']) && !is_array($value['href']) && strlen(trim($value['href'])) > 0 && stripos($value['href'] , "ascript:void(") < 1){
                $result[] = $value['href'];
            }
           
        }
        
        $result = array_unique($result);
        //echo '<pre>'.print_r($result , 1).'</pre>';exit;

        return $result;
}


function parse_next_page($html , $task){
        $t_res = explode('&page=' , $task['url']);
        if(count($t_res) < 2){
            return $task['url'] . '&page=2';
        }else{
            $nextNumber = (int) ( (int)$t_res[1] + 1 );
            return $t_res[0] . '&page=' . $nextNumber;
        }

        return false;
}