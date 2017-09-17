<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function cms_init(){
	$version = 1;
	if(LOCAL_INSTALLATION_SEMAFOR > 0){
	    $t = file_get_contents("../opencart/index.php");
	}else{
	    $t = file_get_contents("../index.php");
	}
	$res = explode("'VERSION', '" , $t , 2);
	if(count($res) > 1){
		$res = explode("'" , $res[1] , 2);
		if(count($res) > 1){
			$version = (int) substr($res[0] , 0 , 1);
		}
	}
	@define('CMS_VERSION', $version);
	@define('CMS_VERSION_NUMBER', (int) str_ireplace("." , "" , $res[0]));
	//echo CMS_VERSION_NUMBER;exit;
	$ci =& get_instance(); 
	$ci->load->database();
	$ci->db->query("SET NAMES utf8");
	return true;
}

function cms_getImagesLocation(){
	$res = '';
	switch(CMS_VERSION){
		case 1:
			$res = "image/data";
			break;
		case 2:
			$res = "image/catalog";
			break;
		default:
			$res = "image/data";
			break;
	}
	return $res;
}
	
/*
 *  список категорий
 */
function cms_getCategories(){
	/*
	// THE OLD ONE
	$loader->load->model('catalog/category');
	return $loader->model_catalog_category->getCategories(0);
	*/
	
	$out = array();
	$langs = cms_getLanguages();
	$lang = array_shift( $langs );
	$ci =& get_instance(); 
	$ci->load->database();
	if ($ci->db->table_exists(DB_PREFIX . "category_path")){
        $q = "SELECT cp.category_id AS category_id,
							 GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order
							  FROM " . DB_PREFIX . "category_path cp 
							  LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) 
							  LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) 
							  LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) 
							  WHERE cd1.language_id = '" . (int) $lang['language_id'] . "' 
							  AND cd2.language_id = '" . (int) $lang['language_id'] . "' 
							  GROUP BY cp.category_id ORDER BY name";
	}else{
		$q = "SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
				LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
				WHERE cd.language_id = '" . (int) $lang['language_id'] . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)";
	}
	//echo $query;exit;
	$query = $ci->db->query($q);
	if (null !== $query->num_rows() &&  $query->num_rows() > 0){
		 foreach ($query->result() as $row){
		 	$out[] = (array) $row;
		 }
	 }
	 //echo '<pre>' . print_r($out , 1) . '</pre>';exit;
	 return $out;
}

/*
 *   список производителей
 */
function cms_getManufacturers(){
	/*
	// THE OLD ONE
	$loader->load->model('catalog/manufacturer');
	return $loader->model_catalog_manufacturer->getmanufacturers();
	*/
	
	$out = array();
	$ci =& get_instance(); 
	$ci->load->database();
	$query = $ci->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER BY name");
	if (null !== $query->num_rows() && $query->num_rows() > 0){
		 foreach ($query->result() as $row){
		 	$out[] = (array) $row;
		 }
	 }
	//echo '<pre>' . print_r($out , 1) . '</pre>';exit;
	return $out;
}


/*
 *   список tax classes
 */
function cms_getTaxClasses(){
    /*
     // THE OLD ONE
     $loader->load->model('catalog/manufacturer');
     return $loader->model_catalog_manufacturer->getmanufacturers();
     */

    $out = array();
    $ci =& get_instance();
    $ci->load->database();
    $query = $ci->db->query("SELECT * FROM " . DB_PREFIX . "tax_class ORDER BY date_added");
    if ($query->num_rows() > 0){
        foreach ($query->result() as $row){
            $out[] = (array) $row;
        }
    }
    //echo '<pre>' . print_r($out , 1) . '</pre>';exit;
    return $out;
}


/*
 * список валют
 */
function cms_getCurrencies(){
	/*
	// THE OLD ONE
	$loader->load->model('localisation/currency');
	return $loader->model_localisation_currency->getCurrencies();
	*/
	
	$out = array();
	$ci =& get_instance(); 
	$ci->load->database();
	$query = $ci->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");
	if ($query->num_rows() > 0){
		 foreach ($query->result() as $row){
		 	$out[$row->code] = (array) $row;
		 }
	 }
	//echo '<pre>' . print_r($out , 1) . '</pre>';exit;
	return $out;
}


/*
 * список валют для process
 */
function cms_getCurrenciesArray(){
	$currency = array();
	
	/*
	// THE OLD ONE
    $loader->load->model('localisation/currency');
    $_currency = $loader->model_localisation_currency->getCurrencies();
    foreach ($_currency as $item) {
    	$currency[$item['currency_id']] = $item;
    }
    //echo '<pre>' . print_r($currency , 1) . '</pre>';exit;
    return $currency;
     */
    
    
	$ci =& get_instance(); 
	$ci->load->database();
	$query = $ci->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");
	if ($query->num_rows() > 0){
		 foreach ($query->result() as $row){
		 	$currency[$row->currency_id] = (array) $row;
		 }
	 }
	//echo '<pre>' . print_r($currency , 1) . '</pre>';exit;
	return $currency;
}

/*
 * список языков
 */
function cms_getLanguages(){
	/*if (!defined("LANG_HOST_DIR")){ 
		define("LANG_HOST_DIR",'../admin/');
	}
	if(!@require_once("../admin/config.php")){
		require_once(LANG_HOST_DIR . "config.php");
	}*/
	$ci =& get_instance(); 
	$ci->load->database();
	$languages=array();
	$query = $ci->db->query("SELECT * FROM " .DB_PREFIX ."language");
	foreach($query->result() as $row){
		$languages[$row->code] = (array) $row;
	}
	
	//echo '<pre>' . print_r($languages , 1) . '</pre>';exit;
	return $languages;
}




function cms_emptyProduct() {
        $product_description = array(
            'name' => '',
            'seo_h1' => '',
            'seo_title' => '',
            'meta_keyword' => '',
            'meta_description' => '',
            'description' => ''
        );
        $product_tag = array('');
        $product_image = array(
            'image' => '',
            'sort_order' => ''
        );
        $product_reward = array(
            6 => array('points' => ''),
            8 => array('points' => '')
        );

        $product = array(
            'product_description' => $product_description,
            'product_tag' => $product_tag,
            'model' => '',
            'sku' => '',
            'upc' => '',
        'ean' => '',
        'jan' => '',
        'isbn' => '',
        'mpn' => '',
            'location' => '',
            'price' => '',
            'tax_class_id' => '0',
            'quantity' => '10',
            'minimum' => '1',
            'subtract' => '1',
            'stock_status_id' => '7',
            'shipping' => '1',
            'keyword' => '',
            'image' => '',
            'date_available' => date("Y-m-d"),
            'length' => '',
            'width' => '',
            'height' => '',
            'length_class_id' => '1',
            'weight' => '',
            'weight_class_id' => '1',
            'status' => '1',
            'sort_order' => '1',
            'manufacturer_id' => '0',
            'main_category_id' => '0',
            'product_store' => array('0'),
            'related' => '',
            'option' => '',
            'points' => '',
            'product_reward' => $product_reward,
            'product_layout' => array(0 => array('layout_id' => ''))
        );
        return $product;
}

/*
 * @param $images_dir - directory from the CMS root
 */

function cms_saveImage($images_dir , $translit_name , $url ,  $id = -1 , $descriptionImage = false){
    
        $local_add = '';
        if(LOCAL_INSTALLATION_SEMAFOR > 0){
            $local_add = 'opencart/';
        }
	
        if (!is_dir('../' . $local_add . $images_dir)) {
            $res = @mkdir('../' . $local_add . $images_dir, 0755, true);
            if (!$res){
            	return '';
            } 
        }
        
        if(substr($url , 0 , 2) == '//'){
        	$url = 'http:' . $url;
        }
        $info = pathinfo($url);
        
		$mime = false;
		if(strpos($url , "ta:image") < 1){
       		try{
		    	@$mime = getimagesize($url);
			}catch (Exception $e) {}
		}
		//var_dump($mime);exit;
        if ($mime) {
            switch ($mime['mime']) {
                case 'image/jpeg':
                case 'image/pjpeg':
                    $ext = 'jpeg'; break;
                case 'image/jpg':
                    $ext = 'jpg'; break;
                case 'image/png':
                    $ext = 'png'; break;
                case 'image/gif':
                    $ext = 'gif'; break;
                case 'image/tiff':
                    $ext = 'tiff'; break;
                default:
                    $ext = $info['extension'];
            }
        }else{
        	$ext = 'jpg';
        	if(strpos($url , ".png") > 0){
        		$ext = 'png';
        	}
        	if(strpos($url , ".gif") > 0){
        		$ext = 'gif';
        	}
       		if(strpos($url , ".tiff") > 0){
        		$ext = 'tiff';
        	}
        }

        if ($ext) {
            $file = $translit_name . "." . $ext;
            if ($id >= 0){
            	$file = $translit_name . "-" . $id . "." . $ext;
            }
            if($descriptionImage){
            	$file = $translit_name . "-descriptionImage" . $id . "." . $ext;
            }
            
            
			if(strpos($url , "ta:image") > 0){
				$url = substr($url,strpos($url,",")+1);
  				file_put_contents('../' . $local_add . $images_dir . '/' . $file, base64_decode(str_replace(' ','+',$url)));
			}else{
            	$im = getUrl($url, false, false, true);
            	//echo $url . '-' . $file . '-' . $im;exit;
            	file_put_contents('../' . $local_add . $images_dir . '/' . $file , $im);
			}
			
            if($descriptionImage){
            	return '' . $images_dir . '/' . $file;
            }
			
            // для бд имя сохраняется с data/
            $to_database_location = explode('/' , $images_dir , 2);
            $to_database_location = $to_database_location[1];

            return $to_database_location.'/'.$file;
        }else{
        	return 'no_image.jpg';
        }
}


function cms_insertProduct($product){
	 /*$loader->load->model('catalog/product');
     $loader->model_catalog_product->addProduct($product);*/
     // echo '<pre>'.print_r($product , 1).'</pre>';
    
     $ci =& get_instance(); 
	 $ci->load->database();
	 
	 
	 // check for date_modified field
	 $sql_ins = '';$upc_ins = '';$ean_ins = '';$jan_ins = '';$isbn_ins = '';$mpn_ins = '';
	 $fields = $ci->db->list_fields(DB_PREFIX . "product");
	 if(in_array("date_modified" , $fields)){$sql_ins = ', date_modified = NOW()';}	 
	 if(in_array('upc' , $fields)){$upc_ins = 'upc = ' . $ci->db->escape($product['upc']) . ', ';}
	 if(in_array('ean' , $fields)){$ean_ins = 'ean = ' . $ci->db->escape($product['ean']) . ', ';}
	 if(in_array('jan' , $fields)){$jan_ins = 'jan = ' . $ci->db->escape($product['jan']) . ', ';}
	 if(in_array('isbn' , $fields)){$isbn_ins = 'isbn = ' . $ci->db->escape($product['isbn']) . ', ';}
	 if(in_array('mpn' , $fields)){$mpn_ins = 'mpn = ' . $ci->db->escape($product['mpn']) . ', ';}
	 
	 // RPODUCT
	 $q = "INSERT INTO " . DB_PREFIX . "product SET 
     						model = " . $ci->db->escape($product['model']) . ", 
     						sku = " . $ci->db->escape($product['sku']) . ", 
     						$upc_ins 
     						$ean_ins
     						$jan_ins
     						$isbn_ins
     						$mpn_ins
     						location = " . $ci->db->escape($product['location']) . ", 
     						quantity = '" . (int) $product['quantity'] . "', 
     						minimum = '" . (int) $product['minimum'] . "', 
     						subtract = '" . (int) $product['subtract'] . "', 
     						stock_status_id = '" . (int) $product['stock_status_id'] . "', 
     						date_available = " . $ci->db->escape($product['date_available']) . ", 
     						manufacturer_id = '" . (int) $product['manufacturer_id'] . "', 
     						shipping = '" . (int) $product['shipping'] . "', 
     						price = '" . (float) $product['price'] . "', 
     						points = '" . (int) $product['points'] . "', 
     						weight = '" . (float) $product['weight'] . "', 
     						weight_class_id = '" . (int)$product['weight_class_id'] . "', 
     						length = '" . (float) $product['length'] . "', 
     						width = '" . (float) $product['width'] . "', 
     						height = '" . (float) $product['height'] . "', 
     						length_class_id = '" . (int) $product['length_class_id'] . "', 
     						status = '" . (int) $product['status'] . "', 
     						tax_class_id = " . $ci->db->escape($product['tax_class_id']) . ", 
     						sort_order = '" . (int) $product['sort_order'] . "', date_added = NOW() " . $sql_ins;
	 //echo $q;
     $ci->db->query($q);
     $product_id = $ci->db->insert_id();
     
	if (isset($product['image'])) {
		$ci->db->query("UPDATE " . DB_PREFIX . "product 
									SET image = " . $ci->db->escape(html_entity_decode( $product['image'], ENT_QUOTES, 'UTF-8' ) ) . "  
									WHERE product_id = '" . (int) $product_id . "'");
	}
	
	// PRODUCT DESCRIPTION
	// check for meta_title field for OC 2.0
	$product_description_fields = $ci->db->list_fields(DB_PREFIX . 'product_description');
	foreach ($product['product_description'] as $language_id => $value) {
		$meta_title_sql = '';
		if( in_array("meta_title" , $product_description_fields) && isset($value['meta_title']) ){
			$meta_title_sql = "meta_title = " . $ci->db->escape( $value['meta_title'] ) . ",";
		}
		$tag_ins = "";
		if( in_array("tag" , $product_description_fields) && isset($value['tag'])){
		    $tag_ins = 'tag = "' . $ci->db->escape( $value['tag'] ) . '", ';
		}
		//echo "VAL_DESC_RAW : " . $value['description'] . "<br />";
		//echo "VAL_DESC_RAW : " . $ci->db->escape( $value['description'] ) . "<br />";
		$q = "INSERT INTO " . DB_PREFIX . "product_description 
							SET product_id = '" . (int) $product_id . "', 
							language_id = '" . (int) $language_id . "', 
							name = " .  $ci->db->escape( $value['name'] ) . ", 
							meta_keyword = " . $ci->db->escape( $value['meta_keyword'] ) . ", 
							meta_description = " . $ci->db->escape( $value['meta_description'] ) . ",
							" . $meta_title_sql . " 
							" . $tag_ins . "
							description = " . $ci->db->escape( $value['description'] )  . " ";
		//echo $q;
		$ci->db->query($q);
	}
	//exit;
	
	// PRODUCT STORES
	if (isset($product['product_store'])) {
		foreach ($product['product_store'] as $store_id) {
			$ci->db->query("INSERT INTO " . DB_PREFIX . "product_to_store 
						 	SET product_id = '" . (int)$product_id . "', 
						 	store_id = '" . (int) $store_id . "'");
		}
	}
	
	// OPTIONS
	if (isset($product['product_option']) && is_array($product['product_option']) && count($product['product_option']) > 0) {
		foreach ($product['product_option'] as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				if (isset($product_option['product_option_value']) && count($product_option['product_option_value']) > 0 ) {
					$ci->db->query("INSERT INTO " . DB_PREFIX . "product_option 
										SET product_id = '" . (int)$product_id . "',
										 option_id = '" . (int)$product_option['option_id'] . "',
					                       value = '',
										  required = '" . (int)$product_option['required'] . "'");
					$product_option_id = $ci->db->insert_id();
					foreach ($product_option['product_option_value'] as $product_option_value) {
						$ci->db->query("INSERT INTO " . DB_PREFIX . "product_option_value 
										SET product_option_id = '" . (int)$product_option_id . "',
										 product_id = '" . (int)$product_id . "',
										 option_id = '" . (int)$product_option['option_id'] . "',
										 option_value_id = '" . (int)$product_option_value['option_value_id'] . "',
										 quantity = '" . (int)$product_option_value['quantity'] . "',
										 subtract = '" . (int)$product_option_value['subtract'] . "',
										 price = '" . (float)$product_option_value['price'] . "',
										 price_prefix = " . $ci->db->escape($product_option_value['price_prefix']) . ",
										 points = '" . (int)$product_option_value['points'] . "',
										 points_prefix = " . $ci->db->escape($product_option_value['points_prefix']) . ",
										 weight = '" . (float)$product_option_value['weight'] . "',
										 weight_prefix = " . $ci->db->escape($product_option_value['weight_prefix']) . "");
					} 
				}
			}
		}
	}
	
	// ATTRIBUTES
	//echo '<pre>'.print_r($product['product_attribute'] , 1).'</pre>';exit;
	if (isset($product['product_attribute']) && is_array($product['product_attribute']) && count($product['product_attribute']) > 0) {
	    foreach ($product['product_attribute'] as $product_attribute) {
	        if ($product_attribute['attribute_id']) {
	            foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
	                $ci->db->query("INSERT INTO " . DB_PREFIX . "product_attribute 
	                                   SET product_id = '" . (int)$product_id . "',
	                                   attribute_id = '" . (int)$product_attribute['attribute_id'] . "',
	                                   language_id = '" . (int)$language_id . "',
	                                   text = " .  $ci->db->escape($product_attribute_description['text']) . "");
	            }
	        }
	    }
	}
	
	
	// PRODUCT IMAGE
	if (isset($product['product_image'])) {
		foreach ($product['product_image'] as $product_image) {
			$ci->db->query("INSERT INTO " . DB_PREFIX . "product_image 
							SET product_id = '" . (int) $product_id . "', 
							image = " . $ci->db->escape( html_entity_decode( $product_image['image'], ENT_QUOTES, 'UTF-8' ) ) . ", 
							sort_order = '" . (int) $product_image['sort_order'] . "'");
		}
	}
	
	// PRODUCT CATEGORY
	if (isset($product['product_category'])) {
		foreach ($product['product_category'] as $category_id) {
			$ci->db->query("INSERT INTO " . DB_PREFIX . "product_to_category 
							SET product_id = '" . (int) $product_id . "', 
							category_id = '" . (int) $category_id . "'");
		}
	}
	
	// PRODUCT REWARD
	if (isset($product['product_reward'])) {
		foreach ($product['product_reward'] as $customer_group_id => $product_reward) {
			$ci->db->query("INSERT INTO " . DB_PREFIX . "product_reward 
							SET product_id = '" . (int) $product_id . "', 
							customer_group_id = '" . (int) $customer_group_id . "', 
							points = '" . (int) $product_reward['points'] . "'");
		}
	}
	
	// PRODUCT LAYOUT
	if (isset($product['product_layout'])) {
		foreach ($product['product_layout'] as $store_id => $layout) {
			if ($layout['layout_id']) {
				$ci->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout 
							SET product_id = '" . (int)$product_id . "', 
							store_id = '" . (int) $store_id . "', 
							layout_id = '" . (int) $layout['layout_id'] . "'");
			}
		}
	}
	
	// PRODUCT KEYWORD
	if ($product['keyword'] || defined('IS_MIJOSHOP')) {
		$ci->db->query("INSERT INTO " . DB_PREFIX . "url_alias 
							SET query = 'product_id=" . (int) $product_id . "', 
							keyword = " . $ci->db->escape( $product['keyword'] ) . " ");
	}
	
	// CHECK FOR TABS
	/*if( $ci->db->table_exists(DB_PREFIX . "product_tab") && $ci->db->table_exists(DB_PREFIX . "product_to_tab") ){
	    $tabs = $ci->db->query("SELECT `tab_id` FROM  " . DB_PREFIX . "product_tab");
	    if ($tabs->num_rows() > 0){
		  foreach ($tabs->result() as $row){
		 	$ci->db->query("INSERT INTO " . DB_PREFIX . "product_to_tab SET `product_id` = " . (int) $product_id . ", `tab_id` = " . $row->tab_id . ", `status` = 1 ");
		 }
	   }
	}*/
	
	
	return $product_id;
	
}


function cms_updateProduct($product , $product_id , $ins){
	//echo '<pre>' . print_r($product , 1) . '</pre>';exit;
	
	 // update price only if instruction does not have "donot_update_price" setting enabled
	 $priceSQL = '';
	 if($ins['donot_update_price'] < 1){
	 	$priceSQL = "price = '" . (float)$product['price'] . "', ";
	 }
	 // update manufacturer only if instruction does not have "do_not_update_manufacturer" setting enabled
	 $manufacturerSQL = '';
	 if($ins['do_not_update_manufacturer'] < 1){
	     $manufacturerSQL = "manufacturer_id = '" . (int)$product['manufacturer_id'] . "', ";
	 }
	 // update Tax class only if instruction does not have "do_not_update_taxclass" setting enabled
	 $taxclassSQL = '';
	 if($ins['do_not_update_taxclass'] < 1){
	     $taxclassSQL = "tax_class_id = '" . (int)$product['tax_class_id'] . "', ";
	 }
	 
	 
	 //$loader->load->model('catalog/product');
	 $ci =& get_instance(); 
	 $ci->load->database();
	 $ci->db->query("UPDATE " . DB_PREFIX . "product SET 
	 											" . $priceSQL . "
	                                            " . $manufacturerSQL . "
	                                            " . $taxclassSQL . "
	                                            quantity = '" . (int)$product['quantity'] . "'
	 									  WHERE product_id = '" . (int)$product_id . "'");
	 
	// UPDATE OPTIONS
	if($ins['do_not_update_options'] < 1){
		$ci->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$ci->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
	}
	if (isset($product['product_option'])) {
	    //echo '<pre>' . print_r($product['product_option'] , 1) . '</pre>';exit;
		foreach ($product['product_option'] as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				if (isset($product_option['product_option_value']) && count($product_option['product_option_value']) > 0 ) {
					$ci->db->query("INSERT INTO " . DB_PREFIX . "product_option 
										SET product_id = '" . (int)$product_id . "',
										 option_id = '" . (int)$product_option['option_id'] . "',
					                      value = '',
										  required = '" . (int)$product_option['required'] . "'");
					$product_option_id = $ci->db->insert_id();
					//echo $product_option_id;exit;
					foreach ($product_option['product_option_value'] as $product_option_value) {
						$ci->db->query("INSERT INTO " . DB_PREFIX . "product_option_value 
										SET product_option_id = '" . (int)$product_option_id . "',
										 product_id = '" . (int)$product_id . "',
										 option_id = '" . (int)$product_option['option_id'] . "',
										 option_value_id = '" . (int)$product_option_value['option_value_id'] . "',
										 quantity = '" . (int)$product_option_value['quantity'] . "',
										 subtract = '" . (int)$product_option_value['subtract'] . "',
										 price = '" . (float)$product_option_value['price'] . "',
										 price_prefix = " . $ci->db->escape($product_option_value['price_prefix']) . ",
										 points = '" . (int)$product_option_value['points'] . "',
										 points_prefix = " . $ci->db->escape($product_option_value['points_prefix']) . ",
										 weight = '" . (float)$product_option_value['weight'] . "',
										 weight_prefix = " . $ci->db->escape($product_option_value['weight_prefix']) . "");
					} 
				}
			}
		}
	}
	
	// UPDATE ATTRIBUTES
	$ci->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
	if (isset($product['product_attribute']) && !empty($product['product_attribute']) && is_array($product['product_attribute']) && count($product['product_attribute']) > 0) {
	    foreach ($product['product_attribute'] as $product_attribute) {
	        if ($product_attribute['attribute_id']) {
	            foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
	                $ci->db->query("INSERT INTO " . DB_PREFIX . "product_attribute 
	                    SET product_id = '" . (int)$product_id . "',
	                    attribute_id = '" . (int)$product_attribute['attribute_id'] . "',
	                    language_id = '" . (int)$language_id . "',
	                    text = " .  $ci->db->escape($product_attribute_description['text']) . "");
	            }
	        }
	    }
	}
	 
	 // UPDATE CATEGORY
	$ci->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
	if (isset($product['product_category'])) {
		foreach ($product['product_category'] as $category_id) {
			$ci->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
		}		
	}
	
	 // UPDATE SEO URL
	 if(!defined('IS_MIJOSHOP')){
    	$ci->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
    	if ($product['keyword']) {
    		$ci->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = " . $ci->db->escape($product['keyword']) . "");
    	}
	 }
	 
     //$loader->model_catalog_product->editProduct($product_id , $data);
}


function cms_deleteProduct($product_id){
	 /*
	 // THE OLD ONE 
	 $loader->load->model('catalog/product');
     $loader->model_catalog_product->deleteProduct($product_id);
     */
	
     $ci =& get_instance(); 
	 $ci->load->database();
     $ci->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
	 $tables = $ci->db->list_tables();
	 if(in_array(DB_PREFIX . "product_profile" , $tables)){
	 	$ci->db->query("DELETE FROM `" . DB_PREFIX . "product_profile` WHERE `product_id` = " . (int)$product_id);
	 }
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
	 $ci->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
}

/*
 *  get product info 
 *  ВРОДЕ ПОКА НЕ НУЖНА
 */
function cms_getProduct($product_id){
	 /*$loader->load->model('catalog/product');
     $product = $loader->model_catalog_product->getProduct($product_id);
     return $product;*/
     //echo '<pre>'.print_r($product , 1).'</pre>';exit;
}

/*
 *  get products info (for Grabbed products table)
 */
function cms_getProducts($product_ids){
	 $ci =& get_instance(); 
	 $ci->load->database();
	 $res = $ci->db->query("SELECT 
	 						" . DB_PREFIX . "product.product_id, 
	 						" . DB_PREFIX . "product.stock_status_id, 
	 						" . DB_PREFIX . "product.quantity, 
	 						" . DB_PREFIX . "product.image, 
	 						" . DB_PREFIX . "product.price, 
	 						" . DB_PREFIX . "product_description.name
	 						FROM  " . DB_PREFIX . "product LEFT JOIN " . DB_PREFIX . "product_description USING(product_id) 
	 						WHERE product_id IN (" . implode(',' , $product_ids) . ")
	 						");
	 $out = array();
	 if ($res->num_rows() > 0){
		 foreach ($res->result() as $row){
		 	$out[$row->product_id] = (array) $row;
		 }
	 }
	 //echo '<pre>'.print_r($out , 1).'</pre>';exit;
	 return $out;
}

/*
 *  создаем линк на товар в opencart store
 */
function cms_createProductStoreLink($product_id){
    if(defined('IS_MIJOSHOP')){
        return '../../../../products/product/' . $product_id . '-' . strtolower(get_product_url_alias_mijoshop($product_id));
    }else{
       if(LOCAL_INSTALLATION_SEMAFOR > 0){
           return 'http://opencart/index.php?route=product/product&product_id=' . $product_id;
       }else{
	       return '../index.php?route=product/product&product_id=' . $product_id;
       }
    }
}

function get_product_url_alias_mijoshop($product_id){
    $ci =& get_instance();
    $ci->load->database();
    $query = 'SELECT `keyword` FROM ' . DB_PREFIX. 'url_alias WHERE `query` = "product_id=' . trim($product_id) . '" ';
    //echo $query;exit;
    $res = $ci->db->query($query)->result();
    if (count($res) > 0){
        $res = $res[0];
        return $res->keyword;
    }
    return $product_id;
}

function cms_getImageThumb($path){
    $base = '../';
    if(LOCAL_INSTALLATION_SEMAFOR > 0){
        $base = 'http://opencart/';
    }
    
	$src = $base . "image/cache/" . str_ireplace(array(".jpg" , ".jpeg" , ".gif" , ".png" , ".tiff") , array("-40x40.jpg" , "-40x40.jpeg" , "-40x40.gif" , "-40x40.png" , "-40x40.tiff") , $path);
	@$mime = getimagesize($src);
	if(!isset($mime['mime'])){
		$src = $base . "image/cache/" . str_ireplace(array(".jpg" , ".jpeg" , ".gif" , ".png" , ".tiff") , array("-100x100.jpg" , "-100x100.jpeg" , "-100x100.gif" , "-100x100.png" , "-100x100.tiff") , $path);
	}
	@$mime = getimagesize($src);
	if(!isset($mime['mime'])){
		$src = $base . "image/" .  $path;
	}
	@$mime = getimagesize($src);
	if(!isset($mime['mime'])){
		$src = $base . "image/no_image.jpg";
	}
	return '<img src="'.$src.'" width="40" height="40" />';
}

/*
 *  ставим этому товару статус "Нет в продаже"
 */
function cms_setOutOfStock($product_id){
	$ci =& get_instance(); 
	$ci->load->database();
	$ci->db->query("UPDATE " . DB_PREFIX . "product SET stock_status_id = '5' WHERE product_id = '" . (int)$product_id . "'");
}

/*
 *  устанавливаем 0 кол-во этого товара 
 */
function cms_setZeroQuantity($product_id){
	$ci =& get_instance(); 
	$ci->load->database();
	$ci->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '0' WHERE product_id = '" . (int)$product_id . "'");
}

/*
 *  отключаем этот товар
 */
function cms_disableProduct($product_id){	
	$ci =& get_instance(); 
	$ci->load->database();
	$ci->db->query("UPDATE " . DB_PREFIX . "product SET status = '0' WHERE product_id = '" . (int)$product_id . "'");
}





function cms_findProduct($product){
	$ci =& get_instance(); 
	$ci->load->database();
		$sql = array();
//        if (isset($product['model'])) $sql[] = "(`model` = '".$this->db->escape(substr($product['model'], 0, 64))."')";
        if (isset($product['sku'])) $sql[] = "(`sku` = ".$ci->db->escape($product['sku']).")";
        if (isset($product['image'])) $sql[] = "(`image` = ".$ci->db->escape($product['image']).")";
        if (isset($product['manufacturer_id'])) $sql[] = "(`manufacturer_id` = '".(int)$product['manufacturer_id']."')";
        if (isset($product['price'])) $sql[] = "(`price` = '".round($product['price'], 4)."')";
        if (isset($product['date_added'])) $sql[] = "(`date_added` = '".$product['date_added']."')";
        if (count($sql) > 0) {
            $where = ' WHERE ' . implode(' and ', $sql);
            
            $res = $ci->db->query("SELECT `product_id` FROM " . DB_PREFIX. "product" . $where)->result();
            
            if (count($res) > 0){
            	$res = $res[0];
            	return $res->product_id;
            } 
        }
       return false;
}

/***************************  OPTIONS  ******************************/

function cms_getOption($name, $type = "select"){
	$ci =& get_instance(); 
	$ci->load->database();
	$query = 'SELECT o.option_id FROM `' . DB_PREFIX. 'option` o LEFT JOIN ' . DB_PREFIX . 'option_description od ON (o.option_id = od.option_id) WHERE od.name = "' . trim($name) . '" AND o.type = ' . $ci->db->escape($type) . '';
	//echo $query;exit;
	$res = $ci->db->query($query)->result();
	if (count($res) > 0){
		$res = $res[0];
		return $res->option_id;
	}
	return false;
}

function cms_insertOption($name, $type = "select"){
	$ci =& get_instance(); 
	$ci->load->database();
	$ci->db->query('INSERT INTO `' . DB_PREFIX. 'option` SET type = ' . $ci->db->escape($type) . ', sort_order = 1');
	$option_id = $ci->db->insert_id();
	$langs = cms_getLanguages();
	if(is_array($langs) && count($langs) > 0){
		foreach($langs as $key => $lang){
			$ci->db->query('INSERT INTO ' . DB_PREFIX . 'option_description SET option_id = ' . (int)$option_id . ', language_id = ' . (int)$lang['language_id'] . ', name = ' . $ci->db->escape($name));
		}
	}
	return (int) $option_id;
	
}
//SELECT ads.attribute_id FROM ' . DB_PREFIX. 'attribute_description ads RIGHT JOIN ' . DB_PREFIX. 'attribute a ON (ads.attribute_id = a.attribute_id) WHERE ads

function cms_getOptionValue($name , $option_id , $type , $translit_name){
	$ci =& get_instance(); 
	$ci->load->database();
	if($type == "image"){
	    $q = 'SELECT ovd.option_value_id FROM ' . DB_PREFIX. 'option_value_description ovd RIGHT JOIN ' . DB_PREFIX. 'option_value ov ON (ovd.option_value_id = ov.option_value_id) WHERE ovd.name = "' . addslashes(trim($name)) . '" AND ovd.option_id = ' . (int)$option_id . ' AND ov.image LIKE "%' . $translit_name . '%"';
	}else{
	    $q = 'SELECT `option_value_id` FROM ' . DB_PREFIX. 'option_value_description WHERE `name` = "' . addslashes(trim($name)) . '" AND `option_id` = ' . (int)$option_id ;
	}
	//echo 'Q : ' . $q . '<br />';
	$res = $ci->db->query($q)->result();
	if (count($res) > 0){
		$res = $res[0];
		return $res->option_value_id;
	}
	return false;
}

function cms_insertOptionValue($name, $option_id , $image = false){
	$ci =& get_instance(); 
	$ci->load->database();
	// add some SQL if type=image
	$image_add = ', image = "", sort_order = 1';
	if($image){
	    $image_add = ', image = "' . addslashes(trim($image)) . '", sort_order = 1';
	}
	$ci->db->query('INSERT INTO ' . DB_PREFIX . 'option_value SET option_id = ' . (int)$option_id . $image_add);
	$option_value_id = $ci->db->insert_id();
	$langs = cms_getLanguages();
	if(is_array($langs) && count($langs) > 0){
		foreach($langs as $key => $lang){
			$ci->db->query('INSERT INTO ' . DB_PREFIX . 'option_value_description SET option_value_id = ' . (int)$option_value_id . ', language_id = ' . (int)$lang['language_id'] . ', option_id = ' . (int)$option_id . ', name = ' . $ci->db->escape($name));
		}
	}
	return (int) $option_value_id;
	
}

/***************************  ATTRIBUTES  ******************************/


function cms_getAttributeGroup($groupName){
    $ci =& get_instance(); 
	$ci->load->database();
	$query = 'SELECT `attribute_group_id` FROM ' . DB_PREFIX. 'attribute_group_description WHERE `name` = "' . trim($groupName) . '" ';
	//echo $query;exit;
	$res = $ci->db->query($query)->result();
	if (count($res) > 0){
		$res = $res[0];
		return $res->attribute_group_id;
	}
	return false;
}

function cms_insertAttributeGroup($groupName){
    $ci =& get_instance();
    $ci->load->database();
    $ci->db->query('INSERT INTO `' . DB_PREFIX. 'attribute_group` SET sort_order = 1');
    $attribute_group_id = $ci->db->insert_id();
    $langs = cms_getLanguages();
    if(is_array($langs) && count($langs) > 0){
        foreach($langs as $key => $lang){
            $ci->db->query('INSERT INTO ' . DB_PREFIX . 'attribute_group_description SET attribute_group_id = ' . (int)$attribute_group_id . ', language_id = ' . (int)$lang['language_id'] . ', name = ' . $ci->db->escape($groupName));
        }
    }
    return (int) $attribute_group_id;
}
function cms_getAttribute($name , $groupID){
    $ci =& get_instance();
    $ci->load->database();
    $query = 'SELECT ads.attribute_id FROM ' . DB_PREFIX. 'attribute_description ads RIGHT JOIN ' . DB_PREFIX. 'attribute a ON (ads.attribute_id = a.attribute_id) WHERE ads.name = "' . addslashes(trim($name)) . '" AND a.attribute_group_id = ' . trim($groupID) . '';
    //echo $query;exit;
    $res = $ci->db->query($query)->result();
    if (count($res) > 0){
        $res = $res[0];
        return $res->attribute_id;
    }
    return false;
}


function cms_insertAttribute($name , $groupID){
    $ci =& get_instance();
    $ci->load->database();
    $ci->db->query('INSERT INTO `' . DB_PREFIX. 'attribute` SET attribute_group_id = ' . (int) $groupID . ', sort_order = 0');
    $attribute_id = $ci->db->insert_id();
    $langs = cms_getLanguages();
    if(is_array($langs) && count($langs) > 0){
        foreach($langs as $key => $lang){
            $ci->db->query('INSERT INTO ' . DB_PREFIX . 'attribute_description SET attribute_id = ' . (int)$attribute_id . ', language_id = ' . (int)$lang['language_id'] . ', name = ' . $ci->db->escape($name));
        }
    }
    return (int) $attribute_id;

}


/***************************  MANUFACTURER  ******************************/

function cms_getManufacturerID($name){
    $manufacturer_id = false;
    $ci =& get_instance();
    $ci->load->database();
    $res = $ci->db->query('SELECT `manufacturer_id` FROM ' . DB_PREFIX. 'manufacturer WHERE `name` = "' . trim($name) . '" ')->result();
    if (count($res) > 0){
        $res = $res[0];
        $manufacturer_id = $res->manufacturer_id;
    }else{
        $ci->db->query('INSERT INTO ' . DB_PREFIX . 'manufacturer SET name = "' . trim($name) . '", sort_order = 0');
        $manufacturer_id = $ci->db->insert_id();
    }
    return $manufacturer_id;
}
