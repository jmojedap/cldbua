<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Questions extends CI_Controller{
    
// Variables generales
//-----------------------------------------------------------------------------
public $views_folder = 'admin/exams/questions/';
public $url_controller = URL_ADMIN . 'questions/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Question_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($question_id = NULL)
    {
        if ( is_null($question_id) ) {
            redirect("admin/questions/explore/");
        } else {
            redirect("admin/questions/info/{$question_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** Exploración de Questions */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Question_model->explore_data($filters, $num_page, 10);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Questions, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Question_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de questions seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Question_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------
    /**
     * Información general de la pregunta
     */
    function info($question_id)
    {        
        //Datos básicos
        $data = $this->Question_model->basic($question_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada de la pregunta desde la perspectiva de base de datos
     * 2020-08-18
     */
    function details($question_id)
    {        
        //Datos básicos
        $data = $this->Question_model->basic($question_id);
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

// CREACIÓN Y EDICIÓN DE UN EXAM
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo question
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Preguntas';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';
            $data['view_a'] = $this->views_folder . 'add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($question_id)
    {
        //Datos básicos
        $data = $this->Question_model->basic($question_id);
        
        //Array data espefícicas
            $data['nav_2'] = $this->views_folder . 'menu_v';
            $data['back_link'] = $this->url_controller . 'explore';
            $data['view_a'] = $this->views_folder . 'edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * POST JSON
     * Toma datos de POST e inserta un registro en la tabla questions. Devuelve
     * result del proceso en JSON
     */ 
    function save()
    {
        $data = $this->Question_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// EXAM IMAGES
//-----------------------------------------------------------------------------

    /**
     * Vista, gestión de imágenes de un question
     * 2020-07-14
     */
    function images($question_id)
    {
        $data = $this->Question_model->basic($question_id);

        $data['images'] = $this->Question_model->images($question_id);
        
        //Para formulario file
        $data['form_table_id'] = 2000;
        $data['form_related_1'] = $question_id;

        $data['view_a'] = $this->views_folder .  'images/images_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Imágenes de un question
     * 2020-07-07
     */
    function get_images($question_id)
    {
        $images = $this->Question_model->images($question_id);
        $data['images'] = $images->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Establecer imagen principal de un question
     * 2020-07-07
     */
    function set_main_image($question_id, $file_id)
    {
        $data = $this->Question_model->set_main_image($question_id, $file_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMPORTACIÓN DE EXAMS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de questions
     * con archivo Excel. El resultado del formulario se envía a 
     * 'questions/import_e'
     */
    function import()
    {
        $data = $this->Question_model->import_config();

        $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];

        $data['head_title'] = 'Preguntas';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['view_a'] = 'common/import_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    //Ejecuta la importación de questions con archivo Excel
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Question_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "questions/explore/";
        
        //Cargar vista
            $data['head_title'] = 'Preguntas';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = $data['nav_2'] = $this->views_folder . 'explore/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
}