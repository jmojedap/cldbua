<title><?= $head_title ?></title>
        <link rel="shortcut icon" href="<?= URL_BRAND ?>favicon.png" type="image/png"/>
        <meta charset="UTF-8">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <!-- Font Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

        <!--JQuery-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

        <!-- Bootstrap 4.3.1 -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <!-- Vue.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.5/vue.min.js" integrity="sha256-GOrA4t6mqWceQXkNDAuxlkJf2U1MF0O/8p1d/VPiqHw=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js" integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ==" crossorigin="anonymous"></script>

        <!-- Toastr: Alertas y Notificaciones -->
        <link href="<?= URL_RESOURCES ?>templates/admin_pml/css/skins/skin-blue-toastr.css" rel="stylesheet" type="text/css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="<?= URL_RESOURCES ?>config/apanel4/toastr-options.js"></script>

        <!-- Moment.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js" integrity="sha256-H9jAz//QLkDOy/nzE9G4aYijQtkLt9FvGmdUTwBk6gs=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js" integrity="sha256-bETP3ndSBCorObibq37vsT+l/vwScuAc9LRJIQyb068=" crossorigin="anonymous"></script>

        <!-- Google Analytics -->
        <?php //$this->load->view('head_includes/google_analytics'); ?>

        <!-- Recursos PML -->
        <link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES ?>css/pacarina.css">
        <script src="<?= URL_RESOURCES . 'js/pcrn.js' ?>"></script>
        
        <!-- Template apanel4 -->
        <link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES ?>templates/apanel4/style.css">
        <link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES ?>templates/apanel4/skin-purple-01.css">
        <script type="text/javascript" src="<?= URL_RESOURCES ?>templates/apanel4/actions.js"></script>

        <!-- App General Vars -->
        <script>
            const url_app = '<?= URL_APP ?>';
            const url_api = '<?= URL_API ?>';
            var app_cf = '<?= $this->uri->segment(1) . '/' . $this->uri->segment(2); ?>';
        </script>

        <!-- Usuario con sesión iniciada -->
        <?php if ( $this->session->userdata('logged') ) : ?>
            <!-- Elementos del menú -->
            <script src="<?= URL_RESOURCES ?>config/apanel4/menus/nav_1_elements_<?= $this->session->userdata('role') ?>.js"></script>

            <script>
                const app_rid = <?= $this->session->userdata('role') ?>;
                const app_uid = <?= $this->session->userdata('user_id') ?>;
            </script>
        <?php endif; ?>

        <!-- Gestión de rutas -->
        <script src="<?= URL_RESOURCES ?>js/pml_routing.js"></script>