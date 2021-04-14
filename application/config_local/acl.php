<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Funciones permitidas para cualquier visitante, incluso sin iniciar sesión
$config['public_functions'] = array(
	'/',

	'accounts/',
	'accounts/index',
	'accounts/login',
	'accounts/validate_login',
	'accounts/logout',
	'accounts/check_email',
	'accounts/signup',
	'accounts/validate_signup',
	'accounts/register',
	'accounts/registered',
	'accounts/activation',
	'accounts/activate',
	'accounts/recovery',
	'accounts/recovery_email',
	'accounts/recover',
	'accounts/reset_password',

	'accounts/g_callback',
	'accounts/g_signup',

	'app/logged',
	'app/denied',
	'app/test',

	'sync/tables_status',
	'sync/get_rows',
	'sync/quan_rows',
);

//Funciones permitidas para cualquier usuario con sesión iniciada
$config['logged_functions'] = array(
	'accounts/profile',
	'accounts/edit',
	'accounts/change_password',
	'accounts/edit',
	'accounts/password',
	'accounts/profile',
	'accounts/remove_image',
	'accounts/set_image',
	'accounts/update',
	'accounts/validate_form',

	'users/assigned_posts',
	'users/profile',
	'users/username',

	'files/crop',
	
	'posts/add_to_user_payed',
	'posts/alt_like',
	'posts/open',

	'comments/element_comments',
	'comments/save',
	'comments/delete',

	'exams/preparation',
	'exams/get_preparation_info',
	'exams/start',
	'exams/resolve',
	'exams/save_answers',
	'exams/finalize',
	'exams/results',

	//Especiales uniandes
	'app/set_avatar',

	'courses/browse',
	'courses/get',
	'courses/info',
	'courses/my_courses',
	'courses/certificate',
);

//Funciones y sus roles permitidos según controlador/función (cf)
$config['function_roles'] = array(
	'users/edit' => array(2),
	'users/save' => array(2),

	//Especiales uniandes
	'courses/enroll' => array(2,21),
	'courses/class' => array(2,21),
	'courses/open_element' => array(2,21),
	'courses/class' => array(2,21),
);