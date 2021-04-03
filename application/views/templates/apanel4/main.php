<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
        
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('templates/apanel4/main/head'); ?>
    </head>
    <body>
        <div class="layout">
            <div class="main-nav">
                <?php $this->load->view("templates/apanel4/main/aside"); ?>
            </div>
            <div class="main-content">
                <div class="container-fluid">
                    <div class="d-flex">
                        <h1>
                            <span id="head_title"><?= $head_title ?></span>
                            <?php if ( ! is_null($head_subtitle) ) { ?>
                                <small id="head_subtitle"><?= $head_subtitle ?></small>
                            <?php } ?>
                        </h1>
                        <?php $this->load->view('templates/apanel4/main/head_tools') ?>
                    </div>

                    <div id="nav_2"><?php if ( ! is_null($nav_2) ) $this->load->view($nav_2); ?></div>
                    <div id="nav_3"><?php if ( ! is_null($nav_3) ) $this->load->view($nav_3); ?></div>

                    <div id="view_a"><?php $this->load->view($view_a) ?></div>
                </div>
                <footer class="footer">
                    &copy; Pacarina Media Lab &middot; Colombia
                </footer>
            </div>
        </div>
    </body>
</html>