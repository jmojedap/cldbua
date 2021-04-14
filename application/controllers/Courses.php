<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Course_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($post_id = NULL)
    {
        if ( is_null($post_id) ) {
            redirect("courses/explore/");
        } else {
            redirect("courses/info/{$post_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** Exploración de Courses */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Course_model->explore_data($filters, $num_page, 10);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Courses, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Course_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de courses seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Course_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function browse($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Course_model->browse_data($filters, $num_page, 10);

        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }
    
// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    /**
     * Abrir o redireccionar a lectura del curso de un curso
     */
    function open($post_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('courses', $post_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "courses/classes/{$post_id}";

        redirect($destination);
    }

    /**
     * Mostrar post en vista lectura
     */
    function read($post_id)
    {
        //Datos básicos
        $data = $this->Course_model->basic($post_id);
        unset($data['nav_2']);
        $data['view_a'] = 'courses/courses/' . 'read_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información general del post
     */
    function info($post_id)
    {        
        //Datos básicos
        $data['row'] = $this->Course_model->row($post_id);
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = 'courses/courses/info_v';
        if ( $this->session->userdata('role') <= 1 ) { $data['nav_2'] = 'courses/courses/menu_v'; }

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada del post desde la perspectiva de base de datos
     * 2020-08-18
     */
    function details($post_id)
    {        
        //Datos básicos
        $data = $this->Course_model->basic($post_id);
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

// CREACIÓN DE UN COURSE
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo post
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'courses';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'courses/explore/menu_v';
            $data['view_a'] = 'courses/add/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function save()
    {
        $data = $this->Course_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($post_id)
    {
        //Datos básicos
        $data = $this->Course_model->basic($post_id);

        $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
        
        //Array data espefícicas
            $data['head_subtitle'] = 'Editar';
            $data['view_a'] = 'posts/types/4110/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function edit_classes($post_id)
    {
        //Datos básicos
        $data = $this->Course_model->basic($post_id);

        //$data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
        $data['classes'] = $this->Course_model->classes($post_id);
        $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
        
        //Array data espefícicas
            $data['head_subtitle'] = 'Editar clases';
            $data['view_a'] = 'courses/courses/edit_classes_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_classes($post_id)
    {
        $classes = $this->Course_model->classes($post_id);
        $data['list'] = $classes->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Asignación a usuario
//-----------------------------------------------------------------------------

    /**
     * Cursos a los que está inscrito el usuario en sesión, y su estado de avance
     * 2021-04-05
     */
    function my_courses()
    {
        $data['head_title'] = 'Mis cursos';
        $data['view_a'] = 'courses/courses/my_courses_v';
        $data['courses'] = $this->Course_model->user_courses($this->session->userdata('user_id'));
        $data['arr_enrolling_status'] = $this->Item_model->arr_cod('category_id = 401');

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Vista certificado de aprobación de un curso, link para descarga del certificado
     * 2021-04-13
     */
    function certificate($course_id, $user_id, $enrolling_id)
    {
        //Si es estudiante, solo ve sus cursos e inscripciones
        if ( $this->session->userdata('role') >= 20 ) {
            $user_id = $this->session->userdata('user_id');
        }

        $data['course'] = $this->Db_model->row_id('posts', $course_id);
        $data['enrolling'] = $this->Db_model->row('users_meta', "id = {$enrolling_id} AND user_id = {$user_id} AND related_1 = {$course_id}");
        $data['user'] = $this->Db_model->row_id('users', $user_id);

        $data['head_title'] = $data['course']->post_name;
        $data['view_a'] = 'courses/courses/enrolling_status_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Inscribe a un usuario en un curso
     * 2021-03-30
     */
    function enroll()
    {
        $arr_row['user_id'] = $this->input->post('user_id');
        $arr_row['related_1'] = $this->input->post('course_id');
        $data = $this->Course_model->enroll($arr_row);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Retira un contenido digital a un usuario
     * 2020-04-30
     */
    function remove_to_user($post_id, $meta_id)
    {
        $data = $this->Course_model->remove_to_user($post_id, $meta_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Ejecución del curso por parte de un usuario
//-----------------------------------------------------------------------------

    /**
     * REDIRECT
     * Abre un elemento específico de un curso, si hay inscripción registra el 
     * evento de apertura para seguimiento
     *
     */
    function open_element($course_id, $index = 0)
    {
        $destination = "courses/info/{$course_id}";
        $num_class = $index + 1;

        $course = $this->Course_model->row($course_id);
        $classes = $this->Course_model->classes($course->id);

        if ( $index < 0 ) $destination = "courses/info/{$course->id}";   //Inicio del curso
        if ( $index >= $classes->num_rows() ) $destination = "courses/info/{$course->id}";    //Final del curso

        //Índice está en entre los elementos
        if ( $index >= 0 && $index < $classes->num_rows() )
        {
            $row_class = $classes->row($index);
        
            $destination = "courses/class/{$course->id}/{$course->slug}/$row_class->id/{$num_class}";

            //Verificar si hay inscripción a curso
            $row_enrolling = $this->Db_model->row('users_meta', "user_id = {$this->session->userdata('user_id')} AND type_id = 411010 AND related_1 = {$course_id}");

            if ( ! is_null($row_enrolling) )
            {
                //Actualizar registro inscripción a curso
                $arr_row = array('integer_1' => $index, 'updated_at' => date('Y-m-d H:i:s'));
                $this->db->where('id', $row_enrolling->id)->update('users_meta', $arr_row);

                //Crear evento de apertura de clase, tabla events
                $event_id = $this->Course_model->save_open_class_event($row_class, $row_enrolling);
            }
        }

        redirect($destination);
    }

    /**
     * Vista para ejecución de clase, abrir vista de clase en el desarrollo del curso
     * 2021-04-05
     */
    function class($course_id, $slug = '', $class_id = 0, $num_class = 1)
    {
        $course = $this->Course_model->row($course_id);        
        $row = $this->Db_model->row_id('posts', $class_id);

        $enrolling_id = 0;
        $row_meta = $this->Db_model->row('users_meta', "user_id = {$this->session->userdata('user_id')} AND type_id = 411010 AND related_1 = {$course_id}");
        if ( ! is_null($row_meta) ) { $enrolling_id = $row_meta->id; }

        $data['course'] = $course;
        $data['clase'] = $row;
        $data['classes'] = $this->Course_model->classes($course_id);
        $data['row'] = $row;
        $data['table_id'] = 2000;
        $data['enrolling_id'] = $enrolling_id;
        $data['num_class'] = $num_class;
        $data['index'] = $num_class - 1;
        $data['head_title'] = $course->post_name;
        $data['view_a'] = "courses/classes/read/type_{$row->type_id}_v";

        $this->App_model->view(TPL_ADMIN, $data);
    }

// Funciones de pruebas
//-----------------------------------------------------------------------------

    /**
     * Función de pruebas
     */
    function get_approval_info($enrolling_id)
    {
        $data = $this->Course_model->approval_info($enrolling_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Repuestas de examen por parte de un usuario.
     */
    function get_enrolling_answers($enrolling_id)
    {
        $answers = $this->Course_model->enrolling_exams_answers($enrolling_id);
        $data['answers'] = $answers->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
        //$this->output->enable_profiler(TRUE);
    }
}