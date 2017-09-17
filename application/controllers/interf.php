<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Interf extends MS_Controller {

	
	
	/*
	 *   main - SETTINGS page
	 */
	public function index(){
		$nots = array();
		if($this->input->post("state") && !$this->config->item('ms_DEMO')){			
			$state = $this->input->post("state");
			$dev_mode = $this->input->post("dev_mode");
			$inv_mode = $this->input->post("inv_mode");
			$lang = $this->input->post("lang");
			$num_product = $this->input->post("num_product");
			// passwords
			$old_pass = trim($this->input->post("old_pass"));
			$new_pass = trim($this->input->post("new_pass"));
			$confirm_pass = trim($this->input->post("confirm_pass"));
			
			if($state || $lang || $num_product || $dev_mode || $inv_mode){
				$this->settings->changeSettings($state , $lang, $num_product , $dev_mode , $inv_mode);
				$nots["modified"] = true;
				if($lang) $this->lang->load($lang , $lang);
			}
			if($old_pass !== "" && $new_pass !== "" && $confirm_pass !== ""){
				$res  = $this->settings->changePass($old_pass , $new_pass , $confirm_pass);
				if($res === "nopass") {$nots["nopass"] = true;}
				if($res === "nopassmatch") {$nots["nopassmatch"] = true;}
			}
			//print_r($nots);exit;
		}
		
		$add = array('addJS' => array('bpopup') , 'addCSS' => array('bpopup'));
		$settings = $this->settings->getSettings();
		//print_r($settings);
		$menu_data  = array('active' => "settings");
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		$this->load->view('settings' , array('settings' => $settings,
											 'langs' => $this->config->item('ms_langs'),
											 'notifications' => $nots 
											));
		$this->load->view('footer');
	}
	
	/*
	 *  HOW TO USE MSPRO PAGE
	 */
	public function howtouse(){
		$add = array('addJS' => array('bpopup') , 'addCSS' => array('bpopup'));
		$menu_data  = array('active' => "howtouse");
		$markets = array('core' => $this->config->item("markets") , 'additional' =>  $this->config->item("additionalmarkets"));
		//echo '<pre>'.print_r($markets , 1).'</pre>';exit;
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		$this->load->view('howtouse' , array('markets' => $markets ));
		$this->load->view('footer');
	}
	
	
	/*
	 *  TASKS
	 */
	public function tasks(){
		$this->load->model("tasks");
		$this->load->helper("cms/" . $this->config->item("ms_cms"));
		$cms_loader = cms_init();
		if(!$cms_loader){
			echo 'could not process cms_init() function!!! stack: controller interf';exit;
		}
		$settings = $this->settings->getSettings();
		$categories = cms_getCategories();
		$manufacturers = cms_getManufacturers();
		$taxclasses = cms_getTaxClasses();
		$currencies = cms_getCurrencies();
		
		
		
		/*   АЯКСОМ ИДУТ САБМИТЫ ФОРМ */
		// check for ajax
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $this->input->post("action") ) {
				$action  = $this->input->post("action");
				$out = array();
				if($this->config->item('ms_DEMO')){
					header('Content-type: application/json; charset=utf-8');
					echo json_encode(array('result' => "demo"));exit;
				}
				switch($action){
					case 'add':
						if(isset($_POST['data']) && is_array($_POST['data']) && count($_POST['data']) > 0 ){
							$this->tasks->createIns($_POST['data']);
						}
						break;
					case 'edit':
						if(isset($_POST['data']) && is_array($_POST['data']) && count($_POST['data']) > 0 && isset($_POST['id'])){
							$this->tasks->updateIns($_POST['id'] , $_POST['data']);
						}
						break;
					case 'switch':
						if(isset($_POST['task_id']) && isset($_POST['switch'])){
							if($this->tasks->setSwitch($_POST['task_id'] , $_POST['switch'])){
								$out['result'] = "success";
							}
						}
						break;
					case 'set_priority':
						if(isset($_POST['task_id']) && isset($_POST['priority'])){
							if($this->tasks->setPriority($_POST['task_id'] , $_POST['priority'])){
								$out['result'] = "success";
							}
						}
						break;
					case 'restart':
					    //echo $_POST['id'];exit;
						if(isset($_POST['id'])){
							$this->tasks->restartIns($_POST['id']);
						}
						break;
					case 'delete':
						if(isset($_POST['id']) && isset($_POST['with_products']) ){
							$delete_products_ids = $this->tasks->deleteIns($_POST['id'] , $_POST['with_products']);
							// удаляем товары из магазина
							if(is_array($delete_products_ids) && count($delete_products_ids) > 0){
								foreach($delete_products_ids as $delete_products_id){
									cms_deleteProduct( $delete_products_id );
								}
							}
						}
						break;
					default:
						break;
				}
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($out);exit;
				exit;
		}
		
		//echo '<pre>'.print_r($this->_prepareGrabbedProductData($this->tasks->getGrabbedProducts()) ,1 ).'</pre>';exit;
		//echo '<pre>'.print_r($this->tasks->getAllIns() ,1 ).'</pre>';exit;
		//echo '<pre>'.print_r($this->tasks->getInstructionsIdsWithGrabbedProducts() ,1 ).'</pre>';exit;
		
		
		$add = array('addJS' => array('ui/js/jquery-ui.min' , 'bpopup',  'dataTable') , 'addCSS' => array("lists" , 'cupertino/jquery-ui.min' , 'bpopup' , 'dataTable'));
		$menu_data  = array('active' => "tasks");
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		$this->load->view( MSPRO_CMS . '/tasks' , array('instructions' => $this->tasks->getAllIns(),
										  'categories'	 => $this->_prepareCats($categories),
										  'settings' => $settings,
										  'manufacturers'	 => $this->_prepareMans($manufacturers),
		                                  'taxclasses'	 => $this->_prepareTaxes($taxclasses),
										  'currencies'	 => $this->_prepareCurs($currencies),
										  'fields' => $this->config->item('fields'),
										  'products_grabbed' => $this->tasks->getInstructionsIdsWithGrabbedProducts()
										   ));
		$this->load->view('footer');
	}
	
	/*
	 *  PRODUCTS FOR AJAX TABLE
	 */
	public function products($target){
		$this->load->model("tasks");
		$this->load->helper("cms/" . $this->config->item("ms_cms"));
		if(!isset($_POST['search']['value'])){ $_POST['search']['value'] = "";}
		$PRODUCTS = $this->tasks->getGrabbedProducts( $target  , $_POST['start'] , $_POST['length'] , $_POST['search']['value'] );
		$products = $PRODUCTS['data'];
		if(count($products) > 0){
			$products = $this->_prepareGrabbedProductData( $products );
		}
		//echo '<pre>'.print_r($products , 1) .'</pre>';exit;
		$res = array( "data" => $products, "recordsTotal" => $PRODUCTS['recordsTotal'], "recordsFiltered" => $PRODUCTS['recordsFiltered'], "draw" => $_POST['draw']);
		echo json_encode($res);exit;
	}
	
	
	
	/*	 LOG PAGE
	 * to write new content to the LOG file 
	   write_file("what to add" , "public/files/log.txt"  , false);
	   write_file("what to add" , "public/files/devlog.txt" , false); 
	 * logs file is in the  "public/files/" folder
	 */
	public function log(){
		$log_info = read_file();
		if($this->input->post("action")){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				header('Content-type: application/json');
				// DEMO MODE
				if($this->config->item('ms_DEMO')){
					echo json_encode(array('response' => "demo"));exit;
				}
				switch($this->input->post("action")){
					case 'refresh':
						echo json_encode(array('response' => $log_info));exit; 
						break;
					case 'clear':
						write_file("" , "public/files/log.txt"  , true);
						$log_info = read_file();
						echo json_encode(array('response' => $log_info));exit; 
						break;
					default;
					/* write используется в коде как 
					write_file("what to add" , "public/files/log.txt"  , false);
					write_file("what to add" , "public/files/devlog.txt" , false); 
					*/
					break;
				}
  				echo $this->input->post("action");exit;
			}
		}
		$menu_data  = array('active' => "log");
		$add = array('addJS' => array() , 'addCSS' => array());
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		$this->load->view('log' , array('info' => $log_info));
		$this->load->view('footer');
	}
	
	
	
	/*
	 * MANUAL LAUNCH PAGE 
	 */
	public function manual(){
		$add = array('addJS' => array() , 'addCSS' => array());
		$menu_data  = array('active' => "contact");
		$this->load->view('header' , $add);
		$this->load->view('menu' , $menu_data);
		$this->load->view('manual' );
		$this->load->view('footer');
	}
	
	
	public function logout(){
		$this->session->unset_userdata('ms_admin_perms');
		redirect('/', 'refresh');
	}
	
	public function reinstall(){
		$this->settings->deleteTables();
		$this->session->set_userdata("multiscraper_restarted" , "yes");
		redirect('/', 'refresh');
	}
	
	
	
	
	
	/************************************   PRIVATE   *****************************/
	private function _prepareCats($categories){
		$out = array();
		if(is_array($categories) && count($categories) > 0){
			foreach($categories as $category){
				$out[$category['category_id']] = $category['name'];
			}
		}
		return $out;
	}
	
	private function _prepareMans($manufacturers){
		$out = array();
		if(is_array($manufacturers) && count($manufacturers) > 0){
			foreach($manufacturers as $manufacturer){
				$out[$manufacturer['manufacturer_id']] = $manufacturer['name'];
			}
		}
		return $out;
	}
	
	private function _prepareTaxes($taxclasses){
	    $out = array();
	    if(is_array($taxclasses) && count($taxclasses) > 0){
	        foreach($taxclasses as $taxclass){
	            $out[$taxclass['tax_class_id']] = $taxclass['title'];
	        }
	    }
	    //echo '<pre>' . print_r($taxclasses , 1) . '</pre>';exit;
	    return $out;
	}
	
	
	private function _prepareCurs($currencies){
		$out = array();
		if(is_array($currencies) && count($currencies) > 0){
			foreach($currencies as $currency){
				$out[$currency['currency_id']] = $currency['title'];
			}
		}
		return $out;
	}
	
	private function _trimArr($arr){
		$out = array();
		if(is_array($arr) && count($arr) > 0){
			foreach($arr as $k => $v){
				$out[] = trim($v);
			}
		}
		return $out;
	}
	
	
	private function _prepareGrabbedProductData( $arr ){
		$out = array();
		if(count($arr) > 0){
			//echo '<pre>'.print_r((array) $arr , 1).'</pre>';exit;
			$product_ids = array();
			foreach($arr as $prod_id => $prod){
				$product_ids[] = $prod_id;
				$arr[$prod_id] = (array) $prod;
			}
			$PRODUCTS = cms_getProducts($product_ids);
			foreach($arr as $prod_id => $prod){
				if(isset($PRODUCTS[$prod_id]) && is_array($PRODUCTS[$prod_id])){
					$arr[$prod_id] = array_merge($prod , $PRODUCTS[$prod_id]);
				}
			}
			//echo '<pre>'.print_r($PRODUCTS , 1).'</pre>';exit;
			$number = 1;
			foreach($arr as $prod){ 
				if( isset($prod['product_id']) && isset($prod['name']) && isset($prod['image']) ) {
					//echo '<pre>'.print_r($product_raw_info , 1).'</pre>';exit;
					$product = array();
					$product[] = $number;$number++;
					$product[] = cms_getImageThumb($prod['image']);
					$product[] = '<a href="' .  cms_createProductStoreLink($prod['product_id']) . '" target="_blank" style="text-decoration:underline;">' . $this->_prepareGrabbedProductName($prod['name']) . '</a>';
					$product[] = '<a href="' . $prod['url'] . '" target="_blank" style="text-decoration:underline;">' . $this->_prepareGrabbedProductName($prod['name']) . '</a>';
					$product[] = $prod['price'];
					$product[] = $prod['quantity'];
					$product[] = $this->lang->line('tasks_form_grabbed_products_popup_table_status_'.$prod['stock_status_id']);
					$product[] = $prod['p_date'];
					$product[] = $prod['p_date_update'];
					$out[] = $product;
				}
			}
		//echo '<pre>'.print_r($out , 1).'</pre>';exit;
		return $out;
		}
	}

	
	private function _prepareGrabbedProductName($name){
		if(function_exists("mb_strlen") && function_exists("mb_substr")){
			return mb_strlen($name) > 40?mb_substr($name , 0 , 40 , "utf-8").'...':$name;
		}else{
			return strlen($name) > 40?substr($name , 0 , 40).'...':$name;
		}
	}
	
}