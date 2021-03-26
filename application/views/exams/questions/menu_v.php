<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['questions_info'] = '';
    $cl_nav_2['questions_edit'] = '';
    //$cl_nav_2['questions_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'questions/explore' ) { $cl_nav_2['questions_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';
    
    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explorar',
        class: '',
        cf: 'questions/explore/',
        anchor: true

    };

    sections.info = {
        icon: '',
        text: 'Información',
        class: '<?= $cl_nav_2['questions_info'] ?>',
        cf: 'questions/info/' + element_id
    };

    sections.edit = {
        icon: '',
        text: 'Editar',
        class: '<?= $cl_nav_2['questions_edit'] ?>',
        cf: 'questions/edit/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['explore', 'info', 'edit'];
    sections_rol[1] = ['explore', 'info', 'edit'];
    
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