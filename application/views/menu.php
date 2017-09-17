<ul class="ca-menu">
                    <li <?php echo $active == "howtouse"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>howtouse">
                            <span class="ca-icon" id="home">R</span>
                            <div class="ca-content">
                                <h2 class="ca-main"><?php echo $this->lang->line('menu_howtouse_title'); ?></h2>
                                <h3 class="ca-sub"><?php echo $this->lang->line('menu_howtouse_desc'); ?></h3>
                            </div>
                        </a>
                    </li>
                    <li <?php echo $active == "settings"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>">
                            <span class="ca-icon" id="download">S</span>
                            <div class="ca-content">
                                <h2 class="ca-main"><?php echo $this->lang->line('menu_settings_title'); ?></h2>
                                <h3 class="ca-sub"><?php echo $this->lang->line('menu_settings_desc'); ?></h3>
                            </div>
                        </a>
                    </li>
                    <li <?php echo $active == "tasks"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>tasks">
                            <span class="ca-icon" id="buy">p</span>
                            <div class="ca-content">
                                <h2 class="ca-main"><?php echo $this->lang->line('menu_tasks_title'); ?></h2>
                                <h3 class="ca-sub"><?php echo $this->lang->line('menu_tasks_desc'); ?></h3>
                            </div>
                        </a>
                    </li>
                    <li <?php echo $active == "log"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>log">
                            <span class="ca-icon" id="contact">c</span>
                            <div class="ca-content">
                                <h2 class="ca-main"><?php echo $this->lang->line('menu_log_title'); ?></h2>
                                <h3 class="ca-sub"><?php echo $this->lang->line('menu_log_desc'); ?></h3>
                            </div>
                        </a>
                    </li>
                    <li <?php echo $active == "contact"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>manual">
                            <span class="ca-icon" id="guest">`</span>
                            <div class="ca-content">
                                <h2 class="ca-main"><?php echo $this->lang->line('menu_contact_title'); ?></h2>
                                <h3 class="ca-sub"><?php echo $this->lang->line('menu_contact_desc'); ?></h3>
                            </div>
                        </a>
                    </li>
</ul>