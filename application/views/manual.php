<script type="text/javascript">
Glob = {};
Glob.manual_launch = 0;
var ConsoleMode = false;

function manual(){
	if(Glob.manual_launch < 1){
		Glob.manual_launch = 1;
		toogle_button();
		$.post('process' , {} ,
				function(data){
				Glob.manual_launch = 0;
				toogle_button();
					if(ConsoleMode){
						console.log(data);
					}
					if(data.fail !== undefined){
						switch(data.fail){
							case "switched_off":
								alert('<?php echo $this->lang->line('manual_page_mspro_switched_off'); ?>');
								break;
							case "no_tasks":
								alert('<?php echo $this->lang->line('manual_page_mspro_no_tasks'); ?>');
								break;
							case "demo":
								alert('<?php echo $this->lang->line('messages_demo'); ?>');
								break;
							case "trial":
								alert('<?php echo $this->lang->line('messages_trial'); ?>');
								break;
							default:
								break;
						}
					}else{
						location.href = "log";
					}
				}
		);
	}
}


function toogle_button(){
	var loader = $("#loader").css("display");
	if(loader == "none"){
		$("#loader").css("display" , "inline-block");
		$("#manual_button").css("display" , "none");
	}else{
		$("#loader").css("display" , "none");
		$("#manual_button").css("display" , "inline-block");
	}
}
</script>



            	
            	
<div class="blocks">
    <h1><?php echo $this->lang->line('manual_title'); ?></h1>
    <div class="text-block" style="min-height: 450px;">
        
        
        
        <div id="tasks-form" class="ms_form">
        	<div id="form-content" style="font-size: 1.4em;">
        		<?php echo $this->lang->line('manual_page_1'); ?>
        		<br />
        		<?php echo $this->lang->line('manual_page_2'); ?>
        		<br />
        		<br />
        		<br />
        		<?php echo $this->lang->line('manual_page_3'); ?>
        		<br />
        		<?php echo $this->lang->line('manual_page_4'); ?>
        		<br />
        		<br />
        		<?php echo $this->lang->line('manual_page_5'); ?>
        		<br />
        		<br />
        		<?php echo $this->lang->line('manual_page_6'); ?>
        		<br />
        		<br />
        		<input type="button" id="manual_button" value="<?php echo $this->lang->line('manual_button'); ?>" class="btn btn-blue actions_button" style="width: 80px;" onclick="manual();" />
        		<div id="loader" style="display:none;">
        			<img src="<?php echo $this->config->item("base_url"); ?>public/images/loader.gif" ><br />
        			<span style="color:#DE1D73;font-size: 18px;">
        				<?php echo $this->lang->line('manual_waiting_message'); ?>
        			</span>
        		</div>
        	</div>
        </div>
    </div>
</div>