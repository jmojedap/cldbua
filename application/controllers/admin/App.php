<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

// Variables generales
//-----------------------------------------------------------------------------
public $views_folder = 'admin/app/';
public $url_controller = URL_ADMIN . 'app/';

// Constructor
//-----------------------------------------------------------------------------
    
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

    function dashboard()
    {
        $data['summary'] = $this->App_model->summary();
        $data['head_title'] = APP_NAME;
        $data['view_a'] = $this->views_folder . 'dashboard_v';
        $this->App_model->view(TPL_ADMIN, $data);

        //$this->output->enable_profiler(TRUE);
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
}