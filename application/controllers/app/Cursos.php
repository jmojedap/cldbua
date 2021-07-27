<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cursos extends CI_Controller{
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/cursos/';
    public $url_controller = URL_APP . 'cursos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Course_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($post_id = NULL)
    {
        if ( is_null($post_id) ) {
            redirect("admin/courses/explore/");
        } else {
            redirect("app/courses/info/{$post_id}");
        }
    }
    
//NAVEGAR CURSOS
//---------------------------------------------------------------------------------------------------

    function catalogo($num_page = 1)
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
            $this->App_model->view(TPL_FRONT, $data);
    }
    
// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    /**
     * Abrir o redireccionar a lectura del curso de un curso
     */
    function abrir($post_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('courses', $post_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "app/cursos/clases/{$post_id}";

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
        $data['view_a'] = $this->views_folder . 'courses/courses/' . 'read_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Información general del curso
     */
    function info($post_id)
    {        
        //Datos básicos
        $data['row'] = $this->Course_model->row($post_id);
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = $this->views_folder . 'cursos/info_v';
        $data['back_link'] = $this->url_controller . 'explore';
        if ( $this->session->userdata('role') <= 1 ) { $data['nav_2'] = $this->views_folder . 'courses/menu_v'; }

        $this->App_model->view(TPL_FRONT, $data);
    }

// Asignación a usuario
//-----------------------------------------------------------------------------

    /**
     * Cursos a los que está inscrito el usuario en sesión, y su estado de avance
     * 2021-04-05
     */
    function mis_cursos()
    {
        $data['head_title'] = 'Mis cursos';
        $data['view_a'] = $this->views_folder . 'cursos/mis_cursos_V';
        $data['courses'] = $this->Course_model->user_courses($this->session->userdata('user_id'));
        $data['arr_enrolling_status'] = $this->Item_model->arr_cod('category_id = 401');

        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Vista certificado de aprobación de un curso, link para descarga del certificado
     * 2021-04-13
     */
    function estado_inscripcion($course_id, $user_id, $enrolling_id)
    {
        //Si es estudiante, solo ve sus cursos e inscripciones
        if ( $this->session->userdata('role') >= 20 ) {
            $user_id = $this->session->userdata('user_id');
        }

        $data['course'] = $this->Db_model->row_id('posts', $course_id);
        $data['enrolling'] = $this->Db_model->row('users_meta', "id = {$enrolling_id} AND user_id = {$user_id} AND related_1 = {$course_id}");
        $data['user'] = $this->Db_model->row_id('users', $user_id);

        $data['head_title'] = $data['course']->post_name;
        $data['view_a'] = $this->views_folder . 'cursos/estado_inscripcion_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

// Ejecución del curso por parte de un usuario
//-----------------------------------------------------------------------------

    /**
     * REDIRECT
     * Abre un elemento específico de un curso, si hay inscripción registra el 
     * evento de apertura para seguimiento
     *
     */
    function abrir_elemento($course_id, $index = 0)
    {
        $destination = "app/cursos/info/{$course_id}";
        $num_class = $index + 1;

        $course = $this->Course_model->row($course_id);
        $classes = $this->Course_model->classes($course->id);

        if ( $index < 0 ) $destination = "app/cursos/info/{$course->id}";   //Inicio del curso
        if ( $index >= $classes->num_rows() ) $destination = "app/cursos/info/{$course->id}";    //Final del curso

        //Índice está en entre los elementos
        if ( $index >= 0 && $index < $classes->num_rows() )
        {
            $row_class = $classes->row($index);
        
            $destination = "app/cursos/clase/{$course->id}/{$course->slug}/$row_class->id/{$num_class}";

            //Verificar si hay inscripción a curso
            $row_enrolling = $this->Db_model->row('users_meta', "user_id = {$this->session->userdata('user_id')} AND type_id = 411010 AND related_1 = {$course_id}");

            if ( ! is_null($row_enrolling) )
            {
                //Actualizar registro inscripción a curso
                $this->Course_model->update_enrolling($row_enrolling, $classes->num_rows(), $index);

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
    function clase($course_id, $slug = '', $class_id = 0, $num_class = 1)
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
        $data['view_a'] = $this->views_folder . "clases/leer_v";

        $this->App_model->view(TPL_FRONT, $data);
    }
}