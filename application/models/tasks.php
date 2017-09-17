<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Model {
	
	// tables
	// multiscraper_settings
	// multiscraper_ins
	// multiscraper_tasks
	
	
	
	
	/***********************************     ОПЕРАЦИИ С ИНСТРУКЦИЯМИ    ****************************************/
	
	/*
	 *  ПОЛУЧЕМ ВСЕ ИНСТРУКЦИИ
	 */
	function getAllIns(){
		$out = array();
		$query = $this->db->get('multiscraper_ins');
		if($query->num_rows() > 0){
		    $q = $query->result();
		    for ($i = 0, $c = count($q); $i < $c; $i++){
		        $ins = (array) $q[$i];
		        $ins['category_urls'] = explode($this->config->item('ms_cat_product_delimiter') , $ins['category_urls']);
		        $ins['product_urls'] = explode($this->config->item('ms_cat_product_delimiter') , $ins['product_urls']);
		        $ins['category_id'] = $this->_cutEmptyValues ( explode("," , $ins['category_id']));
		        $ins['fields_to_insert'] = $this->_cutEmptyValues ( explode("," , $ins['fields_to_insert']));
		        $ins['fields_to_update'] = $this->_cutEmptyValues ( explode("," , $ins['fields_to_update']));
		        $ins['products_found_grabbed'] = $this->joinInsProducts($ins['id']);
		        $other_data = array();
		        if(isset($q[$i]->other_data)){
		            $other_data = (array) json_decode($q[$i]->other_data);
		        }
		        if(is_array($other_data) && count($other_data) > 0){
		            foreach($other_data as $key => $value){
		                $ins[$key] = $value;
		            }
		        }
		        $out[] = $ins;
		    }
		}
		//echo '<pre>'.print_r($out ,1 ).'</pre>';exit;
		return $out;
	}
	
	function _cutEmptyValues($arr){
		if(count($arr) > 0){
			foreach($arr as $key => $val){
				if(strlen($val) < 1){
					unset($arr[$key]);
				}
			}
		}
		return $arr;
	}
	
	
	/*
	 * получаем колличество found/grabbed products 
	 * если $ins_id - по определённой инструкции
	 * 
	 * return array("found" => 5675, "grabbed" => 310);
	 */
	function joinInsProducts($ins_id = false){
	    $out = array("found" => 0, "grabbed" => 0);
	    $this->db->select("product_id");
	    $add_where = '';
	    if($ins_id){
	        $this->db->where('ins_id' , $ins_id);
	    }
	    $this->db->where('owner' , "product");
	    $query = $this->db->get('multiscraper_tasks');
	    if($query->num_rows() > 0){
	        $q = $query->result();
	        for ($i = 0, $c = count($q); $i < $c; $i++){
	            $out["found"] += 1;
	            if($q[$i]->product_id > 0){
	                $out["grabbed"] += 1;
	            }
	        }
	    }
	    return $out;
	}

	
	
	function getInsById($id){
		$this->db->where("id" , $id);
		$res = $this->db->get('multiscraper_ins');
		$res = (array) $res->result();
		if(isset($res[0])){
			$res = (array) $res[0];
			$other_data = (array) json_decode($res['other_data']);
			if(is_array($other_data) && count($other_data) > 0){
				foreach($other_data as $key => $value){
					$res[$key] = $value;
				}
				unset($res['other_data']);
			}
			return $res;
		}
		return false;
	}
	
	
	
	/*
	 *  create new INSTRUCTION
	 */
	function createIns($data){
	    //echo '<pre>'.print_r($data ,1 ).'</pre>';
	    if(empty($data['category_urls']) && empty($data['product_urls'])){
	        echo 'empty ins';exit;
	    }
	    // prepare category_urls
	    $cat_urls = array();
	    $category_urls = explode ("\n" , $data['category_urls']) ;
	    if(count($category_urls) > 0){
	        foreach($category_urls as $category_url){
	            if(strlen(trim($category_url)) > 5){
	                $cat_urls[] = trim($category_url);
	            }
	        }
	    }
	    $data['category_urls'] = implode($this->config->item('ms_cat_product_delimiter') , $cat_urls);
	
	    // prepare product_urls
	    $prod_urls = array();
	    if(isset($data['product_urls'])){
	        $product_urls = explode ("\n" , $data['product_urls']) ;
	        if(count($product_urls) > 0){
	            foreach($product_urls as $product_url){
	                if(strlen(trim($product_url)) > 5){
	                    $prod_urls[] = trim($product_url);
	                }
	            }
	        }
	    }
	    $data['product_urls'] = implode($this->config->item('ms_cat_product_delimiter') , $prod_urls);
	
	    $data['category_id'] = substr($data['category_id'] , -1) == ","?substr($data['category_id'] , 0 ,  -1):$data['category_id'];
	    if(isset($data['fields_to_insert'])){
	        $data['fields_to_insert'] = substr($data['fields_to_insert'] , -1) == ","?substr($data['fields_to_insert'] , 0 ,  -1):$data['fields_to_insert'];
	    }
	    if(isset($data['fields_to_update'])){
	        $data['fields_to_update'] = substr($data['fields_to_update'] , -1) == ","?substr($data['fields_to_update'] , 0 ,  -1):$data['fields_to_update'];
	    }
	    $data['margin_fixed'] = isset($data['margin_fixed'])?(float) $data['margin_fixed']:0;
	    $data['margin_relative'] = isset($data['margin_relative'])?(float) $data['margin_relative']:0;
	    $data['donot_update_price'] = (int) $data['donot_update_price'];
	    $data['create_disabled'] = (int) $data['create_disabled'];
	    $data['comment'] = isset($data['comment'])?addslashes($data['comment']):0;
	    $other_data = array('get_options' => $data['get_options'],
	        'do_not_update_options' => $data['do_not_update_options'],
	        'tax_class_id' => $data['tax_class_id'],
	        'do_not_update_manufacturer' => $data['do_not_update_manufacturer'],
	        'do_not_update_taxclass' => $data['do_not_update_taxclass'],
	        'main_image_limit' => $data['main_image_limit'],
	        'description_image_limit' => $data['description_image_limit'],
	        'do_not_upload_description_image' => $data['do_not_upload_description_image']
	    );
	    $data['other_data'] = json_encode($other_data);
	    unset($data['get_options']);
	    unset($data['do_not_update_options']);
	    unset($data['tax_class_id']);
	    unset($data['do_not_update_manufacturer']);
	    unset($data['do_not_update_taxclass']);
	    unset($data['main_image_limit']);
	    unset($data['description_image_limit']);
	    unset($data['do_not_upload_description_image']);
	
	    // INSERTING INSTRUCTION
	    //$this->db->trans_start();
	    $this->db->insert('multiscraper_ins', $data);
	    $ins_id = $this->db->insert_id();
	
	    // INSERTING TASKS
	    if(is_array($cat_urls) && count($cat_urls) > 0){
	        foreach($cat_urls as $cat){
	            $this->insertTask($cat , $ins_id , 0 , "category");
	        }
	    }
	    if(is_array($prod_urls) && count($prod_urls) > 0){
	        foreach($prod_urls as $prod){
	            $this->insertTask($prod , $ins_id , 0);
	        }
	    }
	    //$this->db->trans_complete();
	}
	
	
	/*
	 *  update INSTRUCTION
	 */
	function updateIns($id , $data){
		unset($data['category_urls']);
		unset($data['product_urls']);
		$other_data = array('get_options' => $data['get_options'],
		                    'do_not_update_options' => $data['do_not_update_options'],
		                    'tax_class_id' => $data['tax_class_id'],
		                    'do_not_update_manufacturer' => $data['do_not_update_manufacturer'],
		                    'do_not_update_taxclass' => $data['do_not_update_taxclass'],
                		    'main_image_limit' => $data['main_image_limit'],
                		    'description_image_limit' => $data['description_image_limit'],
                		    'do_not_upload_description_image' => $data['do_not_upload_description_image']
		                   );
		$data['other_data'] = json_encode($other_data);
		unset($data['get_options']);
		unset($data['do_not_update_options']);
		unset($data['tax_class_id']);
		unset($data['do_not_update_manufacturer']);
		unset($data['do_not_update_taxclass']);
		unset($data['main_image_limit']);
		unset($data['description_image_limit']);
		unset($data['do_not_upload_description_image']);
		$data['category_id'] = substr($data['category_id'] , -1) == ","?substr($data['category_id'] , 0 ,  -1):$data['category_id'];
		//$data['fields_to_insert'] = substr($data['fields_to_insert'] , -1) == ","?substr($data['fields_to_insert'] , 0 ,  -1):$data['fields_to_insert'];
		//$data['fields_to_update'] = substr($data['fields_to_update'] , -1) == ","?substr($data['fields_to_update'] , 0 ,  -1):$data['fields_to_update'];
		$data['comment'] = addslashes($data['comment']);
		//$this->db->trans_start();
		$this->db->where('id', $id);
		$this->db->update('multiscraper_ins', $data );
		//$this->db->trans_complete();
	}
	
	/*
	 *  DELETE INS  
	 */
	function deleteIns($id , $with_products = false){
		$delete_products_ids = array();
		// если удалять с товарами то через цикл
		if($with_products == "true"){
			$res = $this->db->get_where('multiscraper_tasks', array('ins_id' => $id));
			if ($res->num_rows() > 0){
			   foreach ($res->result() as $row){
			   		if(isset($row->product_id) && $row->product_id > 0){
			   			$delete_products_ids[] = $row->product_id;
			   		}
					$this->db->delete('multiscraper_tasks' , array('id' => $row->id) );
			   }
			}
		// иначе просто грохаем по ins_id
		}else{
			$this->db->delete('multiscraper_tasks', array('ins_id' => $id));
		}
		// напоследок грохаем саму инструкцию
		$this->db->delete('multiscraper_ins', array('id' => $id));
		return $delete_products_ids;exit;
	}

	
	/*
	 * RESTART 
	 *  что делает рестарт:
	 *  	 - удалит все category_tasks по этой инструкции и заменит их оригинальными
	 *   	 - по каждому product_task проверит он с оригинальной инструкции или с раcпарсенного листинга
	 *   			- если с инструкции : ставим ему parsed = 0
	 *   			- если с листинга, то не трогаем (при внесении нового таска при парсинге листинга мы всё равно его проверим и поставим parsed = 0 )
	 */
	function restartIns($id){
		$instruction_info = $this->getInsById($id);
		// работаем с category_urls
		$this->db->delete('multiscraper_tasks' , array('ins_id' => $id , 'owner' => "category"));
		$category_urls = explode($this->config->item('ms_cat_product_delimiter') , $instruction_info['category_urls']);
		if(count($category_urls) > 0){
			foreach($category_urls as $category_url){
				if(strlen(trim($category_url)) > 5){
					$this->insertTask($category_url , $id , 0 , "category");
				}
			}
		}
		// работаем с product_urls
		$product_urls = explode($this->config->item('ms_cat_product_delimiter') , $instruction_info['product_urls']);
		if(count($product_urls) > 0){
			foreach($product_urls as $key => $product_url){
				$product_urls[$key] = trim($product_url);
			}
		}
		//echo '<pre>'.print_r($product_urls , 1).'</pre>';exit;
		$product_tasks = $this->getTasksByInsID($id);
		if(count($product_tasks) > 0){
			foreach($product_tasks as $product_task){
				if(in_array( trim($product_task->url) , $product_urls)){
					$this->db->update('multiscraper_tasks' , array("parsed" => 0 , "busy" => 0) , array('id' => $product_task->id ));
				}
			}
		}
		exit;
	}
	
	
	
	function setPriority($ins_id , $priority){
		$this->db->where("id" , $ins_id);
		$this->db->update('multiscraper_ins', array('priority' => $priority));
		return true;
	}
	
	function setSwitch($ins_id , $switch){
		$this->db->where("id" , $ins_id);
		$this->db->update('multiscraper_ins', array('state' => $switch));
		return true;
	}

	
	
	
	function getGrabbedProducts($target , $start = 0 , $length = 10 , $search = false){
		$out = array();	
		$data = array();
		$recordsTotal = 0;
		$recordsFiltered = 0;
		
		// GET DATA 
		if($target !== "all"){ $this->db->where("ins_id" , $target); }
		$this->db->where("owner" , "product");
		$this->db->where("product_id > " , 0);
		if($search){
			$this->db->like('p_date', $search);
			$this->db->or_like('p_date_update', $search);  
			$this->db->or_like('product_name', $search);  
			$this->db->or_like('product_price', $search);  
		}
		$this->db->order_by("p_date " , "DESC");
		$this->db->limit($length, $start);
		$query = $this->db->get('multiscraper_tasks');
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
    			$data[$row->product_id] = $row;
			}
		}
		$query->free_result();
		

		// GET FILTERED RECORDS
		if($target !== "all"){ $this->db->where("ins_id" , $target); }
		if($search){
			$this->db->like('p_date', $search);
			$this->db->or_like('p_date_update', $search);  
			$this->db->or_like('product_name', $search);  
			$this->db->or_like('product_price', $search);  
		}
		$query = $this->db->get('multiscraper_tasks');
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				if($row->product_id > 0 && $row->owner == "product"){
					$recordsFiltered++;
				}
			}
		}
		$query->free_result();
		
		
		// GET TOTAL RECORDS
		if($target !== "all"){
			$this->db->where("ins_id" , $target);
		}
		$this->db->where("owner" , "product");
		$this->db->where("product_id > " , 0);
		$this->db->from('multiscraper_tasks');
		$recordsTotal = $this->db->count_all_results();
		
		
		$out['data'] = $data;
		$out['recordsTotal'] = $recordsTotal;
		$out['recordsFiltered'] = $recordsFiltered;
		//echo '<pre>'.print_r($out , 1) .'</pre>';exit;
		return $out;
	}
	

	
	/*
	 * возврацает массив ins_id у которых есть grabbed products 
	 */
	function getInstructionsIdsWithGrabbedProducts(){
		$out = array();
		$this->db->select("multiscraper_tasks.ins_id");
		$this->db->where("multiscraper_tasks.owner" , "product");
		$this->db->where("multiscraper_tasks.product_id > " , 0);
		$this->db->group_by("multiscraper_tasks.ins_id");
		$query = $this->db->get('multiscraper_tasks');
		foreach ($query->result() as $row){
			if(!in_array($row->ins_id , $out)){
				$out[] = $row->ins_id;
			}
		}
		//echo '<pre>'.print_r($out , 1) .'</pre>';exit;
		return $out;
		
	}
	
	
	/***********************************     ОПЕРАЦИИ С ТАСКАМИ НА ПАРСИНГ    ****************************************/
	
	
	
	/*
	 *   ДОБАВЛЯЕМ TASK НА ПАРСИНГ В multiscraper_tasks
	 *   
	 *    проверяем существует ли таск с таким URL (по этой инструкции)
	 *   			-  если существует, просто ставим ему parsed = 0 
	 *   
	 *   return task id
	 */
	function insertTask($url , $ins_id , $parent_id , $owner = "product"){
		if(strlen(trim($url)) > 0){
			$task_exists = $this->findTask($url , $ins_id);
			if($task_exists){
				//echo 'task exists: URL:' . $url . ' ins_id: ' . $ins_id . '<br />';
				$this->db->update('multiscraper_tasks' , array("parsed" => 0) , array("id" => $task_exists->id ));
				return $task_exists->id;
				
			}else{
				//echo 'task NOT exists: URL:' . $url . ' ins_id: ' . $ins_id . '<br />';
				$this->db->insert('multiscraper_tasks', array(
															'url' => $url,
															'owner' => $owner,
															'ins_id' => $ins_id,
				                                            'busy' => 0,
															'parent_id' => $parent_id,
				 											'p_date' => "0000-00-00 00:00:00",
															'p_date_update' => "0000-00-00 00:00:00"
															));
				return $this->db->insert_id();
			}
		}
	}
	
	
	/*
	 *   УДАЛЯЕМ TASK НА ПАРСИНГ
	 *   если $with_product, то удаляем и товар с магазина
	 */
	function deleteTask($task_id , $with_product = false){
		// удаляем сам таск
		$this->db->delete("multiscraper_tasks" ,  array('id' => $task_id));
		// удаляем товар, если есть
		
	}
	

	
	/*
	 *  найти таск по url и ins_id
	 */
	function findTask($url , $ins_id){
		$url = $this->prepareURLforCHECKING($url);
		// костыль для tinydeal
		if(strpos($url , "tinydeal") > 0){
			$this->db->like('url', $url); 
		}else{
			$this->db->where("url" , $url);
		}
		$this->db->where("ins_id" , $ins_id);
		$res = $this->db->get('multiscraper_tasks');
		$res = (array) $res->result();
		if(count($res) > 0){
			return $res[0];
		}
		return false;
	}
	
	/*
	 * некоторый ебучие магазины ставят random или token к урлу товара
	 * 
	 * в этой функции пытаемся его отсечь 
	 */
	function prepareURLforCHECKING($url){
		// костыль для tinydeal
		if(strpos($url , "tinydeal") > 0){
			$res = explode(".html?" , $url);
			if(count($res) > 1){
				return $res[0];
			}
		}
		
		return $url;
	}
	
	/*
	 *  получить все таски для определённой инструкции
	 */
	function getTasksByInsID($ins_id , $owner = "product"){
		$this->db->where("ins_id" , $ins_id);
		$this->db->where("owner" , $owner);
		$res = $this->db->get('multiscraper_tasks');
		return (array) $res->result();
	}
	
	
	/*
	 *  обновляем таск после парсинга
	 */
	function updateTask($task_id , $inserted_id = false , $just_created = false , $category_task = false , $product_name = false , $product_price = false){
		$this->db->where("id" , $task_id);
		if(!$category_task){
			$data = array('parsed' => 1);
		}
		if($just_created && !$category_task){
			$data['p_date'] = date("Y-m-d H:i:s");
		}
		$data['p_date_update'] = date("Y-m-d H:i:s");
		if($inserted_id && !$category_task){
			$data['product_id'] = $inserted_id;
		}
		if($product_name){
			$data['product_name'] = $product_name;
		}
		if($product_price){
			$data['product_price'] = $product_price;
		}
		$this->db->update('multiscraper_tasks' , $data );
	}
	
	
	
	
	
	
	
	/***********************************     ЗАДАНИЕ НА PROCESS    ****************************************/
	/*
	 *  получает задание для process
	 *  
	 *  
	 *   return false if no tasks in the queque
	 *   
	 *   return tasks as array if extists
	 *    $array(
	 *    		[0] => array(
	 *		    		"owner" => "category" or "product"
	 *    				"url" => ""
	 *		    		"" =>
	 *					),
	 *			[1] => array(),
	 *    		etc  
	 *    )
	 *   
	 *   $num - колличество товаров за 1 проход
	 *   $parse_cat_after_num - через какое колличество товаров парсить категорию
	 *    
	 *    
	 *    если "owner" = "category" возвращает только 1 задание
	 */
	
	function getTasksForProcess($num){
		// если в $num какая то хуйня, то ставим 1 товар
		if(!is_numeric($num) || $num < 0 ){
			$num = 1;
		}
		
		// проверяем подошла ли очередь парсить листинг
		$category_task_queque = false;
		if($this->config->item('ms_category_queque') <= $this->settings->getSettingsByKey("category_queque")){
			$category_task_queque = true;
		}
		
		// вытаскиваем все таски включённых иструкций
		$this->db->select('multiscraper_ins.category_id,
						   multiscraper_ins.manufacturer_id,
						   multiscraper_ins.margin_fixed,
						   multiscraper_ins.margin_relative,
						   multiscraper_ins.state,
						   multiscraper_tasks.*
							 ');
		$this->db->from('multiscraper_ins');
		$this->db->join('multiscraper_tasks', 'multiscraper_ins.id = multiscraper_tasks.ins_id' , "right");
		$this->db->where('multiscraper_ins.state > ' , '0');
		$this->db->where('multiscraper_tasks.busy < ' , '1');
		// вначале нераспарсенные
		$this->db->order_by("multiscraper_tasks.parsed", "asc");
		// приоритетные выше
		$this->db->order_by("multiscraper_ins.priority", "desc");
		// по дате обновления (старые сверху)
		$this->db->order_by("multiscraper_tasks.p_date_update", "asc"); 
		$query = $this->db->get();
		//$str = $this->db->last_query();
		//echo "QUERY : " . $str;exit;
		
	
		$tasks_array = array();
		$category_task_exists = false;
		$result = $query->result();
		if($query->num_rows() > 0){
			foreach ($result as $row){
				//var_dump($row);exit;
				// сразу проверяем листинг: если есть и подошла его очередь - возвращаем задание на листинг
				if($row->owner == "category"){
					$category_task_exists = true;
					if($category_task_queque){
						return array( (array) $row);exit;
					}
				}else{
					$tasks_array[] = (array) $row;
				}
			}
		}
		//echo '<pre>' . print_r( $tasks_array , 1) . '</pre>';exit;
		
		if(count($tasks_array) > 0){
			// если есть хоть один таск по продуктам возвращаем его
			return array_slice($tasks_array , 0 , $num);
		}else{
			// если нет тасков по товарам но есть по категории, то возвращаем даже если не подошла очередь
			if($category_task_exists){
				foreach($result as $row){
					if($row->owner == "category"){
						return array( (array) $row);exit;
					}
				}
			}
		}
		
		return false;
			
	}
	
	
	function makeProductTasksBusy($tasks){
		foreach($tasks as $task){
			$query = "UPDATE `multiscraper_tasks` SET `busy` = 1 WHERE id = " . $task['id'];
			//echo $query;
			$this->db->query($query);
		}
	}
	
	function makeProductTaskFree($task){
		$this->db->query("UPDATE `multiscraper_tasks` SET `busy` = 0 WHERE id = " . $task['id']);
	}
	
	function unBusyAllTasks(){
	    $this->db->query("UPDATE `multiscraper_tasks` SET `busy` = 0 ");
	}
	
	
	
	
	
}