<div id="howtouse" class="blocks">
    <h1><?php echo $this->lang->line('howtouse_title'); ?></h1>
    <div class="text-block">
        <div id="howtouse-form" class="ms_form">
        
            <!--  OVERVIEW -->
        
        	<h2><?php echo $this->lang->line('howtouse_overview_title'); ?></h2>
        	<div class="howtouse_form_content" >
        		<?php echo $this->lang->line('howtouse_overview_1'); ?><br /><br />
        		<?php echo $this->lang->line('howtouse_overview_2'); ?><br /><br />
        		<?php echo $this->lang->line('howtouse_overview_3'); ?><br /><br />
        		<?php echo $this->lang->line('howtouse_overview_4'); ?><br />
        		<h3><?php echo $this->lang->line('howtouse_overview_markets_title'); ?></h3><br /><br /><br />
        		<span style="font-size: 24px;color: #597D84;margin-left: 25px;"><?php echo $this->lang->line('howtouse_overview_markets_core'); ?></span><br />
        		<?php 
        		if(isset($markets['core']) && is_array($markets['core']) && count($markets['core']) > 0){
        			foreach($markets['core'] as $market){
        				echo '  -  ' . $market['title'] . '<br />';
        			}
        		} 
        		if(isset($markets['additional']) && is_array($markets['additional']) && count($markets['additional']) > 0){
        		?>
        		<span style="font-size: 24px;color: #597D84;margin-left: 25px;"><?php echo $this->lang->line('howtouse_overview_markets_additional'); ?></span><br />
        		<?php 
        			foreach($markets['additional'] as $market){
        				echo  '  -  ' . $market['title'] . '<br />';
        			}
        		?>
        		<?php 
        		}
        		?>
        	</div>
        	
        	
        	<!--  USAGE -->
        	
        	 <!-- <h2><?php echo $this->lang->line('howtouse_interface_title'); ?></h2>
        	<div class="howtouse_form_content" >
        		<?php echo $this->lang->line('howtouse_interface_1'); ?><br /><br />
        		<?php echo $this->lang->line('howtouse_interface_2'); ?><br /><br />
        		<?php echo $this->lang->line('howtouse_interface_3'); ?><br /><br />
        		<?php echo $this->lang->line('howtouse_interface_4'); ?><br />
        	</div> -->
        	
        		
        	<!--  CONFIGURATION -->
        	
        	<h2><?php echo $this->lang->line('howtouse_configuration_title'); ?></h2>
        	<div class="howtouse_form_content" >
        		<?php echo $this->lang->line('howtouse_configuration_1'); ?><br /><br />
        		<?php echo $this->lang->line('howtouse_configuration_2'); ?><br /><br />
        		
        		<?php echo $this->lang->line('howtouse_configuration_3'); ?><br />
        		<?php echo $this->lang->line('howtouse_configuration_31'); ?><br /><br />
        		
        		<?php echo $this->lang->line('howtouse_configuration_4'); ?><br />
        		<?php echo $this->lang->line('howtouse_configuration_41'); ?><br />
        		<?php echo $this->lang->line('howtouse_configuration_42'); ?><br /><br />
        		
        		<?php echo $this->lang->line('howtouse_configuration_5'); ?><br /><br />
        		
        		<?php echo $this->lang->line('howtouse_configuration_6'); ?><br />
        		<?php echo $this->lang->line('howtouse_configuration_61'); ?><br /><br />
        		
        		<?php echo $this->lang->line('howtouse_configuration_7'); ?><br />
        		<?php echo $this->lang->line('howtouse_configuration_71'); ?><br />
        		<?php echo $this->lang->line('howtouse_configuration_72'); ?><br /><br />
        		
        		<?php echo $this->lang->line('howtouse_configuration_8'); ?><br />
        		<?php echo $this->lang->line('howtouse_configuration_81'); ?><br />
        		<?php echo $this->lang->line('howtouse_configuration_82'); ?>
        	</div>
        	
        	
        	<!--  PROXY -->
        	
        	 <h2><?php echo $this->lang->line('howtouse_proxy_title'); ?></h2>
        	<div class="howtouse_form_content" >
        		<?php echo $this->lang->line('howtouse_proxy_text'); ?>
        	</div>
        	
        	<!--  SAFETY -->
        	
        	<h2><?php echo $this->lang->line('howtouse_safety_title'); ?></h2>
        	<div class="howtouse_form_content" >
        		<h3><?php echo $this->lang->line('howtouse_safety_1_title'); ?></h3><br /><br /><br />
        		<?php echo $this->lang->line('howtouse_safety_11'); ?><br />
        		<h3><?php echo $this->lang->line('howtouse_safety_2_title'); ?></h3><br /><br /><br />
        		<?php echo $this->lang->line('howtouse_safety_21'); ?><br />
        		<span class="terms_nums">1</span> <?php echo $this->lang->line('howtouse_safety_rename_1'); ?><br />
        		<span class="terms_nums">2</span> <?php echo $this->lang->line('howtouse_safety_rename_2'); ?><br />
        		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        		<?php echo $this->lang->line('howtouse_safety_rename_21'); ?><br />
        		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        		<?php echo $this->lang->line('howtouse_safety_rename_22'); ?><br />
        		<span class="terms_nums">3</span> <?php echo $this->lang->line('howtouse_safety_rename_3'); ?><br />
        		<?php echo $this->lang->line('howtouse_safety_rename_4'); ?><br /><br />
        		<?php echo $this->lang->line('howtouse_safety_rename_5'); ?>
        	</div>
    	
        </div>
    </div>
</div>


<div id="bpopup_proxy_1" class="bpopups">
    	<span class="bpopup_button b-close"><span>X</span></span> 	
    	<img src="<?php echo $this->config->item("base_url"); ?>public/images/proxy.png" style="width:1100px;" />
</div>
