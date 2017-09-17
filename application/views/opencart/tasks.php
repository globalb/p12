<script type="text/javascript">
var ConsoleMode = <?php echo GLOBAL_DEBUG_SEMAFOR > 0?"true":"false"; ?>;
var Tasks = {};
var Categories = {};
var Manufacturers = {};
var Taxclasses = {};
var Currencies = {};
var Fields = {};
var TableSemafors = {};
<?php
if(is_array($instructions) && count($instructions) > 0){
	foreach($instructions as $instruction){
		echo 'Tasks.task' . $instruction['id'] . ' = {};';
		echo 'Tasks.task' . $instruction['id'] . '.state = ' . $instruction['state'] . ';';
		echo 'Tasks.task' . $instruction['id'] . '.priority = ' . $instruction['priority'] . ';';
		echo 'Tasks.task' . $instruction['id'] . '.name = "' . addslashes($instruction['name']) . '";';
		
		echo 'Tasks.task' . $instruction['id'] . '.category_urls = "";';
		if(is_array($instruction['category_urls']) && count($instruction['category_urls']) > 0){
			foreach($instruction['category_urls'] as $category_url){
				echo 'Tasks.task' . $instruction['id'] . '.category_urls += "'.addslashes($category_url).'\n";
		    ';
			}
		}
		
		echo 'Tasks.task' . $instruction['id'] . '.product_urls = "";';
		if(is_array($instruction['product_urls']) && count($instruction['product_urls']) > 0){
			foreach($instruction['product_urls'] as $product_url){
				echo 'Tasks.task' . $instruction['id'] . '.product_urls += "'.addslashes($product_url).'\n";
		    ';
			}
		}
		
		echo 'Tasks.task' . $instruction['id'] . '.category_id = new Array();';
		if(is_array($instruction['category_id']) && count($instruction['category_id']) > 0){
			foreach($instruction['category_id'] as $category_id){
				echo 'Tasks.task' . $instruction['id'] . '.category_id.push('.$category_id.') ;';
			}
		}
		
		echo 'Tasks.task' . $instruction['id'] . '.manufacturer = "' . $instruction['manufacturer_id'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.do_not_update_manufacturer = "' . ((isset($instruction['do_not_update_manufacturer']) && $instruction['do_not_update_manufacturer'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.taxclass = "' . ((isset($instruction['tax_class_id']) && $instruction['tax_class_id'] > 0)?$instruction['tax_class_id']:0) . '";';
		echo 'Tasks.task' . $instruction['id'] . '.do_not_update_taxclass = "' . ((isset($instruction['do_not_update_taxclass']) && $instruction['do_not_update_taxclass'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.main_image_limit = "' . ((isset($instruction['main_image_limit']) && $instruction['main_image_limit'] > -1)?$instruction['main_image_limit']:-1) . '";';
		echo 'Tasks.task' . $instruction['id'] . '.description_image_limit = "' . ((isset($instruction['description_image_limit']) && $instruction['description_image_limit'] > -1)?$instruction['description_image_limit']:-1) . '";';
		echo 'Tasks.task' . $instruction['id'] . '.do_not_upload_description_image = "' . ((isset($instruction['do_not_upload_description_image']) && $instruction['do_not_upload_description_image'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.image_folder = "' . $instruction['image_folder'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.donor_currency = "' . $instruction['donor_currency'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.products_quantity = "' . $instruction['products_quantity'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.margin_fixed = "' . $instruction['margin_fixed'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.margin_relative = "' . $instruction['margin_relative'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.what_to_do_product_not_exists = ' . $instruction['what_to_do_product_not_exists'] . ';';
		echo 'Tasks.task' . $instruction['id'] . '.donot_update_price = "' . ($instruction['donot_update_price'] > 0?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.create_disabled = "' . ($instruction['create_disabled'] > 0?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.get_options = "' . ((isset($instruction['get_options']) && $instruction['get_options'] > 0)?"checked":"") . '";';
		echo 'Tasks.task' . $instruction['id'] . '.do_not_update_options = "' . ((isset($instruction['do_not_update_options']) && $instruction['do_not_update_options'] > 0)?"checked":"") . '";';
		
		echo 'Tasks.task' . $instruction['id'] . '.fields_to_insert = new Array();';
		if(is_array($instruction['fields_to_insert']) && count($instruction['fields_to_insert']) > 0){
			foreach($instruction['fields_to_insert'] as $field_to_insert){
				//echo 'Tasks.task' . $instruction['id'] . '.fields_to_insert.push("'.$field_to_insert.'") ;';
			}
		}
		
		echo 'Tasks.task' . $instruction['id'] . '.fields_to_update = new Array();';
		if(is_array($instruction['fields_to_update']) && count($instruction['fields_to_update']) > 0){
			foreach($instruction['fields_to_update'] as $field_to_update){
				//echo 'Tasks.task' . $instruction['id'] . '.fields_to_update.push("'.$field_to_update.'") ;';
			}
		}
		echo 'Tasks.task' . $instruction['id'] . '.seo_url = "' . $instruction['seo_url'] . '";';
		echo 'Tasks.task' . $instruction['id'] . '.comment = "' . $instruction['comment'] . '";';
	}
}
?>
<?php
if(is_array($categories) && count($categories) > 0){
	foreach($categories as $key => $category){
		echo 'Categories.cat'.$key.' = "' . addslashes($category) . '";
    ';
	}
}
?>
<?php
if(is_array($manufacturers) && count($manufacturers) > 0){
	foreach($manufacturers as $key => $manufacturer){
		echo 'Manufacturers.man'.$key.' = "' . addslashes($manufacturer) . '";
    ';
	}
}
?>
<?php
if(is_array($taxclasses) && count($taxclasses) > 0){
	foreach($taxclasses as $key => $taxclass){
		echo 'Taxclasses.tax'.$key.' = "' . $taxclass . '";
    ';
	}
}
?>
<?php
if(is_array($currencies) && count($currencies) > 0){
	foreach($currencies as $key => $currency){
		echo 'Currencies.cur'.$key.' = "' . $currency . '";
    ';
	}
}
?>
<?php
if(is_array($fields) && count($fields) > 0){
	foreach($fields as $field_name => $field_title){
		//echo 'Fields.'.$field_name.' = "' . $field_title . '";'
    ;
	}
}
?>

if(ConsoleMode){
	console.log(Tasks);
	console.log(Categories);
	console.log(Manufacturers);
	console.log(Taxclasses);
	console.log(Currencies);
	console.log(Fields);
}
jQuery(document).ready(function($) { 
});

<!--   список спарсенных товаров  //-->
<?php
$table_lang = "";
if($this->lang->line('language_key') == "ru"){
	$table_lang = ', "language": { "url": "public/scripts/dataTables.russian.lang" }';
}
?>
<?php
$products_grabbed_all = false;
$products_grabbed_popup = '';
if(isset($products_grabbed) && is_array($products_grabbed) && count($products_grabbed) > 0 ){
	$products_grabbed_all = true;
	$products_grabbed_popup .= '<div id="bpopup_productsList_all" class="bpopups" style="font-size: 1.2em;right:auto !important;">';
	$products_grabbed_popup .= '<span class="bpopup_button b-close"><span>X</span></span>';
	$products_grabbed_popup .= '<div class="grabbed_product_popup_title">';
	$products_grabbed_popup .=   '<span class="grabbed_product_popup_number"></span> ' .$this->lang->line('tasks_form_grabbed_products_popup_title_all');
	$products_grabbed_popup .= '</div><br />';
	// table
	$products_grabbed_popup .= '<table id="products_grabbed_all" class="display" cellspacing="0">';
	$products_grabbed_popup .= '<thead><tr><th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_1').'</th>';
	$products_grabbed_popup .= '<th>----</th><th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_2').'</th>';
	$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_3').'</th>';
	$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_4').'</th>';
	$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_5').'</th>';
	$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_6').'</th>';
	$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_7').'</th>';
	$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_8').'</th></tr></thead>';
	$products_grabbed_popup .= '</table>';
	$products_grabbed_popup .= '</div>';
	echo 'TableSemafors.semafor_all = 0;';
	foreach($products_grabbed as $ins_id){
		$products_grabbed_popup .= '<div id="bpopup_productsList_' . $ins_id . '" class="bpopups" style="font-size: 1.2em;right:auto !important;">';
		$products_grabbed_popup .= '<span class="bpopup_button b-close"><span>X</span></span>';
		$products_grabbed_popup .= '<div class="grabbed_product_popup_title">';
		$products_grabbed_popup .=   '<span class="grabbed_product_popup_number"></span> ' .$this->lang->line('tasks_form_grabbed_products_popup_title');
		$products_grabbed_popup .= '</div><br />';
		// table
		$products_grabbed_popup .= '<table id="products_grabbed_' . $ins_id . '" class="display" cellspacing="0">';
		$products_grabbed_popup .= '<thead><tr><th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_1').'</th>';
		$products_grabbed_popup .= '<th>----</th><th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_2').'</th>';
		$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_3').'</th>';
		$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_4').'</th>';
		$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_5').'</th>';
		$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_6').'</th>';
		$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_7').'</th>';
		$products_grabbed_popup .= '<th>'.$this->lang->line('tasks_form_grabbed_products_popup_table_8').'</th></tr></thead>';
		$products_grabbed_popup .= '</table>';
		$products_grabbed_popup .= '</div>';
		echo 'TableSemafors.semafor_' . $ins_id . ' = 0;';
	}
}
?>

function show_grabbed_table(target){
	eval("var sem = TableSemafors.semafor_" + target + ";");
	show_overlay();
	//console.log(sem);
	
	if(sem < 1){
		$("#products_grabbed_" + target).dataTable( {
			"processing": true,
	        "serverSide": true, 
			 "ajax": {"url" : "products/" + target , "type": "POST"},
			  retrieve: true,
			   "order": [[ 0, "asc" ]],
			   "initComplete": function () {
				   hide_overlay();
				   $('#bpopup_productsList_' + target).bPopup();
			   }
				 <?php echo $table_lang; ?>
				 
		});
	}else{
		$("#products_grabbed_" + target).dataTable();
		hide_overlay();
		$('#bpopup_productsList_' + target).bPopup();
	}
	eval("TableSemafors.semafor_" + target + " = 1;");
}

function show_overlay(){
	var docHeight = $(document).height();
	$("body").append("<div id='overlay'><img id='overlay_img' src='<?php echo $this->config->item("base_url"); ?>public/images/loader.gif' /></div>");
	 $("#overlay_img").css({'margin-top': docHeight / 2});
	 $("#overlay").height(docHeight).css({'z-index': 5000});
}

function hide_overlay(){
	$("#overlay").remove();
}


/*
 *  ОТРИСОВКА ФОРМЫ
 */
function action_form(id){
	$("#dialog_form").remove();
	$("#tasks-form").append('<div id="dialog_form"></div>');
	$("#dialog_form").attr("title" , (id !== undefined? "<?php echo $this->lang->line('tasks_form_button_edit'); ?>" : "<?php echo $this->lang->line('tasks_form_button_create'); ?>" ) );
	$("#dialog_form").html('<div> ' +
			 
							'<!--  Task name , Switched, Priority  //-->' +
								  '<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_name'); ?> : </div>'+
									'<input type="text" id="addeditform_name" name="addeditform_name" style="width:800px;" value="' + (id !== undefined? eval("Tasks.task" + id + ".name") : "" ) + '" /><br />'+
								  '<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_state'); ?> : </div>'+
								  '<select name="addeditform_state" id="addeditform_state">'+
								  	'<option value="1"><?php echo $this->lang->line('tasks_table_state_on'); ?></option>'+
								  	'<option value="0" ' + ( (id !== undefined && eval("Tasks.task" + id + ".state") < 1 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_table_state_off'); ?></option>'+
								  '</select><br />' +
								  '<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_priority'); ?> : </div>'+
								  '<select name="addeditform_priority" id="addeditform_priority">'+
								  	'<option value="0" ' + ( (id !== undefined && eval("Tasks.task" + id + ".priority") < 1 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_priority_vars_0'); ?></option>'+
								  	'<option value="1" ' + ( (id !== undefined && eval("Tasks.task" + id + ".priority") == 1 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_priority_vars_1'); ?></option>'+
								  	'<option value="2" ' + ( (id !== undefined && eval("Tasks.task" + id + ".priority") > 1 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_priority_vars_2'); ?></option>'+
								  '</select>' +

							'<!--  "PRODUCTS LISTING" URLs  , "PRODUCT" URLs  //-->' +
							'<hr />'+
								'<div class="addedit_form_capts addedit_form_capts_testareas"> <?php echo $this->lang->line('tasks_form_category_urls_1'); ?> <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_listing\').bPopup();" /><br /><?php echo $this->lang->line('tasks_form_category_urls_2'); ?></div>'+
								'<textarea rows="4" cols="88" id="addeditform_category_urls" name="addeditform_category_urls" ' + (id !== undefined?"disabled":"") + '>' + (id !== undefined? eval("Tasks.task" + id + ".category_urls") : "" ) + '</textarea><br />'+
								'<div class="addedit_form_capts addedit_form_capts_testareas"> <?php echo $this->lang->line('tasks_form_product_urls_1'); ?> <img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_product\').bPopup();" /><br /><?php echo $this->lang->line('tasks_form_product_urls_2'); ?></div>'+
								'<textarea rows="4" cols="88" id="addeditform_product_urls" name="addeditform_product_urls" ' + (id !== undefined?"disabled":"") + '>' + (id !== undefined? eval("Tasks.task" + id + ".product_urls") : "" ) + '</textarea><br />'+
								
							'<!--  Push into Category , Tax classes   //-->' +
							'<hr />'+
								'<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_category'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_category\').bPopup();" /> : </div>'+
								'<div class="mspro_scrollbox">' + get_categories_choice(id) + '</div><br />'+
								'<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_taxclass'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_taxclass\').bPopup();" /> : </div>'+
								'<select name="addeditform_taxclass" id="addeditform_taxclass">'+ get_taxclass_choice(id) + '</select>&nbsp;&nbsp;&nbsp;'+
								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_do_not_update_taxclass"><?php echo $this->lang->line('tasks_form_do_not_update_taxclass'); ?></label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_do_not_update_taxclass\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_do_not_update_taxclass" ' + (id !== undefined? eval("Tasks.task" + id + ".do_not_update_taxclass") : "" ) + ' style="width: 30px;"><br />' + 

							'<!--   Manufacturer , Do not update manufacturer after grabbing  //-->' +
								'<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_manufacturer'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_manufacturer\').bPopup();" /> : </div>'+
								'<select name="addeditform_manufacturer" id="addeditform_manufacturer">'+ get_manufacturers_choice(id) + '</select>&nbsp;&nbsp;&nbsp;'+
								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_do_not_update_manufacturer"><?php echo $this->lang->line('tasks_form_do_not_update_manufacturer'); ?></label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_do_not_update_manufacturer\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_do_not_update_manufacturer" ' + (id !== undefined? eval("Tasks.task" + id + ".do_not_update_manufacturer") : "" ) + ' style="width: 30px;"><br /><br />' + 

							'<!--  Images limits, Do not upload description image setting, Separate image folder , Products quantity  //-->' +
							'<hr />'+
    							'<div class="addedit_form_capts" style="min-width: 230px;"> <?php echo $this->lang->line('tasks_form_main_image_limit'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_main_image_limit\').bPopup();" /> : </div>'+
    							'<input  type="number" class="inputs" name="addeditform_main_image_limit" id="addeditform_main_image_limit" value="' + (id !== undefined? eval("Tasks.task" + id + ".main_image_limit") : "-1" ) + '"  maxlength="2" style="width: 60px;" />'+
    							'<div class="addedit_form_capts" style="text-align: right;width: 150px;margin-left: 25px;"> <?php echo $this->lang->line('tasks_form_description_image_limit'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_description_image_limit\').bPopup();" /> : </div>'+
    							'<input  type="number" class="inputs" name="addeditform_description_image_limit" id="addeditform_description_image_limit" value="' + (id !== undefined? eval("Tasks.task" + id + ".description_image_limit") : "-1" ) + '"  maxlength="2" style="width: 60px;" />'+
    							'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_do_not_upload_description_image"><?php echo $this->lang->line('tasks_form_do_not_upload_description_image'); ?></label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_do_not_upload_description_image\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_do_not_upload_description_image" ' + (id !== undefined? eval("Tasks.task" + id + ".do_not_upload_description_image") : "" ) + ' style="width: 30px;"><br />' + 
								'<div class="addedit_form_capts" style="margin-bottom:15px;"> <?php echo $this->lang->line('tasks_form_image_folder'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_imageFolder\').bPopup();" /> : </div>'+
								'<input type="text" id="addeditform_image_folder" name="addeditform_image_folder" style="width:500px;" value="' + (id !== undefined? eval("Tasks.task" + id + ".image_folder") : "" ) + '" ' + (id !== undefined?"disabled":"") + ' /><br />'+
								'<div class="addedit_form_capts" > <?php echo $this->lang->line('tasks_form_products_quantity'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_products_quantity\').bPopup();" /> : </div>'+
								'<input  type="number" class="inputs" name="addeditform_products_quantity" id="addeditform_products_quantity" value="' + (id !== undefined? eval("Tasks.task" + id + ".products_quantity") : "<?php echo  $this->config->item("ms_default_quantity_of_products"); ?>" ) + '"  maxlength="2" style="width: 45px;" />'+

							'<!--  Donor market currency , Fixed margin , Relative margin(%)  //-->' +
							'<hr />'+
								'<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_donor_currency'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_currency\').bPopup();" /> : </div>'+
								'<select name="addeditform_donor_currency" id="addeditform_donor_currency">'+ get_donor_currency_choice(id) + '</select>'+
								'<div class="addedit_form_capts" style="text-align: right;width: 150px;margin-left: 25px;"> <?php echo $this->lang->line('tasks_form_margin_fixed'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_margin_fixed\').bPopup();" /> : </div>'+
								'<input  type="number" class="inputs" name="addeditform_donor_margin_fixed" id="addeditform_donor_margin_fixed" value="' + (id !== undefined? eval("Tasks.task" + id + ".margin_fixed") : "" ) + '"  maxlength="2" style="width: 60px;" />'+
								'<div class="addedit_form_capts" style="text-align: right;width: 210px;"> <?php echo $this->lang->line('tasks_form_margin_relative'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_margin_relative\').bPopup();" /> : </div>'+
								'<input  type="number" class="inputs" name="addeditform_donor_margin_relative" id="addeditform_donor_margin_relative" value="' + (id !== undefined? eval("Tasks.task" + id + ".margin_relative") : "" ) + '"  maxlength="2" style="width: 60px;" />'+

							'<!--  What should I do if the product no more available at the donor market  , ( закомментированные fieds_to_insert fieds_to_update)  //-->' +
							'<hr />'+
								'<div class="addedit_form_capts" style="width: 500px;"> <?php echo $this->lang->line('tasks_form_what_to_do_product_not_exists'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_what_to_do_product_not_exists\').bPopup();" /> : </div>'+
								  '<select name="addeditform_what_to_do_product_not_exists" id="addeditform_what_to_do_product_not_exists">'+
								  	'<option value="0" ' + ( (id !== undefined && eval("Tasks.task" + id + ".what_to_do_product_not_exists") < 1 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_what_to_do_product_not_exists_vars_0'); ?></option>'+
								  	'<option value="1" ' + ( (id !== undefined && eval("Tasks.task" + id + ".what_to_do_product_not_exists") == 1 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_what_to_do_product_not_exists_vars_1'); ?></option>'+
								  	'<option value="2" ' + ( (id !== undefined && eval("Tasks.task" + id + ".what_to_do_product_not_exists") == 2 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_what_to_do_product_not_exists_vars_2'); ?></option>'+
								  	'<option value="3" ' + ( (id !== undefined && eval("Tasks.task" + id + ".what_to_do_product_not_exists") == 3 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_what_to_do_product_not_exists_vars_3'); ?></option>'+
								  	'<option value="4" ' + ( (id !== undefined && eval("Tasks.task" + id + ".what_to_do_product_not_exists") > 3 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_what_to_do_product_not_exists_vars_4'); ?></option>'+
								  '</select>' +
								 '<div style="display:none;">' +  
									'<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_fieds_to_insert'); ?> : </div>'+
									'' + get_fields_choice(id , false) + '<br />'+
									'<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_fieds_to_update'); ?> : </div>'+
									'' + get_fields_choice(id , true) +
								'</div>' +

							'<!--  Do not update the price after first scraping , Insert grabbed products as ""Disabled"   //-->' +
							'<hr />'+
								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_donot_update_price"><?php echo $this->lang->line('tasks_form_donot_update_price'); ?></label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_donot_update_price\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_donot_update_price" ' + (id !== undefined? eval("Tasks.task" + id + ".donot_update_price") : "" ) + ' style="width: 30px;">' + 

								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_create_disabled"><?php echo $this->lang->line('tasks_form_create_disabled'); ?></label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_create_disabled\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_create_disabled" ' + (id !== undefined? eval("Tasks.task" + id + ".create_disabled") : "" ) + ' style="width: 30px;">' + 

							'<!--  Get OPTIONS, Do not update Options   //-->' +
							'<hr />'+
								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_get_options"><?php echo $this->lang->line('tasks_form_get_options'); ?></label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_get_options\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_get_options" ' + (id !== undefined? eval("Tasks.task" + id + ".get_options") : "checked" ) + ' style="width: 30px;">' + 

								'<div class="addedit_form_capts" style="margin-left:30px;"><label for="addeditform_do_not_update_options"><?php echo $this->lang->line('tasks_form_do_not_update_options'); ?></label><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_do_not_update_options\').bPopup();" /></div>'+
								'<input type="checkbox" id="addeditform_do_not_update_options" ' + (id !== undefined? eval("Tasks.task" + id + ".do_not_update_options") : "" ) + ' style="width: 30px;">' + 

							'<!--  SEO URL , Comment  //-->' +
							'<hr />'+
								'<div class="addedit_form_capts" style="margin-bottom: 15px;"> <?php echo $this->lang->line('tasks_form_seo_url'); ?><img src="<?php echo $this->config->item("base_url"); ?>public/images/question.png" onclick="$(\'#bpopup_ai_seourl\').bPopup();" /> : </div>'+
								'<select name="addeditform_seo_url" id="addeditform_seo_url">'+
									'<option value="0" ><?php echo $this->lang->line('tasks_table_seourl_off'); ?></option>'+
							  		'<option value="1" ' + ( (id !== undefined && eval("Tasks.task" + id + ".seo_url") > 0 )? "selected" : "" ) + '><?php echo $this->lang->line('tasks_table_seourl_on'); ?></option>'+
							  	'</select><br />'+
								'<div class="addedit_form_capts"> <?php echo $this->lang->line('tasks_form_comment'); ?> : </div>'+
								'<input type="text" id="addeditform_comment" name="addeditform_comment" value="' +  (id !== undefined? eval("Tasks.task" + id + ".comment") : "" )  + '" style="width:500px;" />'+
							'</div>'
							);
	$("#dialog_form").dialog({ width: 1200 ,
										buttons: [{
	                                               	text: (id !== undefined? "<?php echo $this->lang->line('tasks_form_button_edit'); ?>" : "<?php echo $this->lang->line('tasks_form_button_create'); ?>" ),
	                                               	click: function() { get_form_data(id);$(this).dialog("close") }
	                                                },{
	                                              	text: "<?php echo $this->lang->line('tasks_cancel_button'); ?>", click: function() { $(this).dialog("close"); }
	                                      }]});
		
}

/*
 *  GET DATA FROM add/edit FORM, SEND AJAX AND RELOAD PAGE
 */
function get_form_data(id){
	if(id !== undefined){
		action_close(id);
	}
	var action = (id !== undefined? "edit":"add");
	var ins_id = (id !== undefined? id:"");
	var category_id = '';
	$("input[name='addeditform_category_id[]']").each( function () {
		if($(this).is(':checked') == true){
			category_id += $(this).val() + ',';
		}
	 });
	 var fields_to_insert = '';
	 $("input[name='addeditform_fields_to_insert[]']").each( function () {
			if($(this).is(':checked') == true){
				fields_to_insert += $(this).val() + ',';
			}
	 });
	 var fields_to_update = '';
	 $("input[name='addeditform_fields_to_update[]']").each( function () {
			if($(this).is(':checked') == true){
				fields_to_update += $(this).val() + ',';
			}
	});
	$.post('' , {"action" : action ,
				 "data[name]" : $("#addeditform_name").val(),
				 "data[state]" : $("#addeditform_state").val(),
				 "data[priority]" : $("#addeditform_priority").val(),
				 "data[category_urls]" : $("#addeditform_category_urls").val(),
				 "data[product_urls]" : $("#addeditform_product_urls").val(),
				 "data[category_id]" : category_id,
				 "data[manufacturer_id]" : $("#addeditform_manufacturer").val(),
				 "data[do_not_update_manufacturer]" : $("#addeditform_do_not_update_manufacturer").is(':checked')?1:0,
				 "data[tax_class_id]" : $("#addeditform_taxclass").val(),
				 "data[do_not_update_taxclass]" : $("#addeditform_do_not_update_taxclass").is(':checked')?1:0,
				 "data[main_image_limit]" : $("#addeditform_main_image_limit").val(),
				 "data[description_image_limit]" : $("#addeditform_description_image_limit").val(),
				 "data[do_not_upload_description_image]" : $("#addeditform_do_not_upload_description_image").is(':checked')?1:0,
				 "data[image_folder]" : $("#addeditform_image_folder").val(),
				 "data[products_quantity]" : $("#addeditform_products_quantity").val(),
				 "data[donor_currency]" : $("#addeditform_donor_currency").val(),
				 "data[margin_fixed]" : $("#addeditform_donor_margin_fixed").val(),
				 "data[margin_relative]" : $("#addeditform_donor_margin_relative").val(),
				 "data[what_to_do_product_not_exists]" : $("#addeditform_what_to_do_product_not_exists").val(),
				 "data[donot_update_price]" : $("#addeditform_donot_update_price").is(':checked')?1:0,
				 "data[create_disabled]" : $("#addeditform_create_disabled").is(':checked')?1:0,
				 "data[get_options]" : $("#addeditform_get_options").is(':checked')?1:0,
				 "data[do_not_update_options]" : $("#addeditform_do_not_update_options").is(':checked')?1:0,
				 "data[fields_to_insert]" : fields_to_insert,
				 "data[fields_to_update]" : fields_to_update,
				 "data[seo_url]" : $("#addeditform_seo_url").val(),
				 "data[comment]" : $("#addeditform_comment").val(),
				 "id" : ins_id
				 } ,
			function(data){
					if(ConsoleMode){
						 console.log(data);
					 }
					// demo mode
					if(data.result !== undefined){
						if(data.result == "demo"){
							alert('<?php echo $this->lang->line('messages_demo'); ?>');
						}
					}else{
						location.reload();
					}
	 		}
	);
}

/*
 * 
 */
function get_categories_choice(id){
	var res = '';
	var IDS = [];
	var cls = "odd";
	if(id !== undefined){
		eval('var IDS = Tasks.task' + id + '.category_id;');
	}
	$.each(Categories , function( index, value ) {
		if(cls == "even"){
			cls = "odd";
		}else{
			cls = "even";
		}
		res += '<div class="' + cls + '">';
		var checked = '';
		$.each(IDS , function( id_index, id_value ) {
			if(index.substr(3) == id_value){
				checked = 'checked="checked"';
			}
		});
		res += '<input type="checkbox" name="addeditform_category_id[]" value="' + index.substr(3) + '" ' + checked + ' style="width: 25px;" id="addeditform_category_id' + index.substr(3) + '" />';
		res +=  '<label for="addeditform_category_id' + index.substr(3) + '">' + value + '</label>';
		res += '</div>';
	});
	return res;
}


function get_manufacturers_choice(id){
	var res = '<option value="0"><?php echo $this->lang->line('tasks_form_no_manufacturer_option'); ?></option>';
	$.each(Manufacturers , function( index, value ) {
		res += '<option value="' + index.substr(3) + '" ' + ( (id !== undefined && index.substr(3) == eval("Tasks.task" + id + ".manufacturer")) ?'selected="selected"':'') + '>';
		res += value;
		res += '</option>';
	});
	return res;
}

function get_taxclass_choice(id){
	var res = '<option value="0"><?php echo $this->lang->line('tasks_form_no_taxclass_option'); ?></option>';
	$.each(Taxclasses , function( index, value ) {
		res += '<option value="' + index.substr(3) + '" ' + ( (id !== undefined && index.substr(3) == eval("Tasks.task" + id + ".taxclass")) ?'selected="selected"':'') + '>';
		res += value;
		res += '</option>';
	});
	return res;
}


function get_donor_currency_choice(id){
	var res = '';
	$.each(Currencies , function( index, value ) {
		res += '<option value="' + index.substr(3) + '" ' + ( (id !== undefined && index.substr(3) == eval("Tasks.task" + id + ".donor_currency")) ?'selected="selected"':'') + '>';
		res += value;
		res += '</option>';
	});
	return res;
}


function  get_fields_choice(id , update){
	var type = update == true?"update":"insert";
	var res = '<div class="addedit_form_fields_divs">';
	res += '<div style="float:left;margin-left:15px;">';
	var count = 0;
	if(id !== undefined){
		eval('var FIELDS = Tasks.task' + id + '.fields_to_' + type + ';');
	}
	$.each(Fields , function( index, value ) {
		var checked = '';
		if(id !== undefined){
			$.each(FIELDS , function( f_index, f_value ) {
				if(f_value == index){
					checked = 'checked';
				}
			});
		}else{
			checked = 'checked';
		}
		res += '<input type="checkbox" name="addeditform_fields_to_' + type + '[]" value="' + index + '" ' + checked + ' style="width: 25px;" id="addeditform_fields_to_' + type + '_' + index + '" />';
		res +=  '<label for="addeditform_fields_to_' + type + '_' + index + '">' + value + '</label>';
		res +=  '<br />';
		count++;
		if(count > 3){
			res +=  '</div><div style="float:left;margin-left:15px;">';
			count = 0;
		}
	});
	res += '</div>';
	res +=  '</div>';
	return res;
}


/*
 *  open actions block
 */
function open_actions(id){
	var open = $("#actions_tr_" + id).css("display");
	if(open == 'none'){
		$(".actions").css("display" , "none");
		$("#actions_tr_" + id).css("display" , "table-row");
	}else{
		$("#actions_tr_" + id).css("display" , "none");
	}
}

/*
 *  action delete
 */
function action_delete(id , with_products){
	if(confirm("<?php echo $this->lang->line('tasks_form_sure_to_delete'); ?>")){
		action_close(id);
		$.post('' , {"action" : "delete" , "id" : id , "with_products" : with_products} ,
			function(data){
				if(ConsoleMode){
					console.log(data);
				}
				// demo mode
				if(data.result !== undefined){
					if(data.result == "demo"){
						alert('<?php echo $this->lang->line('messages_demo'); ?>');
					}
				}else{
					$("tr#instruction_tr_" + id).remove();
					$("tr#actions_tr_" + id).remove();
					if($("tr.instruction_trs").length < 1){
						$("tr#instruction_table_header").html('<td colspan="10" ><?php echo $this->lang->line('no_tasks_for_maspro'); ?></td>');
					}
				}
			}
		);
	}
}


/*
 *  action restart
 */
function action_restart(id){
	if(confirm("<?php echo $this->lang->line('tasks_form_sure_to_restart'); ?>")){
		action_close(id);
		$.post('' , {"action" : "restart" , "id" : id } ,
			function(data){
				if(ConsoleMode){
					console.log(data);
				}
				// demo mode
				if(data.result !== undefined){
					if(data.result == "demo"){
						alert('<?php echo $this->lang->line('messages_demo'); ?>');
					}
				}else{
					alert("<?php echo $this->lang->line('tasks_form_task_restarted'); ?>");
					location.reload();
				}
			}
		);
	}
}

/*
 * set switch action
 */
function change_switch(res, id){
	var value = res.value;
	$("#actions_switch_select_" + id).attr("disabled" , true);
	$.post('' , {"action" : "switch" , "task_id" : id , "switch" : value} , function(data){
		if(data.result == "success"){
			if(value > 0){
				$("#state_td_" + id).html("<span class='tasks_table_state_td_on'><?php echo $this->lang->line('tasks_table_state_on'); ?></span>");
			}else{
				$("#state_td_" + id).html("<span class='tasks_table_state_td_off'><?php echo $this->lang->line('tasks_table_state_off'); ?></span>");
			}
		}
		if(data.result == "demo"){
			alert('<?php echo $this->lang->line('messages_demo'); ?>');
		}
		$("#actions_tr_" + id).css("display" , "none");
		$("#actions_switch_select_" + id).removeAttr("disabled" , true);
	})
}

/*
 *  set priority action
 */
function change_priority(res, id){
	var value = res.value;
	$("#actions_priority_select_" + id).attr("disabled" , true);
	$.post('' , {"action" : "set_priority" , "task_id" : id , "priority" : value} , function(data){
		if(data.result == "success"){
			switch(value){
				case '2':
					$("#priority_td_" + id).css("background-color" , "palevioletred");
					$("#priority_td_" + id).html("<?php echo $this->lang->line('tasks_priority_vars_2'); ?>");
					break;
				case '1':
					$("#priority_td_" + id).css("background-color" , "peachpuff");
					$("#priority_td_" + id).html("<?php echo $this->lang->line('tasks_priority_vars_1'); ?>");
					break;
				default:
					$("#priority_td_" + id).css("background-color" , "");
					$("#priority_td_" + id).html("<?php echo $this->lang->line('tasks_priority_vars_0'); ?>");
					break;
			}
		}
		if(data.result == "demo"){
			alert('<?php echo $this->lang->line('messages_demo'); ?>');
		}
		$("#actions_tr_" + id).css("display" , "none");
		$("#actions_priority_select_" + id).removeAttr("disabled" , true);
	})
}


function action_close(id){
	$("#actions_tr_" + id).css("display" , "none");
}


</script>
                        	
<div class="blocks">
    <h1><?php echo $this->lang->line('tasks_title'); ?></h1>
    <div class="text-block">
        
 <?php
 // create "Grabbed products list" button (green or grey depending on products grabbed number)
 
 
 
 ?>     
   
        <input type="button" value="<?php echo $this->lang->line('tasks_add_button'); ?>" class="btn btn-blue" onclick="action_form();" id="tasks_add_button" style="float: right;margin: 10px 30px 10px 0;"/>
        <input type="button" value="<?php echo $this->lang->line('tasks_grabbed_product_button'); ?>" class="btn btn-<?php echo $products_grabbed_all?"green":"grey"; ?>" onclick="<?php echo $products_grabbed_all?"show_grabbed_table('all');":""; ?>" id="tasks_grabbed_product_button" style="float: left;margin: 10px 30px 10px 30px;"/>
        <div class="clear"></div>
        
        <div id="tasks-form" class="ms_form" style="padding: 10px;width: 1240px;padding-bottom: 0;">
        	<div id="form-content">
        				
            		<table class="list" id="parser_instruction_table">
					        <thead>
					            <tr id="instruction_table_header">
					            <?php 
					        	if(is_array($instructions) && count($instructions) > 0){
					        	?>
					                <td class="left" style="width:70px;"></td>
					                <td class="left" style="width: 250px;"><?php echo $this->lang->line('tasks_table_name'); ?></td>
					                <td class="left" style="width: 50px;"><?php echo $this->lang->line('tasks_table_state'); ?></td>
					                <td class="left" style="width: 50px;"><?php echo $this->lang->line('tasks_table_priority'); ?></td>
					                <td class="left" style="width: 150px;" ><?php echo $this->lang->line('tasks_table_category'); ?></td>
					                <td class="left" style="width: 150px;"><?php echo $this->lang->line('tasks_table_manufactuer'); ?></td>
					                <td class="left" style="width: 50px;"><?php echo $this->lang->line('tasks_table_seourls'); ?></td>
					                <td class="left" style="width: 100px;"><?php echo $this->lang->line('tasks_table_margins'); ?></td>
					                <td class="left" style="width: 100px;"><?php echo $this->lang->line('tasks_table_products_found_parsed'); ?></td>
					                <td class="left"><?php echo $this->lang->line('tasks_table_comment'); ?></td>
					            <?php
					        	}else{ 
					            ?>
					            	<td colspan="10" ><?php echo $this->lang->line('no_tasks_for_maspro'); ?></td>
					            <?php } ?>
					            </tr>
					        </thead>
					        <tbody>
					        	<?php 
					        		if(is_array($instructions) && count($instructions) > 0){
					        			$tr = '';
					        			foreach($instructions as $instruction){
					        				// prepare category urls
					        				$cats = '';
					        				$cats_res = $instruction['category_urls'];
					        				if(count($cats_res) > 0){
					        					foreach($cats_res as $cat_res){
					        						$cats .= $cat_res."\n";
					        					}
					        				}
					        				// prepare product urls
					        				$products = '';
					        				$products_res = $instruction['product_urls'];
					        				if(count($products_res) > 0){
					        					foreach($products_res as $product_res){
					        						$products .= $product_res."\n";
					        					}
					        				}
					        				/*****   PREPARE DATA FOR DISPLAY   ********/ 
					        				// name
					        				$name = strlen( $instruction['name'] ) > 70? substr($instruction['name'] , 0 , 70). '...' : $instruction['name'];
					        				// state
					        				if($instruction['state'] > 0){
					        					$state = '<span class="tasks_table_state_td_on">'.$this->lang->line('tasks_table_state_on').'</span>';
					        				}else{
					        					$state = '<span class="tasks_table_state_td_off">'.$this->lang->line('tasks_table_state_off').'</span>';
					        				}
					        				// priority
					        				switch($instruction['priority']){
					        					case 2:
					        						$priority = '<td class="left" id="priority_td_'.$instruction['id'].'" style="background-color:palevioletred;">' . $this->lang->line('tasks_priority_vars_'.$instruction['priority']) . '</td>';
					        						break;
					        					case 1:
					        						$priority = '<td class="left" id="priority_td_'.$instruction['id'].'" style="background-color:peachpuff;">' . $this->lang->line('tasks_priority_vars_'.$instruction['priority']) . '</td>';
					        						break;
					        					default:
					        						$priority = '<td class="left" id="priority_td_'.$instruction['id'].'" style="">' . $this->lang->line('tasks_priority_vars_'.$instruction['priority']) . '</td>';
					        				}
					        				// category
					        				//$category = isset($categories[$instruction['category_id']])?$categories[$instruction['category_id']]:"";
					        				$category = '';
					        				if(is_array($instruction['category_id']) && count($instruction['category_id']) > 0){
					        					foreach($instruction['category_id'] as $category_id){
					        						if(isset($categories[$category_id])){
					        							$category .=  $categories[$category_id] . '<br />';
					        						}
					        					}
					        				}
					        				// manufacturer
					        				$manufacturer = isset($manufacturers[$instruction['manufacturer_id']])?$manufacturers[$instruction['manufacturer_id']]:"";
					        				// seo url
					        				if($instruction['seo_url'] > 0){
					        					$seo_url = '<span class="tasks_table_state_td_on">'.$this->lang->line('tasks_table_seourl_on').'</span>';
					        				}else{
					        					$seo_url = '<span class="tasks_table_state_td_off">'.$this->lang->line('tasks_table_seourl_off').'</span>';
					        				}
					        				// margins
					        				$margins = $instruction['margin_fixed'] . '<b> / </b>'. $instruction['margin_relative'] .'%';
					        				// products_found_parsed
					        				$products_found_parsed = $instruction['products_found_grabbed']['found'] . '<b> / </b>'. ( $instruction['products_found_grabbed']['grabbed'] > 0? '<span style="color:green;">'.$instruction['products_found_grabbed']['grabbed'].'</span>' : $instruction['products_found_grabbed']['grabbed']) ;
					        				if($instruction['products_found_grabbed']['grabbed'] > 0){
					        					$products_found_parsed .= '<img src="' . $this->config->item("base_url") . 'public/images/list.png" onclick="show_grabbed_table(\'' . $instruction['id'] . '\');" style="margin-left: 14px;"/>';
					        				}
					        				// comment
					        				$comment = strlen( $instruction['comment'] ) >70? substr($instruction['comment'] , 0 , 70). '...' : $instruction['comment'];
					        				
					        				
					        				$tr .= '<tr id="instruction_tr_'.$instruction['id'].'" class="instruction_trs">
										                <td>
										                	<input type="button" value="'.$this->lang->line('actions_button').'" class="btn btn-blue actions_button" id="actions_button_'.$instruction['id'].'" style="width: 65px;" onclick="open_actions('.$instruction['id'].')" />
										                </td>
										                <td class="left" style="text-align: left;padding-left: 5px;">'.$name.'</td>
										                <td class="left" id="state_td_'.$instruction['id'].'">'.$state.'</td>
										                '.$priority.'
										                <td class="left" style="text-align: left;padding-left: 5px;">'.$category.'</td>
										                <td class="left" style="text-align: left;padding-left: 5px;">'.$manufacturer.'</td>
										                <td class="left">'.$seo_url.'</td>
										                <td class="left">'.$margins.'</td>
										                <td class="left">'.$products_found_parsed.'</td>
										                <td class="left" style="text-align: left;padding-left: 5px;">'.$comment.'</td>
										            </tr>';
					        				
					        				
					        				// ГОТОВИМ ACTIONS
					        				$select_switch = '<select onchange="change_switch(this , '.$instruction['id'].')" id="actions_switch_select_'.$instruction['id'].'">';
					        				$select_switch .= '<option value="1" '. ($instruction['state'] > 0?'selected':'') .' >'.$this->lang->line('tasks_table_state_on').'</option>';
					        				$select_switch .= '<option value="0" '. ($instruction['state'] > 0?'':'selected')   .' >'.$this->lang->line('tasks_table_state_off').'</option>';
					        				$select_switch .= '</select>';
					        				
					        				$select_priority = '<select onchange="change_priority(this , '.$instruction['id'].')" id="actions_priority_select_'.$instruction['id'].'">';
					        				for($i = 0; $i < 3; $i++){
					        					$select_priority .= '<option value="'.$i.'" ';
					        					if($i == $instruction['priority']){
					        						$select_priority .= 'selected';
					        					}
					        					$select_priority .= '>'.$this->lang->line('tasks_priority_vars_'.$i).'</option>';
					        				}
					        				$select_priority .= '</select>';
					        				
					        				
					        				$tr .= '<tr id="actions_tr_'.$instruction['id'].'" class="actions"><td colspan="10">
					        							<input type="button" value="'.$this->lang->line('actions_delete_products_button').'" class="btn btn-red" onclick="action_delete('.$instruction['id'].' , true);" style="float:right;margin-left:10px;width:135px;" />	
					        							<input type="button" value="'.$this->lang->line('actions_delete_button').'" class="btn btn-red" onclick="action_delete('.$instruction['id'].' , false);" style="float:right;margin-left:20px;width:60px;" />
					        							<div style="float:right;margin-left:10px;">
					        								'.$this->lang->line('set_priority_action').':
					        								'.$select_priority.'
					        							</div>
					        							<input type="button" value="'.$this->lang->line('actions_restart_button').'" class="btn btn-blue" onclick="action_restart('.$instruction['id'].');" style="float:right;margin-left:20px;width:85px;" />
					        							<input type="button" value="'.$this->lang->line('actions_edit_button').'" class="btn btn-blue" onclick="action_form('.$instruction['id'].');" style="float:right;margin-left:20px;width:100px;" />
					        							<div style="float:right;margin-left:10px;">
					        								'.$this->lang->line('set_switch_action').':
					        								'.$select_switch.'
					        							</div>
					        							<img src="'.$this->config->item("base_url").'public/images/arrow_top.png" onclick="action_close('.$instruction['id'].' , false);" style="float:left;margin-left:20px;height: 25px;" />
					        									
					        							
					        						</td></tr>';
					        			}
					        			echo $tr ;
					        			
					        		}
					        	?>
			           		</tbody>
			        </table>
            	</div>
            	
            	
            	
            	
            	
            	
            </div>
    </div>
</div>




<!--   "PRODUCTS LISTING" URLs TIP  //-->
 <div id="bpopup_ai_listing" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_listing_1'); ?><br />
    	<?php echo $this->lang->line('tasks_form_tip_listing_2'); ?><br /><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_listing_3'); ?><br />
    	<?php echo $this->lang->line('tasks_form_tip_listing_4'); ?><br />
    	<?php echo $this->lang->line('tasks_form_tip_listing_5'); ?>
    	
</div>
<!--   "PRODUCT" URLs TIP  //-->
 <div id="bpopup_ai_product" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_product_1'); ?>
</div>


<!--   Push into Category TIP  //-->
 <div id="bpopup_ai_category" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_category_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_category_2'); ?>
</div>
<!--   Tax class TIP  //-->
 <div id="bpopup_ai_taxclass" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_taxclass_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_taxclass_2'); ?>
</div>
<!--   DO not update Tax class TIP  //-->
 <div id="bpopup_ai_do_not_update_taxclass" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_do_not_update_taxclass_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_do_not_update_taxclass_2'); ?>
</div>
<!--   Main image limit TIP  //-->
 <div id="bpopup_ai_main_image_limit" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_main_image_limit_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_main_image_limit_2'); ?>
</div>
<!--   Images in description limit TIP  //-->
 <div id="bpopup_ai_description_image_limit" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_description_image_limit_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_description_image_limit_2'); ?>
</div>
<!--   DO not upload description image TIP  //-->
 <div id="bpopup_ai_do_not_upload_description_image" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_do_not_upload_description_image_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_do_not_upload_description_image_2'); ?>
</div>

<!--   Manufacturer TIP //-->
 <div id="bpopup_ai_manufacturer" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_manufacturer_1'); ?><br />
    	<?php echo $this->lang->line('tasks_form_tip_manufacturer_2'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_manufacturer_3'); ?>
</div>
<!--   Do not update Manufacturer after grabbing TIP  //-->
 <div id="bpopup_ai_do_not_update_manufacturer" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_do_not_update_manufacturer_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_do_not_update_manufacturer_2'); ?>
</div>


<!--   Separate image folder TIP  //-->
 <div id="bpopup_ai_imageFolder" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_imageFolder_0'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_imageFolder_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_imageFolder_2'); ?><br /><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_imageFolder_3'); ?><br />
    	<?php echo $this->lang->line('tasks_form_tip_imageFolder_4'); ?><br />
    	<?php echo $this->lang->line('tasks_form_tip_imageFolder_5'); ?><br /><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_imageFolder_6'); ?><br />
</div>
<!--   Products quantity TIP //-->
 <div id="bpopup_ai_products_quantity" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_products_quantity_1'); ?>
</div>


<!--   Donor market currency TIP  //-->
 <div id="bpopup_ai_currency" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_currency_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_currency_2'); ?>
</div>
<!--   Margin Fixed TIP  //-->
 <div id="bpopup_ai_margin_fixed" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_margin_fixed_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_margin_fixed_2'); ?>
</div>
<!--  Margin relative TIP  //-->
 <div id="bpopup_ai_margin_relative" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_margin_relative_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_margin_relative_2'); ?>
</div>

<!--  What should I do if the product no more available at the donor market TIP  //-->
 <div id="bpopup_ai_what_to_do_product_not_exists" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_what_to_do_product_not_exist_1'); ?>
</div>

<!--  Do not update the price after grabbing TIP  //-->
 <div id="bpopup_ai_donot_update_price" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_donot_update_price_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_donot_update_price_2'); ?>
</div>
<!--  Insert products as "Disabled" into my store TIP  //-->
 <div id="bpopup_ai_create_disabled" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_create_disabled_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_create_disabled_2'); ?>
</div>

<!-- Get product Options (if available) TIP  //-->
 <div id="bpopup_ai_get_options" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_get_options_1'); ?>
</div>
<!--  Do not update Options after grabbing TIP  //-->
 <div id="bpopup_ai_do_not_update_options" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_do_not_update_options_1'); ?>
</div>


<!--   SEO URL TIP  //-->
 <div id="bpopup_ai_seourl" class="bpopups" style="font-size: 1.2em;">
    	<span class="bpopup_button b-close"><span>X</span></span>
    	<?php echo $this->lang->line('tasks_form_tip_seourl_1'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_seourl_2'); ?><br /><br />
    	<?php echo $this->lang->line('tasks_form_tip_seourl_3'); ?>
</div>





<?php 
echo $products_grabbed_popup;
?>
