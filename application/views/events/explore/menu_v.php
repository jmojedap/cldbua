<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['events_explore'] = '';
    $cl_nav_2['events_import'] = '';
    $cl_nav_2['events_summary'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'events_import_e' ) { $cl_nav_2['events_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explore = {
        icon: 'fa fa-search',
        text: 'Explorar',
        class: '<?= $cl_nav_2['events_explore'] ?>',
        cf: 'events/explore'
    };

    

    sections.summary = {
        icon: '',
        text: 'Resumen',
        class: '<?= $cl_nav_2['events_summary'] ?>',
        cf: 'events/summary'
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['explore', 'summary'];
    sections_rol[1] = ['explore', 'summary'];
    sections_rol[2] = ['explore', 'summary'];
    
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