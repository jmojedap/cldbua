<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('templates/admin_pml/main/head') ?>

        <?php //$this->load->view('templates/admin_pml/main/routes_script') ?>
    </head>
    <body class="skin-blue">
        <div class="wrapper">
            <?php $this->load->view('templates/admin_pml/main/header'); ?>
            <?php $this->load->view('templates/admin_pml/main/sidebar'); ?>

            <div class="content-wrapper">
                <section class="content">
                    <div id="nav_2">
                        <?php if ( ! is_null($nav_2) ) { ?>
                            <?php $this->load->view($nav_2); ?>
                        <?php } ?>
                    </div>

                    <div id="nav_3">
                        <?php if ( ! is_null($nav_3) ) { ?>
                            <?php $this->load->view($nav_3); ?>
                        <?php } ?>
                    </div>
                    <div id="view_a">
                        <?php $this->load->view($view_a); ?>
                    </div>
                </section>
            </div>
            
            <footer class="main-footer text-center">
                <small>
                    &copy; 2021 &middot; Creado con <i class="fa fa-heart text-danger"></i> por <a href="https://www.pacarina.com">Pacarina Media Lab</a> &middot; Colombia
                </small>
            </footer>
        </div>
    </body>
</html>