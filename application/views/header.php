<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Multiscraper</title>
	<link rel="shortcut icon" href="<?php echo $this->config->item('base_url'); ?>favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>public/css/common.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>public/css/menu.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>public/css/blocks.css" />
	
	<?php 
		if(isset($addCSS)){
			foreach($addCSS as $css){
				echo '<link rel="stylesheet" type="text/css" href="'.$this->config->item('base_url').'public/css/'.$css.'.css" />';
			}
		}
	
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>public/css/terminaldosis.css" />
	
	
	<script src="<?php echo $this->config->item('base_url'); ?>public/scripts/jquery.min.js"></script>
	<?php 
		if(isset($addJS)){
			foreach($addJS as $js){
					echo '<script type="text/javascript"  src="'.$this->config->item('base_url').'public/scripts/'.$js.'.js" /></script>';
			}
		}
	
	?>
	
	
</head>
<body>

<div id="container">

	<div id="header">
		<img src="<?php echo $this->config->item('base_url'); ?>public/images/scraper.jpg" id="header_img"  />
		 <div id="logo_text_wrapper">
		 	<span id="" style="font-size: 40px;"><?php echo $this->lang->line('title'); ?></span>
		 	<br />
		 	<span id="" style="color:#DE1D73;font-size: 18px;"><?php echo $this->lang->line('under_title'); ?></span>
		 </div>
		 <div style="float: right;margin: 10px;">
		 	<a href="<?php echo $this->config->item('base_url'); ?>logout"> 
		 		<img src="<?php echo $this->config->item('base_url'); ?>public/images/exit.jpg" style="width: 50px;height: 50px;" title="Exit from multiscraper admin panel"/>
		 	</a>
		 </div>
	</div>
