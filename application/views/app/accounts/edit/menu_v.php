<?php
    $cl_nav_3['basic'] = '';
    $cl_nav_3['avatar'] = '';
    $cl_nav_3['password'] = '';

    $app_cf_index = $this->uri->segment(4);
    if ( strlen($app_cf_index) == 0 ) { $app_cf_index = 'basic'; }
    
    $cl_nav_3[$app_cf_index] = 'active';
    if ( $app_cf_index == 'crop' ) { $cl_nav_3['avatar'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_role = [];
    var element_id = '<?= $this->uri->segment(4) ?>';
    
    sections.basic = {
        icon: '',
        text: 'General',
        class: '<?= $cl_nav_3['basic'] ?>',
        cf: 'accounts/edit/basic'
    };
    
    sections.avatar = {
        icon: '',
        text: 'Imagen',
        class: '<?= $cl_nav_3['avatar'] ?>',
        cf: 'accounts/edit/avatar'
    };
    
    sections.password = {
        icon: '',
        text: 'Contraseña',
        class: '<?= $cl_nav_3['password'] ?>',
        cf: 'accounts/edit/password'
    };
    
    //Secciones para cada rol
    sections_role[1] = ['basic', 'avatar', 'password'];
    sections_role[2] = ['basic', 'avatar', 'password'];
    sections_role[3] = ['basic', 'avatar', 'password'];
    sections_role[21] = ['basic', 'avatar', 'password'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_rid]) 
    {
        var key = sections_role[app_rid][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
    //Si el perfil visitado es el mismo al de el usuario en sesión
    if ( element_id === '<?= $this->session->userdata('user_id'); ?>'  ) { nav_3.push(sections.password); }
</script>

<?php
$this->load->view('common/nav_3_v');