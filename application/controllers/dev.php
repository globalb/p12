<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dev extends MS_Controller {

	
	
	/*
	 *   index
	 */
	public function index(){
		echo 'index function of DEV controller';exit;
	}
	
	
	/*
	 *   test
	 */
	public function test(){
		echo phpinfo();exit;
	}
	
	
	
	/****************   LOG FUNCTIONS  *****************/
	
	public function log(){
		/*  по умолчанию "public/files/log.txt"  */
		// есть ещё devlog
		//$log_info = read_file("public/files/log.txt");
		
		// to clear log
		//write_file("");
		
		// to write something in log
		// write_file(" what to add to con" , "public/files/log.txt");
		// if we want to clear before add
		// write_file(" what to add to con" , "public/files/log.txt" , true);
		
		// get log file info
		$log_info = read_file();
		//echo '<pre>'.print_r($log_info , 1).'</pre>';
		
		write_file('clear<br />' , "public/files/devlog.txt");
		// show devlog
		$log_info = read_file("public/files/devlog.txt");
		
		
		// clear devlog
		//write_file("clear" , "public/files/devlog.txt" , true);
		
		// write to devlog
		//write_file("clear\r" , "public/files/devlog.txt");
		
		
		echo '<pre>'.print_r($log_info , 1).'</pre>';
		
	}
	
	public function seedevlog(){
		$log_info = read_file("public/files/devlog.txt");
		echo '<pre>'.print_r($log_info , 1).'</pre>';
	}
	
	
	
	/****************   GET SETTINGS  *****************/
	public function settings(){
		$settings = $this->settings->getSettings();
		echo '<pre>'.print_r($settings , 1).'</pre>';
	}
	
	
	/****************   GET CONFIG  *****************/
	public function config(){
		echo '<pre>'.print_r($this->config , 1).'</pre>';
	}
	
	
	public function setZeroQuantity(){
		$this->load->helper("cms/" . $this->config->item("ms_cms"));
		$cms_loader = cms_init();
		// cms_setZeroQuantity('1015' , $cms_loader);
		//cms_setOutOfStock('1015' , $cms_loader);
	}
	
	
	/****************   GET DATABASE DUMP  *****************/
	public function getDatabaseDump(){
	    $out = '';
	    $tables = array('multiscraper_tasks' , 'multiscraper_ins');
	    foreach($tables as $table){
	        $out .= 'INSERT INTO `' . $table . '` (';
	        $fields = $this->db->list_fields($table);
	        foreach ($fields as $field){
	             $out .= '`' . $field . '`,';
	        }
	        $out = substr($out , 0 , -1) . ') VALUES ';
	        $values = $this->db->query("SELECT * FROM `$table` ORDER BY `id`");
            foreach ($values->result_array() as $row){
                $out .= '( ';
                //echo '<pre>'.print_r($row, 1).'</pre>';exit;
                foreach($row as $k => $v){
                    if(is_numeric($v)){
                        $out .= $v . ', ';
                    }else{
                        $out .= "'" . addslashes($v) . "', ";
                    }
                }
                $out = substr($out , 0 , -2) .'), ';
            }
	        $out =  substr($out , 0 , -2) . ';<br /><br />';
	    }
	    echo $out;
	}
	
	
	/*
	 *  проверка включен ли devmode : if($this->config->item('ms_devmode')){
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 */
	
}	