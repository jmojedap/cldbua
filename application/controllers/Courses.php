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
        $data['view_a'] = $data['type_folder'] . 'read_v';

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

// CREACIÓN DE UN POST
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
            $data['view_a'] = $data['type_folder'] . 'edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

// Asignación a usuario
//-----------------------------------------------------------------------------

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

// Presentación del curso
//-----------------------------------------------------------------------------

    function open_element($course_id, $element_index = 0)
    {
        $destination = "courses/info/{$course_id}";

        $course = $this->Course_model->row($course_id);
        $course_data = json_decode($course->content_json, true);
        $index = $course_data['index'];
        $element = $index[$element_index];
        $row_element = $this->Db_model->row_id($element['type'], $element['row_id']);
        
        if ( $element['type'] == 'posts' ) {
            $destination = "courses/class/{$course->id}/{$course->slug}/{$row_element->id}/{$element_index}";
        } elseif ($element['type'] == 'exams' ){
            $destination = "exams/preparation/{$row_element->id}/{$element_index}/{$course->id}/{$course->slug}";
        }

        redirect($destination);
        
        //Verificación
        /*$data['index'] = $index;
        $data['element_index'] = $element_index;
        $data['element'] = $element;
        $data['row_element'] = $row_element;
        $data['destination'] = $destination;*/

        //Salida JSON
        //$this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function class($course_id, $slug = '', $class_id = 0, $element_index = 0)
    {
        $course = $this->Course_model->row($course_id);
        $clase = $this->Db_model->row_id('posts', $class_id);

        $user_id = $this->session->userdata('user_id');
        $row_meta = $this->Db_model->row_id('users_meta', "type_id = 411010 AND user_id = {$user_id}"); //Registro de inscripción del usuario

        $data['course'] = $course;
        $data['clase'] = $clase;
        $data['row_meta'] = $row_meta;
        $data['head_title'] = $course->post_name;
        $data['view_a'] = 'courses/classes/class/class_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
}