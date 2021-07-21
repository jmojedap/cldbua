<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_2['courses_info'] = '';
    $cl_nav_2['courses_edit'] = '';
    $cl_nav_2['courses_details'] = '';
    //$cl_nav_2['courses_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'courses_cropping' ) { $cl_nav_2['courses_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    var element_id = '<?= $row->id ?>';

    sections.info = {
        icon: '',
        text: 'Información',
        class: '<?= $cl_nav_2['courses_info'] ?>',
        cf: 'courses/info/' + element_id
    };

    sections.edit = {
        icon: '',
        text: 'Editar',
        class: '<?= $cl_nav_2['courses_edit'] ?>',
        cf: 'courses/edit/' + element_id,
        anchor: true
    };

    sections.details = {
        icon: '',
        text: 'Detalles',
        class: '<?= $cl_nav_2['courses_details'] ?>',
        cf: 'courses/details/' + element_id
    };

    sections.images = {
        icon: '',
        text: 'Imágenes',
        class: '<?= $cl_nav_2['courses_images'] ?>',
        cf: 'posts/images/' + element_id
    };

    sections.edit_classes = {
        icon: '',
        text: 'Clases',
        class: '<?= $cl_nav_2['courses_edit_classes'] ?>',
        cf: 'courses/edit_classes/' + element_id
    };
    
    //Secciones para cada rol
    sections_role[1] = ['info', 'details', 'images', 'edit', 'edit_classes'];
    sections_role[2] = ['info', 'image', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_rid]) 
    {
        var key = sections_role[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');