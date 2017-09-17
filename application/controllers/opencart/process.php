<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

@ini_set("max_execution_time","0");
@ini_set('memory_limit', '-1');

class Process extends CI_Controller {

    private $images_dir;
    private $translit_name;
    private $donor;
    
 	function __construct(){
        parent::__construct();
        
        /*echo 'd';
        echo file_get_contents('../../../../proxyMyOwn.txt');
        echo 'd';exit;*/
 
        $this->load->helper("cms/" . $this->config->item("ms_cms"));
        // load language
        $ln = $this->settings->getSettingsByKey("lang");
        if($ln){
        	$this->lang->load($ln , $ln);
        }else{
        	$this->lang->load("en" , "en");
        }
        
        // если включен invisible mode - обновляем прокси
        $settings = $this->settings->getSettings();
        
        // если превышен лимит по триалу - не пущаем
 		if($this->config->item('ms_trmode')){
 			if( (int) $this->settings->getSettingsByKey("trmode_num_product") >= (int) $this->config->item('ms_trmode_num_product_max')){
	        	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
					header('Content-type: application/json; charset=utf-8');echo json_encode(array("fail" => "trial"));exit;
				}
				echo $this->lang->line('messages_trial');
				exit;
 			}
        }
        // если демо - сюда вообще не пускаем (DEMO)
        if($this->config->item('ms_DEMO') ){
        	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				header('Content-type: application/json; charset=utf-8');echo json_encode(array("fail" => "demo"));exit;
			}
			echo $this->lang->line('messages_demo');
			exit;
        }
        
        
 	}
	
 	
 	
	/*
	 *   запуск process MSPRO 
	 *   
	 *   здесь определяем включен ли MSPRO, определяем таск
	 *   
	 *   таск на категорию отправляем на _processCategory()
	 *   таски на товары через foreach отправляем на _processProduct()
	 */
	public function index(){
		date_default_timezone_set('America/Los_Angeles');
		// первая строчка лога
		$log = '===  <font color="green"> ' . date("H:i  d ") . $this->lang->line('process_log_month_' . date("m")) . ' </font>|| ';
		
		// проверка идёт запрос аяксом ли нет (со стpаницы manual)
		$manualStart = false;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$manualStart = true;
		}
		
		// определяем включен ли MSPRO
		$settings = $this->settings->getSettings();
		if($settings['state']!== "on"){
			if($settings['dev_mode'] == "on"){
				write_file($log . "<font color='red'>MSPRO is SWITCHED OFF </font><br/>" , "public/files/log.txt" );
			}
			if($manualStart){
				header('Content-type: application/json; charset=utf-8');echo json_encode(array("fail" => "switched_off"));
			}
			exit;
		}
		
		/*
		 *  HACK FOR "BUSY" FIELD IN multiscraper_tasks
		 */
		$this->settings->DB_hack();
		//print_r($settings);
				
		// определяем какой task на очереди (category или product) и есть ли он
		$tasks = $this->tasks->getTasksForProcess((int) $settings['num_product']);
		if(!$tasks){
			if($settings['dev_mode'] == "on"){
				write_file($log . "<font color='red'>NO TASKS FOR MSPRO</font><br/>" , "public/files/log.txt" );
			}
			if($manualStart){
				header('Content-type: application/json; charset=utf-8');echo json_encode(array("fail" => "no_tasks"));exit;
			}
			echo 'process -> NO TASKS';
			exit;
		}
		//echo '<pre>'.print_r($tasks , 1).'</pre>';exit;
		$log .= $this->lang->line('process_log_started') . ($manualStart?$this->lang->line('process_log_manual_start'):"");
		
		// подтягиваем все парсерные либы
		$this->_getParsers();
		
		// отправляем таски на выполнение
		if(is_array($tasks) && count($tasks) > 0){ 
			if($tasks[0]['owner'] == "category"){
				$log_vstavka = '';
				if($settings['dev_mode'] == "on"){
					$log_vstavka = ' (Task: ' . $tasks[0]['url'] . ')';	
				}
				$log .= '||' . $this->lang->line('process_log_target_category'). '' . $log_vstavka . ' ===================<br />';
				// выполняем парсинг листинга
				$parse_category_res = $this->_processCategory($tasks[0] , $settings);
				if(is_array($parse_category_res) && isset($parse_category_res['products_found']) && isset($parse_category_res['next_page_exists']) ){
					write_file( $log . $this->lang->line('process_log_parse_cat_products_found_title') . ' : <b>'. $parse_category_res['products_found'] .'</b> || '. $this->lang->line('process_log_parse_cat_next_page_title') . ' : '. $parse_category_res['next_page_exists'] . '<br />' , "public/files/log.txt" );
				}
			}else{
				// делаем таски занятыми дабы не запустить их ещё раз пока идёт парсинг
			    if(GLOBAL_DEBUG_SEMAFOR < 1){
			        $this->tasks->makeProductTasksBusy($tasks);
			    }
				$log .= '||' . $this->lang->line('process_log_target_product'). '===================<br />';
				$inserted = 0;
				$updated = 0;
				// выполняем парсинг товаров
				foreach($tasks as $task){
					$p = false;
					@$p = $this->_processProduct($task , $settings);
					$this->tasks->makeProductTaskFree($task);
					if($p){
						$task['product_id'] > 0?$updated++:$inserted++;
					}else{
						$log .= '<br /><span style="color:red;"><b>unable to insert product: '.$task['url'].'</b></span><br />';
					}
				}
				write_file( $log . $this->lang->line('process_log_product_inserted'). ': <b>' .$inserted . '</b>; ' . $this->lang->line('process_log_product_updated') . ': <b>' . $updated . '</b>; <br />' , "public/files/log.txt" );
			}
		}
			
		exit;
	}

	
	
	
	
	
	/*
	 *   обрабатываем категорию
	 */
	private function _processCategory($task , $settings){
	    $this->tasks->unBusyAllTasks();
		$products_found = 0;
		$next_page_exists = $this->lang->line('process_log_parse_cat_next_page_exists_no');
		//print_r($task);exit;
		// определяем какой рынок обрабатывается
		$market = $this->_defineMarket($task['url']);
		$this->donor = $market['name'];
		//echo 'MARKET:<pre>' . print_r($market) . '</pre>';exit;
		if(!$market){
			if($settings['dev_mode'] == "on"){
				write_file("CATEGORY PARSER - CANNOT FIND MARKET FOR URL - " . $task['url'] . "; TASK ID: " . $task['ins_id'] . "<br/>" , "public/files/log.txt" );
				$this->tasks->deleteTask($task['id']);
			}
			exit;
		}
		
		// подтягиваем файл парсинга категории для данного рынка
		$this->load->helper('parsers/core/' . $market['name'] . '/' . $market['name'] . '_category');
		$this->load->helper('parsers/additional/' . $market['name'] . '/' . $market['name'] . '_category');
		// подтягиваем файл кастомного парсинга категории для данного рынка
		$this->load->helper('parsers/custom/' . $market['name'] . '/' . $market['name'] . '_category');

		// получаем HTML по запрашиваемому $task['url']
		if(function_exists('mspro_' . $market['name']  . '_category_getUrl_custom')){
		    $html = call_user_func('mspro_' . $market['name']  . '_category_getUrl_custom' , $task['url']);
		}elseif(function_exists('mspro_' . $market['name']  . '_category_getUrl')){
		    $html = call_user_func('mspro_' . $market['name']  . '_category_getUrl' , $task['url']);
		}else{
		    $html = getUrl($task['url'] , false , false);
		}
		if(!$html && function_exists("file_get_contents") ){
			try{
				@$html =  file_get_contents($task['url']);
			}catch (Exception $e) {};
		}
	    //echo 'HTML:' . $html;exit;
		
		if($html){
		    $result = false;
			// получаем массив заданий и следующую страницу листинга
			if (function_exists('parse_category')) {
				$result = parse_category($html , $task);
			}
			if (function_exists('parse_category_custom')) {
				$result = parse_category_custom($html , $task);
			}
			
			//echo '<pre>'.print_r($result['next_page'] , 1).'</pre>';exit;
			// вносим полученные товары как новые таски
			if(isset($result) && is_array($result['products']) && count($result['products']) > 0){
				$product_ids = array();
				foreach($result['products'] as $product){
					$p_id = $this->tasks->insertTask($product , $task['ins_id'] , 0);
					$product_ids[] = $p_id;
				}
				$products_found = count(array_unique($product_ids));
			}
			
			// таск на следующую страницу
			if(isset($result) && $result['next_page'] && strlen(trim($result['next_page'])) > 0 && is_array($result['products']) && count($result['products']) > 0){
				$next_page_exists = $this->lang->line('process_log_parse_cat_next_page_exists_yes');
				//echo $result['next_page'];
				$this->tasks->insertTask($result['next_page'] , $task['ins_id'] , 0 , "category");
			}
			//echo '<pre>'.print_r($result['next_page'] , 1).'</pre>';exit;
			
			// удаляем данный таск на листинг
			// если не нашлось ни одного товара и нет след страницы, то оставляем таск, может быть там затык
			if($products_found > 0 || $result['next_page']){
				$this->tasks->deleteTask($task['id']);
			}
			
		}else{
			if($settings['dev_mode'] == "on"){
				write_file("CANNOT GET HTML FOR THIS URL " . $task['url'] . "<br/>" , "public/files/log.txt" );
			}
		}
		// обнуляем счётчик category_queque
		$this->settings->clearCategoryQueque();
		// обновляем таск (на всякий случай)
		$this->tasks->updateTask($task['id'] , false , false , true);
		
		return array('products_found' => $products_found, 'next_page_exists' => $next_page_exists);
		exit;
	}
	
	
	
	
	
	
	/*
	 *   обрабатываем товар
	 */
	private function _processProduct($task , $settings){
		// подтягиваем CMS
		$cms_loader = cms_init();
		if(!$cms_loader){
			echo 'could not process cms_init() function!!! stack: process.php';exit;
		}
		
		// получаем данные инструкции
		$ins = $this->tasks->getInsById($task['ins_id']);
		//echo '<pre>'.print_r($ins , 1).'</pre>';exit;
		//$fields_to_insert = explode("," , $ins->fields_to_insert);
		//$fields_to_update = explode("," , $ins->fields_to_update);
		
		// определяем какой рынок обрабатывается
		$market = $this->_defineMarket($task['url']);
		$this->donor = $market['name'];
		//echo 'm<pre>' . print_r($market , 1) . '</pre>';exit;
		if(!$market){
			if($settings['dev_mode'] == "on"){
				write_file("PRODUCT PARSER - CANNOT FIND MARKET FOR URL - " . $task['url'] . "<br/>" , "public/files/log.txt" );
			}
			$this->tasks->deleteTask($task['id']);
			return false;
		}
		//echo '<pre>'.print_r($task , 1).'</pre>';
		//echo '<pre>'.print_r($ins , 1).'</pre>';exit;
		//echo '<pre>'.print_r($market , 1).'</pre>';exit; 
		
		// массив languages
		$languages = cms_getLanguages(); 
		//echo '<pre>'.print_r($languages , 1).'</pre>';exit;
		
		// массив currencies
		$currencies = cms_getCurrenciesArray(); 
		//echo '<pre>'.print_r($currencies , 1).'</pre>';exit;
		
		// подтягиваем файл парсинга товара для данного рынка
		$this->load->helper('parsers/core/' . $market['name'] . '/' . $market['name'] . '_product');
		$this->load->helper('parsers/additional/' . $market['name'] . '/' . $market['name'] . '_product');
		// подтягиваем кастомный файл парсинга товара для данного рынка
		$this->load->helper('parsers/custom/' . $market['name'] . '/' . $market['name'] . '_product');
		
		// если есть функция авторизации
		/*if (function_exists('mspro_' . $market['name']  . '_auth')) {
		    $authres = call_user_func('mspro_' . $market['name']  . '_auth');
		}*/
		
		// получаем HTML по запрашиваемому $task['url']
		//echo $task['url'];
		$html = false;
		if(function_exists('mspro_' . $market['name']  . '_getUrl_custom')){
		    $html = call_user_func('mspro_' . $market['name']  . '_getUrl_custom' , $task['url']);
		}elseif(function_exists('mspro_' . $market['name']  . '_getUrl')){
		   $html = call_user_func('mspro_' . $market['name']  . '_getUrl' , $task['url']);
		}else{
		  $html = getUrl($task['url'] , false , false);
		}
		//echo 'HTML:' . var_dump($html);
		//echo 'HTML LENGTH:' . strlen($html);
		
		$PRODUCT = cms_emptyProduct();
		//echo '<pre>'.print_r($PRODUCT , 1).'</pre>';exit;
		
		
		// получем TITLE товара
		$title = false;
		if (function_exists('mspro_' . $market['name']  . '_title')) {
            $title = call_user_func('mspro_' . $market['name']  . '_title' , $html);
        }
        if(function_exists('mspro_' . $market['name']  . '_title_custom')){
        	$title = call_user_func('mspro_' . $market['name']  . '_title_custom' , $title , $html);
        }
        $title = str_ireplace(array('"'), array('&quot;'), $title);
        //echo 'title:'.$title;exit;
	 	if (!$title || $title == '') {
           	// если update то смотрим настройку what_to_do_product_not_exists и от неё пляшем
	 		if($task['product_id'] > 0 && !$html){
	           $this->_productNoMoreAvailable($ins , $task);
	 		}
	 		$this->tasks->updateTask($task['id']);
	 		if($settings['dev_mode'] == "on"){
	 		    echo 'HAVE GOT EMPTY TITLE. TASK URL: ' . $task['url'] . '<br>HTML: ' . $html;
	 		}
	 		return true;
           	exit;
	 	} 
        
		// получаем meta description товара
        $meta_desciption = '';
		if (function_exists('mspro_' . $market['name']  . '_meta_description')) {
              $meta_desciption = call_user_func('mspro_' . $market['name']  . '_meta_description' , $html);
        }
		if (function_exists('mspro_' . $market['name']  . '_meta_description_custom')) {
              $meta_desciption = call_user_func('mspro_' . $market['name']  . '_meta_description_custom' , $meta_desciption , $html);
        }
        $meta_desciption = $this->_clearMetaTags($meta_desciption , $market['name']);
        
		// получаем meta keywords товара
        $meta_keywords = '';
		if (function_exists('mspro_' . $market['name']  . '_meta_keywords')) {
              $meta_keywords = call_user_func('mspro_' . $market['name']  . '_meta_keywords' , $html);
        }
		if (function_exists('mspro_' . $market['name']  . '_meta_keywords_custom')) {
              $meta_keywords = call_user_func('mspro_' . $market['name']  . '_meta_keywords_custom' , $meta_keywords , $html);
        }
        $meta_keywords = $this->_clearMetaTags($meta_keywords , $market['name']);
        
		// получаем meta title товара
        $meta_title = '';
		if (function_exists('mspro_' . $market['name']  . '_meta_title')) {
              $meta_title = call_user_func('mspro_' . $market['name']  . '_meta_title' , $html);
        }
		if (function_exists('mspro_' . $market['name']  . '_meta_title_custom')) {
              $meta_title = call_user_func('mspro_' . $market['name']  . '_meta_title_custom' , $meta_title , $html);
        }
        
		// получаем meta h1 товара
        $meta_h1 = '';
		if (function_exists('mspro_' . $market['name']  . '_meta_h1')) {
              $meta_h1 = call_user_func('mspro_' . $market['name']  . '_meta_h1' , $html);
        }
		if (function_exists('mspro_' . $market['name']  . '_meta_h1_custom')) {
              $meta_h1 = call_user_func('mspro_' . $market['name']  . '_meta_h1_custom' , $meta_h1 , $html);
        }

        // получаем цену
        $margin_relative = 100 +  (float) $ins['margin_relative'];
	    $margin_relative = $margin_relative / 100;
	    $margin_fixed = (float) $ins['margin_fixed'];
	    $rate = $currencies[$ins['donor_currency']]['value'];
        $price = false;
		if (function_exists('mspro_' . $market['name']  . '_price')) {
              $price = call_user_func('mspro_' . $market['name']  . '_price' , $html);
        }
		if (function_exists('mspro_' . $market['name']  . '_price_custom')) {
              $price = call_user_func('mspro_' . $market['name']  . '_price_custom' , $price , $html);
        }
        if($price && (is_int($price) || is_float($price) || is_numeric($price)) ){
        	$PRODUCT['price'] = $this->_apply_margins_to_price($price , $rate , $margin_fixed , $margin_relative);
        }
        
        // получаем SKU, UPC, MODEL
        $sku = '';
		if (function_exists('mspro_' . $market['name']  . '_sku')) $sku = call_user_func('mspro_' . $market['name']  . '_sku' , $html);
		if (function_exists('mspro_' . $market['name']  . '_sku_custom')) $sku = call_user_func('mspro_' . $market['name']  . '_sku_custom' , $sku , $html);
		$PRODUCT['sku'] = $sku;
		
		$upc = '';
		if (function_exists('mspro_' . $market['name']  . '_upc')) $upc = call_user_func('mspro_' . $market['name']  . '_upc' , $html);
		if (function_exists('mspro_' . $market['name']  . '_upc_custom')) $upc = call_user_func('mspro_' . $market['name']  . '_upc_custom' , $upc , $html);
		$PRODUCT['upc'] = $upc;
		
		$mpn = '';
		if (function_exists('mspro_' . $market['name']  . '_mpn')) $mpn = call_user_func('mspro_' . $market['name']  . '_mpn' , $html);
		if (function_exists('mspro_' . $market['name']  . '_mpn_custom')) $mpn = call_user_func('mspro_' . $market['name']  . '_mpn_custom' , $mpn , $html);
		$PRODUCT['mpn'] = $mpn;
		
		$ean = '';
		if (function_exists('mspro_' . $market['name']  . '_ean')) $ean = call_user_func('mspro_' . $market['name']  . '_ean' , $html);
		if (function_exists('mspro_' . $market['name']  . '_ean_custom')) $ean = call_user_func('mspro_' . $market['name']  . '_ean_custom' , $ean , $html);
		$PRODUCT['ean'] = $ean;
		
		$isbn = '';
		if (function_exists('mspro_' . $market['name']  . '_isbn')) $isbn = call_user_func('mspro_' . $market['name']  . '_isbn' , $html);
		if (function_exists('mspro_' . $market['name']  . '_isbn_custom')) $isbn = call_user_func('mspro_' . $market['name']  . '_isbn_custom' , $isbn , $html);
		$PRODUCT['isbn'] = $isbn;
		
		
		$model = '';
		if (function_exists('mspro_' . $market['name']  . '_model')) $model = call_user_func('mspro_' . $market['name']  . '_model' , $html);
		if (function_exists('mspro_' . $market['name']  . '_model_custom')) $model = call_user_func('mspro_' . $market['name']  . '_model_custom' , $model , $html);
		$PRODUCT['model'] = $model;
		
		if(empty($PRODUCT['sku']) && !empty($PRODUCT['model'])){ 
		    $PRODUCT['sku'] = $PRODUCT['model'];
		}
		
		// weight
		// DEFAULTS: 1 - Kilogram, 2 - Gram, 5 - Pound, 6 - Ounce
		$weight = false;
		if (function_exists('mspro_' . $market['name']  . '_weight')) $weight = call_user_func('mspro_' . $market['name']  . '_weight' , $html);
		if (function_exists('mspro_' . $market['name']  . '_weight_custom')) $weight = call_user_func('mspro_' . $market['name']  . '_weight_custom' , $weight , $html);
		if (isset($weight['weight'])) $PRODUCT['weight'] = $weight['weight'];
		if (isset($weight['weight_class_id'])) $PRODUCT['weight_class_id'] = $weight['weight_class_id'];
		
		// length, width, height
		// DEFAULTS: 1 - cm, 2 - mm, 3 - inch 
		$length = false;$width = false;$height = false;
		if (function_exists('mspro_' . $market['name']  . '_dimensions')) $dims = call_user_func('mspro_' . $market['name']  . '_dimensions' , $html);
		if (function_exists('mspro_' . $market['name']  . '_dimensions_custom')) $dims = call_user_func('mspro_' . $market['name']  . '_dimensions_custom' , $dims , $html);
		if (isset($dims['length'])) $PRODUCT['length'] = $dims['length'];
		if (isset($dims['width'])) $PRODUCT['width'] = $dims['width'];
		if (isset($dims['height'])) $PRODUCT['height'] = $dims['height'];
		if (isset($dims['length_class_id'])) $PRODUCT['length_class_id'] = $dims['length_class_id'];
		// echo '<pre>'.print_r($PRODUCT , 1).'</pre>';exit;
		
		// определяем колличество товара
		$PRODUCT['quantity'] = (isset($ins['products_quantity']) && $ins['products_quantity'] >= 0 )?$ins['products_quantity']:$this->config->item('ms_default_quantity_of_products');
		
		// устанавливаем tax_class_id
		$PRODUCT['tax_class_id'] = (isset($ins['tax_class_id']) && $ins['tax_class_id'] > 0 )?$ins['tax_class_id']:0;
			
		// CATEGORY
        $c = 0;
        $category = explode("," , $ins['category_id']);
        if(count($category) > 0){
          	foreach($category as $cat){
          		if($c < 1){ $PRODUCT['main_category_id'] = $cat; }
              	$PRODUCT['product_category'][] = $cat;
             	$c++;
          	}
        }
            
        // SEO URL
        $PRODUCT['keyword'] = false;
        $this->translit_name = $this->_getSeoUrl($title , $PRODUCT['sku'] , $PRODUCT['model']);
        if($ins['seo_url'] > 0 || defined('IS_MIJOSHOP')){
        	$PRODUCT['keyword'] = $this->translit_name;
        }
             
        // MANUFACTURER
        $PRODUCT['manufacturer_id'] = 0;
        if( $ins['manufacturer_id'] < 1){
            $manufacturer = false;
            if (function_exists('mspro_' . $market['name']  . '_manufacturer')) $manufacturer = call_user_func('mspro_' . $market['name']  . '_manufacturer' , $html);
            if (function_exists('mspro_' . $market['name']  . '_manufacturer_custom')) $manufacturer = call_user_func('mspro_' . $market['name']  . '_manufacturer_custom' , $manufacturer , $html);
            if($manufacturer && strlen($manufacturer) > 0){
                $PRODUCT['manufacturer_id'] = cms_getManufacturerID($manufacturer);
            }
        }else{
            $PRODUCT['manufacturer_id'] = $ins['manufacturer_id'];
        }
        //echo '<pre>'.print_r($PRODUCT , 1).'</pre>';exit;
        
        // вычисляем $images_dir
        $this->images_dir = $this->_get_images_dir($ins);
        //echo $this->images_dir;exit;

        // OPTIONS
        if( ($task['product_id'] < 1 && isset($ins['get_options']) && $ins['get_options'] > 0) || ($task['product_id'] > 0 && isset($ins['do_not_update_options']) && $ins['do_not_update_options'] < 1) ){
        	$options = array();
        	if (function_exists('mspro_' . $market['name']  . '_options')) $options = call_user_func('mspro_' . $market['name']  . '_options' , $html);
			if (function_exists('mspro_' . $market['name']  . '_options_custom')) $options = call_user_func('mspro_' . $market['name']  . '_options_custom' , $options , $html);
			if(count($options) > 0){
				$PRODUCT['product_option'] = $this->_prepare_options($options , $rate , $margin_relative , $PRODUCT['quantity']);
			}
			//echo '<pre>'.print_r($PRODUCT['product_option'] , 1).'</pre>';exit;
        }
        
        // ATTRIBUTES
        $attributes = array();
        if (function_exists('mspro_' . $market['name']  . '_attributes')) $attributes = call_user_func('mspro_' . $market['name']  . '_attributes' , $html , $task['url']);
        if (function_exists('mspro_' . $market['name']  . '_attributes_custom')) $attributes = call_user_func('mspro_' . $market['name']  . '_attributes_custom' , $attributes , $html);
        if(count($attributes) > 0){
            $PRODUCT['product_attribute'] = $this->_prepare_attributes($attributes);
            //echo '<pre>'.print_r($PRODUCT['product_attribute'] , 1).'</pre>';exit;
        }
        
        //echo '<pre>'.print_r($PRODUCT , 1).'</pre>';exit;
		// ТОЛЬКО INSERT ТОВАРА
		if($task['product_id'] < 1){
		    
		    // получаем DESCRIPTION товара
		    $desciption = '';
		    if (function_exists('mspro_' . $market['name']  . '_description')) {
		        $desciption = call_user_func('mspro_' . $market['name']  . '_description' , $html);
		    }
		    if (function_exists('mspro_' . $market['name']  . '_description_custom')) {
		        $desciption = call_user_func('mspro_' . $market['name']  . '_description_custom' , $desciption , $html);
		    }
		    //echo 'DESC : ' . $desciption;exit;

             // заполняем данные
			$PRODUCT['product_description'] = array();
			$desciption = $this->_processSpecImages($desciption, $ins['description_image_limit'] , $ins['do_not_upload_description_image'] , $market['name']);
			//echo "DESC : " .  $DESC;exit;
	        foreach ($languages as $lang_alias => $lang_array) {
	               $data['name'] = $title;
	               $data['description'] = trim($desciption);
	               $data['meta_description'] = $meta_desciption;
	               $data['seo_h1'] = $meta_h1;
	               $data['meta_keyword'] = $meta_keywords;
	               $data['meta_title'] = $title;
	               $data['seo_title'] = $meta_title;
	               $PRODUCT['product_description'][$lang_array['language_id']] = $data;
	               $PRODUCT['product_description'][$lang_array['language_id']]['tag'] = $meta_keywords;
	        }
	        
	        //echo '<pre>'.print_r($PRODUCT , 1).'</pre>';exit;
	        
	        //echo '<pre>'.print_r($ins , 1).'</pre>';exit;
             // IMAGES
             // main image
             $main_image = false;
		     if (function_exists('mspro_' . $market['name']  . '_main_image')) {
		     	$main_image = call_user_func('mspro_' . $market['name']  . '_main_image' , $html);	
		     } 
		     if (function_exists('mspro_' . $market['name']  . '_main_image_custom')) {
		     	$main_image = call_user_func('mspro_' . $market['name']  . '_main_image_custom' , $html);
		     }
			if ($main_image && $main_image != '' && ( (isset($ins['main_image_limit']) && (int) $ins['main_image_limit'] !== 0) || !isset($ins['main_image_limit']) ) ) {
            	$PRODUCT['image'] = cms_saveImage($this->images_dir , $this->translit_name , $main_image,  rand(0 , 10000));
			}
			//echo $main_image . ' - ' . $PRODUCT['image'] . '<br />';
			
		    // other images 
		    $other_images = false;          
		    
			if (function_exists('mspro_' . $market['name']  . '_other_images_custom')) {
		     	$other_images = call_user_func('mspro_' . $market['name']  . '_other_images_custom' , $html);
		    }elseif(function_exists('mspro_' . $market['name']  . '_other_images')) {
		     	$other_images = call_user_func('mspro_' . $market['name']  . '_other_images' , $html);
		    }

		    //echo '<pre>'.print_r($ins , 1).'</pre>';exit;
		    if(isset($ins['main_image_limit']) && (int) $ins['main_image_limit'] > 1){
		        array_splice($other_images, (int) ($ins['main_image_limit'] - 1) );
		    }elseif(isset($ins['main_image_limit']) && ( (int) $ins['main_image_limit'] == 0 || (int) $ins['main_image_limit'] == 1 )){
		        unset($other_images);
		    }
		    //echo '<pre>'.print_r($other_images , 1).'</pre>';exit;
		    //exit;
		    if(isset($other_images) && is_array($other_images) && count($other_images) > 0){
             	$product_images = array();
             	foreach ($other_images as $index => $value) {
             	    $product_images[$index]['image'] = cms_saveImage($this->images_dir , $this->translit_name , $value,  $index);
             	    $product_images[$index]['sort_order'] = "";
             	    //echo $value . ' - ' . $product_images[$index]['image'] . '<br />';
             	}
                if (count($product_images) > 0) $PRODUCT['product_image'] = $product_images;
		     }
		     
		     // ставим статус товара 0n (Disabled) , если так установлено в инструкции (create_disabled)
		     if($ins['create_disabled'] > 0){
		     	$PRODUCT['status'] = 0;
		     }
		     

		    //echo '<pre>'.print_r($PRODUCT , 1).'</pre>';
		    
		    // INSERT PRODUCT INTO STORE
			$product_id = cms_insertProduct($PRODUCT);
			//echo $product_id;
			

            if(!$product_id){
            	return false;
            }
            $task['product_id'] = $product_id;
			
			 //echo '<pre>'.print_r($PRODUCT , 1).'</pre>';exit;
			$this->tasks->updateTask($task['id'] , $product_id , true, false , $title , $PRODUCT['price']);
			
		}else{
			// только UPDATE
			cms_updateProduct($PRODUCT , $task['product_id'] , $ins);		
			$this->tasks->updateTask($task['id'] , false , false , false , $title , $PRODUCT['price']);
		}
		unset($PRODUCT);

		// ПРОВЕРЯЕМ ДОСТУПНОСТЬ ТОВАРА (теперь через функции поиска _noMoreAvailable в HTML)
		$noMoreAvailable = false;
		if (function_exists('mspro_' . $market['name']  . '_noMoreAvailable')) {
            $noMoreAvailable = call_user_func('mspro_' . $market['name']  . '_noMoreAvailable' , $html);
        }elseif(function_exists('mspro_' . $market['name']  . '_noMoreAvailable_custom')){
        	$noMoreAvailable = call_user_func('mspro_' . $market['name']  . '_noMoreAvailable_custom' , $html);
        }
        if($noMoreAvailable === true){
        	//echo $ins['what_to_do_product_not_exists'];exit;
        	$this->_productNoMoreAvailable($ins , $task);
        }
				
		
		// добавляем счётчик category_queque
		$this->settings->addCategoryQueque();
		// если TRIAL  - добавляем счётчик спарсенных товаров 
		if($this->config->item('ms_trmode')){
			$this->settings->add_trmode_num_product();
		}
		return true;
		
	}
	
	
	
	
	private function _productNoMoreAvailable($ins , $task){
		switch($ins['what_to_do_product_not_exists']){
	           		// nothing doing
	           		case '0':
	           			break;
	           		// delete product from store
	           		case 1:
	           			// delete task
	           			$this->tasks->deleteTask($task['id']);
	           			// delete product
	           			cms_deleteProduct($task['product_id']);
	           			break;
	           		// set "Out of Stock" status
	           		case 2:
	           			cms_setOutOfStock($task['product_id']);
	           			break;
	           		// disable
	           		case 3:
	           			cms_disableProduct($task['product_id']);
	           			break;
	           		// set 0 quantity
	           		case 4:
	           			cms_setZeroQuantity($task['product_id']);
	           			break;
	           		default:
	           			break;
	           	}
	}
	
	
	
	
	
	
	
	
	
	/*      UTILITY   FUNCTIONS     */
	/*
	 *  определяет какой рынок обрабатывается
	 *  
	 *    возвращает FALSE или общий конфиг рынка 
										array(
											'name' => "aliexpress",
											'title' => "Aliexpress",
											'fields' => array('title' , 'description' , 'price', etc),
											'url_aliases' => array('aliexpress.com' , etc), 
										)
	 */
	private function _defineMarket($url){
		//echo $url;exit;
		// get markets array (see markets_helper.php)
		$markets = merge_custom_markets($this->config->item("additionalmarkets") , $this->config->item("markets"));
		//echo '<pre>'.print_r($markets , 1).'</pre>';exit;
		// get target host from url
		$host = parse_url($url);
		
		// костыль на случай если в урле задано что то что не даёт отработать функе parse_url
		//echo '<pre>'.print_r($host , 1).'</pre>';exit;
		if(!isset($host['host']) && isset($host['path']) && strpos($url , "ttp") < 1 ){
			$host = parse_url("http://" . $url);
			//if( (!isset($host['host']) && isset($host['path'])) ){
				// TODO: pазобраться как вычмслить галиматью в урле и что делать если в урле задания задана галиматья
				//echo 'GALIMATYA V URLE. URL: '.$url;exit;
			//}
		}
		if(isset($host['host'])){
		  $host = str_ireplace(array("www.") , array("") , trim($host['host']));
		}else{
		    echo 'cannot get hostname for URL = ' . $url . '. STACK: _defineMarket at the process';exit;
		}
		//echo '<pre>'.print_r($host , 1).'</pre>';
		
		
		// сравниваем url_aliases каждого рынка с хостом таска
		foreach($markets as $market){
			if(isset($market['url_aliases']) && is_array($market['url_aliases']) && count($market['url_aliases']) > 0){
				foreach($market['url_aliases'] as $market_alias){
					if(strpos($host , $market_alias) > -1){
						//echo '<pre>'.print_r($market , 1).'</pre>';exit;
						return $market;	
					}
				}
			}
		}
		//echo '<pre>'.print_r($markets , 1).'</pre>';exit;
		return false;
	}
	
	
	private function _getParsers(){
		$this->load->helper('libs/parser');
		$this->load->helper('libs/phpquery');
		$this->load->helper('libs/nokogiri');
	}
	
	
	private function _getSeoUrl($name , $sku , $model){
	    $out = preg_replace("/[^a-zA-Z0-9_-]/", "" , $name);
		$out = substr($out , 0, 150);
		if(strlen($sku) > 1){
			$out .= '-'.$sku;
		}elseif(strlen($model) > 1){
			$out .= '-'.$model;
		}
		$random_add = '';
		$hieroglifs = array('taobao' , 'alibaba');
		//echo 'donor:' . $this->donor;exit;
		if(in_array($this->donor , $hieroglifs)){
		    $random_add = '_' . rand(0,10000);
		}
		$out = preg_replace("/[^a-zA-Z0-9_-]/", "" , $out . $random_add);
		//echo $out;
		return $out;
	}
	
	
	// вычисляем $images_dir
	private function _get_images_dir($ins){
	    $images_dir = cms_getImagesLocation();
	    if(strlen($ins['image_folder']) > 0){
	        $images_dir .= '/'.$ins['image_folder'];
	    }
	    $images_dir = str_replace(array("//") , array("/") , $images_dir);
	    return $images_dir;
	}
	
	private function _clearMetaTags($str , $market){
	    //echo $market . ' - ' . $str . ' - ';
	    $markets_array = array("$market.com" , "$market.co.uk" , "$market.fr" , "$market.ru" , "$market.net" , "$market.de" , "$market.pt" , "$market.es" , "$market.us" , $market);
	    return str_ireplace($markets_array , "" , $str);
	}
	
	
	private function _processSpecImages($description , $description_image_limit , $do_not_upload_description_image , $market){
		// get images array
		preg_match_all('/(<img[^<]+>)/Usi', $description, $images);
		$image = array();
        foreach ($images[0] as $index => $value) {
            $s = strpos($value, 'src="') + 5;
            $e = strpos($value, '"', $s + 1);
            $image[$value] =   substr($value, $s, $e - $s);
        }

	 	$cnt_others = 0;
	 	//echo count($image);
	 	if(count($image) > 0){
            foreach ($image as $index => $value) {	
            	// only for focalprice
            	$value = str_ireplace(array("860x666") , array("550x426") , $value);
            	$value = str_replace(array(" ") , array("%20"), $value);
    
            	if($description_image_limit > -1){
            	    if($cnt_others >= $description_image_limit){
            	        $description = str_replace($index, '', $description);
            	    }else{
            	        if($do_not_upload_description_image < 1 && !($cnt_others > 6 && $market == "aliexpress") ){
                	        $res = cms_saveImage($this->images_dir , $this->translit_name , $value,  $cnt_others , true);
                	        $description = str_replace($index, '<img src="' . $res . '" alt="' . $this->translit_name . '" />', $description);
            	        }
            	    }
            	}else{
            	    if($do_not_upload_description_image < 1 && !($cnt_others > 6 && $market == "aliexpress")){
                	    $res = cms_saveImage($this->images_dir , $this->translit_name , $value,  $cnt_others , true);
                	    $description = str_replace($index, '<img src="' . $res . '" alt="' . $this->translit_name . '" />', $description);
            	    }
            	}
            	/*
            	if($do_not_upload_description_image < 1){
                	$res = cms_saveImage($images_dir , $productName , $value,  $cnt_others , true);
                	$description = str_replace($index, '<img src="' . $res . '" alt="' . $productName . '" />', $description);
            	}
            	*/
            	$cnt_others++;
            }
	 	}
        //echo $description;exit;
        return $description;
	}
	
	
	/***************************  OPTIONS  ******************************/
	
	private function _prepare_options($options , $rate , $margin_relative , $quantity){
		$out = array();
		foreach($options as $option){
			if(isset($option['name']) && isset($option['values']) && is_array($option['values']) && count($option['values']) > 0 ){
				if(CMS_VERSION_NUMBER > 2301 &&  $option['type'] == "image"){
				    $option['type'] = "radio";
				}
				$OPTION = array();
				$OPTION['product_option_id'] = '';
				$OPTION['option_id'] = $this->_getOption($option['name'] , $option['type']);
				$OPTION['name'] = $option['name'];
				$OPTION['type'] = $option['type'];
				$OPTION['required'] = $option['required'];
				$OPTION['product_option_value'] = array();
				$check_values_number = 0;
				foreach($option['values'] as $option_value){
					if(isset($option_value['name']) && isset($option_value['price'])){
						$OPTION_VALUE = array();
						$OPTION_VALUE['option_value_id'] = $this->_getOptionValue( $option_value['name'] , $OPTION['option_id'] , isset($option_value['image'])?$option_value['image']:"" , $OPTION['type'] , $check_values_number);
						$OPTION_VALUE['product_option_value_id'] = '';
						$OPTION_VALUE['quantity'] = $quantity;
						$OPTION_VALUE['subtract'] = '';
						$OPTION_VALUE['price_prefix'] = $option_value['price'] < 0?"-":"+";
						$OPTION_VALUE['price'] = $this->_apply_margins_to_price( abs($option_value['price']) , $rate , 0 , $margin_relative);
						$OPTION_VALUE['points_prefix'] = '';
						$OPTION_VALUE['points'] = '';
						$OPTION_VALUE['weight_prefix'] = '';
						$OPTION_VALUE['weight'] = '';
						$OPTION['product_option_value'][] = $OPTION_VALUE;
						$check_values_number++;
					}
				}
				if($check_values_number > 0){
					$out[] = $OPTION;
				}
			}
		}
		//echo '<pre>'.print_r($out , 1).'</pre>';
		return $out;
	}
	
	// try to find Option ID by Name (otherwise will create this option)
	private function _getOption($name , $type){
		$res = cms_getOption($name , $type);
		if(!$res){
			$res = cms_insertOption($name , $type);
		}
		return (int) $res;
	}
	
	function _getOptionValue($valueName , $option_id , $image = '' , $type , $number){
		$res = cms_getOptionValue($valueName, $option_id , $type , $this->translit_name);
		if(!$res){
		    if( ($type == "image" || $type == "radio") && strlen(trim($image)) > 1){
		        $valueName .= ($number + 1);
		        $image = cms_saveImage($this->images_dir , $this->translit_name.'-'.GetInTranslit($valueName) , $image);
		        $res = cms_insertOptionValue($valueName , $option_id , $image);
		    }else{
		        $res = cms_insertOptionValue($valueName , $option_id );
		    }
		}
		return (int) $res;
	}
	
	
	function _apply_margins_to_price($price , $rate , $margin_fixed , $margin_relative){
		$res = $price / $rate;
		$res = ( $res * $margin_relative ) + $margin_fixed;
	    return $res;
	}
	
	/***************************  ATTRIBUTES  ******************************/
	
	private function _prepare_attributes($attributes){
		$out = array();
		$attribute_IDS_exists = array();
		foreach($attributes as $attribute){
			if(isset($attribute['group']) && isset($attribute['name']) && isset($attribute['value']) ){
				$ATTR = array();
				$ATTR['name'] = $attribute['name'];
				$attributeGroupID = $this->_getAttributeGroupID($attribute['group']);
				$ATTR['attribute_id'] = $this->_getAttributeID($attribute['name'] , $attributeGroupID);
				$ATTR['product_attribute_description'] = array('1' => array('text' => $attribute['value']));
				if(!in_array($ATTR['attribute_id'] , $attribute_IDS_exists)){
				    $out[] = $ATTR;
				    $attribute_IDS_exists[] = $ATTR['attribute_id'];
				}
			}
		}
		return $out;
	}
	
	// try to find Attribute GROUP ID by Name (otherwise will create this attribute GROUP)
	private function _getAttributeGroupID($groupName){
	    $res = cms_getAttributeGroup($groupName);
	    if(!$res){
	        $res = cms_insertAttributeGroup($groupName);
	    }
	    return (int) $res;
	}
	
	// try to find Attribute ID by Name and Attribute GROUP ID (otherwise will create this attribute)
	private function _getAttributeID($name , $groupID){
		$res = cms_getAttribute($name , $groupID);
		if(!$res){
			$res = cms_insertAttribute($name , $groupID);
		}
		return (int) $res;
	}
	
	
	
	
	
}

