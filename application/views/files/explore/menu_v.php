<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['files_explore'] = '';
    $cl_nav_2['files_process'] = '';
    $cl_nav_2['files_add'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'files_import_e' ) { $cl_nav_2['files_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explore = {
        icon: 'fa fa-search',
        text: 'Explorar',
        class: '<?= $cl_nav_2['files_explore'] ?>',
        cf: 'files/explore'
    };

    sections.process = {
        icon: '',
        text: 'Procesos',
        class: '<?= $cl_nav_2['files_process'] ?>',
        cf: 'files/process'
    };

    sections.add = {
        icon: 'fa fa-upload',
        text: 'Cargar',
        class: '<?= $cl_nav_2['files_add'] ?>',
        cf: 'files/add'
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['explore', 'process', 'add'];
    sections_rol[1] = ['explore', 'add'];
    sections_rol[2] = ['explore', 'add'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_rid]) 
    {
        //console.log(sections_rol[rol][key_section]);
        var key = sections_rol[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');