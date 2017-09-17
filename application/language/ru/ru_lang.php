<?php

$lang['language_key'] = "ru";
$lang['title'] = "MultiScraper";
$lang['under_title'] = "Тырьте товары с онлайн площадок прямо в ваш " . MSPRO_CMS_DISPLAY_NAME . "";

/*****************  Auth **************/
$lang['auth_title']= "MultiScraper вход";
$lang['auth_form_title']= "Ваш MultiScraper пароль:";
$lang['auth_button_submit']= "Войти";
$lang['auth_error']= "Неправильный пароль!!!";



/*****************  MENU **************/
$lang['menu_howtouse_title']= "Что это";
$lang['menu_howtouse_desc']= "и как им пользоваться";

$lang['menu_settings_title']= "Настройки";
$lang['menu_settings_desc']= "Общие настройки MSPRO";

$lang['menu_tasks_title']= "Задания";
$lang['menu_tasks_desc']= "Инструкции для MSPRO";

$lang['menu_log_title']= "Лог действий";
$lang['menu_log_desc']= "Контроль выполнения";

$lang['menu_contact_title']= "Ручной запуск";
$lang['menu_contact_desc']= "Отладка и демонстрация";

/*****************  SETTINGS **************/

$lang['seettings_title']= "Общие настройки MSPRO";

$lang['settings_form_title']= "Настройки";
$lang['settings_form_state']= "Состояние";
$lang['settings_form_dev_mode']= "Режим отладки";
$lang['settings_form_inv_mode']= 'Режим "Невидимка"';
$lang['settings_form_lang']= "Интерфейс";
$lang['settings_form_num_product']= "Сколько товаров тырим за раз";

$lang['settings_form_button_change_pass']= " + Сменить пароль";
$lang['settings_form_old_pass']= "Текущий пароль";
$lang['settings_form_new_pass']= "Новый пароль";
$lang['settings_form_confirm_pass']= "Новый пароль ещё раз";

$lang['settings_form_button_save']= "Сохранить";

$lang['settings_form_button_reinstall']= "Переустановить MultiScraper";
$lang['settings_form_reinstall_confirm']= "Все настройки и созданные задания будут потеряны. Продолжить?";

$lang['settings_form_success']= "Настройки успешно сохранены";
$lang['settings_form_fail_nopass']= "Неправильный текущий пароль";
$lang['settings_form_fail_nopassmatch']= "Не совпадают новые пароли";

/*   TIPS ON SETTINGS FORM   */
$lang['settings_form_tip_invisible_mode_1'] = '<b>Режим "Невидимка"</b> позволяет тырить товары анонимно, он использует ряд прокси серверов и осуществляет запросы к сайтам донорам через них';
$lang['settings_form_tip_invisible_mode_2'] = "Это может понадобиться если вы тырите товары с сайтов которые поставили защиту от кражи котента или защиту от DDos атак";
$lang['settings_form_tip_invisible_mode_22'] = "яркие примеры когда это может понадобиться: <b>Miniinthebox.com</b> и <b>Lightinthebox.com</b>";
$lang['settings_form_tip_invisible_mode_3'] = "<b>Несколько предупреждений:</b>";
$lang['settings_form_tip_invisible_mode_4'] = " - НЕ ИСПОЛЬЗУЙТЕ без необходимости";
$lang['settings_form_tip_invisible_mode_5'] = " - НЕ ИСПОЛЬЗУЙТЕ на слабых серверах";
$lang['settings_form_tip_invisible_mode_6'] = " - НЕ ИСПОЛЬЗУЙТЕ если не понимаете что это такое";
$lang['settings_form_tip_invisible_mode_7'] = " - Учтите что могут быть потери при парсинге товаров с использованием этого режима";
$lang['settings_form_tip_invisible_mode_8'] = ' - Устанавливайте в <b>МИНИМУМ</b> "Сколько товаров тырим за раз" когда включаете этот режим';

$lang['settings_form_tip_dev_mode_1'] = "<b>Режим отладки</b> предназначен для разработчика в случае если понадобится произвести отладку приложения на вашей стороне";
$lang['settings_form_tip_dev_mode_2'] = "<b>Несколько предупреждений:</b>";
$lang['settings_form_tip_dev_mode_3'] = " - НЕ ИСПОЛЬЗУЙТЕ без необходимости";
$lang['settings_form_tip_dev_mode_4'] = " - НЕ ИСПОЛЬЗУЙТЕ если не понимаете что это такое";




/*****************  TASKS **************/
$lang['tasks_title']= "Задания для Вашего MultiScraper";
$lang['no_tasks_for_maspro']= "Вы не составили ни одного задания для MSPRO. Создайте первое.";


// table and actions
$lang['tasks_table_name']= "Название Задачи";
$lang['tasks_table_state']= "Включена";
$lang['tasks_table_priority']= "Приоритет";
$lang['tasks_table_category']= "Категория";
$lang['tasks_table_manufactuer']= "Производитель";
$lang['tasks_table_seourls']= "SeoURL";
$lang['tasks_table_margins']= "Наценка абс./отн.";
$lang['tasks_table_products_found_parsed']= "Товаров найдено/стырено";
$lang['tasks_table_comment']= "Комментарий";

$lang['actions_button']= "Действия";
$lang['actions_delete_button']= "Удалить";
$lang['actions_delete_products_button']= "Удалить с товарами";
$lang['actions_restart_button']= "Перезапуск";
$lang['actions_edit_button']= "Редактировать";


$lang['set_switch_action']= "Включение";
$lang['tasks_table_state_on']= "ВКЛ";
$lang['tasks_table_state_off']= "ВЫКЛ";

$lang['tasks_table_seourl_on']= "ВКЛ";
$lang['tasks_table_seourl_off']= "ВЫКЛ";


$lang['set_priority_action']= "Приоритет";
$lang['tasks_priority_vars_0']= "Обычный";
$lang['tasks_priority_vars_1']= "Высокий";
$lang['tasks_priority_vars_2']= "Срочный";


$lang['tasks_what_to_do_product_not_exists_vars_0']= "Ничего";
$lang['tasks_what_to_do_product_not_exists_vars_1']= "Удалить товары";
$lang['tasks_what_to_do_product_not_exists_vars_2']= 'Установить статус "Нет в наличии"';
$lang['tasks_what_to_do_product_not_exists_vars_3']= "Выключить этот товар (сделать невидимым)";
$lang['tasks_what_to_do_product_not_exists_vars_4']= "Установить 0 колличество для этих товаров";




// add/edit form
$lang['tasks_form_name']= "Название Задачи";
$lang['tasks_form_state']= "Включена";
$lang['tasks_form_priority']= "Приоритет";
$lang['tasks_form_category_urls_1']= 'Адреса ЛИСТИНГА';
$lang['tasks_form_category_urls_2']= 'каждый с новой строки';
$lang['tasks_form_product_urls_1']= 'Адреса ТОВАРОВ';
$lang['tasks_form_product_urls_2']= 'каждый с новой строки';
$lang['tasks_form_category']= "Вставить в категорию";
$lang['tasks_form_manufacturer']= "Производитель";
$lang['tasks_form_taxclass']= "Налоговый класс";
$lang['tasks_form_main_image_limit']= "Лимит основных фоток";
$lang['tasks_form_description_image_limit']= "Лимит фоток в описании";
$lang['tasks_form_do_not_upload_description_image']= "Не загружать фотки в описании";
$lang['tasks_form_image_folder']= "Отдельная папка для фоток";
$lang['tasks_form_donor_currency']= "Валюта на доноре";
$lang['tasks_form_margin_fixed']= "Абсолютная наценка";
$lang['tasks_form_products_quantity']= "Колличество товаров";
$lang['tasks_form_margin_relative']= "Относительная наценка(%) ";
$lang['tasks_form_what_to_do_product_not_exists']= "Что делать если товара более не существует на доноре";
$lang['tasks_form_donot_update_price']= "Не редактировать цену после парсинга";
$lang['tasks_form_create_disabled']= 'Вставлять товары со статусом "Disabled"';
$lang['tasks_form_get_options']= 'Парсить Опции (если доступно)';
$lang['tasks_form_do_not_update_options']= 'Не обновлять опции при обновлении товара';
$lang['tasks_form_do_not_update_manufacturer']= 'Не обновлять Производителя';
$lang['tasks_form_do_not_update_taxclass']= 'Не обновлять Налоговый класс';
$lang['tasks_form_fieds_to_insert']= "Поля которые вставлять";
$lang['tasks_form_fieds_to_update']= "Поля которые обновлять";
$lang['tasks_form_seo_url']= "SEO URL";
$lang['tasks_form_comment']= "Комментарий";

$lang['tasks_form_no_manufacturer_option']= "Не устанавливать";

$lang['tasks_form_sure_to_delete']= "Уверены что хотите удалить эту задачу?";
$lang['tasks_form_sure_to_restart']= "Уверены что хотите переустановить все инструкции по этой задаче?";

$lang['tasks_form_task_restarted']= "Задача была перезапущена";

$lang['tasks_add_button']= " +Добавить задачу";
$lang['tasks_grabbed_product_button']= "Товаров стырено";
$lang['tasks_save_button']= "Сохранить";
$lang['tasks_cancel_button']= "Отмена";
$lang['tasks_table_category_urls'] = 'Адреса ЛИСТИНГА';
$lang['tasks_table_category_urls_hint_1'] = "каждый с новой строки";
$lang['tasks_table_category_urls_hint_2'] = "пример";
$lang['tasks_table_products_urls'] = 'Адреса ТОВАРОВ';
$lang['tasks_table_products_urls_hint_1'] = "каждый с новой строки";
$lang['tasks_table_products_urls_hint_2'] = "пример";
$lang['tasks_table_margin_hint']= "Пример: введите 1.15 для 15% наценки";

$lang['tasks_form_button_create']= "Создать задачу";
$lang['tasks_form_button_edit']= "Редактировать задачу";

/*   TIPS ON PRODUCT FORM   */
$lang['tasks_form_tip_listing_1'] = "<b>ЛИСТИНГ ТОВАРОВ</b> это страница списка товаров";
$lang['tasks_form_tip_listing_2'] = "Это может быть страница Категории, страница результатов поиска или любой другой список товаров";
$lang['tasks_form_tip_listing_3'] = "Примеры <b>ЛИСТИНГА ТОВАРОВ</b>:";
$lang['tasks_form_tip_listing_4'] = '<a href="http://www.focalprice.com/7.9-in-quad-core-tablet/ca-013001015.html" target="_blank">http://www.focalprice.com/7.9-in-quad-core-tablet/ca-013001015.html</a>';
$lang['tasks_form_tip_listing_5'] = '<a href="http://www.aliexpress.com/category/100005062/tablet-pcs.html" target="_blank">http://www.aliexpress.com/category/100005062/tablet-pcs.html</a>';

$lang['tasks_form_tip_product_1'] = "<b>Адрес ТОВАРА</b> это просто URL страницы 1 товара";

$lang['tasks_form_tip_category_1'] = "Стыренные товары будут вставляться в выбранную категорию (категории) Вашего " . MSPRO_CMS_DISPLAY_NAME . " магазина";
$lang['tasks_form_tip_category_2'] = "Можно выбрать одну или несколько ";

$lang['tasks_form_tip_taxclass_1'] = "Налоговыми классами Вы можете управлять из админки " . MSPRO_CMS_DISPLAY_NAME . ": <b>Settings -> Localization -> Taxes -> Tax classes</b>";
$lang['tasks_form_tip_taxclass_2'] = "Выбранный здесь Налоговый класс будет применён ко всем товарам стыренным по данному заданию";

$lang['tasks_form_tip_do_not_update_taxclass_1'] = "Если отметите этот чекбокс то Налоговый класс не будет изменяться при апдейте товаров";
$lang['tasks_form_tip_do_not_update_taxclass_2'] = "Это может быть полезно если вы собираетесь вручную менять Налоговый класс у стыренных товаров и не хотите чтобы MultiScraper перетёр эти изменения при апдейте товаров ";

$lang['tasks_form_tip_main_image_limit_1'] = "Вы можете ограничить колличество основных фоток товара  (имеются ввиду фотки из главной галереи/слайдшоу)";
$lang['tasks_form_tip_main_image_limit_2'] = 'Установите значение в "-1" чтобы снять лимит';

$lang['tasks_form_tip_description_image_limit_1'] = "Вы можете ограничить колличество фоток из описания которые будут стырены, остальные будут вырезаны из описания";
$lang['tasks_form_tip_description_image_limit_2'] = 'Установите значение в "-1" чтобы снять лимит';

$lang['tasks_form_tip_do_not_upload_description_image_1'] = 'Если отметите этот чекбокс то фотки из описания товара не будут загружаться на ваш сервер а будут "подтягиваться" из внешнего источника';
$lang['tasks_form_tip_do_not_upload_description_image_2'] = "Может быть полезно с целью экономии дискового пространства";

$lang['tasks_form_tip_manufacturer_1'] = 'Если выберете <b>"Не устанавливать"</b> то MultiScraper будет пытаться сам определить производителя у каждого товара';
$lang['tasks_form_tip_manufacturer_2'] = "Но учтите что это доступно далеко не для всех догоров";
$lang['tasks_form_tip_manufacturer_3'] = "Или можете сразу указать определённого производителя всех товаров стыренных по данному заданию";

$lang['tasks_form_tip_do_not_update_manufacturer_1'] = "Если отметите этот чекбокс то Производитель не будет изменяться при апдейте товаров";
$lang['tasks_form_tip_do_not_update_manufacturer_2'] = "Это может быть полезно если вы собираетесь вручную менять Производителя у стыренных товаров и не хотите чтобы MultiScraper перетёр эти изменения при апдейте товаров ";


$lang['tasks_form_tip_imageFolder_0'] = "По умолчанию, MultiScraper будет тырить все фотки и загружать их в корневую папку хранения фоток.";
$lang['tasks_form_tip_imageFolder_1'] = "Но Вы можете указать особое место куда будут загружаться фотки стыренные по данному заданию (путь задаётся от корневой <b>image/data/</b> папки).";
$lang['tasks_form_tip_imageFolder_2'] = "Вы можете указать подпапки любого уровня глубины. Примеры:";
$lang['tasks_form_tip_imageFolder_3'] = '<span style="color: grey;font-size: 1.4em;">/mysubfolder/products/</span>';
$lang['tasks_form_tip_imageFolder_4'] = '<span style="color: grey;font-size: 1.4em;">/mysubfolder1/test/mysubfolder1</span>';
$lang['tasks_form_tip_imageFolder_5'] = '<span style="color: grey;font-size: 1.4em;">/onelevelsubfolder/</span>';
$lang['tasks_form_tip_imageFolder_6'] = 'Оставьте это поле пустым и все фотки будут попадать в корневую <b>image/data/</b> папку (<b>image/catalog/</b> для версии 2.x)';

$lang['tasks_form_tip_products_quantity_1'] = 'Это Колличество каждого товара старенного по данному заданию';

$lang['tasks_form_tip_currency_1'] = "Вы должны указать валюту сайта с которого тырите товары.";
$lang['tasks_form_tip_currency_2'] = "Если Ваш магазин работает в другой валюте MultiScraper конвертирует цену в валюту вашего магазина согласно обменному курсу указанному в настройках Вашего магазина.";

$lang['tasks_form_tip_margin_fixed_1'] = "Это фиксированная наценка в валюте Вашего магазина. Например, Вы захотите добавить 100р к спарсенной цене по каждому товару";
$lang['tasks_form_tip_margin_fixed_2'] = 'Эта наценка применяется уже после того как начальная цена сконвертирована в валюту Вашего магазина';

$lang['tasks_form_tip_margin_relative_1'] = "Это относительная наценка (в процентах) к цене. Например, Вы захотите добавить 20% к спарсенной цене по каждому товару";
$lang['tasks_form_tip_margin_relative_2'] = 'Эта наценка применяется уже после того как начальная цена сконвертирована в валюту Вашего магазина';

$lang['tasks_form_tip_what_to_do_product_not_exist_1'] = "Что делать MultiScraper если при обновлении товара он не может найти оригинальный товар на доноре или он уже распродан";

$lang['tasks_form_tip_donot_update_price_1'] = "Если отметите этот чекбокс то Цена не будет изменяться при апдейте товаров";
$lang['tasks_form_tip_donot_update_price_2'] = "Это может быть полезно если вы собираетесь вручную менять Цену у стыренных товаров и не хотите чтобы MultiScraper перетёр эти изменения при апдейте товаров ";

$lang['tasks_form_tip_create_disabled_1'] = 'Если отмечено - стыренные товары попадут в Ваш магазин со статусом "Выключен"';
$lang['tasks_form_tip_create_disabled_2'] = "Это может быть полезно если вы не хотите чтобы стыренные товары сразу были видны посетителям вашего магазина";

$lang['tasks_form_tip_get_options_1'] = "Снимите галочку если не хотите чтобы MultiScraper тырил опции (Цвет, Размер итп)";

$lang['tasks_form_tip_do_not_update_options_1'] = 'Если отметите этот чекбок MultiScraper не будет обновлять опции при обновлении товара';

$lang['tasks_form_tip_seourl_1'] = "MultiScraper использует встроенный в " . MSPRO_CMS_DISPLAY_NAME . " механизм формирования SEO url.";
$lang['tasks_form_tip_seourl_2'] = "Включите эту настройку и MultiScraper будет создавать SEO url для стыренных товаров.";
$lang['tasks_form_tip_seourl_3'] = "Если Вы ничего не знаете о SEO url, оставьте эту настройку."; 


/* PRODUCTS GRABBED POPUPS, TABLES AND BUTTONS   */
$lang['tasks_form_grabbed_products_popup_title'] = "Товары стыренные по данному заданию";
$lang['tasks_form_grabbed_products_popup_title_all'] = "Все стыренные товары";
$lang['tasks_form_grabbed_products_popup_table_1'] = "No";
$lang['tasks_form_grabbed_products_popup_table_2'] = "Товар в нашем магазине";
$lang['tasks_form_grabbed_products_popup_table_3'] = "Товар на рынке ДОНОРЕ";
$lang['tasks_form_grabbed_products_popup_table_4'] = "Цена";
$lang['tasks_form_grabbed_products_popup_table_5'] = "Колличество";
$lang['tasks_form_grabbed_products_popup_table_6'] = "Статус";
$lang['tasks_form_grabbed_products_popup_table_7'] = "Когда стырили";
$lang['tasks_form_grabbed_products_popup_table_8'] = "Когда обновили";

$lang['tasks_form_grabbed_products_popup_table_status_5'] = "Нет в наличии";
$lang['tasks_form_grabbed_products_popup_table_status_6'] = "Наличие за 2-3 дня";
$lang['tasks_form_grabbed_products_popup_table_status_7'] = "В наличии";
$lang['tasks_form_grabbed_products_popup_table_status_8'] = "Под заказ";




/*****************  LOG **************/
$lang['log_title']= "Лог действий";
$lang['log_refresh_button']= "Обновить";
$lang['log_clear_button']= "Очистить";
$lang['log_seedevlog_link']= "смотреть отладку";


/***************** MANUAL  **************/
$lang['manual_title']= "Ручной запуск";

$lang['manual_page_1']= "MSPRO создан для работы в автоматическом режиме: Вы составляете задания и MSPRO тырит товары пока вы отдыхаете.";
$lang['manual_page_2']= 'Как заставить MSPRO работать автоматически Вы найдёте в разделе "Конфигурация".';
$lang['manual_page_3']= "Но здесь вы можете запустить MSPRO вручную.";
$lang['manual_page_4']= "Это может быть полезно для целей демонстрации либо отладки вашего MSPRO.";
$lang['manual_page_5']= 'Каждый раз нажимая кнопку "ЗАПУСК" Вы будете инициировать 1 запуск MSPRO.';
$lang['manual_page_6']= 'После того как MSPRO отработает Вы будете перенаправлены на страницу "Лог" где сможете ознакомиться с результатом его работы.';

$lang['manual_button']= "ЗАПУСК";
$lang['manual_waiting_message']= "Тырить не так просто. Это может занять какое то время.";

$lang['manual_page_mspro_switched_off']= 'Ваш MSPRO выключен. Включите его вв разделе "Настройки".';
$lang['manual_page_mspro_no_tasks']= "Нет активных задач для вашего MSPRO.";




/*****************  HOW TO USE **************/
$lang['howtouse_title']= 'Как пользоваться MultiScraper';
$lang['howtouse_inteface_title']= "Интерфейс";


$lang['howtouse_overview_title'] = 'Обзор';
$lang['howtouse_overview_1'] = 'MultiScraperPRO (MSPRO) is the stand-alone application that will grab products from other online marketplaces (amazon, aliexpress, etsy, focalprice,  or any others)
and insert them directly into your ' . MSPRO_CMS_DISPLAY_NAME . ' store.';
$lang['howtouse_overview_2'] = 'MultiScraper able to grab separate products lists as well as the whole categories or search results listings, even if the result lists are paginated.';
$lang['howtouse_overview_3'] = 'MultiScraper able to make different modifications with the grabed data on-the-fly (for example, add relative or fixed margin to product price).';
$lang['howtouse_overview_4'] = 'MultiScraper поддерживает SEO URL.';
$lang['howtouse_overview_markets_title'] = 'Ваш MSPRO в настоящее время может тырить следующие рынки:';
$lang['howtouse_overview_markets_core'] = 'Основные:';
$lang['howtouse_overview_markets_additional'] = 'Дополнительные:';


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



$lang['howtouse_configuration_title'] = 'Конфигурация';
$lang['howtouse_configuration_1'] = 'MultiScraper создан чтобы работать в автоматическом режиме: Вы создаёте инструкции для парсинга и MultiScraper тырит товар за товаром пока Вы расслабляетесь.';
$lang['howtouse_configuration_2'] = 'Он также может запускаться вручную (смотрите раздел "Ручной запуск") но это скорее для целей демонстрации или отладки.';
$lang['howtouse_configuration_3'] = 'Чтобы MultiScraper тырил товары автоматически, Вам нужно добавить в КРОН периодический запуск 1 скрипта. КРОН это серверная программа для запуска задач по расписанию (раз в день, раз в час, на 8 марта итд). Имеется на любом хостинге (или убегайте с такого хостинга).';
$lang['howtouse_configuration_4'] = 'Только 1 скрипт расположенный по адресу <b>"http://yourstore.com/multiscraper/process/"</b> должен быть добавлен к список задач КРОН.';
$lang['howtouse_configuration_5'] = 'Если Вы никогда не добавляли задачи для КРОН, попросите поддержку хостинга сделать это за Вас.';
$lang['howtouse_configuration_6'] = 'Это очень простое задание и как правило оно решается очень быстро.';
$lang['howtouse_configuration_7'] = 'Рекомендуемый интервал запуска <b>КАЖДЫЕ 5 МИНУТ</b>.';
$lang['howtouse_configuration_8'] = 'Кроме того, в разделе "Настройки" вы найдёте параметр "Сколько товаров тырим за раз". Это колличество товаров которые MultiScraper будет пытаться стырить за 1 запуск.';
$lang['howtouse_configuration_9'] = 'Используя эти цифры Вы можете управлять мощностью вашего MultiScraper:';
$lang['howtouse_configuration_10'] = 'Например, в КРОНЕ Вы настроили запуск MultiScraper каждые 5 минут и установили "Сколько товаров тырим за раз" - 2 товара';
$lang['howtouse_configuration_11'] = 'Итак, мощность вашего MultiScraper: <b>( 60/5 минут  * 2 товара) * 24часа  =  580 стыренных товаров</b> в день.';
$lang['howtouse_configuration_12'] = 'Играясь этими 2 параметрами можно регулировать интенсивность парсинга и нагрузку на Ваш сервер.';

$lang['howtouse_configuration_1'] = 'MultiScraper создан чтобы работать в автоматическом режиме: Вы создаёте инструкции для парсинга и MultiScraper тырит товар за товаром пока Вы расслабляетесь.';
$lang['howtouse_configuration_2'] = 'MultiScraper также может запускаться вручную (смотрите раздел "Ручной запуск") но это скорее для целей демонстрации или отладки.';
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


$lang[''] = '';


$lang['howtouse_safety_title'] = 'Безопасность';
$lang['howtouse_safety_1_title'] = 'Смена пароля вашего MSPRO';
$lang['howtouse_safety_11'] = 'Начальный пароль: <b>"password"</b> и рекомендовано его сменить в разделе "Настройки".';
$lang['howtouse_safety_2_title'] = 'Переименование папки MultiScraper';
$lang['howtouse_safety_21'] = 'Изначально Ваш MSPRO расположен по адресу "http://yourstore.com/multiscraper/". Рекомендовано сменить расположение MultiScraper на Ваше собственное.';
$lang['howtouse_safety_22'] = '';
$lang['howtouse_safety_rename_1']= 'выберите подходящее название (например: {секретное слово}_scraper )';
$lang['howtouse_safety_rename_2']= 'откройте файл multiscraper/.htaccess ';
$lang['howtouse_safety_rename_21'] = 'найдите: <b>multiscraper</b>';
$lang['howtouse_safety_rename_22'] = 'замените на: {ВЫБРАННОЕ ВАМИ НАЗВАНИЕ}';
$lang['howtouse_safety_rename_3']= 'Переименуйте папку "multiscraper" в "{ВЫБРАННОЕ ВАМИ НАЗВАНИЕ}" на сервере';
$lang['howtouse_safety_rename_4']= 'Теперь попробуйте следующий URL в вашем браузере: "http://{yourshop.com}/{ВЫБРАННОЕ ВАМИ НАЗВАНИЕ}/".';
$lang['howtouse_safety_rename_5'] = 'Не забудьте также поменять адреса скрипта запуска для КРОНа (см. раздел "Конфигурация").';






/*****************  PROCESS (+ process logs) **************/
$lang['process_log_started'] = "<font color='green'> Запущен </font>";
$lang['process_log_manual_start'] = "<font color='green'> (Вручную) </font>";
$lang['process_log_target_category'] = "<font color='green'> Цель: Парсинг листинга </font>";
$lang['process_log_target_product'] = "<font color='green'> Цель: Парсинг товара </font>";

$lang['process_log_product_inserted'] = "Товаров добавлено:";
$lang['process_log_product_updated'] = "Товаров обновлено:";


$lang['process_log_parse_cat_products_found_title'] = "Найдено товаров";
$lang['process_log_parse_cat_next_page_title'] = "Следующая страница";
$lang['process_log_parse_cat_next_page_exists_no'] = "НЕТ";
$lang['process_log_parse_cat_next_page_exists_yes'] = "ДА";

// months
$lang['process_log_month_01'] = "Января";
$lang['process_log_month_02'] = "Февраля";
$lang['process_log_month_03'] = "Марта";
$lang['process_log_month_04'] = "Апреля";
$lang['process_log_month_05'] = "Мая";
$lang['process_log_month_06'] = "Июня";
$lang['process_log_month_07'] = "Июля";
$lang['process_log_month_08'] = "Августа";
$lang['process_log_month_09'] = "Сентября";
$lang['process_log_month_10'] = "Октября";
$lang['process_log_month_11'] = "Ноября";
$lang['process_log_month_12'] = "Декабря";


/*
$lang['']= "";
*/

/***************** Trial and Demo messages **************/
$lang['messages_demo'] = "Эта операция недопустима, MultiScraper в ДЕМО режиме.";
$lang['messages_trial'] = "Вы используете пробную версию и уже стырили несколько товаров. Купите полную и продолжайте пользоваться...";

