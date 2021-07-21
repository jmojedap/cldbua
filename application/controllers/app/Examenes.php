<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Examenes extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/examenes/examenes/';
    public $url_controller = URL_ADMIN . 'examenes/';

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
            redirect("app/examenes/explore/");
        } else {
            redirect("app/examenes/info/{$exam_id}");
        }
    }

// Ejecución del examen
//-----------------------------------------------------------------------------

    function vista_previa($exam_id, $num_question = 1)
    {
        $data = $this->Exam_model->basic($exam_id);
        $data['questions'] = $this->Exam_model->questions($exam_id);
        $data['num_question'] = $num_question;

        $data['view_a'] = $this->views_folder . 'examenes/vista_previa/vista_previa_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Dirige a vista de clase-examen de un curso, identifica el índice correspondiente
     * 2021-04-19
     */
    function preparacion($exam_id, $course_id)
    {
        $index = 0;

        $this->load->model('Course_model');
        $classes = $this->Course_model->classes($course_id);

        //Identificar index de clase-examen en curso
        foreach( $classes->result() as $row_class )
        {
            if ( $row_class->type_id == 4140 && $row_class->related_2 == $exam_id ) { break; }
            $index += 1;    //No coincide con característcas, siguiente clase
        }

        redirect("app/cursos/abrir_elemento/{$course_id}/{$index}");
    }

    /**
     * Vista de resolución del cuestionario
     * 2021-03-22
     */
    function resolver($exam_id, $eu_id, $enrolling_id = 0, $num_question = 1)
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

        $data['view_a'] = $this->views_folder . 'examens/resolver/resolver_v';
        unset($data['nav_2']);
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Vista detalle resultados respuesta, pregunta a pregunta
     * 2021-03-22
     */
    function resultados($exam_id, $eu_id, $enrolling_id)
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

        $data['view_a'] = $this->views_folder . 'resultados/resultados_v';
        unset($data['nav_2']);
        $this->App_model->view(TPL_ADMIN, $data);
    }

}