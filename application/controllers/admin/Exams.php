<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exams extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/exams/';
    public $url_controller = URL_ADMIN . 'exams/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Exam_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($exam_id = NULL)
    {
        if ( is_null($exam_id) ) {
            redirect("exams/explore/");
        } else {
            redirect("exams/info/{$exam_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** Exploración de Exams */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Exam_model->explore_data($filters, $num_page, 10);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Exams, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Exam_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de exams seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->exam('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Exam_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    /**
     * Abrir o redireccionar a la vista pública de un exam
     */
    function open($exam_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('exams', $exam_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "exams/read/{$exam_id}";

        if ( $row->type_id == 2 ) $destination = "noticias/leer/{$row->id}/{$row->slug}";
        if ( $row->type_id == 5 ) $destination = "girls/album/{$row->related_1}/{$row->id}/{$meta_id}";
        if ( $row->type_id == 8 ) $destination = "books/read/{$row->code}/{$row_meta->id}/{$row->slug}";

        redirect($destination);
    }

    /**
     * Mostrar exam en vista lectura
     */
    function read($exam_id)
    {
        //Datos básicos
        $data = $this->Exam_model->basic($exam_id);
        unset($data['nav_2']);
        $data['view_a'] = $this->Exam_model->type_folder($data['row']) . 'read_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información general del exam
     */
    function info($exam_id)
    {        
        //Datos básicos
        $data = $this->Exam_model->basic($exam_id);
        $data['view_a'] = $this->views_folder . 'exams/info_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_info($exam_id)
    {
        $data = $this->Exam_model->basic($exam_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Información detallada del exam desde la perspectiva de base de datos
     * 2020-08-18
     */
    function details($exam_id)
    {        
        //Datos básicos
        $data = $this->Exam_model->basic($exam_id);
        $data['view_a'] = $this->views_folder . 'details_v';
        $data['fields'] = $this->db->list_fields('exams');

        $this->App_model->view(TPL_ADMIN, $data);
    }

// CREACIÓN Y EDICIÓN DE UN EXAM
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo exam
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Cuestionarios';
            $data['nav_2'] = $this->views_folder . 'exams/explore/menu_v';
            $data['view_a'] = $this->views_folder . 'exams/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($exam_id)
    {
        //Datos básicos
        $data = $this->Exam_model->basic($exam_id);
        
        //Array data espefícicas
            $data['nav_2'] = $this->views_folder . 'exams/menu_v';
            $data['view_a'] = $this->views_folder . 'exams/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * POST JSON
     * Toma datos de POST e inserta un registro en la tabla exams. Devuelve
     * result del proceso en JSON
     */ 
    function save()
    {
        $data = $this->Exam_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Gestión de preguntas
//-----------------------------------------------------------------------------

    /**
     * Vista gestión de preguntas de un examen
     * 2021-03-18
     */
    function questions($exam_id)
    {
        $data = $this->Exam_model->basic($exam_id);
        $data['view_a'] = $this->views_folder . 'exams/questions/questions_v';
        $data['nav_2'] = $this->views_folder . 'exams/menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_questions($exam_id)
    {
        $questions = $this->Exam_model->questions($exam_id);
        $data['list'] = $questions->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Ejecución del examen
//-----------------------------------------------------------------------------

    function preview($exam_id, $num_question = 1)
    {
        $data = $this->Exam_model->basic($exam_id);
        $data['questions'] = $this->Exam_model->questions($exam_id);
        $data['num_question'] = $num_question;

        $data['view_a'] = $this->views_folder . 'exams/preview/preview_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Dirige a vista de clase-examen de un curso, identifica el índice correspondiente
     * 2021-04-19
     */
    function preparation($exam_id, $course_id)
    {
        $index = 0;

        $this->load->model('Course_model');
        $classes = $this->Course_model->classes($course_id);

        //Identificar index de clase-examen en curso
        foreach( $classes->result() as $row_class )
        {
            if ( $row_class->type_id == 4140 && $row_class->related_2 == $exam_id ) { break; }
            $index += 1;    //No coincide con característcas, siguiente clae
        }

        redirect("app/courses/abrir_elemento/{$course_id}/{$index}");
    }

    function get_preparation_info($exam_id)
    {
        $data['row'] = $this->Db_model->row_id('exams', $exam_id);
        $data['row_eu'] = $this->Db_model->row('exam_user', "exam_id = {$exam_id} AND user_id = {$this->session->userdata('user_id')}");

        //Cantidad de intentos
        $data['qty_attempts'] = 1;
        if ( ! is_null($data['row_eu']) ) $data['qty_attempts'] = $data['row_eu']->qty_attempts + 1;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Inicializar respuesta de un examen
     */
    function start()
    {
        $data = $this->Exam_model->start();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Vista de resolución del cuestionario
     * 2021-03-22
     */
    function resolve($exam_id, $eu_id, $enrolling_id = 0, $num_question = 1)
    {
        $data = $this->Exam_model->basic($exam_id);
        $data['row_eu'] = $this->Db_model->row_id('exam_user', $eu_id);
        $data['eu_id'] = $eu_id;    //ID tabla exam_user
        $data['questions'] = $this->Exam_model->questions($exam_id);
        $data['enrolling_id'] = $enrolling_id;
        $data['num_question'] = $num_question;
        $data['row_enrolling'] = $this->Db_model->row_id('users_meta', $enrolling_id);

        //Segundos disponibles
        $mkt1 = strtotime($data['row_eu']->start);
        $mkt2 = $mkt1 + ( $data['row']->minutes * 60 );
        $data['seconds'] = $mkt2 - time();
        
        //Identificar curso
        $data['course'] = NULL;
        if ( ! is_null($data['row_enrolling']) ) {
            $data['course'] = $this->Db_model->row_id('posts', $data['row_enrolling']->related_1);
            $data['head_title'] = $data['course']->post_name;
        }

        $data['view_a'] = $this->views_folder . 'exams/resolve/resolve_v';
        unset($data['nav_2']);
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Guardar respuestas de un cuestionario en la tabla exame_user (eu)
     * 2021-03-22
     */
    function save_answers($status = 2)
    {
        $data = $this->Exam_model->save_answers();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Guardar respuestas y marcar un exam como finalizado (tabla exam_user)
     * 2021-03-23
     */
    function finalize()
    {
        //Primero guardar respuestas
        $data_save_answers = $this->Exam_model->save_answers();

        //Finalizar si se guardaron las respuestas
        if ( $data_save_answers['saved_id'] > 0 )
        {
            $data = $this->Exam_model->finalize();
            $data['save_answers'] = $data_save_answers;
        } else {
            $data = $data_save_answers;
            $data['status'] = 0;
        }

        //Calcular aprobación del curso
        if ( $this->input->post('enrolling_id') > 0 )
        {
            $this->load->model('Course_model');
            $enrolling_id = $this->input->post('enrolling_id');
            $data['course_approval_status'] = $this->Course_model->update_approval_status($enrolling_id);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Vista detalle resultados respuesta, pregunta a pregunta
     * 2021-03-22
     */
    function results($exam_id, $eu_id, $enrolling_id)
    {
        $data = $this->Exam_model->basic($exam_id);
        $data['eu_id'] = $eu_id;    //ID tabla exam_user
        $data['row_answer'] = $this->Db_model->row_id('exam_user', $eu_id);
        $data['questions'] = $this->Exam_model->questions($exam_id);

        //Información sobre el curso e incripción
        $data['row_enrolling'] = $this->Db_model->row_id('users_meta', $enrolling_id);
        $data['course'] = null;
        if ( ! is_null($data['row_enrolling']) ) {
            $data['course'] = $this->Db_model->row_id('posts', $data['row_enrolling']->related_1);
            $data['head_title'] = $data['course']->post_name;
        }

        $data['view_a'] = $this->views_folder . 'exams/results/results_v';
        unset($data['nav_2']);
        $this->App_model->view(TPL_ADMIN, $data);
    }

}