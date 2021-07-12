<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

// Variables generales
//-----------------------------------------------------------------------------
public $views_folder = 'admin/config/';
public $url_controller = URL_ADMIN . 'config/';


// Constructor
//-----------------------------------------------------------------------------

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Admin_model');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");

    }
        
    function index()
    {
        redirect('admin/acl');
    }
        
// SIS OPTION 2019-06-15
//---------------------------------------------------------------------------------------------------

    /**
     * Listas de documentos, creación, edición y eliminación de opciones
     */
    function options()
    {
        $data['head_title'] = 'Opciones del sistema';
        $data['nav_2'] = 'system/admin/menu_v';        
        $data['view_a'] = 'system/admin/options_v';        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX - JSON
     * Listado de las opciones de documentos (posts.type_id = 7022)
     */
    function get_options()
    {
        $data['options'] = $this->db->get('sis_option')->result();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX - JSON
     * Inserta o actualiza una opcione de documentos (posts.type_id = 7022)
     */
    function save_option($option_id = 0)
    {
        $option_id = $this->Admin_model->save_option($option_id);

        $data = array('status' => 0, 'message' => 'La opción no fue guardada');
        if ( ! is_null($option_id) ) { $data = array('status' => 1, 'message' => 'Opción guardada: ' . $option_id); }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina una opcione de documentos, registro de la tabla post
     */
    function delete_option($option_id)
    {
        $data = $this->Admin_model->delete_option($option_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Colores
//-----------------------------------------------------------------------------

    /**
     * Conjunto de colores de la herramienta
     * 2020-03-18
     */
    function colors()
    {
        $data['head_title'] = 'Colores';
        $data['nav_2'] = 'system/admin/menu_v';
        $data['view_a'] = 'system/admin/colors_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
//Login maestro
//---------------------------------------------------------------------------------------------------
    
    /**
     * ml > master login
     * Función para el login de administradores ingresando con otro user
     * 
     * @param type $user_id
     */
    function ml($user_id)
    {
        $this->load->model('Account_model');
        $username = $this->Db_model->field_id('users', $user_id, 'username');
        if ( $this->session->userdata('rol_id') <= 1 ) { $this->Account_model->create_session($username, FALSE); }
        
        redirect('app/accounts/logged');
    }    

// Pruebas y desarrollo
//-----------------------------------------------------------------------------

    /**
     * Reestablecer sistema para pruebas
     * 2019-07-19
     */
    function reset()
    {
        
    }

    function test()
    {
        $data['head_title'] = 'Test';
        $data['view_a'] = 'app/test_v';
        $data['cant_resultados'] = '50';

        $this->db->select('*');
        $data['pictures'] = $this->db->get('pictures');

        $this->App_model->view('templates/remark/main_v', $data);
    }

    function test_ajax()
    {
        $data['view_a'] = '<h1>Hola desde ajax</h1>';
        $data['status'] = 1;

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
}