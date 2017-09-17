<script type="text/javascript">
jQuery(document).ready(function($) { 
	// char count
	$("#ch_pass_button").on("click" , function(){
		var dis = $("#change_pass-form_div").css("display");
		if(dis == "none"){
			$("#change_pass-form_div").slideDown(500);
		}else{
			$("#change_pass-form_div").slideUp(500);
		}
	});
});
</script>

<div id="success-content" class="<?php echo !isset($notifications['modified'])?"disp_none":""; ?> success-notification">
	<?php echo $this->lang->line('settings_form_success'); ?>
</div>
<div id="nopass-content" class="<?php echo !isset($notifications['nopass'])?"disp_none":""; ?> failure-notification">
	<?php echo $this->lang->line('settings_form_fail_nopass'); ?>
</div>
<div id="nopassmatch-content" class="<?php echo !isset($notifications['nopassmatch'])?"disp_none":""; ?> failure-notification">
	<?php echo $this->lang->line('settings_form_fail_nopassmatch'); ?>
</div>
            	
            	
<div id="settings" class="blocks">
    <h1><?php echo $this->lang->line('seettings_title'); ?></h1>
    <div class="text-block">
        
        
        
        <div id="settings-form" class="ms_form">
        	<div id="form-content">
        				
            			<h2><?php echo $this->lang->line('settings_form_title'); ?></h2>
            			<form id="g_form" method="post" action="<?php echo $this->config->item('base_url'); ?>" >
            				
            				
            				<div class="form-element-wrappers">
            					<div class="form-label-wrappers">
            						<label for="g_email" class="labels" ><?php echo $this->lang->line('settings_form_state'); ?>:</label>
            					</div>
            					<input type="radio" id="radio_state_on" name="state" value="on" style="width:auto;" <?php echo ($settings['state'] == "on")?'checked="checked"':""; ?> />
            					&nbsp;
            					<label for="radio_state_on">
            						ON
            					</label>
            					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            					<input type="radio" id="radio_state_off" name="state" value="off" style="width:auto;" <?php echo ($settings['state'] == "off")?'checked="checked"':""; ?>  />
            					&nbsp;
            					<label for="radio_state_off">
            						OFF
            					</label>
            					<div class="clear"></div>
            				</div>
            				
            				
            				<div class="form-element-wrappers">
            					<div class="form-label-wrappers">
            						<label for="g_email" class="labels" ><?php echo $this->lang->line('settings_form_dev_mode'); ?> <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$('#bpopup_ai_2').bPopup();" /> :</label>
            					</div>
            					<input type="radio" id="radio_dev_mode_on" name="dev_mode" value="on" style="width:auto;" <?php echo ($settings['dev_mode'] == "on")?'checked="checked"':""; ?> />
            					&nbsp;
            					<label for="radio_dev_mode_on">
            						ON
            					</label>
            					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            					<input type="radio" id="radio_dev_mode_off" name="dev_mode" value="off" style="width:auto;" <?php echo ($settings['dev_mode'] == "off")?'checked="checked"':""; ?>  />
            					&nbsp;
            					<label for="radio_dev_mode_off">
            						OFF
            					</label>
            					<div class="clear"></div>
            				</div>
            				
            				<!-- 
            				<div class="form-element-wrappers">
            					<div class="form-label-wrappers">
            						<label for="g_email" class="labels" ><?php echo $this->lang->line('settings_form_inv_mode'); ?> <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$('#bpopup_ai_1').bPopup();" /> :</label>
            					</div>
            					<input type="radio" id="radio_inv_mode_on" name="inv_mode" value="on" style="width:auto;" <?php echo ($settings['proxy'] == "on")?'checked="checked"':""; ?> />
            					&nbsp;
            					<label for="radio_inv_mode_on">
            						ON
            					</label>
            					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            					<input type="radio" id="radio_inv_mode_off" name="inv_mode" value="off" style="width:auto;" <?php echo ($settings['proxy'] == "off")?'checked="checked"':""; ?>  />
            					&nbsp;
            					<label for="radio_inv_mode_off">
            						OFF
            					</label>
            					<div class="clear"></div>
            				</div>
            				 -->
            				
            				<div class="form-element-wrappers">
            					<div class="form-label-wrappers">
            						<label for="g_email" class="labels" ><?php echo $this->lang->line('settings_form_lang'); ?>:</label>
            					</div>
            					<?php 
            						foreach($langs as $key => $val){
            							echo '<input id="radio_lang_'.$key.'" type="radio" name="lang" value="'.$key.'" style="width:auto;" '.(($settings['lang'] == $key)?'checked="checked"':'').' />
            									&nbsp;
            									<label for="radio_lang_'.$key.'">
            										<img src="'.$this->config->item("base_url").'public/images/flags/'.$key.'.png" style="width:30px;margin-bottom: -6px;" />
            									</label>	
            									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            						}
            					?>
            					<div class="clear"></div>
            				</div>
            				
            				<div class="form-element-wrappers">
            					<div class="form-label-wrappers">
            						<label for="num_product" class="labels" ><?php echo $this->lang->line('settings_form_num_product'); ?>:</label>
            					</div>
            					<input  type="number" class="inputs" name="num_product" id="num_product" value="<?php echo $settings['num_product']; ?>"  maxlength="2" style="width: 40px;" />
            					<div class="clear"></div>
            				</div>
            				
            				
            				<div class="form-element-wrappers">
            					<input type="button" value="<?php echo $this->lang->line('settings_form_button_change_pass'); ?>" class="btn btn-blue" id="ch_pass_button" />
            					<br />
            					<div id="change_pass-form_div" class="disp_none">
            							<div class="form-element-wrappers">
			            					<div class="form-label-wrappers">
			            						<label for="old_pass" class="labels" ><?php echo $this->lang->line('settings_form_old_pass'); ?>:</label>
			            					</div>
			            					<input type="text" class="inputs" name="old_pass" id="old_pass" value=""  maxlength="32" />
            							</div>
            							<div class="form-element-wrappers">
			            					<div class="form-label-wrappers">
			            						<label for="new_pass" class="labels" ><?php echo $this->lang->line('settings_form_new_pass'); ?>:</label>
			            					</div>
			            					<input type="text" class="inputs" name="new_pass" id="new_pass" value=""  maxlength="32" />
			            					<div class="clear"></div>
            							</div>
            							<div class="form-element-wrappers">
			            					<div class="form-label-wrappers">
			            						<label for="confirm_pass" class="labels" ><?php echo $this->lang->line('settings_form_confirm_pass'); ?>:</label>
			            					</div>
			            					<input type="text" class="inputs" name="confirm_pass" id="confirm_pass" value=""  maxlength="32" />
			            					<div class="clear"></div>
            							</div>
            					</div>
            				</div>
            				
            				
            				<div class="form-element-wrappers">
            					<a href="<?php echo $this->config->item('base_url'); ?>reinstall" onclick="return confirm('<?php echo $this->lang->line('settings_form_reinstall_confirm'); ?>');" >
            						<input type="button" value="<?php echo $this->lang->line('settings_form_button_reinstall'); ?>" class="btn btn-red" id="reinstall_button" />
            					</a>
            				</div>
            				
            				<div  style="margin:15px;text-align:center;" >
            						<input type="submit" value="<?php echo $this->lang->line('settings_form_button_save'); ?>" class="btn btn-green" id="c_send" />
            				</div>
            				
            				
            			</form>
            	</div>
            </div>
    </div>
</div>

<!--   "INVISIBLE MODE" TIP  //-->
 <div id="bpopup_ai_1" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('settings_form_tip_invisible_mode_1'); ?><br /><br />
    	<?php echo $this->lang->line('settings_form_tip_invisible_mode_2'); ?><br />
    	<?php echo $this->lang->line('settings_form_tip_invisible_mode_22'); ?><br /><br /><br />
    	<?php echo $this->lang->line('settings_form_tip_invisible_mode_3'); ?><br />
    	<?php echo $this->lang->line('settings_form_tip_invisible_mode_4'); ?><br />
    	<?php echo $this->lang->line('settings_form_tip_invisible_mode_5'); ?><br />
    	<?php echo $this->lang->line('settings_form_tip_invisible_mode_6'); ?><br />
    	<?php echo $this->lang->line('settings_form_tip_invisible_mode_7'); ?><br />
    	<?php echo $this->lang->line('settings_form_tip_invisible_mode_8'); ?><br />
</div>
<!--   "DEV MODE" TIP  //-->
 <div id="bpopup_ai_2" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('settings_form_tip_dev_mode_1'); ?><br /><br />
    	<?php echo $this->lang->line('settings_form_tip_dev_mode_2'); ?><br />
    	<?php echo $this->lang->line('settings_form_tip_dev_mode_3'); ?><br />
    	<?php echo $this->lang->line('settings_form_tip_dev_mode_4'); ?>
</div>

