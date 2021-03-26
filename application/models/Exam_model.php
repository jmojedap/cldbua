<?php
class Exam_model extends CI_Model{

    function basic($exam_id)
    {
        $row = $this->Db_model->row_id('exams', $exam_id);

        $data['row'] = $row;
        $data['head_title'] = $data['row']->title;
        $data['nav_2'] = 'exams/exams/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - exams/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'exams';                      //Nombre del controlador
            $data['cf'] = 'exams/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'exams/exams/explore/';           //Carpeta donde están las vistas de exploración
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
     * String con condición WHERE SQL para filtrar exam
     */
    function search_condition_org($filters)
    {
        $condition = NULL;
        
        //Tipo de exam
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
        $arr_select['general'] = 'exams.*';
        $arr_select['export'] = 'exams.*';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de exams filtrados, por página y offset
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
            $query = $this->db->get('exams', $per_page, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * String con condición WHERE SQL para filtrar exam
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('title', 'description'));
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
        $query = $this->db->get('exams'); //Para calcular el total de resultados

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
        $query = $this->db->get('exams', 5000);  //Hasta 5000 registros

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'exams.id = 0';  //Valor por defecto, ningún exam, se obtendrían cero exams.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los exam
            $condition = 'exams.id > 0';
        } else {
            $condition = 'exams.type_id IN (6)';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de exam en la vista de
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
     * Objeto registro de un exam ID, con un formato específico
     * 2021-01-04
     */
    function row($exam_id, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $exam_id);
        $query = $this->db->get('exams', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    /**
     * Actualiza un registro en la tabla exams
     * 2021-03-18
     */
    function save()
    {
        $data = array('status' => 0, 'saved_id' => 0);              //Resultado inicial
        $data['saved_id'] = $this->Db_model->save_id('exams');      //Guardar
        if ( $data['saved_id'] > 0 ){ $data['status'] = 1; }        //Verificar y actualizar resultado
        
        return $data;
    }

// ELIMINACIÓN DE UN EXAM
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro tabla exam
     * 2020-08-18
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('exams', $row_id);

        $deleteable = 0;
        if ( $this->session->userdata('role') <= 2 ) $deleteable = 1;                   //Es Administrador
        if ( $row->creator_id = $this->session->userdata('user_id') ) $deleteable = 1;  //Es el creador

        return $deleteable;
    }

    /**
     * Eliminar un exam de la base de datos, se eliminan registros de tablas relacionadas con triggers de MySQL
     * 2021-03-18
     */
    function delete($exam_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($exam_id) ) 
        {
            //Tabla principal
                $this->db->where('id', $exam_id)->delete('exams');

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
    function import_config($type)
    {
        $data = array();

        if ( $type == 'general' )
        {
            $data['help_note'] = 'Se importarán exams a la base de datos.';
            $data['help_tips'] = array();
            $data['template_file_name'] = 'f50_exams.xlsx';
            $data['sheet_name'] = 'exams';
            $data['head_subtitle'] = 'Importar';
            $data['destination_form'] = "exams/import_e/{$type}";
        }

        return $data;
    }

    /**
     * Importa exams a la base de datos
     * 2020-02-22
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_exam($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla exam, y agrega al grupo asignado.
     * 2020-02-22
     */
    function import_exam($row_data)
    {
        //Validar
            $error_text = '';
                            
            if ( strlen($row_data[0]) == 0 ) { $error_text = 'La casilla Nombre está vacía. '; }
            if ( strlen($row_data[1]) == 0 ) { $error_text .= 'La casilla Cod Tipo está vacía. '; }
            if ( strlen($row_data[2]) == 0 ) { $error_text .= 'La casilla Resumen está vacía. '; }
            if ( strlen($row_data[14]) == 0 ) { $error_text .= 'La casilla Fecha Publicación está vacía. '; }

        //Si no hay error
            if ( $error_text == '' )
            {
                $arr_row['exam_name'] = $row_data[0];
                $arr_row['type_id'] = $row_data[1];
                $arr_row['excerpt'] = $row_data[2];
                $arr_row['content'] = $row_data[3];
                $arr_row['content_json'] = $row_data[4];
                $arr_row['keywords'] = $row_data[5];
                $arr_row['code'] = $row_data[6];
                $arr_row['place_id'] = $this->pml->if_strlen($row_data[7], 0);
                $arr_row['related_1'] = $this->pml->if_strlen($row_data[8], 0);
                $arr_row['related_2'] = $this->pml->if_strlen($row_data[9], 0);
                $arr_row['image_id'] = $this->pml->if_strlen($row_data[10], 0);
                $arr_row['text_1'] = $this->pml->if_strlen($row_data[11], '');
                $arr_row['text_2'] = $this->pml->if_strlen($row_data[12], '');
                $arr_row['status'] = $this->pml->if_strlen($row_data[13], 2);
                $arr_row['published_at'] = $this->pml->dexcel_dmysql($row_data[14]);
                $arr_row['slug'] = $this->Db_model->unique_slug($row_data[0], 'exams');
                
                $arr_row['creator_id'] = $this->session->userdata('user_id');
                $arr_row['updater_id'] = $this->session->userdata('user_id');

                //Guardar en tabla user
                $data_insert = $this->insert($arr_row);

                $data = array('status' => 1, 'text' => '', 'imported_id' => $data_insert['saved_id']);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

// GESTIÓN DE PREGUNTAS
//-----------------------------------------------------------------------------

    /**
     * Query con preguntas que componen un examen
     * 2021-03-18
     */
    function questions($exam_id)
    {
        $this->db->select('*');
        $this->db->order_by('position', 'ASC');
        $this->db->where('exam_id', $exam_id);
        $questions = $this->db->get('questions');

        return $questions;
    }

    /**
     * Actualiza el campo exams.answers, dependiente de las preguntas asociadas al cuestionario
     * 2021-03-23
     */
    function update_questions_info($exam_id)
    {
        $questions = $this->questions($exam_id);
        $arr_row['answers'] = $this->pml->query_to_str($questions, 'correct_option');
        $arr_row['qty_questions'] = $questions->num_rows();

        $data['saved_id'] = $this->Db_model->save('exams', "id = {$exam_id}", $arr_row);
        $data['arr_row'] = $arr_row;
        
        return $data;
    }

// Ejecución del examen
//-----------------------------------------------------------------------------

    /**
     * Inicializar respuesta de cuestionario, tabla exam_user
     * 2021-03-22
     */
    function start()
    {
        $questions = $this->questions($this->input->post('exam_id'));

        $arr_row = $this->Db_model->arr_row();
        $arr_row['exam_id'] = $this->input->post('exam_id');
        $arr_row['user_id'] = $this->session->userdata('user_id');
        $arr_row['status'] = 3; //Inicializado
        $arr_row['qty_attempts'] = $this->input->post('qty_attempts');
        $arr_row['answer_start'] = date('Y-m-d H:i:s');
        $arr_row['answers'] = implode(',', array_fill(0,$questions->num_rows(), '0'));
        $arr_row['results'] = implode(',', array_fill(0,$questions->num_rows(), '0'));
        $arr_row['qty_correct'] = 0;
        $arr_row['pct_correct'] = 0;
        $arr_row['approved'] = 0;

        $condition = "exam_id = {$arr_row['exam_id']} AND user_id = {$arr_row['user_id']}";
        $data['saved_id'] = $this->Db_model->save('exam_user', $condition, $arr_row);

        return $data;
    }

    /**
     * Guardar respuestas y resultados de la resolución de un examen por parte de un usuario
     * tabla exam_user
     * 2021-03-33
     */
    function save_answers()
    {
        $data['saved_id'] = 0;
        $row_exam = $this->Db_model->row_id('exams', $this->input->post('exam_id'));    //Identificar exam

        if ( ! is_null($row_exam) )
        {
            $arr_results = $this->arr_results($row_exam, $this->input->post('answers'));

            $arr_row['status'] = 2; //Respuestas iniciadas
            $arr_row['answers'] = $this->input->post('answers');
            $arr_row['results'] = implode(',', $arr_results);
            $arr_row['qty_correct'] = array_sum($arr_results);
            $arr_row['pct_correct'] = $this->pml->percent($arr_row['qty_correct'], count($arr_results));
            $arr_row['approved'] = ( $arr_row['pct_correct'] >= $row_exam->pct_approval ) ? 1 : 0 ;

            $arr_row['updated_at'] = date('Y-m-d H:i:s');
    
            //Identificar respuesta (con status no finializado, diferente a 1)
            $condition = "id = {$this->input->post('eu_id')} AND exam_id = {$this->input->post('exam_id')} AND status <> 1";
            $row_eu = $this->Db_model->row('exam_user', $condition);

            if ( ! is_null($row_eu) )
            {
                $data['saved_id'] = $this->Db_model->save('exam_user', "id = {$row_eu->id}", $arr_row);
            } else {
                $data['message'] = 'No se guardó. El cuestionario fue respondido anteriormente.';
            }
        }

        return $data;
    }

    /**
     * String con resultados de respuestas a un cuestionario, cadena de 1 (correcto) y 0 (incorrecto)
     * separados por comas
     * 2021-03-22
     */
    function arr_results($row_exam, $user_answers)
    {
        $correct_answers = explode(',', $row_exam->answers);
        $arr_answers = explode(',', $user_answers);
        $arr_results = array();

        foreach ($correct_answers as $key => $correct_answer) {
            $arr_results[$key] = 0;
            if ( $arr_answers[$key] == $correct_answer ) { $arr_results[$key] = 1; }
        }

        return $arr_results;
    }

    /**
     * Marcar una respuesta de cuestionario (tabla exam_user) como finalizada (status 1)
     * 2021-03-23
     */
    function finalize()
    {
        //Resultado por defecto
        $data = array('status' => 0, 'message' => 'Ocurrió un error al finalizar el examen.');

        $condition = "id = {$this->input->post('eu_id')} AND exam_id = {$this->input->post('exam_id')}";
        $row_eu = $this->Db_model->row('exam_user', $condition);

        if ( ! is_null($row_eu) )
        {
            $arr_row['status'] = 1; //Respuestas finalizado
            $arr_row['updated_at'] = date('Y-m-d H:i:s');
            $arr_row['user_id'] = $this->session->userdata('user_id');
    
            $data['saved_id'] = $this->Db_model->save('exam_user', "id = {$row_eu->id}", $arr_row);

            //Actualizar resultado
            if ( $data['saved_id'] > 0 )
            {
                $data['status'] = 1;
                $data['message'] = 'Cuestionario guardado y finalizado';
            }
        }

        return $data;
    }
}