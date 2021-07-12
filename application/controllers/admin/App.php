<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función de la aplicación
     */
    function index()
    {
        if ( $this->session->userdata('logged') )
        {
            $this->logged();
        } else {
            redirect('accounts/login');
        }    
    }

    function denied()
    {
        $data['head_title'] = 'Acceso no permitido';
        $data['view_a'] = 'app/denied_v';

        $this->load->view('templates/apanel4/start', $data);
    }

    
//GENERAL AJAX SERVICES
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX - POST
     * Return String, with unique slut
     */
    function unique_slug()
    {
        $text = $this->input->post('text');
        $table = $this->input->post('table');
        $field = $this->input->post('field');
        
        $unique_slug = $this->Db_model->unique_slug($text, $table, $field);
        
        $this->output->set_content_type('application/json')->set_output($unique_slug);
    }

// FUNCIONES ESPECIALES PARA LA APLICACIÓN UNIANDES
//-----------------------------------------------------------------------------

    /**
     * Establecer imagen de avatar a usuario, como imagen de perfil
     * El nombre de archivo de avatar es enviadpo via POST
     * 2019-03-26
     */
    function set_avatar()
    {
        $data = $this->App_model->set_avatar();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// PRUEBAS Y EXPERIMENTACIÓN
//-----------------------------------------------------------------------------

    function test()
    {
        $data['head_title'] = 'Template Logistic';
        $data['view_a'] = 'templates/logistic/test_content';
        //$this->load->view('templates/logistic/test_v', $data);
        $this->load->view('templates/logistic/main', $data);
    }

    

    function test_recaptcha()
    {
        $recaptcha = $this->App_model->recaptcha();

        $data = array('status' => 0, 'message' => 'No aprobado por RC');
        if ( $recaptcha->score > 0.5 )
        {
            $data = array('status' => 1, 'message' => 'SÍ APROBADO POR RC: ' . $recaptcha->score);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function maps()
    {
        $this->load->view('app/map_v');
    }

    function vuesortable()
    {
        $this->load->view('app/test/vuesortable');
    }

    function grid()
    {
        $this->load->view('tests/grid');
    }
}