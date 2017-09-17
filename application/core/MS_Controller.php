<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MS_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        
        // load language
        $ln = false;
        if ($this->db->table_exists('multiscraper_settings')){
        	$ln = $this->settings->getSettingsByKey("lang");
        }else{
        	// check installation
        	$this->settings->checkTables();
        }
        if($ln){
        	$this->lang->load($ln , $ln);
        }else{
        	$this->lang->load("en" , "en");
        }
        
        
        // check autorization
        if(!$this->session->userdata("ms_admin_perms") && !isset($_POST['password'])){
        	echo $this->_authPage();exit;
        }else{
        	if(isset($_POST['password'])){
        		$res = $this->settings->checkAuth($this->input->post("password"));
        		if($res){
        			$this->session->unset_userdata('ms_admin_error');
        			$this->session->set_userdata("ms_admin_perms" , "admin");
        			// check installation one more time while logging in
        			$this->settings->checkTables();
        		}else{
        			$this->session->set_userdata("ms_admin_error" , "error");
        			echo $this->_authPage();exit;
        		}
        	}
        }
        
    }
    
    
    
    private function _authPage(){
    	return '<!DOCTYPE html>
					<html lang="'.$this->lang->line('language_key').'">
						<head>
							<meta charset="utf-8">
							<title>'.$this->lang->line('auth_title').'</title>
							<link rel="shortcut icon" href="'.$this->config->item("base_url").'favicon.ico" type="image/x-icon" />
							<link rel="stylesheet" type="text/css" href="'.$this->config->item("base_url").'public/css/common.css" />
							<link rel="stylesheet" type="text/css" href="'.$this->config->item("base_url").'public/css/terminaldosis.css" />
						</head>
						<body>
							<form action="'.$this->config->item("base_url").'" name="auth" method="post" />
							<div style="width:100%;margin-top:300px;text-align:center;">
								<p style="font-size: 20px;">'.$this->lang->line('auth_form_title').'</p>
								<br/>
								'.($this->session->userdata("ms_admin_error")?'<p style="margin: 0;padding: 0;color:red;">'.$this->lang->line('auth_error').'</p><br/>':"").'
								<input class="inputs" type="password" name="password" value="" />
								<br/>
								<input type="submit" value="'.$this->lang->line('auth_button_submit').'" style="margin-top:20px;" />
								
							</div>
						</body>
					</html>';
    }
}