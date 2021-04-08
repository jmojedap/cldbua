<?php
class Course_model extends CI_Model{

    function basic($course_id)
    {
        $row = $this->row($course_id);

        $data['row'] = $row;
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = 'courses/course_v';
        $data['nav_2'] = 'courses/courses/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - courses/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'courses';                      //Nombre del controlador
            $data['cf'] = 'courses/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'courses/courses/explore/';           //Carpeta donde están las vistas de exploración
            $data['num_page'] = $num_page;                      //Número de la página
            
        //Vistas
            $data['head_title'] = 'Courses';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($filters, $num_page, $per_page = 10)
    {
        //Load
            $this->load->model('Search_model');

        //Búsqueda y Resultados
            $data['filters'] = $filters;
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado
            $elements = $this->search($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['list'] = $elements->result();
            $data['str_filters'] = $this->Search_model->str_filters($filters);
            $data['search_num_rows'] = $this->search_num_rows($filters);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de products
     * 2020-12-12
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'id, post_name, excerpt, content, content_json, url_thumbnail, url_image, slug, text_1';
        $arr_select['export'] = '*';

        //Hace referencia a un post tipo clase, no curso
        $arr_select['course_class'] = 'id, post_name, excerpt, content, slug, type_id, text_1, related_1, position, related_2, parent_id';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de courses filtrados, por página y offset
     * 2020-07-15
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Segmento SELECT
            $select_format = 'general';
            if ( $filters['sf'] != '' ) { $select_format = $filters['sf']; }
            $this->db->select($this->select($select_format));

        //Condición fija, tipo curso
            $this->db->where('type_id', 4110);
        
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('updated_at', 'DESC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('posts', $per_page, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * String con condición WHERE SQL para filtrar course
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('post_name', 'content', 'excerpt', 'keywords'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['type'] != '' ) { $condition .= "type_id = {$filters['type']} AND "; }
        if ( $filters['condition'] != '' ) { $condition .= "{$filters['condition']} AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('posts'); //Para calcular el total de resultados

        return $query->num_rows();
    }

    /**
     * Query para exportar
     * 2020-12-12
     */
    function export($filters)
    {
        $this->db->select($this->select('export'));
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('posts', 5000);  //Hasta 5000 productos

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún course, se obtendrían cero courses.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los course
            $condition = 'id > 0';
        } else {
            $condition = 'status = 1';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de course en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Course',
            'post_name' => 'Nombre'
        );
        
        return $order_options;
    }

// BROWSE EXPLORACIÓN DE CURSOS CATÁLOGO
//-----------------------------------------------------------------------------

    /**
     * Array con los datos para la vista de exploración
     * 2021-03-30
     */
    function browse_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'courses';                      //Nombre del controlador
            $data['cf'] = 'courses/browse/';                      //Nombre del controlador
            $data['views_folder'] = 'courses/courses/browse/';           //Carpeta donde están las vistas de exploración
            $data['num_page'] = $num_page;                      //Número de la página
            
        //Vistas
            $data['head_title'] = 'Cursos';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
        
        return $data;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un course ID, con un formato específico
     * 2021-01-04
     */
    function row($course_id, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $course_id);
        $query = $this->db->get('posts', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    function save()
    {
        $data['saved_id'] = $this->Db_model->save_id('posts');
        return $data;
    }

// ELIMINACIÓN DE UN COURSE
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro tabla posts
     * 2020-08-18
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('posts', $row_id);

        $deleteable = 0;
        if ( $this->session->userdata('role') <= 2 ) $deleteable = 1;                   //Es Administrador
        if ( $row->creator_id = $this->session->userdata('user_id') ) $deleteable = 1;  //Es el creador

        return $deleteable;
    }

    /**
     * Eliminar un course de la base de datos, se eliminan registros de tablas relacionadas
     * 2020-08-18
     */
    function delete($course_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($course_id) ) 
        {
            //Tablas relacionadas
                $this->db->where('parent_id', $course_id)->delete('posts');
                //$this->db->where('course_id', $course_id)->delete('course_meta');
            
            //Tabla principal
                $this->db->where('id', $course_id)->delete('posts');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal
        }

        return $qty_deleted;
    }

// Datos de un course
//-----------------------------------------------------------------------------

    function classes($course_id)
    {
        $this->db->select($this->select('course_class'));
        $this->db->where('parent_id', $course_id);
        $this->db->where('type_id > 4110 AND type_id < 4190'); //Tipos clase
        $this->db->order_by('related_1', 'ASC');
        $this->db->order_by('position', 'ASC');
        $classes = $this->db->get('posts');

        return $classes;
    }

// Inscripción de usuario
//-----------------------------------------------------------------------------

    /**
     * Query, cursos a los que está inscrito un usuario
     * 2021-04-05
     */
    function user_courses($user_id)
    {
        $this->db->select('posts.*, users_meta.user_id, users_meta.integer_1 AS current_element_index, users_meta.score_1 AS user_score, users_meta.score_2 AS enrolling_status, users_meta.score_3 AS user_progress');
        $this->db->join('users_meta', 'posts.id = users_meta.related_1', 'left');    
        $this->db->where('users_meta.user_id', $user_id);
        $this->db->where('users_meta.type_id', 411010); //Inscripción a curso
        $this->db->order_by('users_meta.updated_at', 'DESC');
        $courses = $this->db->get('posts');

        return $courses;
    }

    /**
     * Asignar un contenido de la tabla posts a un usuario, lo agrega como metadato
     * en la tabla users_meta, con el tipo 411010
     * 2021-03-30
     */
    function enroll($arr_row)
    {
        //Construir registro
        $arr_row['type_id'] = 411010;       //Asignación de course a usuario
        $arr_row['integer_2'] = 1;          //Num clase actual, 1 por defecto
        $arr_row['updater_id'] = $this->session->userdata('user_id');    //Usuario que inscribe
        $arr_row['creator_id'] = $this->session->userdata('user_id');    //Usuario que inscribe

        $condition = "type_id = {$arr_row['type_id']} AND user_id = {$arr_row['user_id']} AND related_1 = {$arr_row['related_1']}";
        $meta_id = $this->Db_model->save('users_meta', $condition, $arr_row);

        //Establecer resultado
        $data = array('status' => 0, 'saved_id' => '0');
        if ( $meta_id > 0) { $data = array('status' => 1, 'saved_id' => $meta_id); }

        return $data;
    }

    /**
     * Quita la asignación de un course a un usuario
     * 2020-04-30
     */
    function remove_to_user($course_id, $meta_id)
    {
        $data = array('status' => 0, 'qty_deleted' => 0);

        $this->db->where('id', $meta_id);
        $this->db->where('related_1', $course_id);
        $this->db->delete('users_meta');

        $data['qty_deleted'] = $this->db->affected_rows();

        if ( $data['qty_deleted'] > 0) { $data['status'] = 1; }

        return $data;
    }

    /**
     * Guarda evento (tabla events) de apertura de clase por un usuario inscrito
     * 2021-04-08
     */
    function save_open_class_event($row_class, $row_enrolling)
    {
        $arr_event['type_id'] = 51; //Apertura de post
        $arr_event['start'] = date('Y-m-d H:i:s');  //Momento de apertura
        $arr_event['element_id'] = $row_class->id; //ID post, clase
        $arr_event['related_1'] = $row_class->parent_id;       //ID curso
        $arr_event['related_2'] = $row_enrolling->id;    //ID inscripción
        $arr_event['related_3'] = $row_class->type_id;  //Tipo Post
        $arr_event['user_id'] = $this->session->userdata('user_id');    //Usuario en sesión

        $this->load->model('Event_model');
        $event_id = $this->Event_model->save($arr_event, 'id = 0'); //Condición imposible, siempre se crea

        return $event_id;
    }
}