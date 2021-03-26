<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['exams_info'] = '';
    $cl_nav_2['exams_previw'] = '';
    $cl_nav_2['exams_questions'] = '';
    $cl_nav_2['exams_edit'] = '';
    //$cl_nav_2['exams_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'exams/explore' ) { $cl_nav_2['exams_explore'] = 'active'; }
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
        cf: 'exams/explore/',
        anchor: true

    };

    sections.info = {
        icon: '',
        text: 'Información',
        class: '<?= $cl_nav_2['exams_info'] ?>',
        cf: 'exams/info/' + element_id
    };

    sections.preview = {
        icon: '',
        text: 'Vista previa',
        class: '<?= $cl_nav_2['exams_preview'] ?>',
        cf: 'exams/preview/' + element_id
    };

    sections.questions = {
        icon: '',
        text: 'Preguntas',
        class: '<?= $cl_nav_2['exams_questions'] ?>',
        cf: 'exams/questions/' + element_id
    };

    sections.edit = {
        icon: '',
        text: 'Editar',
        class: '<?= $cl_nav_2['exams_edit'] ?>',
        cf: 'exams/edit/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['explore', 'info', 'preview', 'questions', 'edit'];
    sections_rol[1] = ['explore', 'info', 'preview', 'questions', 'edit'];
    
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