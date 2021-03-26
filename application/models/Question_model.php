<?php
class Question_model extends CI_Model{

    function basic($question_id)
    {
        $row = $this->Db_model->row_id('questions', $question_id);

        $data['row'] = $row;
        $data['head_title'] = 'Pregunta' . $data['row']->id;
        $data['nav_2'] = 'exams/questions/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - questions/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'questions';                      //Nombre del controlador
            $data['cf'] = 'questions/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'exams/questions/explore/';           //Carpeta donde están las vistas de exploración
            $data['num_page'] = $num_page;                      //Número de la página
            
        //Vistas
            $data['head_title'] = 'Cuestionarios';
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
     * String con condición WHERE SQL para filtrar question
     */
    function search_condition_org($filters)
    {
        $condition = NULL;
        
        //Tipo de question
        if ( $filters['type'] != '' ) { $condition .= "type_id = {$filters['type']} AND "; }
        if ( $filters['condition'] != '' ) { $condition .= "{$filters['condition']} AND "; }
        
        if ( strlen($condition) > 0 )
        {
            $condition = substr($condition, 0, -5);
        }
        
        return $condition;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de products
     * 2020-12-12
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'questions.*';
        $arr_select['export'] = 'questions.*';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de questions filtrados, por página y offset
     * 2020-07-15
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Segmento SELECT
            $select_format = 'general';
            if ( $filters['sf'] != '' ) { $select_format = $filters['sf']; }
            $this->db->select($this->select($select_format));
        
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
            $query = $this->db->get('questions', $per_page, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * String con condición WHERE SQL para filtrar question
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('code', 'question_text', 'keywords'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['type'] != '' ) { $condition .= "type_id = {$filters['type']} AND "; }
        if ( $filters['org'] != '' ) { $condition .= "institution_id = {$filters['org']} AND "; }
        
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
        $query = $this->db->get('questions'); //Para calcular el total de resultados

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
        $query = $this->db->get('questions', 5000);  //Hasta 5000 registros

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'questions.id = 0';  //Valor por defecto, ningún question, se obtendrían cero questions.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los question
            $condition = 'questions.id > 0';
        } else {
            $condition = 'questions.type_id IN (6)';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de question en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Cuestionario',
            'title' => 'Nombre'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un question ID, con un formato específico
     * 2021-01-04
     */
    function row($question_id, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $question_id);
        $query = $this->db->get('questions', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    /**
     * Actualiza un registro en la tabla questions
     * 2021-03-18
     */
    function save()
    {
        $data = array('status' => 0, 'saved_id' => 0);              //Resultado inicial
        $data['saved_id'] = $this->Db_model->save_id('questions');      //Guardar
        if ( $data['saved_id'] > 0 ){ $data['status'] = 1; }        //Verificar y actualizar resultado
        
        return $data;
    }

    /**
     * Insertar registro en la tabla questions, una posición específica
     * 2021-03-19
     */
    function insert($arr_row, $row_exam, $position = NULL)
    {
        if ( is_null($position) )
        {
            $arr_row['position'] = $row_exam->qty_questions;
        } else {
            $arr_row['position'] = $postition;
        }

        //Otros campos
        $arr_row['created_at'] = date('Y-m-d H:i:s');
        $arr_row['updated_at'] = date('Y-m-d H:i:s');
        $arr_row['creator_id'] = $this->session->userdata('user_id');
        $arr_row['updater_id'] = $this->session->userdata('user_id');

        $saved_id = $this->Db_model->save_id('questions', $arr_row);      //Guardar

        //Actualizar campo exam.qty_questions
        if ( $saved_id > 1 ) {
            $this->db->query("UPDATE exams SET qty_questions = qty_questions + 1 WHERE id = {$row_exam->id}");
        }

        return $saved_id;
    }

// ELIMINACIÓN DE UN EXAM
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro tabla question
     * 2020-08-18
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('questions', $row_id);

        $deleteable = 0;
        if ( $this->session->userdata('role') <= 2 ) $deleteable = 1;                   //Es Administrador
        if ( $row->creator_id = $this->session->userdata('user_id') ) $deleteable = 1;  //Es el creador

        return $deleteable;
    }

    /**
     * Eliminar un question de la base de datos, se eliminan registros de tablas relacionadas con triggers de MySQL
     * 2021-03-18
     */
    function delete($question_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($question_id) ) 
        {
            //Tabla principal
                $this->db->where('id', $question_id)->delete('questions');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal
        }

        return $qty_deleted;
    }

// VALIDATION
//-----------------------------------------------------------------------------

    

// IMPORTAR
//-----------------------------------------------------------------------------}

    /**
     * Array con configuración de la vista de importación según el tipo de usuario
     * que se va a importar.
     * 2019-11-20
     */
    function import_config()
    {
        $data['help_note'] = 'Se importarán preguntas a la base de datos.';
        $data['help_tips'] = array();
        $data['template_file_name'] = 'f43_preguntas.xlsx';
        $data['sheet_name'] = 'preguntas';
        $data['head_subtitle'] = 'Importar';
        $data['destination_form'] = "questions/import_e";

        return $data;
    }

    /**
     * Importa questions a la base de datos
     * 2021-03-19
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());

        $this->load->model('Exam_model');
        $affected_exams = array();
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_question($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;

            //Especial
            /*if ( isset($data_import['exam_id']) ) $affected_exams[] = $data_import['exam_id'];
            $this->update_exams_info($affected_exams);*/
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla question, y agrega al grupo asignado.
     * 2020-02-22
     */
    function import_question($row_data)
    {
        //Validar
            $error_text = '';
            $row_exam = $this->Db_model->row_id('exams', $row_data[0]);
                            
            if ( strlen($row_data[0]) == 0 ) { $error_text .= 'La casilla de ID Cuestionario está vacía. '; }
            if ( is_null($row_exam) ) { $error_text .= "ID Cuestionario '{$row_data[0]}' no existe. "; }
            if ( strlen($row_data[2]) == 0 ) { $error_text .= "La opción 1 está vacía. "; }
            if ( strlen($row_data[3]) == 0 ) { $error_text .= "La opción 2 está vacía. "; }
            if ( intval($row_data[6]) < 1 ) { $error_text .= "La opción correcta (" . intval($row_data[6]) . ") debe ser mayor o igual a 1. "; }
            if ( intval($row_data[6]) > 4 ) { $error_text .= "La opción correcta (" . intval($row_data[6]) . ") debe menor o igual a 4. "; }

        //Si no hay error
            if ( $error_text == '' )
            {
                $arr_row['exam_id'] = $row_exam->id;
                $arr_row['question_text'] = $row_data[1];
                $arr_row['option_1'] = $row_data[2];
                $arr_row['option_2'] = $row_data[3];
                $arr_row['option_3'] = $row_data[4];
                $arr_row['option_4'] = $row_data[5];
                $arr_row['correct_option'] = $row_data[6];

                //Guardar en tabla questions
                $saved_id = $this->insert($arr_row, $row_exam);

                //Actualizar examen
                if ( $saved_id > 0 ) $this->Exam_model->update_questions_info($row_exam->id);

                $data = array('status' => 1, 'text' => '', 'imported_id' => $saved_id, 'exam_id' => $row_exam->id);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

    /**
     * Tras la importación de preguntas, se actualiza la información de cuestionarios relacionados
     * 2021-03-23
     */
    function update_exams_info($affected_exams)
    {
        $this->load->model('Exam_model');
        foreach ($affected_exams as $exam_id) {
            $this->Exam_model->update_answers_info($exam_id);
        }
    }

// Asignación a usuario
//-----------------------------------------------------------------------------

    /**
     * Asignar un contenido de la tabla question a un usuario, lo agrega como metadato
     * en la tabla users_meta, con el tipo 100012
     * 2020-04-15
     */
    function add_to_user($question_id, $user_id)
    {
        //Construir registro
        $arr_row['user_id'] = $user_id;     //Usuario ID, al que se asigna
        $arr_row['type_id'] = 100012;       //Asignación de question
        $arr_row['related_1'] = $question_id;   //ID contenido
        $arr_row['updater_id'] = 100001;    //Usuario que asigna
        $arr_row['creator_id'] = 100001;    //Usuario que asigna

        //Establecer usuario que ejecuta
        if ( $this->session->userdata('logged') ) {
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }

        $condition = "type_id = {$arr_row['type_id']} AND user_id = {$arr_row['user_id']} AND related_1 = {$arr_row['related_1']}";
        $meta_id = $this->Db_model->save('users_meta', $condition, $arr_row);

        //Establecer resultado
        $data = array('status' => 0, 'saved_id' => '0');
        if ( $meta_id > 0) { $data = array('status' => 1, 'saved_id' => $meta_id); }

        return $data;
    }

    /**
     * Quita la asignación de un question a un usuario
     * 2020-04-30
     */
    function remove_to_user($question_id, $meta_id)
    {
        $data = array('status' => 0, 'qty_deleted' => 0);

        $this->db->where('id', $meta_id);
        $this->db->where('related_1', $question_id);
        $this->db->delete('users_meta');

        $data['qty_deleted'] = $this->db->affected_rows();

        if ( $data['qty_deleted'] > 0) { $data['status'] = 1; }

        return $data;
    }
}