<?php



$lang['language_key'] = "en";
$lang['title'] = "MultiScraper";
$lang['under_title'] = "Grab products from other marketplaces directly into your " . MSPRO_CMS_DISPLAY_NAME . " store";


/*****************  Auth **************/
$lang['auth_title']= "MultiScraper login";
$lang['auth_form_title']= "Enter your MultiScraper password:";
$lang['auth_button_submit']= "ENTER";
$lang['auth_error']= "Wrong Password!!!";


/*****************  MENU **************/
$lang['menu_howtouse_title']= "How to use";
$lang['menu_howtouse_desc']= "Learn more about MultiScraper";

$lang['menu_settings_title']= "Settings";
$lang['menu_settings_desc']= "Change common settings";

$lang['menu_tasks_title']= "Tasks";
$lang['menu_tasks_desc']= "Manage tasks for MultiScraper";

$lang['menu_log_title']= "Log";
$lang['menu_log_desc']= "Monitor its behavior";

$lang['menu_contact_title']= "Manual Startup";
$lang['menu_contact_desc']= "Test your MultiScraper";


/*****************  SETTINGS **************/
$lang['seettings_title']= "Configure your MultiScraper";
$lang['settings_form_title']= "Your MultiScraper Settings";
$lang['settings_form_state']= "State";
$lang['settings_form_dev_mode']= "Dev mode";
$lang['settings_form_inv_mode']= "Invisible mode";
$lang['settings_form_lang']= "Interface Lang";
$lang['settings_form_num_product']= "Products grabbed in each iteration";

$lang['settings_form_old_pass']= "Current password";
$lang['settings_form_new_pass']= "New password";
$lang['settings_form_confirm_pass']= "Confirm password";


$lang['settings_form_button_save']= "Save";
$lang['settings_form_button_change_pass']= " + Change password";
$lang['settings_form_button_reinstall']= "Reinstall MultiScraper";
$lang['settings_form_reinstall_confirm']= "All your tasks will be lost. All your settings will be lost. Continue?";


$lang['settings_form_success']= "Settings has been modified";
$lang['settings_form_fail_nopass']= "Wrong current password";
$lang['settings_form_fail_nopassmatch']= "Passwords do not match!";


/*   TIPS ON SETTINGS FORM   */
$lang['settings_form_tip_invisible_mode_1'] = "<b>Invisible mode</b> allows to grab products anonymously, it uses the number of proxy servers and makes requests to the donor sites through them.";
$lang['settings_form_tip_invisible_mode_2'] = "This may be useful if you are grabbing stores that use protection from content grabbing or have switched on the protection from DDos attacks.";
$lang['settings_form_tip_invisible_mode_22'] = "the examples are <b>Miniinthebox.com</b> and <b>Lightinthebox.com</b>.";
$lang['settings_form_tip_invisible_mode_3'] = "<b>There are several warnings about it:</b>";
$lang['settings_form_tip_invisible_mode_4'] = " - DO NOT use this mode without necessity";
$lang['settings_form_tip_invisible_mode_5'] = " - DO NOT use on weak servers or servers with limited abilities";
$lang['settings_form_tip_invisible_mode_6'] = " - DO NOT use if you do not understand what is it";
$lang['settings_form_tip_invisible_mode_7'] = " - Note that there may be some losses in grabbed products while using this feature";
$lang['settings_form_tip_invisible_mode_8'] = ' - set the <b>MINIMUM</b> "Products grabbed in each iteration" when using this mode';

$lang['settings_form_tip_dev_mode_1'] = "<b>Dev mode</b> may be needed if the MultiScraper's developer will have to debug this application on your side";
$lang['settings_form_tip_dev_mode_2'] = "<b>There are several warnings about it:</b>";
$lang['settings_form_tip_dev_mode_3'] = " - DO NOT use this mode without necessity";
$lang['settings_form_tip_dev_mode_4'] = " - DO NOT use if you do not understand what is it";





/*****************  TASKS **************/
$lang['tasks_title']= "Tasks for your MultiScraper";
$lang['no_tasks_for_maspro']= "You have not yet made ​​any task for your MSPRO. Please, create the first one";


// table and actions
$lang['tasks_table_name']= "Task name";
$lang['tasks_table_state']= "Switched";
$lang['tasks_table_priority']= "Priority";
$lang['tasks_table_category']= "Category";
$lang['tasks_table_manufactuer']= "Manufacturer";
$lang['tasks_table_seourls']= "SeoURL";
$lang['tasks_table_margins']= "Margins fixed/relative";
$lang['tasks_table_products_found_parsed']= "Products found/grabbed";
$lang['tasks_table_comment']= "Comment";

$lang['actions_button']= "Actions";
$lang['actions_delete_button']= "Delete";
$lang['actions_delete_products_button']= "Delete with products";
$lang['actions_restart_button']= "Restart";
$lang['actions_edit_button']= "Edit task";


$lang['set_switch_action']= "Switch";
$lang['tasks_table_state_on']= "ON";
$lang['tasks_table_state_off']= "OFF";

$lang['tasks_table_seourl_on']= "ON";
$lang['tasks_table_seourl_off']= "OFF";


$lang['set_priority_action']= "Set priority";
$lang['tasks_priority_vars_0']= "Normal";
$lang['tasks_priority_vars_1']= "High";
$lang['tasks_priority_vars_2']= "Urgent";


$lang['tasks_what_to_do_product_not_exists_vars_0']= "Nothing";
$lang['tasks_what_to_do_product_not_exists_vars_1']= "Delete this product from store";
$lang['tasks_what_to_do_product_not_exists_vars_2']= 'Set "Out of Stock" status for this product';
$lang['tasks_what_to_do_product_not_exists_vars_3']= "Disable this product (make inactive)";
$lang['tasks_what_to_do_product_not_exists_vars_4']= "Set 0 quantity for this product";




// add/edit form
$lang['tasks_form_name']= "Task name";
$lang['tasks_form_state']= "Switched";
$lang['tasks_form_priority']= "Priority";
$lang['tasks_form_category_urls_1']= '"PRODUCTS LISTING" URLs';
$lang['tasks_form_category_urls_2']= 'each one from new line';
$lang['tasks_form_product_urls_1']= '"PRODUCT" URLs';
$lang['tasks_form_product_urls_2']= 'each one from new line';
$lang['tasks_form_category']= "Push into Category";
$lang['tasks_form_manufacturer']= "Manufacturer";
$lang['tasks_form_taxclass']= "Tax class";
$lang['tasks_form_main_image_limit']= "Main images limit";
$lang['tasks_form_description_image_limit']= "Images in description limit";
$lang['tasks_form_do_not_upload_description_image']= "Do not upload images in description";
$lang['tasks_form_image_folder']= "Separate image folder";
$lang['tasks_form_donor_currency']= "Donor market currency";
$lang['tasks_form_margin_fixed']= "Fixed margin ";
$lang['tasks_form_products_quantity']= "Products quantity";
$lang['tasks_form_margin_relative']= "Relative margin(%) ";
$lang['tasks_form_what_to_do_product_not_exists']= "What should I do if the product no more available at the donor market";
$lang['tasks_form_donot_update_price']= "Do not update the price after grabbing";
$lang['tasks_form_create_disabled']= 'Insert products as "Disabled" into my store';
$lang['tasks_form_get_options']= 'Get product Options (if available)';
$lang['tasks_form_do_not_update_options']= 'Do not update Options after grabbing';
$lang['tasks_form_do_not_update_manufacturer']= 'Do not update Manufacturer after grabbing';
$lang['tasks_form_do_not_update_taxclass']= 'Do not update Tax class after grabbing';
$lang['tasks_form_fieds_to_insert']= "Fields to insert";
$lang['tasks_form_fieds_to_update']= "Fields to update";
$lang['tasks_form_seo_url']= "SEO URL";
$lang['tasks_form_comment']= "Comment";

$lang['tasks_form_no_manufacturer_option']= "Do not set";
$lang['tasks_form_no_taxclass_option']= "Do not set";

$lang['tasks_form_sure_to_delete']= "Are you sure you want to delete this task?";
$lang['tasks_form_sure_to_restart']= "Are you sure you want to restart all instructions of this task?";

$lang['tasks_form_task_restarted']= "This task was successfully restarted";

$lang['tasks_add_button']= " + Add new task";
$lang['tasks_grabbed_product_button']= "Products grabbed";
$lang['tasks_save_button']= "Save";
$lang['tasks_cancel_button']= "Cancel";
$lang['tasks_table_category_urls'] = '"List of Products" Urls';
$lang['tasks_table_category_urls_hint_1'] = "each entry on a new line";
$lang['tasks_table_category_urls_hint_2'] = "exapmle";
$lang['tasks_table_products_urls'] = '"Products" Urls';
$lang['tasks_table_products_urls_hint_1'] = "each entry on a new line";
$lang['tasks_table_products_urls_hint_2'] = "exapmle";
$lang['tasks_table_margin_hint']= "Example: enter 1.15 here for 15% margin";

$lang['tasks_form_button_create'] = "Create new task";
$lang['tasks_form_button_edit'] = "Edit task";


/*   TIPS ON PRODUCT FORM   */
$lang['tasks_form_tip_listing_1'] = "<b>Listing URL</b> is a page with a list of products";
$lang['tasks_form_tip_listing_2'] = "This may be a Category page, search results page or any other product listing";
$lang['tasks_form_tip_listing_3'] = "The examples of <b>Listing URL</b>:";
$lang['tasks_form_tip_listing_4'] = '<a href="http://www.focalprice.com/7.9-in-quad-core-tablet/ca-013001015.html" target="_blank">http://www.focalprice.com/7.9-in-quad-core-tablet/ca-013001015.html</a>';
$lang['tasks_form_tip_listing_5'] = '<a href="http://www.aliexpress.com/category/100005062/tablet-pcs.html" target="_blank">http://www.aliexpress.com/category/100005062/tablet-pcs.html</a>';

$lang['tasks_form_tip_product_1'] = "<b>Product URL</b> is the particular product page URL";

$lang['tasks_form_tip_category_1'] = "Grabbed products will be inserted into the choosen category (or several categories) of your " . MSPRO_CMS_DISPLAY_NAME . " store";
$lang['tasks_form_tip_category_2'] = "You may choose one or several ";

$lang['tasks_form_tip_taxclass_1'] = "You may manage your Tax classes in your " . MSPRO_CMS_DISPLAY_NAME . " admin panel: <b>Settings -> Localization -> Taxes -> Tax classes</b>";
$lang['tasks_form_tip_taxclass_2'] = "The one that be chosen here will be applied to all products grabbed by this Task ";

$lang['tasks_form_tip_do_not_update_taxclass_1'] = "If checked the Tax class will not be updated after grabbing";
$lang['tasks_form_tip_do_not_update_taxclass_2'] = "This may be useful if you are going to change Tax class manually (from admin panel) for some products after grabbing, and you don't want MultiScraper break these changes when it will update the products";

$lang['tasks_form_tip_main_image_limit_1'] = "You may limit the main product's images (the main image and other images in gallery/slideshow)";
$lang['tasks_form_tip_main_image_limit_2'] = 'Set this setting to "-1" to break the limit';

$lang['tasks_form_tip_description_image_limit_1'] = "You may limit the images in the product's description, all other will be cut from the description";
$lang['tasks_form_tip_description_image_limit_2'] = 'Set this setting to "-1" to break the limit';

$lang['tasks_form_tip_do_not_upload_description_image_1'] = "If checked - the images in description will not be uploaded to your server (the images will be displayed from the external source - from the donor market)";
$lang['tasks_form_tip_do_not_upload_description_image_2'] = "This may be useful if you want to save disk space";

$lang['tasks_form_tip_manufacturer_1'] = 'If chosen <b>"Do not set"</b> the MultiScraper will try to define manufacturer by itself';
$lang['tasks_form_tip_manufacturer_2'] = "But this feature is implemented NOT FOR ALL donor markets";
$lang['tasks_form_tip_manufacturer_3'] = "Or you may define the particular manufacturer for all products grabbed by this Task";

$lang['tasks_form_tip_do_not_update_manufacturer_1'] = "If checked the manufacturer will not be updated after grabbing";
$lang['tasks_form_tip_do_not_update_manufacturer_2'] = "This may be useful if you are going to change manufacturer manually (from admin panel) for some products after grabbing, and you don't want MultiScraper break these changes when it will update the products";


$lang['tasks_form_tip_imageFolder_0'] = "By default, MultiScraper will grab all images which it will find relates to particular product (images in slideshow, in the description) and place them into your image folder.";
$lang['tasks_form_tip_imageFolder_1'] = "All images grabbed by this particular task may be located in custom subfolder (of your main <b>image/data/</b> image location) configured here.";
$lang['tasks_form_tip_imageFolder_2'] = "You may create the any nesting level subfolder. The Examples are:";
$lang['tasks_form_tip_imageFolder_3'] = '<span style="color: grey;font-size: 1.4em;">/mysubfolder/products/</span>';
$lang['tasks_form_tip_imageFolder_4'] = '<span style="color: grey;font-size: 1.4em;">/mysubfolder1/test/mysubfolder1</span>';
$lang['tasks_form_tip_imageFolder_5'] = '<span style="color: grey;font-size: 1.4em;">/onelevelsubfolder/</span>';
$lang['tasks_form_tip_imageFolder_6'] = 'Leave this field blank and all images will be pushed into your main  <b>image/data/</b> folder (<b>image/catalog/</b> for 2.x version)';

$lang['tasks_form_tip_products_quantity_1'] = 'This is the quantity of each product grabbed by this Task';

$lang['tasks_form_tip_currency_1'] = "You have to configure the currency of the DONOR MARKET.";
$lang['tasks_form_tip_currency_2'] = "If your store is in the another currency MultiScraper will translate the price into your currency using your " . MSPRO_CMS_DISPLAY_NAME . " exchange rates.";

$lang['tasks_form_tip_margin_fixed_1'] = "This is the fixed margin in your store's currency. For example you want to add $10 to the price of each product grabbed ";
$lang['tasks_form_tip_margin_fixed_2'] = 'This margin will be applied to the price AFTER been converted into your store default currency';

$lang['tasks_form_tip_margin_relative_1'] = "This is the relative margin (in percents) in your store's currency. For example you want to add 20% to the price of each product grabbed";
$lang['tasks_form_tip_margin_relative_2'] = 'This margin will be applied to the price AFTER been converted into your store default currency';

$lang['tasks_form_tip_what_to_do_product_not_exist_1'] = "What should the MultiScraper do with the existing product if while updating it can't find the original product at the donor market (it was removed or Sold out)";

$lang['tasks_form_tip_donot_update_price_1'] = "If checked the Price will not be updated after grabbing";
$lang['tasks_form_tip_donot_update_price_2'] = "This may be useful if you are going to change Price manually (from admin panel) for some products after grabbing, and you don't want MultiScraper break these changes when it will update the products";

$lang['tasks_form_tip_create_disabled_1'] = 'If checked - the grabbed products will be inserted in your store with "Disabled" status';
$lang['tasks_form_tip_create_disabled_2'] = "This may be useful if you don't want the visitors of your store view these products for a while";

$lang['tasks_form_tip_get_options_1'] = "Uncheck if you don't want MultiScraper grab product options (Sizes, Colors etc)";

$lang['tasks_form_tip_do_not_update_options_1'] = 'If checked - MultiScraper will NOT update the product options while updating the grabbed product';

$lang['tasks_form_tip_seourl_1'] = "MultiScraper just use the " . MSPRO_CMS_DISPLAY_NAME . " built-it SEO url mechanism.";
$lang['tasks_form_tip_seourl_2'] = "Switch ON this setting and MultiScraper will create auto SEO url for each product grabbed.";
$lang['tasks_form_tip_seourl_3'] = "if you don't know about SEO url, you may swith ON or OFF this feature, nothing will be damaged."; 


/* PRODUCTS GRABBED POPUPS, TABLES AND BUTTONS   */
$lang['tasks_form_grabbed_products_popup_title'] = "Products grabbed by this Task";
$lang['tasks_form_grabbed_products_popup_title_all'] = "Products grabbed by MultiScraper";
$lang['tasks_form_grabbed_products_popup_table_1'] = "No";
$lang['tasks_form_grabbed_products_popup_table_2'] = "Link at your " . MSPRO_CMS_DISPLAY_NAME . " store";
$lang['tasks_form_grabbed_products_popup_table_3'] = "Link at DONOR MARKET";
$lang['tasks_form_grabbed_products_popup_table_4'] = "Price";
$lang['tasks_form_grabbed_products_popup_table_5'] = "Quantity";
$lang['tasks_form_grabbed_products_popup_table_6'] = "Stock status";
$lang['tasks_form_grabbed_products_popup_table_7'] = "Date grabbed";
$lang['tasks_form_grabbed_products_popup_table_8'] = "Date updated";

$lang['tasks_form_grabbed_products_popup_table_status_5'] = "Out Of Stock";
$lang['tasks_form_grabbed_products_popup_table_status_6'] = "2-3 days Out Of Stock";
$lang['tasks_form_grabbed_products_popup_table_status_7'] = "In Stock";
$lang['tasks_form_grabbed_products_popup_table_status_8'] = "Pre-Order";





/*****************  LOG **************/
$lang['log_title']= "Log";
$lang['log_refresh_button']= "Refresh";
$lang['log_clear_button']= "Clear log";
$lang['log_seedevlog_link']= "see dev log";


/***************** MANUAL  **************/
$lang['manual_title']= "Manual Launch";

$lang['manual_page_1']= "MSPRO created to be used in the automatical mode: you add the tasks for scraping and MSPRO grabs product one by one while you relax.";
$lang['manual_page_2']= 'You may find how to configure MSPRO operate in the automatical mode at the "How to" page in the "Configuration" section ';
$lang['manual_page_3']= "But here you can startup MSPRO manually";
$lang['manual_page_4']= "It is very useful for demonstration or testing your MSPRO";
$lang['manual_page_5']= 'Each time you press the "Launch" button you will launch 1 iteration of MSPRO';
$lang['manual_page_6']= 'After this you will be redirected to the "Log" section where you will be able to view the results of MSPRO processing';

$lang['manual_button']= "Launch";
$lang['manual_waiting_message']= "Grabbing is not the easy process. So this may take some time.";

$lang['manual_page_mspro_switched_off']= 'Your MSPRO is switched OFF. Please, switch on it at the "Settings" section.';
$lang['manual_page_mspro_no_tasks']= "You have no active tasks for MSPRO.";









/*****************  HOW TO USE **************/
$lang['howtouse_title']= 'How to use "MultiScraper"';
$lang['howtouse_inteface_title']= "User interface";


// Overview
$lang['howtouse_overview_title'] = 'Overview';
$lang['howtouse_overview_1'] = 'MultiScraperPRO is the stand-alone application that will grab products from other big online marketplaces (amazon, aliexpress, etsy, focalprice, or any others that are configured)
and insert them directly into your ' . MSPRO_CMS_DISPLAY_NAME . ' store.';
$lang['howtouse_overview_2'] = 'MultiScraper able to grab separate products lists as well as the whole categories or search results listings, even if the result lists are paginated.';
$lang['howtouse_overview_3'] = 'MultiScraper able to make different modifications with the grabed data on-the-fly (for example, add relative or fixed margin to product price).';
$lang['howtouse_overview_4'] = 'MultiScraper supports ' . MSPRO_CMS_DISPLAY_NAME . ' SEO URL feature.';
$lang['howtouse_overview_markets_title'] = 'Your MultiScraper now supports the next donor markets:';
$lang['howtouse_overview_markets_core'] = 'Core:';
$lang['howtouse_overview_markets_additional'] = 'Additional:';

// Usage


// Proxy
$lang['howtouse_proxy_title'] = 'Proxy support';
$lang['howtouse_proxy_text'] = 'Many users of MultiScraper faced the huge problem grabbing products from Aliexpress, Alibaba and some other donor markets.<br />
Sometimes it occurs right after the MultiScraper\'s installation, sometimes some later.<br />
Almost always such problem occurs while intensive grabbing: donor market just blocks the domain/IP when see too many requests from it - donor site thinks that this is some kind of DDoS attack and redirects all requests from this IP to the authorisation page or just returns the blank page.<br />
Trying to resolve this problem for our customers we added the PROXY support feature to the MultiScraper.<br />
It works as follows: if attempt to get product\'s page by the usual methods is failed, the page will be requested through an elite or an anonymous proxy server.<br />
<br />
The situation is complicated by the fact that we have only limited number of the proxy, but already have an impressive number of users of MultiScraper, so this is not the stable solution.<br />
To resolve this, we added the ability to use YOUR OWN PROXY and do not depend on ours.<br />
You may create them by yourself or use special services where you can buy or rent a list of proxies of different levels of anonymity (about PROXY types you can read <a href="http://forumaboutproxy.com/general-discussions/what-is-an-anonymous-proxy-server-proxy-types-transparent-anonymous-or-elite/" target="_blank" style="text-decoration:underline;color:#4856EA;cursor: help;"><b>here</b></a>).<br />
Such services are easy to find at the request of "Rent elite proxy" or "Rent anonymous proxy".<br />
The examples of such services are <a href="http://affiliate.proxy-list.org/UF6VFAQFC/PG8QZJYG97/" target="_blank" style="text-decoration:underline;">proxy-list.org</a>, <a href="http://my-proxy.com/" target="_blank" style="text-decoration:underline;">my-proxy.com</a> or <a href="http://coolproxies.com/" target="_blank" style="text-decoration:underline;">coolproxies.com</a> etc. There are many similar services and unfortunately we cannot test them all or give you any guarantee of any of them :(<br />
    You may use them at your own risk.<br />
<br />
How to install your OWN proxies list: in the root of your MultiScraper\'s folder you will find the <b>proxyMyOwn.txt</b> file. Open it, paste your proxy and save.<br />
You may paste the list of proxies (each one from new line), it should look <a onclick="$(\'#bpopup_proxy_1\').bPopup();" style="text-decoration:underline;color:#4856EA;cursor: help;"><b>[like this]</b></a><br />
If this file is not empty, MultiScraper will use the proxies from it. If empty - it will try to use our proxies list.<br />
<br />
<b><span style="color:red;">Warning:</span></b> using Proxy you load the MultiScraper (and so your server) more then usual, so it is NOT recomended to make intensive grabbing from such "hard" donor markets as Aliexpress, Alibaba, Taobao, Amazon and so on.<br />';

// Configuration
$lang['howtouse_configuration_title'] = 'Configuration';
$lang['howtouse_configuration_1'] = 'MultiScraper created to be used in the automatical mode: you add the tasks for scraping and MultiScraper grabs products one by one while you relax.';
$lang['howtouse_configuration_2'] = 'MultiScraper also has the ability to be launched manually (see "Manual Startup" section) but this feature is just for testing or demonstration.';
$lang['howtouse_configuration_3'] = 'To let MultiScraper grabbing products automatically you must add 1 task into your server CRONJOBS.';
$lang['howtouse_configuration_31'] = 'CRON is a server utility designed to run scripts on a schedule (once in hour, every 10 minute, every minute, etc).';
$lang['howtouse_configuration_4'] = 'The task for CRON is opening the particular url (just like you do it using your web browser): <b>"http://YOURSTORE.COM/multiscraper/process/"</b>';
$lang['howtouse_configuration_41'] = 'as a rule this is command like this:';
$lang['howtouse_configuration_42'] = '<b><span style="color:#2D75FA;">wget -O - http://YOURSTORE.COM/multiscraper/process/ >/dev/null 2>&1</span></b>';
$lang['howtouse_configuration_5'] = 'Recommended startup interval for MultiScraper is <b>EVERY 5 MINUTES</b>.';
$lang['howtouse_configuration_6'] = 'Try to configure it by yourself and if will have no success just ask the server administrator help you to create this task.';
$lang['howtouse_configuration_61'] = 'This is very frequent request to the hosting support and as a usual it is resolved very fast.';
$lang['howtouse_configuration_7'] = 'Also, in the "Settings" sections you will find <b>"Products grabbed in each iteration"</b> parameter.';
$lang['howtouse_configuration_71'] = 'This is the quantity of products that will be grabbed in one iteration (during 1 launch of MultiScraper).';
$lang['howtouse_configuration_72'] = 'Using these numbers you may define your MultiScraper data capacity.';
$lang['howtouse_configuration_8'] = 'For example, you configured your MultiScraper to launch every 5 minute and defined "Products grabbed in each iteration" as 2 products.';
$lang['howtouse_configuration_81'] = 'So, your MultiScraper data capacity: <b>( 60/5 minutes  * 2 products) * 24hours  =  580 products</b> grabbed daily';
$lang['howtouse_configuration_82'] = 'Using these two parameters (MultiScraper startup interval and "Products grabbed in each iteration") you may adjust your MultiScraper power, make the scraping more or less intensive';
//$lang[''] = '';

$lang['howtouse_safety_title'] = 'Safety';
$lang['howtouse_safety_1_title'] = 'Change password for your MSPRO';
$lang['howtouse_safety_11'] = 'Initial password is <b>"password"</b> and it is recommended to change it in the Settings section.';
$lang['howtouse_safety_2_title'] = 'Rename your MultiScraper location folder';
$lang['howtouse_safety_21'] = 'Originally your MSPRO location is "http://yourstore.com/multiscraper/". It is recommended to change it to your own location name.';
$lang['howtouse_safety_22'] = '';
$lang['howtouse_safety_rename_1']= 'choose appropriate location name (for example: {your_secret_word}_scraper )';
$lang['howtouse_safety_rename_2']= 'open file multiscraper/.htaccess ';
$lang['howtouse_safety_rename_21'] = 'find: <b>multiscraper</b>';
$lang['howtouse_safety_rename_22'] = ' replace with: {YOUR_CHOOSEN_LOCATION_NAME}';
$lang['howtouse_safety_rename_3']= 'rename "multiscraper" folder into "{YOUR_CHOOSEN_LOCATION_NAME}" folder';
$lang['howtouse_safety_rename_4']= 'Then try the next url in your browser: "http://{yourshop.com}/{YOUR_CHOOSEN_LOCATION_NAME}/". You will the same "MultiScraper" interface, but more safely.';
$lang['howtouse_safety_rename_5'] = 'Do not forget to edit your CRONJOBS scripts location (see configuration section)';



/*****************  PROCESS (+ process logs) **************/
$lang['process_log_started'] = "<font color='green'> Started </font>";
$lang['process_log_manual_start'] = "<font color='green'> (Manual Startup) </font>";
$lang['process_log_target_category'] = "<font color='green'> Target: Listing parsing </font>";
$lang['process_log_target_product'] = "<font color='green'> Target: Product parsing </font>";

$lang['process_log_product_inserted'] = "Products Inserted:";
$lang['process_log_product_updated'] = "Products Updated:";


$lang['process_log_parse_cat_products_found_title'] = "Products Found";
$lang['process_log_parse_cat_next_page_title'] = "Next Page";
$lang['process_log_parse_cat_next_page_exists_no'] = "NO";
$lang['process_log_parse_cat_next_page_exists_yes'] = "YES";

// months
$lang['process_log_month_01'] = "January";
$lang['process_log_month_02'] = "February";
$lang['process_log_month_03'] = "March";
$lang['process_log_month_04'] = "April";
$lang['process_log_month_05'] = "May";
$lang['process_log_month_06'] = "June";
$lang['process_log_month_07'] = "July";
$lang['process_log_month_08'] = "August";
$lang['process_log_month_09'] = "September";
$lang['process_log_month_10'] = "October";
$lang['process_log_month_11'] = "November";
$lang['process_log_month_12'] = "December";


/*
$lang['']= "";
*/

/***************** Trial and Demo messages **************/
$lang['messages_demo'] = "Sorry, you are unable to do this in the DEMO mode.";
$lang['messages_trial'] = "You use the TRIAL version and have already grabbed several products. Purchase the full version to continue...";
