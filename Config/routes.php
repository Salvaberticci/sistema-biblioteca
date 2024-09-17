<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome'; // Controlador por defecto
$route['404_override'] = ''; // Página de error 404 personalizada
$route['translate_uri_dashes'] = FALSE; // Permite guiones en las URLs

// Ruta personalizada para la sección de recursos
$route['recursos'] = 'recursos/lista'; // Ajusta el nombre del controlador según tu estructura

// Otras rutas personalizadas según tu aplicación...

