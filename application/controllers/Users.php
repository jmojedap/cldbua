<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('User_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($user_id)
    {
        redirect("users/profile/{$user_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
            
    /**
     * Exploración y búsqueda de usuarios
     * 2020-08-01
     */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->User_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_role'] = $this->Item_model->options('category_id = 58', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_cod('category_id = 58');
            $data['arr_document_types'] = $this->Item_model->arr_item('category_id = 53', 'cod_abr');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Listado de users, según filtros de búsqueda
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data = $this->User_model->get($filters, $num_page, $per_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de users seleccionados
     * 2021-02-20
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) $data['qty_deleted'] += $this->User_model->delete($row_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// DATOS
//-----------------------------------------------------------------------------
    /**
     * Información general del usuario
     */
    function profile($user_id)
    {        
        //Datos básicos
        $data = $this->User_model->basic($user_id);

        $data['view_a'] = 'users/profile/profile_v';
        if ( $data['row']->role == 21  ) { $data['view_a'] = 'users/profile/student_v'; }
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Formulario para la creación de un nuevo usuario
     * 2021-02-17
     */
    function add($role_type = 'student')
    {
        //Variables específicas
            $data['role_type'] = $role_type;

        //Opciones Select
            $data['options_role'] = $this->Item_model->options('category_id = 58', 'Rol de usuario');
            $data['options_gender'] = $this->Item_model->options('category_id = 59 AND cod <= 2', 'Sexo');
            $data['options_city'] = $this->App_model->options_place('type_id = 4', 'cr', 'Ciudad');
            $data['options_document_type'] = $this->Item_model->options('category_id = 53', 'Tipo documento');

        //Variables generales
            $data['head_title'] = 'Usuarios';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'users/explore/menu_v';
            $data['view_a'] = "users/add/{$role_type}/add_v";

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Se validan los datos de un user add o existente ($user_id), los datos deben cumplir varios criterios
     * 2021-02-02
     */
    function validate($user_id = NULL)
    {
        $data = $this->User_model->validate($user_id);
        
        //Enviar resultado de validación
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Guardar datos de un usuario, insertar o actualizar
     * 2021-02-17
     */
    function save($user_id = NULL)
    {
        $validation = $this->User_model->validate($user_id);
        
        if ( $validation['status'] == 1 )
        {
            $data = $this->User_model->save($user_id);
        } else {
            $data = $validation;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($user_id, $section = 'basic')
    {
        //Datos básicos
        $data = $this->User_model->basic($user_id);

        //Opciones Select
        $data['options_role'] = $this->Item_model->options('category_id = 58', 'Rol de usuario');
        $data['options_gender'] = $this->Item_model->options('category_id = 59 AND cod <= 2', 'Sexo');
        $data['options_city'] = $this->App_model->options_place('type_id = 4', 'cr', 'Ciudad');
        
        $view_a = "users/edit/{$section}_v";
        if ( $section == 'cropping' )
        {
            $view_a = 'files/cropping_v';
            $data['image_id'] = $data['row']->image_id;
            $data['url_image'] = $data['row']->url_image;
            $data['back_destination'] = "users/edit/{$user_id}/image";
        }
        
        //Array data espefícicas
            $data['nav_3'] = 'users/edit/menu_v';
            $data['view_a'] = $view_a;
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Actualiza el campo user.activation_key, para activar o restaurar la contraseña de un usuario
     * 2019-11-18
     */
    function set_activation_key($user_id)
    {
        $this->load->model('Account_model');
        $activation_key = $this->Account_model->activation_key($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($activation_key));
    }
    
// IMAGEN DE PERFIL DE USUARIO
//-----------------------------------------------------------------------------
    /**
     * AJAX JSON
     * Carga file de image y se la asigna a un user.
     * 2020-02-22
     */
    function set_image($user_id)
    {
        //Cargue
        $this->load->model('File_model');
        $data_upload = $this->File_model->upload();
        
        $data = $data_upload;
        if ( $data_upload['status'] )
        {
            $this->User_model->remove_image($user_id);                                  //Quitar image actual, si tiene una
            $data = $this->User_model->set_image($user_id, $data_upload['row']->id);    //Asignar imagen nueva
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * POST REDIRECT
     * 
     * Proviene de la herramienta de recorte users/edit/$user_id/crop, 
     * utiliza los datos del form para hacer el recorte de la image.
     * Actualiza las miniaturas
     * 
     * @param type $user_id
     * @param type $file_id
     */
    function crop_image_e($user_id, $file_id)
    {
        $this->load->model('File_model');
        $this->File_model->crop($file_id);
        redirect("users/edit/{$user_id}/image");
    }
    
    /**
     * AJAX
     * Desasigna y elimina la image asociada a un user, si la tiene.
     * 
     * @param type $user_id
     */
    function remove_image($user_id)
    {
        $data = $this->User_model->remove_image($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMPORTACIÓN DE USUARIOS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de usuarios
     * con archivo Excel. El resultado del formulario se envía a 
     * 'users/import_e'
     */
    function import()
    {
        //Iniciales
            $data['help_note'] = 'Se importarán usuarios a la herramienta.';
            $data['help_tips'] = array(
                'La contraseña debe tener al menos 8 caracteres'
            );
        
        //Variables específicas
            $data['destination_form'] = "users/import_e";
            $data['template_file_name'] = 'f01_usuarios.xlsx';
            $data['sheet_name'] = 'usuarios';
            $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];
            
        //Variables generales
            $data['head_title'] = 'Usuarios';
            $data['head_subtitle'] = 'Importar';
            $data['view_a'] = 'common/import_v';
            $data['nav_2'] = 'users/explore/menu_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Ejecuta (e) la importación de usuarios con archivo Excel
     * 2019-09-20
     */
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->User_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "users/import/";
        
        //Cargar vista
            $data['head_title'] = 'Usuarios';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = 'users/explore/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
    
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX
     * Devuelve un valor de username sugerido disponible, da y last_name
     */
    function username()
    {
        $first_name = 'usuario';
        $last_name = APP_NAME;

        if ( ! is_null($this->input->post('last_name')) )
        {
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
        } elseif ( ! is_null($this->input->post('display_name')) ) {
            $name_parts = explode(' ', $this->input->post('display_name'));
            if ( count($name_parts) > 0 ) $first_name = $name_parts[0];
            if ( count($name_parts) > 0 ) $last_name = $name_parts[1];
        }

        $username = $this->User_model->generate_username($first_name, $last_name);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($username));
    }

// POSTS ASIGNADOS COMO CLIENTE
//-----------------------------------------------------------------------------

    function assigned_posts($user_id = 0)
    {
        $this->load->model('File_model');
        //Control de permisos de acceso
        if ( $this->session->userdata('role') >= 10 ) { $user_id = $this->session->userdata('user_id'); }
        if ( $user_id == 0 ) { $user_id = $this->session->userdata('user_id'); }

        $data = $this->User_model->basic($user_id);

        $data['posts'] = $this->User_model->assigned_posts($user_id);
        $data['options_post'] = $this->App_model->options_post('type_id IN (5,8)', 'n', 'Contenido');

        $data['view_a'] = 'users/assigned_posts_v';
        if ( $this->session->userdata('role') >= 20 )
        {
            $data['head_title'] = 'Mis contenidos';
            $data['nav_2'] = NULL;
        }

        $this->App_model->view(TPL_ADMIN, $data);
    }

// SEGUIDORES
//-----------------------------------------------------------------------------

    /** Alternar seguir o dejar de seguir un usuario por parte del usuario en sesión */
    function alt_follow($user_id)
    {
        $data = $this->User_model->alt_follow($user_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Listado de usuarios seguidos por usuario en sesión
     * 2020-07-15
     */
    function following($user_id)
    {
        $users = $this->User_model->following($user_id);
        $data['list'] = $users->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}