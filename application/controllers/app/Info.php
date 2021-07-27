<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Info_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index()
    {
        $this->inicio();
            
    }

    function no_permitido()
    {
        $data['head_title'] = 'Acceso no permitido';
        $data['view_a'] = 'app/info/no_permitido_v';

        $this->load->view('templates/apanel4/start', $data);
    }

// FUNCIONES FRONT INFO
//-----------------------------------------------------------------------------

    function about()
    {
        //Variables generales
            $data['head_title'] = 'Sobre nosotras';
            $data['view_a'] = 'info/about_v';
            $this->App_model->view(TPL_FRONT, $data);
    }

    function contacto()
    {
        $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php

        //Variables generales
        $data['head_title'] = 'Contacto';
        $data['view_a'] = 'info/contacto_v';
        $this->App_model->view('templates/magnews/main_v', $data);
    }

    function loading()
    {
        $data['head_title'] = 'cloudbook :: Cargando...';
        $data['view_a'] = 'info/loading_v';
        $this->App_model->view('templates/magnews/main_v', $data);
    }

    function ayuda($section = 'como-comprar')
    {
        $titles['como-comprar'] = '¿Cómo comprar?';
        $titles['que-es-bonitas-vbn'] = '¿Qué es Bonitas VBN?';

        $data['head_title'] = $titles[$section];
        $data['view_a'] = 'info/ayuda/' . str_replace('-','_',$section) . '_v';

        $this->App_model->view('templates/bssocial/main_v', $data);
    }

// EL COLOR DE TU VERDAD
//-----------------------------------------------------------------------------

    function ecdtv()
    {
        $data['view_a'] = 'tests/ecdtv_v';
        $data['head_title'] = 'El Color de tu Verdad';

        $options = array();
        $preguntas = array();

        //$data['content'] = file_get_contents(PATH_RESOURCES . "tests/ecdtv/screens.json");
        $str_screens = file_get_contents(PATH_RESOURCES . "tests/ecdtv/screens.json");
        $json_screens = json_decode($str_screens, true);

        foreach ($json_screens as $screen)
        {
            if ( isset($screen['options']) )
            {
                foreach ($screen['options'] as $key => $option) {
                    $arr_option['id'] = intval('1' . str_replace('q', '', $screen['id']) . substr('0' . $key, -2));
                    $arr_option['question'] = $screen['id'];
                    $arr_option['key'] = $key;
                    $arr_option['text'] = $option;

                    $options[] = $arr_option;
                }
            }

            if ( isset($screen['stat']) ) {
                $pregunta['id'] = $screen['id'];
                $pregunta['texto'] = $screen['stat'];
                $preguntas[] = $pregunta;
            }

        }
        //$json_a = json_decode($string, true);

        $data['options'] = $options;
        $data['preguntas'] = $preguntas;

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Generar respuestas para preguntas con única respuesta
     * 2021-01-28
     */
    function pac_generar_respuestas()
    {
        $this->db->query('DELETE FROM pac_respuestas WHERE id > 0');

        $encuestas = $this->db->get('pac_encuestas');
        $data['cant_cargados'] = 0;

        $arr_preguntas = array('01','02','03','04','05','06','07','08','09','10','12','14','15','16','17');
        
        foreach ($encuestas->result() as $encuesta)
        {
            foreach ($arr_preguntas as $pregunta)
            {
                $campo = "q" . $pregunta;

                $arr_row['encuesta_id'] = $encuesta->id;
                $arr_row['pregunta_id'] = intval($pregunta);
                $arr_row['respuesta'] = $encuesta->$campo;
                $arr_row['opcion_id'] = '1' . $pregunta . substr('0' . $encuesta->$campo, -2);
    
                $this->db->insert('pac_respuestas', $arr_row);

                $data['cant_cargados'] += 1;
            }
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Generar respuestas para las preguntas 11 y 13, multiple respuesta
     */
    function pac_generar_respuestas_multi()
    {
        $this->db->query('DELETE FROM pac_respuestas WHERE pregunta_id IN (11,13)');

        $encuestas = $this->db->get('pac_encuestas');
        $data['cant_cargados'] = 0;

        $arr_preguntas = array('11','13');
        
        foreach ($encuestas->result() as $encuesta)
        {
            foreach ($arr_preguntas as $pregunta)
            {
                $campo = "q" . $pregunta;
                $arr_respuestas = explode(',', $encuesta->$campo);

                foreach ($arr_respuestas as $respuesta)
                {
                    $arr_row['encuesta_id'] = $encuesta->id;
                    $arr_row['pregunta_id'] = intval($pregunta);
                    $arr_row['respuesta'] = $respuesta;
                    $arr_row['opcion_id'] = '1' . $pregunta . substr('0' . $respuesta, -2);
        
                    $this->db->insert('pac_respuestas', $arr_row);
    
                    $data['cant_cargados'] += 1;
                }
            }
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}