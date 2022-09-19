<?php

session_start();
include_once(dirname(__FILE__) . "/helpers/_helpers.php");
include_once(dirname(__FILE__) . "/models/_models.php");
include_once(dirname(__FILE__) . "/lib/Router.php");
include_once(dirname(__FILE__) . "/lib/Response.php");

// ##################################################
// ##################################################
// ##################################################

/**
 * Obtener ruta relativa
 */

$path_current = str_replace('\\', '/', dirname(__FILE__));
$path_relative = str_replace($_SERVER["DOCUMENT_ROOT"], '', $path_current);

// ##################################################
// ##################################################
// ##################################################

/**
 * Validar si hay autenticación
 */
function validate()
{
    if (!isset($_SESSION["name"])) {
        Response::redirect("/");
    }
}

// ##################################################
// ##################################################
// ##################################################
//
// Formato de opciones
// array(
//     "relativePath" => null, : string
//     "validateAuth" => null, : Función
//     "globalParams" => array(llave => valor),
//     "views" => array(
//         "layoutsDir" => string
//         "partialsDir" => string
//         "mainDir" => string
//     )
// );
//
// ##################################################
// ##################################################
// ##################################################

$routerConf = array(
    "relativePath" => $path_relative,
    "validateAuth" => "validate",
    "globalParams" => array(
        "userName" => isset($_SESSION["name"]) ? $_SESSION["name"] : "Bandido",
        "base" => "$path_relative/"
    ),
    "views" => array(
        "layoutsDir" => dirname(__FILE__) . "/views/layouts",
        "partialsDir" => dirname(__FILE__) . "/views/partials",
        "mainDir" => dirname(__FILE__) . "/views/main.html"
    )
);

$router = new Router($routerConf);

// Ejemplo de ruta raiz 
$router->get('/', 'controllers/root.php');

// Renderizando php
$router->get('/ejemplo_php', 'controllers/ejemplo_php.php');

// Ejemplo enviando parametro en la ruta
$router->get('/ejemplo_parametro/$numero/$numero', 'controllers/ejemplo_parametro.php');

// Ejemplo devolviendo parametros a php
$router->get('/ejemplo_parametro_php', 'controllers/ejemplo_parametro_php.php');


$router->post("/ejemplo_post", "controllers/ejemplo_post.php");


$router->any('/404', 'views/404.php');

