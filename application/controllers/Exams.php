<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exams extends CI_Controller{
    
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
        $data['view_a'] = 'exams/exams/info_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada del exam desde la perspectiva de base de datos
     * 2020-08-18
     */
    function details($exam_id)
    {        
        //Datos básicos
        $data = $this->Exam_model->basic($exam_id);
        $data['view_a'] = 'exams/details_v';
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
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'exams/exams/explore/menu_v';
            $data['view_a'] = 'exams/exams/add_v';

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
            $data['nav_2'] = 'exams/exams/menu_v';
            $data['head_subtitle'] = 'Editar';
            $data['view_a'] = 'exams/exams/edit_v';
        
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
        $data['view_a'] = 'exams/exams/questions/questions_v';
        $data['nav_2'] = 'exams/exams/menu_v';
        $data['head_subtitle'] = 'Preguntas';
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

        $data['head_subtitle'] = 'Vista previa';
        $data['view_a'] = 'exams/exams/preview/preview_v';
        //$data['view_a'] = 'exams/exams/test_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Vista preliminar informativa antes de iniciar a responder un examen
     * 2021-03-23
     */
    function preparation($exam_id)
    {
        $data = $this->Exam_model->basic($exam_id);
        
        //Respuesta previa, si es que existe
        $data['row_eu'] = $this->Db_model->row('exam_user', "exam_id = {$exam_id} AND user_id = {$this->session->userdata('user_id')}");
        
        //Cantidad de intentos
        $data['qty_attempts'] = 1;
        if ( ! is_null($data['row_eu']) ) $data['qty_attempts'] = $data['row_eu']->qty_attempts + 1;

        $data['head_subtitle'] = 'Empezando';
        $data['view_a'] = 'exams/exams/preparation_v';
        unset($data['nav_2']);
        $this->App_model->view(TPL_ADMIN, $data);
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
    function resolve($exam_id, $eu_id, $num_question = 1)
    {
        $data = $this->Exam_model->basic($exam_id);
        $data['row_eu'] = $this->Db_model->row_id('exam_user', $eu_id);
        $data['eu_id'] = $eu_id;    //ID tabla exam_user
        $data['questions'] = $this->Exam_model->questions($exam_id);
        $data['num_question'] = $num_question;

        $data['view_a'] = 'exams/exams/resolve/resolve_v';
        unset($data['nav_2']);
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
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
        //Guardar respuestas
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

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Detalle resultados respuesta, pregunta a pregunta
     * 2021-03-22
     */
    function results($exam_id, $eu_id)
    {
        $data = $this->Exam_model->basic($exam_id);
        $data['eu_id'] = $eu_id;    //ID tabla exam_user
        $data['row_eu'] = $this->Db_model->row_id('exam_user', $eu_id);
        $data['questions'] = $this->Exam_model->questions($exam_id);

        $data['view_a'] = 'exams/exams/results/results_v';
        unset($data['nav_2']);
        $this->App_model->view(TPL_ADMIN, $data);
    }

}