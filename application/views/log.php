<script type="text/javascript">
Glob = {};
Glob.semafors = {};
Glob.semafors.log_refresh = 1;
Glob.semafors.log_clear = 1;
jQuery(document).ready(function($) {
	// refresh log
	$("#refresh_log_button").on("click" , function (){
		if(Glob.semafors.log_refresh > 0){
			Glob.semafors.log_refresh = 0;
			refresh_toogle_button();
			$.post("" , {"action" : "refresh"} , function(data){
				refresh_toogle_button();
				if(data.response !== undefined){
					// demo
					if(data.response == "demo"){
						alert('<?php echo $this->lang->line('messages_demo'); ?>');
					}else{
						$("#form-content").html(data.response);
					}
				}
				Glob.semafors.log_refresh = 1;
			});
		}
	});
	// clear log
	$("#clear_log_button").on("click" , function (){
		if(Glob.semafors.log_clear > 0){
			Glob.semafors.log_clear = 0;
			clear_toogle_button()
			$.post("" , {"action" : "clear"} , function(data){
				clear_toogle_button()
				if(data.response !== undefined){
					// demo
					if(data.response == "demo"){
						alert('<?php echo $this->lang->line('messages_demo'); ?>');
					}else{
						$("#form-content").html(data.response);
					}
				}
				Glob.semafors.log_clear = 1;
			});
		}
	});
});


function refresh_toogle_button(){
	var loader = $("#refresh_log_loader").css("display");
	if(loader == "none"){
		$("#refresh_log_loader").css("display" , "inline-block");
		$("#refresh_log_button").css("display" , "none");
	}else{
		$("#refresh_log_loader").css("display" , "none");
		$("#refresh_log_button").css("display" , "inline-block");
	}
}


function clear_toogle_button(){
	var loader = $("#clear_log_loader").css("display");
	if(loader == "none"){
		$("#clear_log_loader").css("display" , "inline-block");
		$("#clear_log_button").css("display" , "none");
	}else{
		$("#clear_log_loader").css("display" , "none");
		$("#clear_log_button").css("display" , "inline-block");
	}
}
</script>



            	
            	
<div id="settings" class="blocks">
     <h1><?php echo $this->lang->line('log_title'); ?></h1>
    <div class="text-block">
        <div id="log-form" class="ms_form">
        	<div id="form-content">
        			<?php echo $info; ?>
        	</div>
        </div>
        <div style="float: left;padding: 20px;">
        	<input type="button" value="<?php echo $this->lang->line('log_refresh_button'); ?>" class="btn btn-blue" id="refresh_log_button" />
        	<div id="refresh_log_loader" style="display:none;width:190px;">
        		<img src="<?php echo $this->config->item("base_url"); ?>public/images/loader.gif" >
        	</div>
        	<input type="button" value="<?php echo $this->lang->line('log_clear_button'); ?>" class="btn btn-red" id="clear_log_button" />
        	<div id="clear_log_loader" style="display:none;width:190px;">
        		<img src="<?php echo $this->config->item("base_url"); ?>public/images/loader.gif" >
        	</div>
        </div>
        
        <!--
        <div style="float: right;padding: 20px;">
        	<a href="<?php echo $this->config->item('base_url'); ?>dev/seedevlog" target="_blank"><?php echo $this->lang->line('log_seedevlog_link'); ?></a>
        </div>
         -->
    </div>
    

   
</div>

