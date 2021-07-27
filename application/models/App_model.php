<?php
class App_model extends CI_Model{
    
    /* Application model,
     * Functions to Legalink Admin Application
     * 
     */
    
    function __construct(){
        parent::__construct();
        
    }
    
//SYSTEM
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Carga la view solicitada, si por get se solicita una view específica
     * se devuelve por secciones el html de la view, por JSON.
     * 
     * @param type $view
     * @param type $data
     */
    function view($view, $data)
    {
        if ( $this->input->get('json') )
        {
            //Sende sections JSON
            $result['head_title'] = $data['head_title'];
            $result['head_subtitle'] = '';
            $result['nav_2'] = '';
            $result['nav_3'] = '';
            $result['view_a'] = '';
            
            if ( isset($data['head_subtitle']) ) { $result['head_subtitle'] = $data['head_subtitle']; }
            if ( isset($data['view_a']) ) { $result['view_a'] = $this->load->view($data['view_a'], $data, TRUE); }
            if ( isset($data['nav_2']) ) { $result['nav_2'] = $this->load->view($data['nav_2'], $data, TRUE); }
            if ( isset($data['nav_3']) ) { $result['nav_3'] = $this->load->view($data['nav_3'], $data, TRUE); }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
            //echo trim(json_encode($result));
        } else {
            //Cargar view completa de forma normal
            $this->load->view($view, $data);
        }
    }
    
    /**
     * Devuelve el valor del campo sis_option.valor
     * @param type $option_id
     * @return type
     */
    function option_value($option_id)
    {
        $option_value = $this->Db_model->field_id('sis_option', $option_id, 'value');
        return $option_value;
    }

    /**
     * Array con datos de sesión adicionales específicos para la aplicación actual.
     * 2021-03-18
     */
    function app_session_data($row_user)
    {
        $data = array();

        return $data;
    }

    //Resumen para dashboard
    function summary()
    {
        $summary = array();

        $summary['users']['num_rows'] = $this->Db_model->num_rows('users', 'id > 0');
        $summary['students']['num_rows'] = $this->Db_model->num_rows('users', 'role = 21');
        $summary['courses']['num_rows'] = $this->Db_model->num_rows('posts', 'type_id = 4110');
    
        return $summary;
    }

// NOMBRES
//-----------------------------------------------------------------------------

    /**
     * Devuelve el nombre de un user ($user_id) en un format específico ($format)
     */
    function name_user($user_id, $format = 'd')
    {
        $name_user = 'ND';
        $row = $this->Db_model->row_id('users', $user_id);

        if ( ! is_null($row) ) 
        {
            $name_user = $row->username;

            if ($format == 'u') {
                $name_user = $row->username;
            } elseif ($format == 'FL') {
                $name_user = "{$row->first_name} {$row->last_name}";
            } elseif ($format == 'LF') {
                $name_user = "{$row->last_name} {$row->first_name}";
            } elseif ($format == 'FLU') {
                $name_user = "{$row->first_name} {$row->last_name} | {$row->username}";
            } elseif ($format == 'd') {
                $name_user = $row->display_name;
            }
        }

        return $name_user;
    }

    /**
     * Devuelve el nombre de una registro ($place_id) en un format específico ($format)
     */
    function place_name($place_id, $format = 1)
    {
        
        $place_name = 'ND';
        
        if ( strlen($place_id) > 0 )
        {
            $this->db->select("places.id, places.place_name, region, country"); 
            $this->db->where('places.id', $place_id);
            $row = $this->db->get('places')->row();

            if ( $format == 1 ){
                $place_name = $row->place_name;
            } elseif ( $format == 'CR' ) {
                $place_name = $row->place_name . ', ' . $row->region;
            } elseif ( $format == 'CRP' ) {
                $place_name = $row->place_name . ' - ' . $row->region . ' - ' . $row->country;
            }
        }
        
        
        return $place_name;
    }

// OPCIONES
//-----------------------------------------------------------------------------

    /** Devuelve un array con las opciones de la tabla place, limitadas por una condición definida
    * en un formato ($format) definido    
    */
    function options_place($condition, $value_field = 'full_name', $empty_text = 'Lugar')
    {
        
        $this->db->select("CONCAT('0', places.id) AS place_id, place_name, full_name, CONCAT((place_name), ', ', (region)) AS cr", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('places.place_name', 'ASC');
        $query = $this->db->get('places');
        
        $options_place = array_merge(array('' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, 'place_id'));
        
        return $options_place;
    }

    /* Devuelve un array con las opciones de la tabla place, limitadas por una condición definida
    * en un format ($format) definido
    */
    function options_user($condition, $empty_text = 'Usuario', $value_field = 'display_name')
    {
        
        $this->db->select("CONCAT('0', users.id) AS user_id, display_name, username", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('users.display_name', 'ASC');
        $query = $this->db->get('users');
        
        $options_user = array_merge(array('' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, 'user_id'));
        
        return $options_user;
    }

    /* Devuelve un array con las opciones de la tabla post, limitadas por una condición definida
    * en un formato ($formato) definido
    */
    function options_post($condition, $format = 'n', $empty_text = 'posts')
    {
        
        $this->db->select("CONCAT('0', posts.id) AS post_id, post_name", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('posts.id', 'ASC');
        $query = $this->db->get('posts');
        
        $index_field = 'post_id';
        
        if ( $format == 'n' )
        {
            $value_field = 'post_name';
        }
        
        $options_post = array_merge(array('' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, $index_field));
        
        return $options_post;
    }

// IMÁGENES
//-----------------------------------------------------------------------------

    function src_img_user($row_user, $prefix = '')
    {
        $src = URL_IMG . 'users/'. $prefix . 'user.png';
            
        if ( $row_user->image_id > 0 ) { $src = $row_user->url_thumbnail;}
        
        return $src;
    }

    function att_img_user($row_user, $prefix = '')
    {
        $att_img = array(
            'src' => $this->src_img_user($row_user, $prefix),
            'alt' => 'Imagen del usuario ' . $row_user->username,
            'width' => '100%',
            'onerror' => "this.src='" . URL_IMG . 'users/sm_user.png' . "'"
        );
        
        return $att_img;
    }

// FUNCIONES ESPECIALES uniandes
//-----------------------------------------------------------------------------

    /**
     * Establecer imagen ususario, con una de avatar, campo users.url_image
     * 201-03-26
     */
    function set_avatar()
    {
        $data = array('status' => 0, 'url_avatar' => '');

        //Constriur registro
        $arr_row['url_image'] = URL_CONTENT . 'avatars/' . $this->input->post('file_name');
        $arr_row['url_thumbnail'] = URL_CONTENT . 'avatars/' . $this->input->post('file_name');

        //Actualizar
        $this->db->where('id', $this->session->userdata('user_id'));
        $this->db->update('users', $arr_row);

        //Verificar resultado
        if ( $this->db->affected_rows() > 0 ) {
            $data['status'] = 1;
            $data['url_avatar'] = $arr_row['url_image'];
        }

        return $data;
    }

// Procesos del sistema para la aplicación
//-----------------------------------------------------------------------------

    function processes()
    {
        $this->db->select('id, post_name AS process_name, content AS description, text_2 AS module, text_1 AS process_link');
        $this->db->where('type_id', 10);
        $processes = $this->db->get('posts');

        return $processes;
    }

}